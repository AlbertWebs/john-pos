<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class MpesaService
{
    /**
     * Get M-Pesa configuration
     */
    public function getConfig(): array
    {
        $environment = config('mpesa.environment', 'sandbox');
        $callbackUrl = config('mpesa.callback_url');
        
        // If callback URL is not set, use route (but only if we're in a web context)
        if (empty($callbackUrl)) {
            try {
                $callbackUrl = route('mpesa.callback');
            } catch (\Exception $e) {
                // If route() is not available (e.g., in CLI), use default
                $callbackUrl = url('/mpesa/callback');
            }
        }
        
        return [
            'consumer_key' => config('mpesa.consumer_key'),
            'consumer_secret' => config('mpesa.consumer_secret'),
            'passkey' => config('mpesa.passkey'),
            'shortcode' => config('mpesa.shortcode'),
            'environment' => $environment,
            'callback_url' => $callbackUrl,
            'base_url' => config("mpesa.base_urls.{$environment}", config('mpesa.base_urls.sandbox')),
            'timeout' => config('mpesa.timeout', 30),
            'transaction_type' => config('mpesa.transaction_type', 'CustomerPayBillOnline'),
        ];
    }

    /**
     * Get base URL based on environment
     */
    public function getBaseUrl(): string
    {
        $environment = config('mpesa.environment', 'sandbox');
        return config("mpesa.base_urls.{$environment}", config('mpesa.base_urls.sandbox'));
    }

    /**
     * Generate Access Token
     * 
     * @throws \Exception
     */
    public function getAccessToken(): string
    {
        $config = $this->getConfig();
        
        // Validate credentials are configured
        if (empty($config['consumer_key']) || empty($config['consumer_secret'])) {
            Log::error('M-Pesa Credentials Not Configured', [
                'consumer_key_set' => !empty($config['consumer_key']),
                'consumer_secret_set' => !empty($config['consumer_secret']),
            ]);
            throw new \Exception('M-Pesa credentials are not configured. Please set MPESA_CONSUMER_KEY and MPESA_CONSUMER_SECRET in your .env file.');
        }

        // Try to get from cache first (tokens expire after 1 hour)
        $cacheKey = 'mpesa_access_token_' . $config['environment'];
        $cachedToken = Cache::get($cacheKey);
        
        if ($cachedToken) {
            return $cachedToken;
        }

        $baseUrl = $this->getBaseUrl();
        $url = $baseUrl . config('mpesa.endpoints.oauth') . '?grant_type=client_credentials';
        
        try {
            $response = Http::timeout($config['timeout'])
                ->withBasicAuth($config['consumer_key'], $config['consumer_secret'])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['access_token'])) {
                    $token = $data['access_token'];
                    
                    // Cache the token for 55 minutes (tokens expire after 1 hour)
                    Cache::put($cacheKey, $token, now()->addMinutes(55));
                    
                    return $token;
                }
                
                Log::error('M-Pesa Access Token Response Missing Token', [
                    'response' => $data,
                    'status' => $response->status(),
                ]);
                throw new \Exception('M-Pesa API returned invalid response. Access token not found.');
            }

            // Log detailed error information
            $errorData = [
                'status' => $response->status(),
                'response' => $response->json(),
                'body' => $response->body(),
            ];
            
            Log::error('M-Pesa Access Token Error', $errorData);
            
            $errorMessage = 'Failed to get M-Pesa access token';
            if ($response->status() === 401) {
                $errorMessage = 'Invalid M-Pesa credentials. Please check your consumer key and secret.';
            } elseif ($response->status() === 0 || $response->status() === null) {
                $errorMessage = 'Unable to connect to M-Pesa API. Please check your internet connection and try again.';
            } else {
                $errorMessage = 'M-Pesa API error: ' . ($response->json()['error_description'] ?? $response->json()['errorMessage'] ?? 'Unknown error');
            }
            
            throw new \Exception($errorMessage);
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('M-Pesa Connection Error', [
                'error' => $e->getMessage(),
                'url' => $url,
            ]);
            throw new \Exception('Unable to connect to M-Pesa API. Please check your internet connection.');
        } catch (\Exception $e) {
            // Re-throw if already our custom exception
            if (strpos($e->getMessage(), 'M-Pesa') !== false) {
                throw $e;
            }
            
            Log::error('M-Pesa Access Token Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \Exception('Failed to get M-Pesa access token: ' . $e->getMessage());
        }
    }

    /**
     * Initiate STK Push
     * 
     * @param string $phoneNumber Phone number in format 254XXXXXXXXX
     * @param float $amount Amount to charge
     * @param string $accountReference Account reference
     * @param string|null $transactionDesc Transaction description
     * @return array
     * @throws \Exception
     */
    public function initiateStkPush(string $phoneNumber, float $amount, string $accountReference, ?string $transactionDesc = null): array
    {
        $config = $this->getConfig();
        
        // Validate all required credentials
        $requiredFields = ['shortcode', 'passkey'];
        $missingFields = [];
        foreach ($requiredFields as $field) {
            if (empty($config[$field])) {
                $missingFields[] = 'MPESA_' . strtoupper($field);
            }
        }
        
        if (!empty($missingFields)) {
            throw new \Exception('M-Pesa configuration incomplete. Please configure: ' . implode(', ', $missingFields));
        }

        $accessToken = $this->getAccessToken();
        $baseUrl = $this->getBaseUrl();
        $url = $baseUrl . config('mpesa.endpoints.stk_push');

        // Format phone number
        $phone = $this->formatPhoneNumber($phoneNumber);

        // Generate timestamp
        $timestamp = date('YmdHis');

        // Generate password
        $password = base64_encode($config['shortcode'] . $config['passkey'] . $timestamp);

        $payload = [
            'BusinessShortCode' => $config['shortcode'],
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $config['transaction_type'],
            'Amount' => (int) $amount,
            'PartyA' => $phone,
            'PartyB' => $config['shortcode'],
            'PhoneNumber' => $phone,
            'CallBackURL' => $config['callback_url'],
            'AccountReference' => $accountReference,
            'TransactionDesc' => $transactionDesc ?? 'Payment for order',
        ];

        try {
            $response = Http::timeout($config['timeout'])
                ->withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == 0) {
                return [
                    'success' => true,
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'customer_message' => $responseData['CustomerMessage'],
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'response_code' => $responseData['ResponseCode'],
                    'response_description' => $responseData['ResponseDescription'] ?? null,
                ];
            }

            throw new \Exception($responseData['errorMessage'] ?? $responseData['ResponseDescription'] ?? 'Failed to initiate STK Push');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('M-Pesa STK Push Connection Error', [
                'error' => $e->getMessage(),
                'url' => $url,
            ]);
            throw new \Exception('Unable to connect to M-Pesa API. Please check your internet connection.');
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Error', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);
            throw $e;
        }
    }

    /**
     * Query STK Push Status
     * 
     * @param string $checkoutRequestId Checkout request ID from STK Push
     * @return array
     * @throws \Exception
     */
    public function queryStkPushStatus(string $checkoutRequestId): array
    {
        $config = $this->getConfig();
        $accessToken = $this->getAccessToken();
        $baseUrl = $this->getBaseUrl();
        $url = $baseUrl . config('mpesa.endpoints.stk_push_query');

        $timestamp = date('YmdHis');
        $password = base64_encode($config['shortcode'] . $config['passkey'] . $timestamp);

        $payload = [
            'BusinessShortCode' => $config['shortcode'],
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestId,
        ];

        try {
            $response = Http::timeout($config['timeout'])
                ->withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'result_code' => $responseData['ResultCode'] ?? null,
                    'result_desc' => $responseData['ResultDesc'] ?? null,
                    'checkout_request_id' => $responseData['CheckoutRequestID'] ?? null,
                    'merchant_request_id' => $responseData['MerchantRequestID'] ?? null,
                    'data' => $responseData,
                ];
            }

            throw new \Exception($responseData['errorMessage'] ?? 'Failed to query STK Push status');

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('M-Pesa Query Connection Error', [
                'error' => $e->getMessage(),
                'url' => $url,
            ]);
            throw new \Exception('Unable to connect to M-Pesa API. Please check your internet connection.');
        } catch (\Exception $e) {
            Log::error('M-Pesa Query Error', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Format phone number to 254XXXXXXXXX format
     * 
     * @param string $phone Phone number in various formats
     * @return string Formatted phone number
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove any spaces or special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to 254 format if needed
        if (strlen($phone) == 9) {
            // 9 digits starting with 7 (e.g., 712345678)
            return '254' . $phone;
        } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
            // Starts with 0, remove and add 254 (e.g., 0712345678)
            return '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 4) == '+254') {
            // Already has +254, remove +
            return substr($phone, 1);
        } elseif (substr($phone, 0, 3) == '254') {
            // Already in correct format
            return $phone;
        }
        
        return $phone;
    }

    /**
     * Validate M-Pesa configuration
     * 
     * @return array Array with 'valid' boolean and 'errors' array
     */
    public function validateConfig(): array
    {
        $config = $this->getConfig();
        $errors = [];

        if (empty($config['consumer_key'])) {
            $errors[] = 'MPESA_CONSUMER_KEY is not configured';
        }

        if (empty($config['consumer_secret'])) {
            $errors[] = 'MPESA_CONSUMER_SECRET is not configured';
        }

        if (empty($config['passkey'])) {
            $errors[] = 'MPESA_PASSKEY is not configured';
        }

        if (empty($config['shortcode'])) {
            $errors[] = 'MPESA_SHORTCODE is not configured';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }
}

