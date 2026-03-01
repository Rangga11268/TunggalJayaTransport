<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PhoneVerificationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected OtpService $otpService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = app(OtpService::class);
    }

    /**
     * Test user can request OTP
     */
    public function test_user_can_request_otp(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'phone_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('verification.phone.send'), [
                'method' => 'email',
            ]);

        $response->assertRedirect();
        $this->assertEquals(
            'Kode OTP telah dikirim ke email anda.',
            session('status')
        );
    }

    /**
     * Test user can update phone and request OTP
     */
    public function test_user_can_update_phone_and_request_otp(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => null,
            'phone_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('verification.phone.send'), [
                'phone' => '081234567890',
                'method' => 'email',
            ]);

        $response->assertRedirect();

        // Phone should be updated
        $this->assertEquals('081234567890', $user->fresh()->phone);
    }

    /**
     * Test user can verify OTP with phone
     */
    public function test_user_can_verify_otp_with_phone(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'phone_verified_at' => null,
        ]);

        // Generate OTP
        $otp = $this->otpService->generate($user->phone, 'whatsapp');

        $response = $this->actingAs($user)
            ->post(route('verification.phone.verify'), [
                'otp' => $otp,
            ]);

        $response->assertRedirect();

        // User phone should now be verified
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }

    /**
     * Test user can verify OTP with email
     */
    public function test_user_can_verify_otp_with_email(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'phone_verified_at' => null,
        ]);

        // Generate OTP for email
        $otp = $this->otpService->generate($user->email, 'email');

        $response = $this->actingAs($user)
            ->post(route('verification.phone.verify'), [
                'otp' => $otp,
            ]);

        $response->assertRedirect();

        // User should now be verified
        $this->assertNotNull($user->fresh()->phone_verified_at);
    }

    /**
     * Test verification fails with incorrect OTP
     */
    public function test_verification_fails_with_incorrect_otp(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'phone_verified_at' => null,
        ]);

        $this->otpService->generate($user->phone, 'whatsapp');

        $response = $this->actingAs($user)
            ->post(route('verification.phone.verify'), [
                'otp' => 'wrongcode',
            ]);

        $response->assertRedirect();

        // User phone should NOT be verified
        $this->assertNull($user->fresh()->phone_verified_at);
    }

    /**
     * Test verification fails after max attempts
     */
    public function test_verification_fails_after_max_attempts(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'phone_verified_at' => null,
        ]);

        $otp = $this->otpService->generate($user->phone, 'whatsapp');

        // Try 3 wrong attempts
        for ($i = 0; $i < 3; $i++) {
            $this->actingAs($user)
                ->post(route('verification.phone.verify'), [
                    'otp' => 'wrong' . $i,
                ]);
        }

        // Even correct OTP should fail now
        $response = $this->actingAs($user)
            ->post(route('verification.phone.verify'), [
                'otp' => $otp,
            ]);

        $response->assertRedirect();

        // User phone should NOT be verified
        $this->assertNull($user->fresh()->phone_verified_at);
    }

    /**
     * Test already verified user sees dashboard
     */
    public function test_already_verified_user_redirects_to_dashboard(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)
            ->get(route('verification.phone.show'));

        $response->assertRedirect(route('dashboard'));
    }

    /**
     * Test OTP validation requires 6 digits
     */
    public function test_otp_validation_requires_6_digits(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'phone_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->post(route('verification.phone.verify'), [
                'otp' => '12345', // Only 5 digits
            ]);

        $response->assertSessionHasErrors('otp');
    }

    /**
     * Test completely unverified user (no email_verified_at and no phone_verified_at)
     */
    public function test_completely_unverified_user_requires_verification(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'phone' => '081234567890',
            'email_verified_at' => null,
            'phone_verified_at' => null,
        ]);

        $response = $this->actingAs($user)
            ->get(route('verification.phone.show'));

        // Should show verification page
        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('Auth/VerifyPhone'));
    }

    /**
     * Test fully verified user (email_verified_at AND phone_verified_at)
     */
    public function test_fully_verified_user_can_access_dashboard(): void
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)
            ->get(route('verification.phone.show'));

        $response->assertRedirect(route('dashboard'));
    }
}
