<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentApiController extends Controller
{
    public function createRevolutOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            // Add other necessary fields like course_id, schedule_id, email, etc.
        ]);

        $amount = (int) ($request->input('amount') * 100); // Revolut uses minor units (cents)
        $currency = strtoupper($request->input('currency'));

        $revolutUrl = env('REVOLUT_URL');
        $revolutSecretKey = env('REVOLUT_SECRET_KEY');

        try {
            // Revolut requires Basic Auth with the Secret Key or Bearer Token (if it's in Bearer format)
            $authHeader = str_starts_with($revolutSecretKey, 'Bearer ') ? $revolutSecretKey : "Bearer {$revolutSecretKey}";

            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($revolutUrl, [
                'amount' => $amount,
                'currency' => $currency,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'public_id' => $data['token'] ?? $data['public_id'] ?? $data['id'] ?? null, 
                    // Let's return the whole response for flexibility
                    'order' => $data
                ]);
            }

            Log::error('Revolut Order Creation Failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order.',
                'error' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            Log::error('Revolut API Exception', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment service is unavailable.'
            ], 500);
        }
    }
}
