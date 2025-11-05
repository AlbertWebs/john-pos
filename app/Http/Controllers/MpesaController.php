<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\PendingPayment;
use App\Services\MpesaService;

class MpesaController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
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
            // Validate M-Pesa configuration
            $validation = $this->mpesaService->validateConfig();
            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => 'M-Pesa configuration incomplete',
                    'error' => 'Please configure the following in your .env file: ' . implode(', ', $validation['errors']),
                ], 400);
            }

            // Initiate STK Push using service
            $result = $this->mpesaService->initiateStkPush(
                $validated['phone_number'],
                $validated['amount'],
                $validated['account_reference'],
                $validated['transaction_desc'] ?? null
            );

            // Save transaction request
            if ($request->filled('sale_id')) {
                Payment::create([
                    'sale_id' => $validated['sale_id'],
                    'payment_method' => 'M-Pesa',
                    'amount' => $validated['amount'],
                    'transaction_reference' => $result['checkout_request_id'],
                    'payment_date' => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'STK Push initiated successfully',
                'checkout_request_id' => $result['checkout_request_id'],
                'customer_message' => $result['customer_message'],
                'data' => $result,
            ]);

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

            // Handle STK Push callback
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

            // Handle C2B (Customer to Business) payment callback
            if (isset($data['TransactionType']) && ($data['TransactionType'] == 'Pay Bill' || $data['TransactionType'] == 'CustomerPayBillOnline')) {
                $transactionType = $data['TransactionType'] ?? 'C2B';
                $transactionId = $data['TransID'] ?? null;
                $transTime = $data['TransTime'] ?? null;
                $transAmount = $data['TransAmount'] ?? null;
                $businessShortCode = $data['BusinessShortCode'] ?? null;
                $billRefNumber = $data['BillRefNumber'] ?? null; // Account reference
                $invoiceNumber = $data['InvoiceNumber'] ?? null;
                $orgAccountBalance = $data['OrgAccountBalance'] ?? null;
                $thirdPartyTransID = $data['ThirdPartyTransID'] ?? null;
                $msisdn = $data['MSISDN'] ?? null; // Phone number
                $firstName = $data['FirstName'] ?? null;
                $middleName = $data['MiddleName'] ?? null;
                $lastName = $data['LastName'] ?? null;

                // Check if transaction already exists
                $existingPayment = PendingPayment::where('transaction_reference', $transactionId)->first();
                
                if ($existingPayment) {
                    Log::info('C2B Payment Already Processed', [
                        'transaction_id' => $transactionId,
                    ]);
                    return response()->json(['status' => 'success', 'message' => 'Payment already processed'], 200);
                }

                // Parse transaction date
                $transactionDate = null;
                if ($transTime) {
                    try {
                        // Format: YYYYMMDDHHmmss
                        $transactionDate = \Carbon\Carbon::createFromFormat('YmdHis', $transTime);
                    } catch (\Exception $e) {
                        $transactionDate = now();
                    }
                } else {
                    $transactionDate = now();
                }

                // Create pending payment
                PendingPayment::create([
                    'transaction_reference' => $transactionId,
                    'phone_number' => $msisdn,
                    'amount' => $transAmount,
                    'account_reference' => $billRefNumber,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'transaction_type' => 'C2B',
                    'status' => 'pending',
                    'transaction_date' => $transactionDate,
                    'raw_data' => $data,
                ]);

                Log::info('C2B Payment Created as Pending', [
                    'transaction_id' => $transactionId,
                    'amount' => $transAmount,
                    'account_reference' => $billRefNumber,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Payment received and pending allocation'], 200);
            }

            return response()->json(['status' => 'received'], 200);

        } catch (\Exception $e) {
            Log::error('M-Pesa Callback Error', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
                'trace' => $e->getTraceAsString(),
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
            $result = $this->mpesaService->queryStkPushStatus($validated['checkout_request_id']);

            return response()->json($result);

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
     * Simulate Random C2B Transaction (for testing)
     */
    public function simulateC2B(Request $request)
    {
        // Generate random transaction data
        $firstNames = ['John', 'Jane', 'James', 'Mary', 'Robert', 'Patricia', 'Michael', 'Linda', 'William', 'Elizabeth'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez'];
        $middleNames = ['', 'A', 'B', 'C', 'D', 'E'];
        
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        $middleName = $middleNames[array_rand($middleNames)];
        
        // Generate random phone number (Kenyan format)
        $phoneNumber = '2547' . rand(10000000, 99999999);
        
        // Generate random amount between 100 and 10000
        $amount = rand(100, 10000);
        
        // Generate random transaction ID (format: RAI + timestamp + random)
        $transactionId = 'RAI' . date('YmdHis') . rand(1000, 9999);
        
        // Generate transaction time (current time in M-Pesa format: YYYYMMDDHHmmss)
        $transTime = date('YmdHis');
        
        // Generate random account reference (can be invoice number or any reference)
        $accountReferences = ['INV-202501-0001', 'SALE-001', 'POS-' . date('Ymd'), 'CUSTOM-' . rand(1000, 9999)];
        $accountReference = $accountReferences[array_rand($accountReferences)];
        
        // Simulate M-Pesa C2B callback payload
        $simulatedData = [
            'TransactionType' => 'CustomerPayBillOnline',
            'TransID' => $transactionId,
            'TransTime' => $transTime,
            'TransAmount' => $amount,
            'BusinessShortCode' => env('MPESA_SHORTCODE', '123456'),
            'BillRefNumber' => $accountReference,
            'InvoiceNumber' => '',
            'OrgAccountBalance' => rand(100000, 1000000),
            'ThirdPartyTransID' => '',
            'MSISDN' => $phoneNumber,
            'FirstName' => $firstName,
            'MiddleName' => $middleName,
            'LastName' => $lastName,
        ];
        
        // Process the simulated C2B transaction directly
        try {
            // Check if transaction already exists
            $existingPayment = PendingPayment::where('transaction_reference', $transactionId)->first();
            
            if ($existingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction with this ID already exists',
                    'transaction_id' => $transactionId,
                ], 400);
            }

            // Parse transaction date
            $transactionDate = \Carbon\Carbon::createFromFormat('YmdHis', $transTime);

            // Create pending payment
            $pendingPayment = PendingPayment::create([
                'transaction_reference' => $transactionId,
                'phone_number' => $phoneNumber,
                'amount' => $amount,
                'account_reference' => $accountReference,
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'transaction_type' => 'C2B',
                'status' => 'pending',
                'transaction_date' => $transactionDate,
                'raw_data' => $simulatedData,
            ]);

            Log::info('C2B Transaction Simulated', [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'account_reference' => $accountReference,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'C2B transaction simulated successfully',
                'simulated_transaction' => [
                    'transaction_id' => $transactionId,
                    'phone_number' => $phoneNumber,
                    'amount' => $amount,
                    'account_reference' => $accountReference,
                    'customer_name' => trim("$firstName $middleName $lastName"),
                    'transaction_date' => $transactionDate->toDateTimeString(),
                    'pending_payment_id' => $pendingPayment->id,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('C2B Simulation Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to simulate C2B transaction',
                'error' => $e->getMessage(),
                'simulated_data' => $simulatedData,
            ], 500);
        }
    }
}
