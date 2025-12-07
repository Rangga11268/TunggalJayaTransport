<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PhoneVerificationController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function show(): \Inertia\Response
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

        return Inertia::render('Auth/VerifyPhone', [
            'debugOtp' => $debugOtp,
            'status' => session('status'),
        ]);
    }

    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => 'nullable|string',
            'method' => 'required|in:whatsapp,email'
        ]);

        $user = Auth::user();
        if ($request->phone) {
            $user->update(['phone' => $request->phone]);
        }
        
        $method = $request->method;
        $identifier = $method === 'email' ? $user->email : $user->phone;

        if (!$identifier) {
             return redirect()->back()->withErrors(['phone' => 'Kontak tujuan tidak ditemukan.']);
        }

        // Generate OTP
        $this->otpService->generate($identifier, $method);
        
        $destination = $method === 'email' ? 'email' : 'nomor WhatsApp';
        return redirect()->back()->with('status', "Kode OTP telah dikirim ke $destination anda.");
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        
        // Try verifying with phone
        $isValid = false;
        if ($user->phone && $this->otpService->verify($user->phone, $request->otp)) {
            $isValid = true;
        } elseif ($user->email && $this->otpService->verify($user->email, $request->otp)) {
            $isValid = true;
        }

        if ($isValid) {
            // Update status verifikasi
            $user->update([
                'phone_verified_at' => now(), // Still use this column for "verified status"
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

        return redirect()->back()->withErrors(['otp' => 'Kode OTP tidak valid atau kadaluarsa.']);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Default to phone if not specified, or support method in request
        $method = $request->input('method', 'whatsapp');
        
        $identifier = $method === 'email' ? $user->email : $user->phone;

        if (!$identifier) {
             return redirect()->back()->withErrors(['phone' => 'Kontak tujuan tidak ditemukan.']);
        }
        
        $this->otpService->generate($identifier, $method);
        
        $destination = $method === 'email' ? 'email' : 'nomor WhatsApp';
        return redirect()->back()->with('status', "Kode OTP baru telah dikirim ke $destination.");
    }
}