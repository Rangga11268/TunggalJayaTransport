<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the Midtrans payment gateway configuration values.
    | You can get these values from your Midtrans dashboard.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY', ''),
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),
    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'),
    'payment_url' => env('MIDTRANS_PAYMENT_URL', ''),
    'api_url' => env('MIDTRANS_API_URL', 
        env('MIDTRANS_ENVIRONMENT', 'sandbox') === 'sandbox' 
            ? 'https://api.sandbox.midtrans.com/v2/' 
            : 'https://api.midtrans.com/v2/'
    ),
];