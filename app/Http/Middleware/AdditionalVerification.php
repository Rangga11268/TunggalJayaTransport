<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdditionalVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Allow access to verification pages
            if ($request->routeIs('verification.phone.show') || 
                $request->routeIs('verification.phone.verify') ||
                $request->routeIs('verification.phone.resend')) {
                return $next($request);
            }
            
            // Check if user has verified their phone number
            // After initial registration/verification, users shouldn't be blocked from accessing the app
            if (!$user->hasPhoneVerified()) {
                // Redirect to verification page if phone is not verified
                return redirect()->route('verification.phone.show');
            }
        }

        return $next($request);
    }
}