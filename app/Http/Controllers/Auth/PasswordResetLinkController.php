<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    
    public function create(): \Inertia\Response
    {
        return \Inertia\Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if we're in development mode and mailer is set to log
        $isDevelopment = app()->environment('local', 'development');
        $usesLogMailer = config('mail.default') === 'log';
        
        if ($isDevelopment && $usesLogMailer) {
            return $this->handleDevelopmentReset($request);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
    
    
    private function handleDevelopmentReset(Request $request): RedirectResponse
    {
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
}
