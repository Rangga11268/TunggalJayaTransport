<?php

namespace Tests\Feature;

use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test valid promo code validation endpoint
     */
    public function test_valid_promo_code_validation(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'SAVE100',
            'discount_type' => 'fixed',
            'discount_amount' => 100000,
            'min_purchase_amount' => 200000,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 100,
            'usage_count' => 10,
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'SAVE100',
            'total_amount' => 500000,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'promo_code_id' => $promoCode->id,
                'discount_amount' => 100000,
                'final_price' => 400000,
                'message' => 'Kode promo berhasil digunakan!',
            ]);
    }

    /**
     * Test inactive promo code rejection
     */
    public function test_inactive_promo_code_rejection(): void
    {
        PromoCode::create([
            'code' => 'INACTIVE10',
            'discount_type' => 'percentage',
            'discount_amount' => 10,
            'is_active' => false, // Inactive
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'INACTIVE10',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
    }

    /**
     * Test expired promo code rejection
     */
    public function test_expired_promo_code_rejection(): void
    {
        PromoCode::create([
            'code' => 'EXPIRED20',
            'discount_type' => 'percentage',
            'discount_amount' => 20,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDay(), // Already expired
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'EXPIRED20',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
    }

    /**
     * Test promo code not yet valid (future start date)
     */
    public function test_promo_code_not_yet_valid(): void
    {
        PromoCode::create([
            'code' => 'FUTURE30',
            'discount_type' => 'percentage',
            'discount_amount' => 30,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->addDay(), // Starts tomorrow
            'end_date' => now()->addDays(5),
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'FUTURE30',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
    }

    /**
     * Test usage limit reached
     */
    public function test_usage_limit_reached(): void
    {
        PromoCode::create([
            'code' => 'LIMITED5',
            'discount_type' => 'fixed',
            'discount_amount' => 50000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 5,
            'usage_count' => 5, // At limit
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'LIMITED5',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
    }

    /**
     * Test nonexistent promo code
     */
    public function test_nonexistent_promo_code(): void
    {
        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'NOTEXIST99',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Kode promo tidak valid atau sudah kadaluarsa.',
            ]);
    }

    /**
     * Test minimum purchase amount not met
     */
    public function test_minimum_purchase_amount_validation(): void
    {
        PromoCode::create([
            'code' => 'MIN500',
            'discount_type' => 'fixed',
            'discount_amount' => 100000,
            'is_active' => true,
            'min_purchase_amount' => 500000, // Minimum 500k
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'MIN500',
            'total_amount' => 300000, // Less than minimum
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'valid' => false,
                'message' => 'Minimal pembelian untuk kode ini adalah Rp 500.000',
            ]);
    }

    /**
     * Test case insensitive code validation
     */
    public function test_case_insensitive_code_validation(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'CASE123',
            'discount_type' => 'fixed',
            'discount_amount' => 50000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        // Try with lowercase
        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'case123',
            'total_amount' => 100000,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'promo_code_id' => $promoCode->id,
            ]);
    }

    /**
     * Test percentage discount calculation in validation
     */
    public function test_percentage_discount_in_validation(): void
    {
        PromoCode::create([
            'code' => 'PERCENT15',
            'discount_type' => 'percentage',
            'discount_amount' => 15, // 15%
            'is_active' => true,
            'min_purchase_amount' => 0,
            'max_discount_amount' => null,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'PERCENT15',
            'total_amount' => 1000000, // Rp1M
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'discount_amount' => 150000, // 15% of 1M
                'final_price' => 850000,
            ]);
    }

    /**
     * Test max discount amount cap in validation
     */
    public function test_max_discount_cap_in_validation(): void
    {
        PromoCode::create([
            'code' => 'PERCENT20MAX',
            'discount_type' => 'percentage',
            'discount_amount' => 20, // 20%
            'is_active' => true,
            'min_purchase_amount' => 0,
            'max_discount_amount' => 100000, // Capped at 100k
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
        ]);

        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'PERCENT20MAX',
            'total_amount' => 1000000, // 20% = 200k
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'discount_amount' => 100000, // Capped
                'final_price' => 900000,
            ]);
    }

    /**
     * Test validation requires code
     */
    public function test_validation_requires_code(): void
    {
        $response = $this->postJson(route('api.promo.validate'), [
            'total_amount' => 100000,
            // Missing 'code'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('code');
    }

    /**
     * Test validation requires total amount
     */
    public function test_validation_requires_total_amount(): void
    {
        $response = $this->postJson(route('api.promo.validate'), [
            'code' => 'SOME123',
            // Missing 'total_amount'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('total_amount');
    }
}
