<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-Pesa API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for M-Pesa API integration.
    | You can set these values in your .env file or override them here.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Consumer Key
    |--------------------------------------------------------------------------
    |
    | Your M-Pesa API consumer key. Get this from the Safaricom Developer Portal.
    |
    */
    'consumer_key' => env('MPESA_CONSUMER_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Consumer Secret
    |--------------------------------------------------------------------------
    |
    | Your M-Pesa API consumer secret. Get this from the Safaricom Developer Portal.
    |
    */
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Passkey
    |--------------------------------------------------------------------------
    |
    | Your M-Pesa API passkey. This is used for generating the password in STK Push.
    |
    */
    'passkey' => env('MPESA_PASSKEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Shortcode
    |--------------------------------------------------------------------------
    |
    | Your M-Pesa Business Shortcode (Paybill or Till number).
    |
    */
    'shortcode' => env('MPESA_SHORTCODE', ''),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Set to 'sandbox' for testing or 'production' for live transactions.
    |
    */
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Base URLs
    |--------------------------------------------------------------------------
    |
    | M-Pesa API base URLs for sandbox and production environments.
    |
    */
    'base_urls' => [
        'sandbox' => 'https://sandbox.safaricom.co.ke',
        'production' => 'https://api.safaricom.co.ke',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    |
    | M-Pesa API endpoints for different operations.
    |
    */
    'endpoints' => [
        'oauth' => '/oauth/v1/generate',
        'stk_push' => '/mpesa/stkpush/v1/processrequest',
        'stk_push_query' => '/mpesa/stkpushquery/v1/query',
    ],

    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | The URL where M-Pesa will send payment callbacks.
    |
    */
    'callback_url' => env('MPESA_CALLBACK_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | HTTP request timeout in seconds for M-Pesa API calls.
    |
    */
    'timeout' => env('MPESA_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Transaction Type
    |--------------------------------------------------------------------------
    |
    | Default transaction type for STK Push.
    | Options: 'CustomerPayBillOnline' or 'CustomerBuyGoodsOnline'
    |
    */
    'transaction_type' => env('MPESA_TRANSACTION_TYPE', 'CustomerPayBillOnline'),
];

