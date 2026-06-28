<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingApiController extends Controller
{
    public function beforePay(Request $request)
    {
        try {
            $validated = $request->validate([
                'contact' => 'required|array',
                'cart_items' => 'required|array',
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'url' => 'nullable|string'
            ]);

            $contact = $validated['contact'];
            $ipAddress = $request->ip();
            $currencySign = $validated['currency'] == 'GBP' ? '£' : ($validated['currency'] == 'USD' ? '$' : '€');

            // 1. Save logs to user_log table for each item with 'beforepay' status
            foreach ($validated['cart_items'] as $item) {
                DB::table('user_log')->insert([
                    'log_type' => 'beforepay',
                    'name' => trim(($contact['firstName'] ?? '') . ' ' . ($contact['lastName'] ?? '')),
                    'email' => $contact['email'] ?? '',
                    'phone_no' => $contact['phone'] ?? '',
                    'ip' => $ipAddress,
                    'country' => $contact['country'] ?? '',
                    'course_url' => $validated['url'] ?? '',
                    'coursename' => $item['title'] ?? $item['name'] ?? 'Unknown',
                    'categoryname' => '', 
                    'coursevenue' => $item['location'] ?? $item['venue'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'coursestartdt' => $item['date'] ?? null, 
                    'currency' => $currencySign,
                    'price' => $item['price'] ?? 0,
                    'status' => 'Ready for Payment',
                    'created_dt' => now(),
                ]);
            }

            // 2. Send Admin Mail
            $apiKey = env('MANDRILL_API_KEY', 'md-C4tnrZLOhMZ6QVa-Q5TEEg');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';
            
            $adminMailBody = "<h1>Notification of pending order</h1><p>Customer {$contact['firstName']} {$contact['lastName']} is preparing to checkout.</p>";
            
            if (view()->exists('emails.order-mail-before-payment')) {
                $adminMailBody = view('emails.order-mail-before-payment', [
                    'contact' => $contact,
                    'cart_items' => $validated['cart_items'],
                    'amount' => $validated['amount'],
                    'currency' => $validated['currency'],
                    'client_ip' => $ipAddress
                ])->render();
            }

            $toAdmin = [
                [
                    'email' => env('TO_MAIL', 'tlog@londontfe.com'),
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

            Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $adminMailBody,
                    'text' => strip_tags($adminMailBody),
                    'subject' => 'Notification to pending order',
                    'from_email' => 'no-reply@londontfe.com',
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => $toAdmin
                ]
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Before Pay Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_type' => 'required|string',
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'contact' => 'required|array',
                'cart_items' => 'required|array',
                'transaction_id' => 'nullable|string',
                'invoice' => 'nullable|array'
            ]);

            DB::beginTransaction();

            // 1. Create or Update User (similar to legacy logic)
            // For simplicity, we just store contact details in booking for now,
            // but you can expand this to insert into 'users' table if needed.
            $contact = $validated['contact'];

            // 2. Insert Booking Master
            $bookingId = DB::table('booking_master')->insertGetId([
                'payment_type' => $validated['payment_type'],
                'payment_currency' => $validated['currency'],
                'payment_amount' => $validated['amount'],
                'booking_date' => now(),
                'user_email' => $contact['email'] ?? 'NA',
                'user_phone' => $contact['phone'] ?? 'NA',
                'first_name' => $contact['firstName'] ?? 'NA',
                'last_name' => $contact['lastName'] ?? 'NA',
                'transaction_id' => $validated['transaction_id'] ?? null,
                'status' => in_array($validated['payment_type'], ['card', 'gpay', 'applepay']) ? 'completed' : 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bookingReference = substr(md5(time()), 0, 10) . $bookingId;
            
            DB::table('booking_master')
                ->where('id', $bookingId)
                ->update(['booking_reference' => $bookingReference]);

            // 3. Insert Cart Items
            foreach ($validated['cart_items'] as $item) {
                $itemId = $item['id'] ?? '';
                $courseId = null;
                $scheduleId = null;

                if (str_contains($itemId, '-')) {
                    $parts = explode('-', $itemId);
                    $courseId = is_numeric($parts[0]) ? (int)$parts[0] : null;
                    $scheduleId = is_numeric($parts[1]) ? (int)$parts[1] : null;
                } else {
                    $courseId = is_numeric($itemId) ? (int)$itemId : null;
                    $scheduleId = $item['scheduleId'] ?? null;
                }

                DB::table('booking_items')->insert([
                    'booking_id' => $bookingId,
                    'course_id' => $courseId,
                    'schedule_id' => $scheduleId,
                    'course_name' => $item['title'] ?? $item['name'] ?? 'Unknown Course',
                    'quantity' => $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                    'venue' => $item['location'] ?? $item['venue'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 4. Handle Invoice details if payment_type is 'invoice'
            if ($validated['payment_type'] === 'invoice' && !empty($validated['invoice'])) {
                DB::table('booking_invoices')->insert([
                    'booking_id' => $bookingId,
                    'company_name' => $validated['invoice']['companyName'] ?? null,
                    'finance_name' => $validated['invoice']['financeName'] ?? null,
                    'finance_email' => $validated['invoice']['financeEmail'] ?? null,
                    'finance_phone' => $validated['invoice']['financePhone'] ?? null,
                    'address' => $validated['invoice']['address'] ?? null,
                    'address2' => $validated['invoice']['address2'] ?? null,
                    'city' => $validated['invoice']['city'] ?? null,
                    'postcode' => $validated['invoice']['postcode'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // 5. Save logs to user_log table for each item
            $ipAddress = $request->ip();
            foreach ($validated['cart_items'] as $item) {
                DB::table('user_log')->insert([
                    'log_type' => 'aftercheckout',
                    'name' => trim(($contact['firstName'] ?? '') . ' ' . ($contact['lastName'] ?? '')),
                    'email' => $contact['email'] ?? '',
                    'phone_no' => $contact['phone'] ?? '',
                    'ip' => $ipAddress,
                    'country' => $contact['country'] ?? '',
                    'course_url' => '',
                    'coursename' => $item['title'] ?? $item['name'] ?? 'Unknown',
                    'categoryname' => '', // Add category if passed from frontend
                    'coursevenue' => $item['location'] ?? $item['venue'] ?? '',
                    'quantity' => $item['quantity'] ?? 1,
                    'coursestartdt' => $item['date'] ?? null, // Add start date if passed from frontend
                    'currency' => $validated['currency'],
                    'price' => $item['price'] ?? 0,
                    'status' => 'Payment Success with ' . $validated['payment_type'],
                    'created_dt' => now(),
                ]);
            }

            // 6. Send Confirmation Emails (Mandrill)
            $this->sendConfirmationEmail($bookingId, $bookingReference, $contact, $validated);

            return response()->json([
                'success' => true,
                'booking_reference' => $bookingReference,
                'message' => 'Booking successfully created.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }

    private function sendConfirmationEmail($bookingId, $bookingReference, $contact, $validated)
    {
        try {
            $apiKey = env('MANDRILL_API_KEY', 'md-C4tnrZLOhMZ6QVa-Q5TEEg');
            $url = 'https://mandrillapp.com/api/1.0/messages/send.json';

            $courseNames = collect($validated['cart_items'])->map(function($item) {
                return $item['title'] ?? $item['name'] ?? 'Unknown Course';
            })->implode(', ');
            
            $amount = $validated['amount'];
            $currency = $validated['currency'];
            
            $name = trim(($contact['firstName'] ?? '') . ' ' . ($contact['lastName'] ?? ''));
            $email = $contact['email'] ?? '';
            $city = $contact['city'] ?? '';
            $country = $contact['country'] ?? '';
            $address = $contact['address'] ?? '';

            // User Mail Logic
            $subject = 'Order Confirmation';
            $userMailBody = "<h1>Order Confirmation</h1><p>Thank you for your booking! Reference: {$bookingReference}</p>";
            
            // Try fetching from DB (like old autoResponse logic)
            try {
                $resDetails = DB::table('auto_responce_content')->where('form_name', 'ecommerce_payment')->first();
                if ($resDetails && !empty($resDetails->mail_content)) {
                    $subject = $resDetails->mail_subject ?? 'Order Confirmation';
                    $preview = $resDetails->mail_preview ?? '';
                    $contents = $resDetails->mail_content;
                    
                    $replaceFrom = ["{NAME}", "{{TXNTEXT}}", "{{TXN}}", "{{COURSETEXT}}", "{{COURSE}}", "{{PAYMENTTEXT}}", "{{PAYMENTAMT}}", "{{NAMETEXT}}", "{{FULLNAME}}", "{{EMAILTEXT}}", "{{EMAIL}}", "{{CITYTEXT}}", "{{CITY}}", "{{COUNTRYTEXT}}", "{{COUNTRY}}", "{{ADDRESSTEXT}}", "{{ADDRESS}}"];
                    $replaceTo = [$name, "Transaction ID", $bookingReference, "Courses", $courseNames, "Payment Amount", $amount, "Name", $name, "Email", $email, "City", $city, "Country", $country, "Address", $address];
                    
                    $userMailBody = "<!doctype html><html><head><meta charset='utf-8'><title>Order Confirmation</title></head><body><div style='display: none; max-height: 0px; overflow: hidden;'>".$preview."</div>".str_replace($replaceFrom, $replaceTo, $contents)."</body></html>";
                } elseif ($resDetails && !empty($resDetails->default_content)) {
                    $userMailBody = $resDetails->default_content;
                }
            } catch (\Exception $e) {
                // Table might not exist, ignore and use fallback
            }

            // Alternatively, load from blade view if it exists (like the old code's final override)
            if (view()->exists('emails.order-mail-user')) {
                $userMailBody = view('emails.order-mail-user', [
                    'bookingReference' => $bookingReference,
                    'contact' => $contact,
                    'cart_items' => $validated['cart_items'],
                    'amount' => $amount,
                    'currency' => $currency
                ])->render();
            }

            // Send to User
            Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $userMailBody,
                    'subject' => $subject,
                    'from_email' => 'no-reply@londontfe.com',
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

            // Admin Mail Logic
            $adminMailBody = "<h1>New Booking Received</h1><p>Reference: {$bookingReference}</p>";
            if (view()->exists('emails.order-mail-admin')) {
                $adminMailBody = view('emails.order-mail-admin', [
                    'bookingReference' => $bookingReference,
                    'contact' => $contact,
                    'cart_items' => $validated['cart_items'],
                    'amount' => $amount,
                    'currency' => $currency,
                    'client_ip' => request()->ip()
                ])->render();
            }

            // Send to Admin
            $toAdmin = [
                [
                    'email' => env('TO_MAIL', 'tlog@londontfe.com'),
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

            Http::post($url, [
                'key' => $apiKey,
                'message' => [
                    'html' => $adminMailBody,
                    'subject' => 'Order Details',
                    'from_email' => 'no-reply@londontfe.com',
                    'from_name' => env('FROM_MSG', 'Londontfe'),
                    'to' => $toAdmin
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send Mandrill email: ' . $e->getMessage());
        }
    }
}
