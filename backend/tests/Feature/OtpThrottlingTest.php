<?php

namespace Tests\Feature;

use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpThrottlingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test OTP generation has per-identifier cooldown
     */
    public function test_otp_has_cooldown_per_identifier(): void
    {
        $email = 'test@example.com';
        $service = app(OtpService::class);

        // First OTP generation should succeed
        $otp1 = $service->generate($email, 'email');
        $this->assertNotNull($otp1);

        // Immediate second request should fail (cooldown)
        try {
            $otp2 = $service->generate($email, 'email');
            $this->fail('OTP generation should fail within cooldown period');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Tunggu sebentar', $e->getMessage());
        }

        // Clear cache to test next request
        Cache::forget("otp_throttle:{$email}");
        $otp3 = $service->generate($email, 'email');
        $this->assertNotNull($otp3);
    }

    /**
     * Test OTP has IP-based burst limit
     */
    public function test_otp_has_ip_burst_limit(): void
    {
        $emails = [
            'user1@example.com',
            'user2@example.com',
            'user3@example.com',
            'user4@example.com',
            'user5@example.com',
        ];

        $service = app(OtpService::class);

        // First 5 should succeed (per-IP limit is 5/15min)
        foreach ($emails as $email) {
            $otp = $service->generate($email, 'email');
            $this->assertNotNull($otp);
            Cache::forget("otp_throttle:{$email}");
        }

        // 6th should fail due to IP burst limit
        try {
            $service->generate('user6@example.com', 'email');
            $this->fail('OTP generation should fail due to IP burst limit');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Terlalu banyak percobaan', $e->getMessage());
        }
    }

    /**
     * Test OTP verification reduces attempts counter
     */
    public function test_otp_verification_validates_correctly(): void
    {
        $email = 'test@example.com';
        $service = app(OtpService::class);

        $otp = $service->generate($email, 'email');

        // Valid OTP should verify
        $result = $service->verify($email, $otp);
        $this->assertTrue($result);

        // Cache should be cleared after successful verification
        $result = $service->verify($email, $otp);
        $this->assertFalse($result);
    }

    /**
     * Test OTP fails after max attempts exceeded
     */
    public function test_otp_fails_after_max_attempts(): void
    {
        $email = 'test@example.com';
        $service = app(OtpService::class);

        $otp = $service->generate($email, 'email');

        // Wrong attempts
        for ($i = 1; $i < OtpService::MAX_ATTEMPTS; $i++) {
            $result = $service->verify($email, '000000');
            $this->assertFalse($result);
        }

        // Final wrong attempt should clear cache
        $result = $service->verify($email, '000000');
        $this->assertFalse($result);

        // Cache should be cleared, can generate new OTP (need to clear cooldown first)
        Cache::forget("otp_throttle:{$email}");
        $newOtp = $service->generate($email, 'email');
        $this->assertNotNull($newOtp);
    }
}
