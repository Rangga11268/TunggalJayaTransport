<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): RedirectResponse
    {
        return redirect()->route('frontend.home', ['auth' => 'login']);
    }

    
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Get the authenticated user
        $user = Auth::user();

        // Redirect berdasarkan role user - no verification check after login
        if ($user->hasRole('admin') || $user->hasRole('schedule_manager')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        } else {
            // Redirect regular user ke halaman home
            return redirect()->intended(route('frontend.home', absolute: false));
        }
    }

    
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
