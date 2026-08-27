<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_amount',
        'max_discount_amount',
        'min_purchase_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isValid(): bool
    {
        return $this->isActive() && !$this->isExpired() && !$this->isLimitReached();
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isExpired(): bool
    {
        $now = now();
        return ($this->start_date && $now->lt($this->start_date)) ||
               ($this->end_date && $now->gt($this->end_date));
    }

    public function isLimitReached(): bool
    {
        return $this->usage_limit !== null && $this->usage_count >= $this->usage_limit;
    }

    public function calculateDiscount($totalAmount)
    {
        if ($totalAmount < $this->min_purchase_amount) {
            return 0;
        }

        if ($this->discount_type === 'fixed') {
            return min($this->discount_amount, $totalAmount);
        }

        // Percentage
        $discount = $totalAmount * ($this->discount_amount / 100);

        if ($this->max_discount_amount) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return min($discount, $totalAmount);
    }
}
