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
            // Update existing user dengan Google info
            $user->update([
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'phone_verified_at' => $user->phone_verified_at ?? now(),
            ]);

            // Ensure role is assigned (in case it was missed during creation)
            if ($user->roles()->count() === 0) {
                $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
                $user->assignRole($customerRole);
            }
        } else {
            // Create new user from Google
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(24)), // Random password
                'phone_verified_at' => now(), // Auto-verify Google users
                'email_verified_at' => now(), // Email verified via Google
            ]);

            // Assign role 'customer' by default (create role if it doesn't exist)
            if ($user->roles()->count() === 0) {
                $customerRole = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
                $user->assignRole($customerRole);
            }
        }

        // Login user
        Auth::login($user, remember: true);

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
