<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    public const OTP_LENGTH = 6;
    public const OTP_EXPIRY_MINUTES = 10;
    public const MAX_ATTEMPTS = 3;

    public function generate(string $identifier, string $method = 'whatsapp'): string
    {
        // Check rate limiting / existing otp (Optional, simple override here)
        
        // Generate OTP baru
        $otp = $this->createOtpString();
        
        // Simpan ke Cache
        $cacheKey = "otp_verification:{$identifier}";
        Cache::put($cacheKey, [
            'otp' => $otp,
            'attempts' => 0
        ], now()->addMinutes(self::OTP_EXPIRY_MINUTES));

        // Simpan OTP ke session untuk keperluan development/testing
        if (app()->environment('local', 'development', 'testing')) {
            Session::put('debug_otp', $otp);
            Session::put('debug_identifier', $identifier);
        }

        if ($method === 'email') {
            $this->sendViaEmail($identifier, $otp);
        } else {
            // WhatsApp / SMS
            $this->sendViaWhatsapp($identifier, $otp);
        }
        
        return $otp;
    }

    public function verify(string $identifier, string $otp): bool
    {
        $cacheKey = "otp_verification:{$identifier}";
        $data = Cache::get($cacheKey);

        if (!$data) {
            return false; 
        }

        if ($data['otp'] !== $otp) {
            // Increment attempts
            $data['attempts']++;
            if ($data['attempts'] >= self::MAX_ATTEMPTS) {
                Cache::forget($cacheKey);
            } else {
                Cache::put($cacheKey, $data, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
            }
            return false;
        }

        // Valid, clear cache
        Cache::forget($cacheKey);
        
        return true;
    }

    private function sendViaEmail(string $email, string $otp): void
    {
        try {
            Mail::to($email)->send(new OtpMail($otp));
            Log::info("OTP $otp sent to email $email");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email: " . $e->getMessage());
        }
    }

    private function sendViaWhatsapp(string $phone, string $otp): void
    {
        // Simulasikan pengiriman SMS/WA
        // Di implementasi nyata, tambahkan integrasi SMS gateway di sini
        Log::info("OTP $otp dikirim ke nomor $phone");
    }

    public function getDebugOtp(): ?string
    {
        if (app()->environment('local', 'development', 'testing')) {
            return Session::get('debug_otp');
        }
        return null;
    }

    public function clearDebugOtp(): void
    {
        if (app()->environment('local', 'development', 'testing')) {
            Session::forget(['debug_otp', 'debug_identifier']);
        }
    }

    private function createOtpString(): string
    {
        return str_pad(random_int(0, pow(10, self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }
}