<?php

namespace App\Services;

use App\Models\OtpCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OtpService
{
    public const OTP_LENGTH = 6;
    public const OTP_EXPIRY_MINUTES = 10;

    public function generate(string $phone): string
    {
        // Hapus OTP lama yang belum digunakan
        $this->deleteUnusedOtp($phone);

        // Generate OTP baru
        $otp = $this->createOtpString();
        
        // Simpan ke database
        OtpCode::create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);

        // Simpan OTP ke session untuk keperluan development/testing
        if (app()->environment('local', 'development', 'testing')) {
            Session::put('debug_otp', $otp);
            Session::put('debug_phone', $phone);
        }

        // Simulasikan pengiriman SMS
        // Di implementasi nyata, tambahkan integrasi SMS gateway di sini
        Log::info("OTP $otp dikirim ke nomor $phone");
        
        return $otp;
    }

    public function verify(string $phone, string $otp): bool
    {
        $otpRecord = OtpCode::where('phone', $phone)
            ->where('otp', $otp)
            ->first();

        if (!$otpRecord) {
            return false;
        }

        if (!$otpRecord->isUsable()) {
            return false;
        }

        // Tandai OTP sebagai sudah digunakan
        $otpRecord->update(['used' => true]);

        return true;
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
            Session::forget(['debug_otp', 'debug_phone']);
        }
    }

    private function createOtpString(): string
    {
        return str_pad(random_int(0, pow(10, self::OTP_LENGTH) - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }

    private function deleteUnusedOtp(string $phone): void
    {
        OtpCode::where('phone', $phone)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->delete();
    }
}