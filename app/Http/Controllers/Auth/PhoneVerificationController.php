<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PhoneVerificationController extends Controller
{
    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Show phone verification page
     */
    public function show(): Response|RedirectResponse
    {
        $user = Auth::user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if ($user->hasPhoneVerified()) {
            return redirect()->intended(route('frontend.home'));
        }

        $debugOtp = $this->getDebugOtp();

        return Inertia::render('Auth/VerifyPhone', [
            'debugOtp' => $debugOtp,
            'status' => session('status'),
        ]);
    }

    /**
     * Send OTP to user's email or WhatsApp
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'nullable|string',
            'method' => 'required|in:whatsapp,email',
        ]);

        $user = Auth::user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if (!empty($validated['phone'])) {
            $user->update(['phone' => $validated['phone']]);
        }

        $identifier = $this->getIdentifier($user, $validated['method']);

        if ($identifier === null) {
            return redirect()->back()->withErrors([
                'phone' => 'Kontak tujuan tidak ditemukan.',
            ]);
        }

        $this->otpService->generate($identifier, $validated['method']);
        $destination = $validated['method'] === 'email' ? 'email' : 'nomor WhatsApp';

        return redirect()->back()->with(
            'status',
            "Kode OTP telah dikirim ke {$destination} anda."
        );
    }

    /**
     * Verify OTP and mark phone as verified
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $isValid = $this->verifyUserOtp($user, $validated['otp']);

        if ($isValid) {
            return $this->completeVerification($user);
        }

        return redirect()->back()->withErrors([
            'otp' => 'Kode OTP tidak valid atau kadaluarsa.',
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $method = $request->input('method', 'whatsapp');
        $identifier = $this->getIdentifier($user, $method);

        if ($identifier === null) {
            return redirect()->back()->withErrors([
                'phone' => 'Kontak tujuan tidak ditemukan.',
            ]);
        }

        $this->otpService->generate($identifier, $method);
        $destination = $method === 'email' ? 'email' : 'nomor WhatsApp';

        return redirect()->back()->with(
            'status',
            "Kode OTP baru telah dikirim ke {$destination}."
        );
    }

    /**
     * Get contact identifier based on method
     */
    private function getIdentifier(User $user, string $method): ?string
    {
        return $method === 'email' ? $user->email : $user->phone;
    }

    /**
     * Verify OTP against user's phone or email
     */
    private function verifyUserOtp(User $user, string $otp): bool
    {
        if ($user->phone !== null && $this->otpService->verify($user->phone, $otp)) {
            return true;
        }

        if ($user->email !== null && $this->otpService->verify($user->email, $otp)) {
            return true;
        }

        return false;
    }

    /**
     * Complete verification process and redirect to appropriate dashboard
     */
    private function completeVerification(User $user): RedirectResponse
    {
        $user->update([
            'phone_verified_at' => now(),
            'is_verified' => true,
        ]);

        $this->otpService->clearDebugOtp();

        $dashboardRoute = $user->hasRole('admin') || $user->hasRole('schedule_manager')
            ? 'admin.dashboard'
            : 'frontend.home';

        return redirect()->intended(route($dashboardRoute, absolute: false));
    }

    /**
     * Get debug OTP if in development/testing environment
     */
    private function getDebugOtp(): ?string
    {
        return app()->environment('local', 'development', 'testing')
            ? $this->otpService->getDebugOtp()
            : null;
    }
}
