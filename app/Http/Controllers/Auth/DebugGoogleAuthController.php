<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class DebugGoogleAuthController extends Controller
{
    /**
     * Debug Google OAuth Configuration
     */
    public function debug()
    {
        $config = config('services.google');

        $debug = [
            'GOOGLE_CLIENT_ID' => [
                'value' => env('GOOGLE_CLIENT_ID'),
                'config' => $config['client_id'] ?? 'NOT SET',
                'status' => !empty($config['client_id']) ? '✅ OK' : '❌ MISSING',
            ],
            'GOOGLE_CLIENT_SECRET' => [
                'value' => substr(env('GOOGLE_CLIENT_SECRET'), 0, 10) . '...' ?? 'NOT SET',
                'config' => $config['client_secret'] ? 'Set' : 'NOT SET',
                'status' => !empty($config['client_secret']) ? '✅ OK' : '❌ MISSING',
            ],
            'GOOGLE_REDIRECT_URI' => [
                'value' => env('GOOGLE_REDIRECT_URI'),
                'config' => $config['redirect'] ?? 'NOT SET',
                'status' => !empty($config['redirect']) ? '✅ OK' : '❌ MISSING',
            ],
            'Routes' => [
                'auth.google' => route('auth.google'),
                'auth.google.callback' => route('auth.google.callback'),
            ],
            'Database' => [
                'Users table has google_id' => 'Check migrations',
                'Migration status' => 'Run: php artisan migrate',
            ],
            'Laravel Socialite' => [
                'Package installed' => 'Yes (v5.24.3)',
                'Config published' => 'Check config/services.php',
            ],
        ];

        return response()->json($debug, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
