<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OtpService $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = app(OtpService::class);
    }

    /**
     * Test OTP generation creates valid code
     */
    public function test_otp_generation_creates_valid_code(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        $this->assertNotNull($otp);
        $this->assertEquals(6, strlen($otp));
        $this->assertTrue(ctype_digit($otp));
    }

    /**
     * Test OTP is stored in cache
     */
    public function test_generated_otp_stored_in_cache(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        $cacheKey = "otp_verification:{$identifier}";
        $this->assertTrue(Cache::has($cacheKey));

        $cachedData = Cache::get($cacheKey);
        $this->assertEquals($otp, $cachedData['otp']);
        $this->assertEquals(0, $cachedData['attempts']);
    }

    /**
     * Test OTP verification with correct code
     */
    public function test_otp_verification_with_correct_code(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        $result = $this->otpService->verify($identifier, $otp);

        $this->assertTrue($result);

        // Cache should be cleared after successful verification
        $this->assertFalse(Cache::has("otp_verification:{$identifier}"));
    }

    /**
     * Test OTP verification with incorrect code
     */
    public function test_otp_verification_with_incorrect_code(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        $result = $this->otpService->verify($identifier, 'wrong123');

        $this->assertFalse($result);

        // Cache should still exist with incremented attempts
        $this->assertTrue(Cache::has("otp_verification:{$identifier}"));
        $cachedData = Cache::get("otp_verification:{$identifier}");
        $this->assertEquals(1, $cachedData['attempts']);
    }

    /**
     * Test OTP max attempts enforcement (3 attempts)
     */
    public function test_otp_max_attempts_enforcement(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        // First attempt
        $this->otpService->verify($identifier, 'wrong111');
        // Second attempt
        $this->otpService->verify($identifier, 'wrong222');
        // Third attempt - should lock it
        $this->otpService->verify($identifier, 'wrong333');

        // Cache should be cleared after max attempts
        $this->assertFalse(Cache::has("otp_verification:{$identifier}"));

        // Even with correct code, should fail now
        $result = $this->otpService->verify($identifier, $otp);
        $this->assertFalse($result);
    }

    /**
     * Test per-identifier rate limiting (2 minute cooldown)
     */
    public function test_per_identifier_rate_limiting(): void
    {
        $identifier = 'test@example.com';

        // First generation should succeed
        $otp1 = $this->otpService->generate($identifier, 'email');
        $this->assertNotNull($otp1);

        // Second generation within 2 minutes should throw exception
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Tunggu sebentar ya');

        $otp2 = $this->otpService->generate($identifier, 'email');
    }

    /**
     * Test IP-based burst prevention (max 5 per 15 minutes)
     */
    public function test_ip_based_burst_prevention(): void
    {
        // Simulate 5 OTP generation from same IP
        for ($i = 0; $i < 5; $i++) {
            // Use different identifiers to bypass per-identifier rate limiting
            $identifier = "user{$i}@example.com";

            // Clear throttle key to allow generation
            Cache::forget("otp_throttle:{$identifier}");

            // This should succeed
            $otp = $this->otpService->generate($identifier, 'email');
            $this->assertNotNull($otp);
        }

        // 6th attempt from same IP should fail
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Terlalu banyak percobaan dari IP ini');

        $identifier = 'sixthuser@example.com';
        Cache::forget("otp_throttle:{$identifier}");
        $this->otpService->generate($identifier, 'email');
    }

    /**
     * Test OTP expiry (10 minutes)
     */
    public function test_otp_expiry(): void
    {
        $identifier = 'test@example.com';

        $otp = $this->otpService->generate($identifier, 'email');

        // Manually expire the cache
        Cache::forget("otp_verification:{$identifier}");

        // Verification should fail
        $result = $this->otpService->verify($identifier, $otp);

        $this->assertFalse($result);
    }

    /**
     * Test OTP generation for different methods (email vs whatsapp)
     */
    public function test_otp_generation_with_different_methods(): void
    {
        // For email
        $emailOtp = $this->otpService->generate('test@example.com', 'email');
        $this->assertNotNull($emailOtp);

        // Clear throttle for next test
        Cache::forget('otp_throttle:test_phone');

        // For WhatsApp
        $whatsappOtp = $this->otpService->generate('test_phone', 'whatsapp');
        $this->assertNotNull($whatsappOtp);

        // Both should be valid 6-digit codes
        $this->assertEquals(6, strlen($emailOtp));
        $this->assertEquals(6, strlen($whatsappOtp));
    }

    /**
     * Test multiple concurrent OTP generations for different identifiers
     */
    public function test_multiple_concurrent_otp_generations(): void
    {
        $identifiers = ['user1@example.com', 'user2@example.com', 'user3@example.com'];
        $otps = [];

        foreach ($identifiers as $identifier) {
            // Clear throttle for each identifier
            Cache::forget("otp_throttle:{$identifier}");

            $otp = $this->otpService->generate($identifier, 'email');
            $otps[$identifier] = $otp;

            $this->assertTrue(Cache::has("otp_verification:{$identifier}"));
        }

        // Verify each OTP independently
        foreach ($identifiers as $identifier) {
            $result = $this->otpService->verify($identifier, $otps[$identifier]);
            $this->assertTrue($result);
        }
    }
}
