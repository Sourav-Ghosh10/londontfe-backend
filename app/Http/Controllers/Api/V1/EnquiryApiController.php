<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnquiryApiController extends Controller
{
    public function quickenquery(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string',
                'lname' => 'required|string',
                'email' => 'nullable|string',
                'phone' => 'required|string',
                'company' => 'nullable|string',
                'jobTitle' => 'nullable|string',
                'moreInfo' => 'nullable|string',
                'hearAbout' => 'nullable|string',
                'course' => 'nullable|array',
            ]);

            $courseTitle = $request->input('course.title', 'Unknown');
            $courseVenue = $request->input('course.venue', '');
            $courseDate = $request->input('course.date', '');
            $ipAddress = $request->ip();

            $fullName = trim(($validated['name'] ?? '') . ' ' . ($validated['lname'] ?? ''));
            if ($fullName === 'NA') {
                $fullName = $validated['lname'] ?? '';
            }

            // Storing moreInfo and hearAbout in finance_info or status
            $extraInfo = json_encode([
                'moreInfo' => $validated['moreInfo'] ?? '',
                'hearAbout' => $validated['hearAbout'] ?? ''
            ]);

            // Insert into user_log (quickenquery)
            $logData = [
                'log_type' => 'quickenquery',
                'name' => $fullName,
                'email' => $validated['email'] === 'NA' ? '' : ($validated['email'] ?? ''),
                'phone_no' => $validated['phone'] ?? '',
                'company_name' => $validated['company'] ?? '',
                'job_title' => $validated['jobTitle'] ?? '',
                'ip' => $ipAddress,
                'country' => $request->input('country', ''),
                'coursename' => $courseTitle,
                'coursevenue' => $courseVenue,
                'coursestartdt' => $courseDate,
                'finance_info' => $extraInfo,
                'created_dt' => now(),
            ];
            DB::table('user_log')->insert($logData);

            // Insert into course_booking_enquiry
            DB::table('course_booking_enquiry')->insert([
                'first_name' => $validated['name'] ?? '',
                'last_name' => $validated['lname'] ?? '',
                'email' => $validated['email'] === 'NA' ? '' : ($validated['email'] ?? ''),
                'phone_no' => $validated['phone'] ?? '',
                'phone_code' => $request->input('phoneCode', ''),
                'company_name' => $validated['company'] ?? '',
                'job_title' => $validated['jobTitle'] ?? '',
                'more_info' => $validated['moreInfo'] ?? '',
                'where' => $validated['hearAbout'] ?? '',
                'course_name_search' => $courseTitle,
                'course_date' => $courseDate,
                'course_month' => '',
                'course_venue' => $courseVenue,
                'course_category' => $request->input('course.category', ''),
                'booking_status' => '0',
                'course_type' => '1',
                'request_date' => now(),
                'user_log_id' => DB::getPdo()->lastInsertId(),
            ]);

            // Send Admin Email via Mandrill
            $this->sendAdminEmail(
                $logData,
                $validated['moreInfo'] ?? '',
                $validated['hearAbout'] ?? '',
                $request->input('phoneCode', ''),
                $validated['name'] ?? '',
                $validated['lname'] ?? ''
            );

            // Send User Auto-response Email
            if (!empty($logData['email'])) {
                $this->autoResponseCourseEnquiry($logData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Enquiry submitted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Quick Enquiry Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the enquiry.'
            ], 500);
        }
    }

    private function sendAdminEmail($dtArr, $moreInfo, $where, $phoneCode, $firstName, $lastName)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            // Fetch template from auto_responce_content
            $resDetails = DB::table('auto_responce_content')->where('form_name', 'admin_booking_enquery')->first();
            $subject = 'Enquiry to book a course';
            $messageBody = "<h1>New Course Enquiry</h1><p>Name: {$dtArr['name']}</p><p>Course: {$dtArr['coursename']}</p>";

            if ($resDetails && !empty($resDetails->mail_content)) {
                $subject = str_replace('{COURSENAME}', $dtArr['coursename'], $resDetails->mail_subject ?? 'Course Enquiry');

                $replaceArray = [
                    '{COURSENAME}'  => $dtArr['coursename'] ?? '',
                    '{FIRSTNAME}'   => $firstName ?? '',
                    '{LASTNAME}'    => $lastName ?? '',
                    '{EMAIL}'       => $dtArr['email'] ?? '',
                    '{PHONECODE}'   => $phoneCode ?? '',
                    '{PHONENO}'     => $dtArr['phone_no'] ?? '',
                    '{COMPANYNAME}' => $dtArr['company_name'] ?? '',
                    '{JOBTITLE}'    => $dtArr['job_title'] ?? '',
                    '{MORE_INFO}'   => $moreInfo,
                    '{HEAR_ABOUT}'  => $where,
                    '{COURSEVENUE}' => $dtArr['coursevenue'] ?? '',
                    '{COURSEDATE}'  => $dtArr['coursestartdt'] ?? '',
                ];

                $messageBody = str_replace(
                    array_keys($replaceArray),
                    array_values($replaceArray),
                    $resDetails->mail_content
                );
            }

            $toAdmin = [
                [
                    'email' => env('TO_MAIL', 'sales@londontfe.com'),
                    'type' => 'to'
                ]
            ];

            $ccEmail = env('MANDRIL_MAIL_CC', '');
            if (!empty($ccEmail)) {
                $toAdmin[] = [
                    'email' => $ccEmail,
                    'type' => 'cc'
                ];
            }

            $bccEmail = env('BCC_MAIL', '');

            $postData = [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'text' => strip_tags($messageBody),
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => $toAdmin
                ]
            ];

            if (!empty($bccEmail)) {
                $postData['message']['bcc_address'] = $bccEmail;
            }

            $response = \Illuminate\Support\Facades\Http::post($url, $postData);

            if ($response->failed()) {
                Log::error('Mandrill Admin Email Error: ' . $response->body());
            } else {
                Log::info('Mandrill Admin Email Success: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send Mandrill email for enquiry: ' . $e->getMessage());
        }
    }

    private function autoResponseCourseEnquiry($logData)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', 'md-C4tnrZLOhMZ6QVa-Q5TEEg');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $resDetails = DB::table('auto_responce_content')->where('form_name', 'course_enquiry')->first();

            if (!$resDetails || empty($resDetails->mail_content)) {
                return;
            }

            $subject = str_replace('{COURSENAME}', $logData['coursename'], $resDetails->mail_subject ?? 'Thank you for your enquiry');

            $replaceArray = [
                '{NAME}' => $logData['name'] ?? '',
                '{COURSENAME}' => $logData['coursename'] ?? '',
            ];

            $messageBody = str_replace(
                array_keys($replaceArray),
                array_values($replaceArray),
                $resDetails->mail_content
            );

            $response = \Illuminate\Support\Facades\Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => [
                        [
                            'email' => $logData['email'],
                            'name' => $logData['name'],
                            'type' => 'to'
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Mandrill Auto Response Error: ' . $response->body());
            } else {
                Log::info('Mandrill Auto Response Success: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send auto response email for enquiry: ' . $e->getMessage());
        }
    }


    public function calendarForm(Request $request)
    {
        try {
            $validated = $request->validate([
                'fname' => 'required|string',
                'lname' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'phoneCode' => 'nullable|string',
                'company' => 'nullable|string',
                'jobtitle' => 'nullable|string',
                'directoryyear' => 'nullable|string',
                'cf_turnstile_response' => 'nullable|string',
                'cf_action' => 'nullable|string',
            ]);

            $token = $validated['cf_turnstile_response'] ?? null;
            // if (empty($token)) {
            //     return response()->json(['success' => false, 'message' => 'Missing Turnstile token'], 400);
            // }

            // Verify with Cloudflare Turnstile if secret is available
            // $turnstileSecret = env('TURNSTILE_SECRET', '0x4AAAAAAAP-Wc2b0dYc_v1uV1G9166qH5M');
            // if ($turnstileSecret && $turnstileSecret != '') {
            //     $verify = \Illuminate\Support\Facades\Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            //         'secret' => $turnstileSecret,
            //         'response' => $token,
            //         'remoteip' => $request->ip()
            //     ]);
            //
            //     if (!$verify->json('success')) {
            //         return response()->json(['success' => false, 'message' => 'Turnstile verification failed'], 400);
            //     }
            // }

            $ipAddress = $request->ip();
            $directoryyear = $validated['directoryyear'] ?? date('Y');
            $fullName = trim(($validated['fname'] ?? '') . ' ' . ($validated['lname'] ?? ''));

            $logData = [
                'log_type' => 'dirpdf',
                'name' => $fullName,
                'email' => $validated['email'] ?? '',
                'phone_no' => $validated['phone'] ?? '',
                'company_name' => $validated['company'] ?? '',
                'job_title' => $validated['jobtitle'] ?? '',
                'ip' => $ipAddress,
                'country' => session('country_name', ''),
                'course_url' => url()->current(),
                'coursename' => null,
                'categoryname' => null,
                'coursevenue' => null,
                'quantity' => null,
                'coursestartdt' => null,
                'currency' => null,
                'price' => null,
                'status' => null,
                'created_dt' => now()
            ];

            DB::table('user_log')->insert($logData);

            $course_dir_url = env('APP_URL', url('/')) . "/course/pdfdirectory?file=London-Training-Course-Directory-" . $directoryyear . ".pdf";

            $this->sendAdminCalendarEmail($validated, $course_dir_url);
            
            if (!empty($logData['email'])) {
                $this->autoResponseCalendarEmail($logData, $course_dir_url, $directoryyear);
            }

            return response()->json([
                'success' => true,
                'message' => 'Success'
            ]);

        } catch (\Exception $e) {
            Log::error('Calendar Form API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the request.'
            ], 500);
        }
    }

    private function sendAdminCalendarEmail($validated, $course_dir_url)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $resDetails = DB::table('auto_responce_content')->where('form_name', 'admin_dir_email')->first();
            $subject = 'Course PDF Directory Mail Success';
            $messageBody = "<h1>New Course Directory Download</h1><p>Name: {$validated['fname']} {$validated['lname']}</p><p>Email: {$validated['email']}</p>";

            if ($resDetails && !empty($resDetails->mail_content)) {
                $subject = $resDetails->mail_subject ?? $subject;

                $replaceArray = [
                    '{FIRSTNAME}'   => $validated['fname'] ?? '',
                    '{LASTNAME}'    => $validated['lname'] ?? '',
                    '{EMAIL}'       => $validated['email'] ?? '',
                    '{PHONECODE}'   => $validated['phoneCode'] ?? '',
                    '{PHONENO}'     => $validated['phone'] ?? '',
                    '{COMPANYNAME}' => $validated['company'] ?? '',
                    '{JOBTITLE}'    => $validated['jobtitle'] ?? '',
                ];

                $messageBody = str_replace(
                    array_keys($replaceArray),
                    array_values($replaceArray),
                    $resDetails->mail_content
                );
            }

            $toAdmin = [
                [
                    'email' => env('TO_MAIL', 'sales@londontfe.com'),
                    'type' => 'to'
                ]
            ];

            $ccEmail = env('MANDRIL_MAIL_CC', '');
            if (!empty($ccEmail)) {
                $toAdmin[] = [
                    'email' => $ccEmail,
                    'type' => 'to'
                ];
            }

            $postData = [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'text' => strip_tags($messageBody),
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => $toAdmin
                ]
            ];

            $response = \Illuminate\Support\Facades\Http::post($url, $postData);

            if ($response->failed()) {
                Log::error('Mandrill Admin Calendar Email Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send Admin Calendar email: ' . $e->getMessage());
        }
    }

    private function autoResponseCalendarEmail($logData, $course_dir_url, $directoryyear)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            // Based on legacy, we'll try to find a suitable template or send a default one
            $resDetails = DB::table('auto_responce_content')->where('form_name', 'course_dir_url_user')->first(); 
            // The exact legacy form_name for the user was not provided, but we can assume 'document_download_mail' is close, or create a default.
            
            $subject = 'Your London TFE Course Directory';
            $messageBody = "<h1>Hi {$logData['name']},</h1><p>Thank you for requesting our Course Directory for {$directoryyear}.</p><p>You can download it using the link below:</p><p><a href=\"{$course_dir_url}\">Download Course Directory</a></p>";

            if ($resDetails && !empty($resDetails->mail_content)) {
                $subject = $resDetails->mail_subject ?? $subject;
                
                // You can add more replacements as needed
                $replaceArray = [
                    '{NAME}' => $logData['name'] ?? '',
                    '{COURSE_DIR_URL}' => $course_dir_url,
                    '{YEAR}' => $directoryyear
                ];

                $messageBody = str_replace(
                    array_keys($replaceArray),
                    array_values($replaceArray),
                    $resDetails->mail_content
                );
            } else {
                // Let's try 'document_download_mail' as a fallback
                $resDetailsFallback = DB::table('auto_responce_content')->where('form_name', 'document_download_mail')->first();
                if ($resDetailsFallback && !empty($resDetailsFallback->mail_content)) {
                    $subject = $resDetailsFallback->mail_subject ?? $subject;
                    $replaceArray = [
                        '{NAME}' => $logData['name'] ?? '',
                        '{COURSE_OUTLINE_URL}' => $course_dir_url,
                        '{EXPDATE}' => '7 days'
                    ];
                    $messageBody = str_replace(
                        array_keys($replaceArray),
                        array_values($replaceArray),
                        $resDetailsFallback->mail_content
                    );
                }
            }

            $response = \Illuminate\Support\Facades\Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => [
                        [
                            'email' => $logData['email'],
                            'name' => $logData['name'],
                            'type' => 'to'
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Mandrill Auto Response Calendar Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Failed to send auto response Calendar email: ' . $e->getMessage());
        }
    }
}
