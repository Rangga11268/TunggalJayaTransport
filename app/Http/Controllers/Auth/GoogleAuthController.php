<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google for authentication
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }

        // Check if user exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update existing user dengan Google info (DON'T touch verification status)
            $user->update([
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
            ]);

            // Ensure role is assigned (in case it was missed during creation)
            if ($user->roles()->count() === 0) {
                $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
                $user->assignRole($customerRole);
            }
        } else {
            // Create new user from Google
            // Email is verified via Google, but phone still needs OTP verification
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(), // Email verified via Google
                // phone_verified_at intentionally NOT set — needs OTP verification
            ]);

            // Assign role 'customer'
            $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
            $user->assignRole($customerRole);
        }

        // Login user
        Auth::login($user, remember: true);

        // If phone not verified yet, redirect to verification flow
        if (!$user->hasPhoneVerified()) {
            return redirect()->route('verification.phone.show');
        }

        // Redirect based on user role
        if ($user->hasRole('admin') || $user->hasRole('schedule_manager')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('frontend.home');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
