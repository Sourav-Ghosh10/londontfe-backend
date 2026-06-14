<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SendOutlineController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', '1')->orderBy('course_name')->get(['id', 'course_name']);

        $allVenues = Schema::hasTable('venue')
            ? DB::table('venue')->orderBy('venue_name')->pluck('venue_name', 'id')
            : collect();

        $currencies = [
            ['code' => 'GBP', 'label' => 'GBP'],
        ];

        return view('admin.courses.send-outline', compact('courses', 'currencies', 'allVenues'));
    }

    /**
     * Dynamically return date/venue options for a given course (AJAX).
     */
    public function getDates(Request $request)
    {
        $courseId = $request->input('course_id');

        $course = DB::table('course')->where('id', $courseId)->first(['id', 'course_duration', 'price_tier_id']);

        $basePrice = 0;
        if ($course) {
            $tier = DB::table('price_tier')->where('id', $course->price_tier_id)->first();
            if ($tier) {
                $days = (int) $course->course_duration;
                $base_rate = ($tier->base_rate * (round($days / 5)));
                $daily_rate = $tier->daily_rate;
                $basePrice = $base_rate + ($daily_rate * $days);
            }
        }

        $dates = Schema::hasTable('course_date_venue')
            ? DB::table('course_date_venue')
                ->where('course_id', $courseId)
                ->where('status', '1')
                ->orderBy('start_date')
                ->get(['id', 'start_date', 'venue_id'])
            : collect();

        $venueIds = $dates->pluck('venue_id')->unique()->filter();
        $venues = Schema::hasTable('venue')
            ? DB::table('venue')->whereIn('id', $venueIds)->pluck('venue_name', 'id')
            : collect();

        $locationBands = Schema::hasTable('location_band')
            ? DB::table('location_band')->get(['location_band_type', 'adjustment', 'venue'])
            : collect();

        $options = $dates->map(function ($d) use ($venues, $basePrice, $locationBands) {
            $price = $basePrice;
            $venue_id = $d->venue_id;

            // Apply location band adjustment
            $adjustment = 0;
            $type = '';
            foreach ($locationBands as $band) {
                $bandVenues = explode(',', $band->venue);
                if (in_array($venue_id, $bandVenues)) {
                    $adjustment = $band->adjustment;
                    $type = $band->location_band_type;
                    break;
                }
            }

            if (!empty($adjustment)) {
                if ($type === 'plus') {
                    $price += ($price * $adjustment) / 100;
                } elseif ($type === 'minus') {
                    $price -= ($price * $adjustment) / 100;
                }
            }

            $price = round($price / 100) * 100;

            return [
                'id' => $d->id,
                'start_date' => $d->start_date,
                'venue_id' => $venue_id,
                'venue_name' => $venues[$venue_id] ?? 'Unknown',
                'price' => $price,
            ];
        });

        return response()->json($options);
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email',
            'course_id' => 'required|integer',
            'currency' => 'required|in:GBP',
        ]);

        $title = $request->input('title');
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $courseID = $request->input('course_id');
        $footnote = $request->input('footer_note');
        $currency = $request->input('currency');
        $isCustom = $request->input('is_custom') == '1';
        $price = $request->input('price');

        if (!empty($footnote)) {
            $footnote = "Special Note : " . $footnote;
        }

        $course = DB::table('course')->where('id', $courseID)->first();
        $user = auth()->user();
        $userDetails = $user ? DB::table('user_details')->where('user_id', $user->id)->first() : null;

        // Building user data
        $log_name = trim(($user->fname ?? '') . ' ' . ($user->lname ?? ''));
        $log_email = $user->email ?? '';
        $log_whats = $user->whats ?? '';
        $log_phone = $userDetails->phone ?? '';
        $log_phone_code = $userDetails->phone_code ?? '';
        $log_job_title = $userDetails->job_title ?? '';
        $log_passport_image = $userDetails->passport_image ?? '';
        $calender_link = $user->calender_link ?? 'https://outlook.office365.com/owa/calendar/Consultation@londontfe.com/bookings/';

        $name = "";
        if ($title !== 'other')
            $name .= $title . ' ';
        $name .= $firstName . ' ' . $lastName;

        $course_name = $course->course_name ?? '';
        $course_overview = $course->overview ?? '';
        $course_duration = $course->course_duration ?? '';
        $course_duration_type = 'days';
        if ($course->course_duration_type == 1)
            $course_duration_type = 'days';
        elseif ($course->course_duration_type == 7)
            $course_duration_type = 'weeks';
        elseif ($course->course_duration_type == 30)
            $course_duration_type = 'months';
        elseif ($course->course_duration_type == 365)
            $course_duration_type = 'years';

        $image_url = \Illuminate\Support\Facades\Storage::disk('s3')->url('theme-v-1/images/custom-email/');
        $profile_url = \Illuminate\Support\Facades\Storage::disk('s3')->url('uploads/staff_picture/');
        $currency_val = ($currency === 'GBP') ? '&pound;' : (($currency === 'USD') ? '$' : '&euro;');

        $date = null;
        $venueName = '';
        $flagImage = '';

        if (!$isCustom) {
            $venueDateId = $request->input('venue_date_id');
            $dateVenue = DB::table('course_date_venue')->where('id', $venueDateId)->first();
            if ($dateVenue) {
                $venue = DB::table('venue')->where('id', $dateVenue->venue_id)->first();
                $date = $dateVenue->start_date;
                $venueName = $venue->venue_name ?? '';
                $flagImage = $venue->flag_image ?? '';
            }
        } else {
            $date = $request->input('custom_start_date');
            $venueId = $request->input('custom_venue_id');
            $venue = DB::table('venue')->where('id', $venueId)->first();
            $venueName = $venue->venue_name ?? '';
            $flagImage = $venue->flag_image ?? '';
        }

        $custom_section = "";
        if ($date && $venueName) {
            $venue_img = 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/venue/' . strtolower($venueName) . '.jpg';
            $flag_img = 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/flags/' . str_replace(" ", "-", strtolower($flagImage)) . '.png';
            $newDate = date("d-m-Y", strtotime($date));
            $download_url = 'https://' . $_SERVER['HTTP_HOST'] . '/course/custom_pdf_download_user/?Course_URL_Name=' . $course->course_name . '&Event_Venue_=' . $venueName . '&Event_Date_=' . $newDate . '&Course_ID=' . $courseID . '&price=' . $price . '&currency=' . $currency;
            $details_link = 'https://' . $_SERVER['HTTP_HOST'] . '/course/' . ($course->category_seo_name ?? '') . '/' . ($course->seo_name ?? '') . '/' . $course->id . '/register';

            $topMonth = date("d M Y", strtotime($date)); // Simplified for standard display

            $custom_section = '<tr>
                <td align="left" valign="top" style="padding: 0 50px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="55%" align="left" valign="top" style="background:#202f44; padding:0 20px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td colspan="3" height="15"></td>
            </tr>
            <tr>
                <td align="left" valign="middle" style="border-bottom:1px solid #5c6879;"><img src="' . $image_url . 'calender-icon.png" width="26" height="28" alt="" /></td>
                <td align="left" valign="middle" style="border-bottom:1px solid #5c6879;">&nbsp;</td>
                <td height="44" align="left" valign="middle" style="border-bottom:1px solid #5c6879; position:relative;"> 
            
            <p style="font-size:15px; color:#fff; font-weight:600;">
            <span style="font-size:12px; color:#9eabbd; font-weight:400;">Upcoming</span> <br>
        ' . $topMonth . '</p></td>
        <td width="11" align="right" valign="middle"><img src="' . $image_url . 'greendot.png" alt="" style="float: right; top:10px; right:0px;" /></td>
            </tr>
            <tr>
                <td width="32" align="left" valign="middle" style="border-bottom:1px solid #5c6879;"><img src="' . $flag_img . '" width="27" height="27" alt="" /></td>
                <td align="left" valign="middle" style="border-bottom:1px solid #5c6879;">&nbsp;</td>
                <td height="44" align="left" valign="middle" style="border-bottom:1px solid #5c6879;"><p style="font-size:15px; color:#fff; font-weight:600;">
            <span style="font-size:12px; color:#9eabbd; font-weight:400;">Location</span> <br>
            ' . $venueName . '</p></td>
            </tr>
            <tr>
                <td width="32" align="left" valign="middle"><img src="' . $image_url . 'tag.png" width="27" height="27" alt="" /></td>
                <td align="left" valign="middle">&nbsp;</td>
                <td height="44" align="left" valign="middle"><p style="font-size:15px; color:#fff; font-weight:600;">
            <span style="font-size:12px; color:#9eabbd; font-weight:400;">Price</span> <br>
            ' . $currency_val . $price . '</p></td>
            </tr>
            </table>
            
            
                </td>
                <td align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left" valign="top">
        <img src="' . $venue_img . '" width="250" height="128" alt=""/>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="z-index: 1; bottom: 0; width: 100%; left: 0; right: 0; margin: 0 auto; background: #000; padding: 0px;">
            <tr>
                <td align="left" valign="middle" width="50%" style="background:#ffa834; padding:0 10px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="30" align="left" valign="middle"><a href="' . $download_url . '" target="_blank" style=" color:#fff; text-decoration:none;"><img src="' . $image_url . 'pdf-icon.png" width="" height="" alt="" /></a></td>
                <td align="left" valign="middle" style="font-size:12px; font-weight:600; color:#fff; line-height:13px;"><a href="' . $download_url . '" target="_blank" style=" color:#fff; text-decoration:none;">Download Outline</a></td>
            </tr>
            </table>
            
                </td>
                <td width="10px">&nbsp;</td>
                <td align="left" valign="top"  style="background:#ffa834; padding:0 10px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="25" valign="middle"><a href="' . $details_link . '" target="_blank" style=" color:#fff; text-decoration:none;"><img src="' . $image_url . 'register-icon.png" width="" height="" alt="" /></a></td>
                <td height="30" align="left" valign="middle" style="font-size:12px; font-weight:600; color:#fff; line-height:18px;"><a href="' . $details_link . '" target="_blank" style=" color:#fff; text-decoration:none;">Register</a></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
            </td>
            </tr>
            </table>
            </td>
            </tr>
            </table>
            
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" height="10"></td>
            </tr>';
        }

        $resDetails = DB::table('auto_responce_content')->where('form_name', 'custom_email')->first();
        if ($resDetails && !empty($resDetails->mail_content)) {
            $contents = $resDetails->mail_content;
            $subject = str_replace("{COURSENAME}", $course_name, $resDetails->mail_subject);

            $replaceFrom = ["{IMGURL}", "{NAME}", "{EMAIL}", "{COURSENAME}", "{OVERVIEW}", "{COURSEDURATION}", "{COURSEDURATIONTYPE}", "{LOGNAME}", "{LOGEMAIL}", "{LOGEWHATSAPP}", "{LOGPHONE}", "{LOGPHONECODE}", "{LOGJOBTITLE}", "{PROFILE_URL}", "{PASSPORT_IMG}", "{CUSTOM_DATA}", "{FOOTNOTE}", "{CALENDERLINK}"];
            $replaceTo = [$image_url, $name, $email, $course_name, $course_overview, $course_duration, $course_duration_type, $log_name, $log_email, $log_whats, $log_phone, $log_phone_code, $log_job_title, $profile_url, $log_passport_image, $custom_section, $footnote, $calender_link];

            $mailContent = "<!doctype html>
                            <html>
                                <head>
                                <style>
                                    *{padding:0; margin:0; font-family: arial;}
                                    body{font-family: Arial; font-weight:normal;}
                                    img{max-width:100%;}
                                    .bodycontent{padding:30px 0;}
                                    .bodycontent p{font-family:Arial; font-size:12px; color:#000; line-height:18px; padding-bottom:15px; font-weight:normal;}
                                    .list{margin-left: 26px; margin-bottom: 20px;}
                                    .list li{font-size:13px; color:#2c3d52; line-height:18px;}
                                    .noshow ~ * {display: none;}
                            </style>
                                    <meta charset='utf-8'>
                                    <meta name='viewport' content='width=600'>
                                    <title>Course Outline</title>
                                </head><body>" . str_replace($replaceFrom, $replaceTo, $contents) . "</body></html>";
        } else {
            $mailContent = $resDetails->default_content ?? '';
            $subject = 'Course Outline';
        }

        $apiKey = 'md-C4tnrZLOhMZ6QVa-Q5TEEg';
        $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

        $response = \Illuminate\Support\Facades\Http::post($url, [
            'key' => $apiKey,
            'message' => [
                'html' => $mailContent,
                'text' => strip_tags($mailContent),
                'subject' => $subject,
                'from_email' => $log_email ?: 'info@londontfe.com',
                'from_name' => $log_name ?: 'London TFE',
                'to' => [
                    [
                        'email' => $email,
                        'type' => 'to'
                    ]
                ]
            ]
        ]);

        $responseData = $response->json();

        if (isset($responseData[0]['status']) && in_array($responseData[0]['status'], ['sent', 'queued'])) {
            return back()->with('success', 'Email Send Successfully.');
        } else {
            return back()->with('error', 'Failed to send email.');
        }
    }
}
