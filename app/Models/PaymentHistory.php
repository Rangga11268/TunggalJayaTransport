<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'charter_booking_id',
        'transaction_id',
        'payment_method',
        'gross_amount',
        'transaction_status',
        'fraud_status',
        'payment_url',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function charterBooking()
    {
        return $this->belongsTo(CharterBooking::class);
    }
}
