<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestimonialApiController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', '1')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $testimonials
        ]);
    }

    public function haveyoursay(Request $request)
    {
        $validated = $request->validate([
            'txttitle' => 'required|string|max:255',
            'course_name' => 'nullable|string|max:255',
            'txtcomments' => 'required|string',
            'txtfname' => 'required|string|max:100',
            'txtlname' => 'required|string|max:100',
            'txtemail' => 'required|email|max:100',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $authorName = $validated['txtfname'] . ' ' . $validated['txtlname'];

        // 1. Insert into Testimonials (Waiting for approval in status 0)
        Testimonial::insert([
            'author_name' => $authorName,
            'testimonial_text' => $validated['txtcomments'],
            'author_description' => $validated['course_name'] ?? '',
            'status' => '0',
            'created_on' => now(),
        ]);

        // 2. Insert into user_log (Tracking)
        $jsonData = [
            'First_name' => $validated['txtfname'],
            'Last_name' => $validated['txtlname'],
            'Email' => $validated['txtemail'],
            'your_title' => $validated['txttitle'],
            'Comment' => $validated['txtcomments'],
            'Rate' => $validated['rating']
        ];
        
        DB::table('user_log')->insert([
            'name' => $authorName,
            'email' => $validated['txtemail'],
            'phone_no' => '',
            'log_type' => 'Haveyoursay',
            'status' => 'success',
            'ip' => $request->ip() ?? '',
            'course_url' => $request->headers->get('referer') ?? '',
            'created_dt' => now(),
        ]);

        // 3. Send Admin Notification Email via Mandrill
        $this->sendAdminEmail($jsonData, $authorName);

        return response()->json([
            'success' => true,
            'process' => 'success',
            'msg' => 'Thank you for your feedback!'
        ]);
    }

    private function sendAdminEmail($data, $authorName)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', 'md-n5axIxQ2NGULfGac5pxzAw'); // Fallback API key from legacy snippet
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $resDetails = DB::table('auto_responce_content')->where('form_name', 'adminhaveyousay')->first();
            
            $subject = 'Review Email';
            $messageBody = "<h1>New Review</h1><p>Name: {$authorName}</p>";

            if ($resDetails && !empty($resDetails->mail_content)) {
                $subject = $resDetails->mail_subject;
                
                $replaceArray = [
                    '{NAME}' => $authorName,
                    '{EMAIL}'   => $data['Email'],
                    '{TITLE}'    => $data['your_title'],
                    '{COMMENT}'   => $data['Comment'],
                    '{RATE}'     => $data['Rate'],
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
            
            if (env('MANDRIL_MAIL_CC')) {
                $toAdmin[] = [
                    'email' => env('MANDRIL_MAIL_CC'),
                    'type' => 'to'
                ];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'text' => strip_tags($messageBody),
                    'subject' => $subject,
                    'from_email' => 'no-reply@londontfe.com',
                    'from_name' => env('FROM_MSG', 'London TFE'),
                    'to' => $toAdmin
                ]
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            Log::info('Mandrill HaveYourSay Email Response: ' . $response);

        } catch (\Exception $e) {
            Log::error('Mandrill HaveYourSay Error: ' . $e->getMessage());
        }
    }
}
