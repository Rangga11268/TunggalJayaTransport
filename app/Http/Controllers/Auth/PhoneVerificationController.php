<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PhoneVerificationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function show(): View
    {
        $user = Auth::user();
        
        // Jika user sudah fully verified, arahkan ke dashboard
        if ($user->isFullyVerified()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Dapatkan OTP untuk keperluan debugging di lingkungan development
        $debugOtp = null;
        if (app()->environment('local', 'development', 'testing')) {
            $debugOtp = $this->otpService->getDebugOtp();
        }

        return view('auth.verify-phone', compact('debugOtp'));
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $phone = $request->phone;
        
        // Update phone user
        $user = Auth::user();
        $user->update(['phone' => $phone]);
        
        // Generate OTP
        $this->otpService->generate($phone);
        
        return redirect()->back()->with('status', 'Kode OTP telah dikirim ke nomor anda.');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        
        if (!$user->phone) {
            return redirect()->back()->withErrors(['phone' => 'Nomor telepon belum diatur.']);
        }

        $isValid = $this->otpService->verify($user->phone, $request->otp);

        if ($isValid) {
            // Update status verifikasi
            $user->update([
                'phone_verified_at' => now(),
                'is_verified' => true
            ]);
            
            $this->otpService->clearDebugOtp(); // Clear OTP dari session setelah verifikasi berhasil
            
            // Redirect berdasarkan role user setelah verifikasi selesai
            if ($user->hasRole('admin') || $user->hasRole('schedule_manager')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            } else {
                // Redirect regular user ke halaman home
                return redirect()->intended(route('frontend.home', absolute: false));
            }
        }

        return redirect()->back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
    }

    public function resendOtp(): RedirectResponse
    {
        $user = Auth::user();
        
        if (!$user->phone) {
            return redirect()->back()->withErrors(['phone' => 'Nomor telepon belum diatur.']);
        }
        
        $this->otpService->generate($user->phone);
        
        return redirect()->back()->with('status', 'Kode OTP baru telah dikirim.');
    }
}