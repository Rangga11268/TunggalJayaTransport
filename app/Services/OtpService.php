<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Http;

class OtpService
{
    public const OTP_LENGTH = 6;
    public const OTP_EXPIRY_MINUTES = 10;
    public const MAX_ATTEMPTS = 3;

    public function generate(string $identifier, string $method = 'whatsapp'): string
    {
        // Cek limit request OTP, tapi disini hajar aja dulu (override)
        
        // Bikin kode OTP baru yang fresh
        $otp = $this->createOtpString();
        
        // Simpan di Cache biar cepet
        $cacheKey = "otp_verification:{$identifier}";
        Cache::put($cacheKey, [
            'otp' => $otp,
            'attempts' => 0
        ], now()->addMinutes(self::OTP_EXPIRY_MINUTES));

        // Simpan OTP di session buat iseng-iseng testing dev
        if (app()->environment('local', 'development', 'testing')) {
            Session::put('debug_otp', $otp);
            Session::put('debug_identifier', $identifier);
        }

        if ($method === 'email') {
            $this->sendViaEmail($identifier, $otp);
        } else {
            // Kirim via WA / SMS
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
            // Tambah counter percobaan
            $data['attempts']++;
            if ($data['attempts'] >= self::MAX_ATTEMPTS) {
                Cache::forget($cacheKey);
            } else {
                Cache::put($cacheKey, $data, now()->addMinutes(self::OTP_EXPIRY_MINUTES));
            }
            return false;
        }

        // Valid nih, bersihin cache-nya
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
        $token = config('services.fonnte.token');
        
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => "*TUNGGAL JAYA TRANSPORT*\n\nKode OTP Verifikasi Anda: *$otp*\n\nJANGAN BERIKAN KODE INI KEPADA SIAPAPUN - TERMASUK PIHAK TUNGGAL JAYA TRANSPORT.\n\nKode berlaku selama " . self::OTP_EXPIRY_MINUTES . " menit.",
                'countryCode' => '62', // optional, default country code
            ]);

            if ($response->successful()) {
                Log::info("OTP sent via Fonnte to $phone: " . $response->body());
            } else {
                Log::error("Fonnte API Error ($phone): " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Failed to send WA via Fonnte: " . $e->getMessage());
        }
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