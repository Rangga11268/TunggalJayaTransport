<?php

return [

    

    'name' => env('APP_NAME', 'Laravel'),

    

    'logo' => env('APP_LOGO', 'images/logo.png'),

    

    'contact_email' => env('CONTACT_EMAIL', 'contact@example.com'),

    

    'contact_phone' => env('CONTACT_PHONE', '+1 (555) 123-4567'),

    

    'env' => env('APP_ENV', 'production'),

    

    'debug' => (bool) env('APP_DEBUG', false),

    

    'url' => env('APP_URL', 'http://localhost'),

    

    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),

    

    'locale' => env('APP_LOCALE', 'id'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
