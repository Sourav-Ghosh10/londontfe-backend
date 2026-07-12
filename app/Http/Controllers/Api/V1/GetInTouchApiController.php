<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GetInTouchApiController extends Controller
{
    public function submitForm(Request $request)
    {
        try {
            $validated = $request->validate([
                'url' => 'nullable|string',
                'fname' => 'required|string',
                'lname' => 'required|string',
                'email' => 'required|email',
                'company' => 'nullable|string',
                'phone' => 'required|string',
                'phonecode' => 'nullable|string',
                'message' => 'required|string',
                'contentName' => 'nullable|string',
            ]);

            $url = ltrim($request->input('url', ''), '/index.php');
            $fname = trim($validated['fname']);
            $lname = trim($validated['lname']);
            $your_name = $fname . " " . $lname;
            $your_mail = trim($validated['email']);
            $your_company = trim($validated['company'] ?? '');
            $your_phone = trim($validated['phone']);
            $your_message = trim($validated['message']);
            $phonecode = $request->input('phonecode', '');
            $contentName = $request->input('contentName', '');

            // Log to user_log
            $logData = [
                'log_type' => 'Lerningsolution',
                'name' => $your_name,
                'email' => $your_mail,
                'phone_no' => $your_phone,
                'company_name' => $your_company,
                'ip' => $request->ip(),
                'status' => 'success',
                'created_dt' => now(),
            ];
            DB::table('user_log')->insert($logData);

            // Insert to course_quotation
            $contArr = [
                'quote_type' => trim($contentName),
                'name' => $your_name,
                'email' => $your_mail,
                'company' => $your_company,
                'phone' => $your_phone,
                'message' => $your_message,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            DB::table('course_quotation')->insert($contArr);

            // Send admin email
            $this->sendAdminEmail($url, $fname, $lname, $your_mail, $phonecode, $your_phone, $your_company, $your_message);
            
            // Auto response (quotemail)
            $this->sendAutoResponseEmail($your_name, $contentName, $your_mail);

            return response()->json([
                'success' => true,
                'process' => 'success',
                'msg' => 'Mail sent successfully.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('GetInTouch Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'process' => 'fail',
                'msg' => 'An error occurred while submitting the request.'
            ], 500);
        }
    }

    private function sendAdminEmail($url, $fname, $lname, $email, $phonecode, $phone, $company, $messageTxt)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $mandrillUrl = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $contentType = '';
            if (!empty($url)) {
                $segments = explode('/', trim($url, '/'));
                $contentType = end($segments); 
                $contentType = ucwords(str_replace('-', ' ', $contentType)); 
            }

            $resDetails = DB::table('auto_responce_content')->where('form_name', 'admin_quote_mail')->first();
            $subject = $resDetails->mail_subject ?? 'Quote Request';
            
            if ($resDetails && !empty($resDetails->mail_content)) {
                $replaceArray = [
                    '{CONTENTTYPE}' => $contentType,
                    '{FIRSTNAME}'   => $fname,
                    '{LASTNAME}'    => $lname,
                    '{EMAIL}'       => $email,
                    '{PHONECODE}'   => $phonecode,
                    '{PHONENO}'     => $phone,
                    '{COMPANY}'     => $company,
                    '{HELP_TEXT}'   => $messageTxt,
                    '{CLIENTIP}'    => request()->ip(),
                ];

                $messageBody = str_replace(
                    array_keys($replaceArray),
                    array_values($replaceArray),
                    $resDetails->mail_content
                );
            } else {
                $messageBody = "<p>New request from $fname $lname ($email).</p><p>Message: $messageTxt</p>";
            }

            $toAdmin = [
                [
                    'email' => env('TO_MAIL', 'sales@londontfe.com'),
                    'type' => 'to'
                ]
            ];

            $postData = [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => $toAdmin
                ]
            ];

            \Illuminate\Support\Facades\Http::post($mandrillUrl, $postData);
        } catch (\Exception $e) {
            Log::error('Mandrill GetInTouch Admin Email Error: ' . $e->getMessage());
        }
    }

    private function sendAutoResponseEmail($name, $quoteType, $email)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', '');
            $mandrillUrl = 'https://mandrillapp.com/api/1.0/messages/send.json';
            
            $resDetails = DB::table('auto_responce_content')->where('form_name', 'quote_mail')->first();
            
            if (!$resDetails) {
                return;
            }

            $subject = $resDetails->mail_subject ?? 'Thank you for your enquiry';
            $messageBody = str_replace(
                ['{NAME}', '{QUOTE_TYPE}', '{COURSENAME}'],
                [$name, $quoteType, $quoteType],
                $resDetails->mail_content
            );

            \Illuminate\Support\Facades\Http::post($mandrillUrl, [
                'key' => $apiKey,
                'message' => [
                    'html' => $messageBody,
                    'subject' => $subject,
                    'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@londontfe.com'),
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => [
                        [
                            'email' => $email,
                            'name' => $name,
                            'type' => 'to'
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Mandrill GetInTouch Auto Response Error: ' . $e->getMessage());
        }
    }
}
