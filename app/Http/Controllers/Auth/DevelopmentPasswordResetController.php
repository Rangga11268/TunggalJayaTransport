<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DevelopmentPasswordResetController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request for development.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Find the user
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'User not found.']);
        }

        // Create a password reset token manually
        $token = Str::random(60);
        
        // Store the token in the password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => hash('sha256', $token),
                'created_at' => now(),
            ]
        );

        // Return the reset link directly to the user for development
        $resetLink = url("/reset-password/{$token}?email={$user->email}");
        
        // Add flash message with the reset link for development
        session()->flash('dev_reset_link', $resetLink);
        session()->flash('dev_email', $user->email);
        
        return redirect()->back()->with('status', 'Password reset link generated successfully for development. Check the flashed message for the link.');
    }

    /**
     * Display the password reset view for development.
     */
    public function showResetForm($token)
    {
        $email = request()->query('email');
        
        return view('auth.reset-password', [
            'request' => request(),
            'token' => $token,
            'email' => $email
        ]);
    }
}