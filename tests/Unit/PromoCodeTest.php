<?php

namespace Tests\Unit;

use App\Models\PromoCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test fixed discount calculation
     */
    public function test_calculate_fixed_discount(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'FIXED100',
            'discount_type' => 'fixed',
            'discount_amount' => 100000,
            'min_purchase_amount' => 0,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        // Discount Rp100.000 for Rp500.000 purchase
        $result = $promoCode->calculateDiscount(500000);
        $this->assertEquals(100000, $result);

        // Discount should not exceed purchase amount
        $result = $promoCode->calculateDiscount(50000);
        $this->assertEquals(50000, $result);
    }

    /**
     * Test percentage discount calculation
     */
    public function test_calculate_percentage_discount(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'PERCENT10',
            'discount_type' => 'percentage',
            'discount_amount' => 10, // 10%
            'min_purchase_amount' => 0,
            'max_discount_amount' => null,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        // 10% of Rp100.000 = Rp10.000
        $result = $promoCode->calculateDiscount(100000);
        $this->assertEquals(10000, $result);

        // 10% of Rp500.000 = Rp50.000
        $result = $promoCode->calculateDiscount(500000);
        $this->assertEquals(50000, $result);
    }

    /**
     * Test max discount amount limitation
     */
    public function test_percentage_discount_respects_max_amount(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'PERCENT20MAX',
            'discount_type' => 'percentage',
            'discount_amount' => 20, // 20%
            'min_purchase_amount' => 0,
            'max_discount_amount' => 50000, // Max Rp50.000
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        // 20% of Rp100.000 = Rp20.000 (within limit)
        $result = $promoCode->calculateDiscount(100000);
        $this->assertEquals(20000, $result);

        // 20% of Rp500.000 = Rp100.000, but capped at Rp50.000
        $result = $promoCode->calculateDiscount(500000);
        $this->assertEquals(50000, $result);
    }

    /**
     * Test minimum purchase amount requirement
     */
    public function test_discount_requires_minimum_purchase(): void
    {
        $promoCode = PromoCode::create([
            'code' => 'MIN200',
            'discount_type' => 'fixed',
            'discount_amount' => 50000,
            'min_purchase_amount' => 200000,
            'is_active' => true,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        // Below minimum: no discount
        $result = $promoCode->calculateDiscount(100000);
        $this->assertEquals(0, $result);

        // At minimum: full discount
        $result = $promoCode->calculateDiscount(200000);
        $this->assertEquals(50000, $result);

        // Above minimum: full discount
        $result = $promoCode->calculateDiscount(300000);
        $this->assertEquals(50000, $result);
    }

    /**
     * Test active status check
     */
    public function test_is_active_check(): void
    {
        $activeCode = PromoCode::create([
            'code' => 'ACTIVE',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        $inactiveCode = PromoCode::create([
            'code' => 'INACTIVE',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => false,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        $this->assertTrue($activeCode->isActive());
        $this->assertFalse($inactiveCode->isActive());
    }

    /**
     * Test expiry date validation
     */
    public function test_expiry_date_validation(): void
    {
        $futureCode = PromoCode::create([
            'code' => 'FUTURE',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->addDay(), // Starts tomorrow
            'end_date' => now()->addDays(2),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        $expiredCode = PromoCode::create([
            'code' => 'EXPIRED',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDays(2),
            'end_date' => now()->subDay(), // Expired yesterday
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        $validCode = PromoCode::create([
            'code' => 'VALID',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 0,
        ]);

        $this->assertTrue($futureCode->isExpired()); // Not started yet
        $this->assertTrue($expiredCode->isExpired()); // Already expired
        $this->assertFalse($validCode->isExpired()); // Currently valid
    }

    /**
     * Test usage limit validation
     */
    public function test_usage_limit_validation(): void
    {
        $limitedCode = PromoCode::create([
            'code' => 'LIMITED5',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 5,
            'usage_count' => 5, // At limit
        ]);

        $unlimitedCode = PromoCode::create([
            'code' => 'UNLIMITED',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => null,
            'usage_count' => 100, // Over typical limit but usage_limit is null
        ]);

        $this->assertTrue($limitedCode->isLimitReached());
        $this->assertFalse($unlimitedCode->isLimitReached());
    }

    /**
     * Test complete validity check
     */
    public function test_is_valid_combines_all_checks(): void
    {
        $validCode = PromoCode::create([
            'code' => 'VALID',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 10,
            'usage_count' => 5,
        ]);

        $inactiveCode = PromoCode::create([
            'code' => 'INACTIVE',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => false,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 10,
            'usage_count' => 5,
        ]);

        $expiredCode = PromoCode::create([
            'code' => 'EXPIRED',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDays(2),
            'end_date' => now()->subDay(),
            'usage_limit' => 10,
            'usage_count' => 5,
        ]);

        $limitReachedCode = PromoCode::create([
            'code' => 'LIMITED',
            'discount_type' => 'fixed',
            'discount_amount' => 10000,
            'is_active' => true,
            'min_purchase_amount' => 0,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'usage_limit' => 5,
            'usage_count' => 5,
        ]);

        $this->assertTrue($validCode->isValid());
        $this->assertFalse($inactiveCode->isValid());
        $this->assertFalse($expiredCode->isValid());
        $this->assertFalse($limitReachedCode->isValid());
    }
}
