<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enhanced throttle middleware untuk critical endpoints
 * Support multiple limit strategies: per-user, per-IP, per-endpoint
 */
class ThrottleApiRequests
{
    /**
     * Handle an incoming request.
     * 
     * Limit format: "limit,minutes,key_prefix"
     * Examples:
     *   - "60,1,auth.login" = 60 requests per minute by email (key = email)
     *   - "5,15,otp.send" = 5 OTP requests per 15 minutes by IP
     *   - "100,1,payment.process" = 100 payment requests per minute by user_id
     */
    public function handle(Request $request, Closure $next, string $limit = null): Response
    {
        if (!$limit) {
            return $next($request);
        }

        [$maxRequests, $minutes, $keyPrefix] = $this->parseLimitString($limit);

        // Determine the key based on endpoint
        $key = $this->buildThrottleKey($request, $keyPrefix);

        if (RateLimiter::tooManyAttempts($key, $maxRequests)) {
            return $this->buildResponse($request, $key, $maxRequests);
        }

        RateLimiter::hit($key, (int) ($minutes * 60));

        return $next($request);
    }

    /**
     * Parse limit string into components
     * Format: "limit,minutes,key_prefix"
     */
    protected function parseLimitString(string $limit): array
    {
        $parts = explode(',', $limit);
        $maxRequests = (int) ($parts[0] ?? 60);
        $minutes = (int) ($parts[1] ?? 1);
        $keyPrefix = $parts[2] ?? 'api';

        return [$maxRequests, $minutes, $keyPrefix];
    }

    /**
     * Build throttle key based on endpoint and request context
     */
    protected function buildThrottleKey(Request $request, string $keyPrefix): string
    {
        // For authenticated endpoints, use user_id
        if ($request->user()) {
            return "throttle:{$keyPrefix}:user:{$request->user()->id}";
        }

        // For email-based (login, register, OTP), use email
        if ($request->has('email')) {
            return "throttle:{$keyPrefix}:email:" . md5($request->input('email'));
        }

        // Fallback to IP address
        return "throttle:{$keyPrefix}:ip:" . $request->ip();
    }

    /**
     * Build too many requests response
     */
    protected function buildResponse(Request $request, string $key, int $maxRequests): Response
    {
        $retryAfter = RateLimiter::availableIn($key);

        return response()->json(
            [
                'status' => 'error',
                'message' => "Terlalu banyak request. Silakan coba lagi dalam {$retryAfter} detik.",
                'retry_after' => $retryAfter,
                'max_requests' => $maxRequests,
            ],
            429
        )->header('Retry-After', $retryAfter)
            ->header('X-RateLimit-Limit', $maxRequests)
            ->header('X-RateLimit-Remaining', max(0, $maxRequests - RateLimiter::attempts($key)))
            ->header('X-RateLimit-Reset', now()->addSeconds($retryAfter)->timestamp);
    }
}
