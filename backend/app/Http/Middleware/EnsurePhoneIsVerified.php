<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePhoneIsVerified
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() ||
            ! $request->user()->hasPhoneVerified()) {
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Phone verification required.'], 403);
            }

            return redirect()->route('verification.phone.show');
        }

        return $next($request);
    }
}
