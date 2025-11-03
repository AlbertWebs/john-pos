<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class MpesaController extends Controller
{
    /**
     * M-Pesa API Configuration
     * Sandbox credentials for testing
     */
    private function getMpesaConfig()
    {
        return [
            'consumer_key' => env('MPESA_CONSUMER_KEY', 'YOUR_CONSUMER_KEY'),
            'consumer_secret' => env('MPESA_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET'),
            'passkey' => env('MPESA_PASSKEY', 'YOUR_PASSKEY'),
            'shortcode' => env('MPESA_SHORTCODE', 'YOUR_SHORTCODE'), // Paybill or Till number
            'environment' => env('MPESA_ENVIRONMENT', 'sandbox'), // sandbox or production
            'callback_url' => env('MPESA_CALLBACK_URL', route('mpesa.callback')),
        ];
    }

    /**
     * Generate Access Token
     */
    private function getAccessToken()
    {
        $config = $this->getMpesaConfig();
        $baseUrl = $config['environment'] === 'sandbox' 
            ? 'https://sandbox.safaricom.co.ke' 
            : 'https://api.safaricom.co.ke';

        $url = $baseUrl . '/oauth/v1/generate?grant_type=client_credentials';
        
        $response = Http::withBasicAuth($config['consumer_key'], $config['consumer_secret'])
            ->get($url);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('M-Pesa Access Token Error', ['response' => $response->json()]);
        throw new \Exception('Failed to get M-Pesa access token');
    }

    /**
     * Initiate STK Push
     */
    public function stkPush(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|min:10|max:12',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'required|string',
            'transaction_desc' => 'nullable|string|max:255',
            'sale_id' => 'nullable|exists:sales,id',
        ]);

        try {
            $config = $this->getMpesaConfig();
            $accessToken = $this->getAccessToken();
            
            $baseUrl = $config['environment'] === 'sandbox' 
                ? 'https://sandbox.safaricom.co.ke' 
                : 'https://api.safaricom.co.ke';

            $url = $baseUrl . '/mpesa/stkpush/v1/processrequest';

            // Format phone number (254XXXXXXXXX)
            $phone = $this->formatPhoneNumber($validated['phone_number']);

            // Generate timestamp
            $timestamp = date('YmdHis');

            // Generate password
            $password = base64_encode($config['shortcode'] . $config['passkey'] . $timestamp);

            $payload = [
                'BusinessShortCode' => $config['shortcode'],
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) $validated['amount'],
                'PartyA' => $phone,
                'PartyB' => $config['shortcode'],
                'PhoneNumber' => $phone,
                'CallBackURL' => $config['callback_url'],
                'AccountReference' => $validated['account_reference'],
                'TransactionDesc' => $validated['transaction_desc'] ?? 'Payment for order',
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == 0) {
                // Save transaction request
                if ($request->filled('sale_id')) {
                    Payment::create([
                        'sale_id' => $validated['sale_id'],
                        'payment_method' => 'M-Pesa',
                        'amount' => $validated['amount'],
                        'transaction_reference' => $responseData['CheckoutRequestID'],
                        'payment_date' => now(),
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'STK Push initiated successfully',
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'customer_message' => $responseData['CustomerMessage'],
                    'data' => $responseData,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $responseData['errorMessage'] ?? 'Failed to initiate STK Push',
                'error' => $responseData,
            ], 400);

        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * M-Pesa Callback Handler
     */
    public function callback(Request $request)
    {
        try {
            $data = $request->all();
            
            Log::info('M-Pesa Callback Received', $data);

            // Process callback based on M-Pesa callback structure
            if (isset($data['Body']['stkCallback'])) {
                $callback = $data['Body']['stkCallback'];
                $resultCode = $callback['ResultCode'] ?? null;
                $resultDesc = $callback['ResultDesc'] ?? null;
                $checkoutRequestID = $callback['CheckoutRequestID'] ?? null;

                if ($resultCode == 0) {
                    // Payment successful
                    $callbackMetadata = $callback['CallbackMetadata']['Item'] ?? [];
                    $mpesaReceiptNumber = null;
                    $amount = null;
                    $phoneNumber = null;

                    foreach ($callbackMetadata as $item) {
                        if ($item['Name'] == 'MpesaReceiptNumber') {
                            $mpesaReceiptNumber = $item['Value'];
                        }
                        if ($item['Name'] == 'Amount') {
                            $amount = $item['Value'];
                        }
                        if ($item['Name'] == 'PhoneNumber') {
                            $phoneNumber = $item['Value'];
                        }
                    }

                    // Update payment record
                    $payment = Payment::where('transaction_reference', $checkoutRequestID)->first();
                    if ($payment) {
                        $payment->update([
                            'transaction_reference' => $mpesaReceiptNumber,
                            'payment_date' => now(),
                        ]);
                    }

                    return response()->json(['status' => 'success'], 200);
                } else {
                    // Payment failed
                    Log::warning('M-Pesa Payment Failed', [
                        'checkout_request_id' => $checkoutRequestID,
                        'result_code' => $resultCode,
                        'result_desc' => $resultDesc,
                    ]);

                    return response()->json([
                        'status' => 'failed',
                        'result_code' => $resultCode,
                        'result_desc' => $resultDesc,
                    ], 200);
                }
            }

            return response()->json(['status' => 'received'], 200);

        } catch (\Exception $e) {
            Log::error('M-Pesa Callback Error', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Check Payment Status
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'checkout_request_id' => 'required|string',
        ]);

        try {
            $config = $this->getMpesaConfig();
            $accessToken = $this->getAccessToken();
            
            $baseUrl = $config['environment'] === 'sandbox' 
                ? 'https://sandbox.safaricom.co.ke' 
                : 'https://api.safaricom.co.ke';

            $url = $baseUrl . '/mpesa/stkpushquery/v1/query';

            $timestamp = date('YmdHis');
            $password = base64_encode($config['shortcode'] . $config['passkey'] . $timestamp);

            $payload = [
                'BusinessShortCode' => $config['shortcode'],
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $validated['checkout_request_id'],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            return response()->json($response->json());

        } catch (\Exception $e) {
            Log::error('M-Pesa Status Check Error', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to check payment status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to 254 format if needed
        if (strlen($phone) == 9) {
            // Starts with 0, remove and add 254
            return '254' . $phone;
        } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
            // Starts with 0, remove and add 254
            return '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) == '+254') {
            // Already has +254, remove +
            return substr($phone, 1);
        }
        
        return $phone;
    }
}
