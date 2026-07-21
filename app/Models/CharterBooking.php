<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharterBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'charter_code',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'institution_name',
        'bus_count',
        'assigned_bus_id',
        'bus_type_requested',
        'bus_requests',
        'passenger_count',
        'pickup_date',
        'pickup_time',
        'return_date',
        'pickup_location',
        'pickup_lat',
        'pickup_lng',
        'pickup_address',
        'destination',
        'destination_lat',
        'destination_lng',
        'destination_address',
        'notes',
        'total_price',
        'down_payment',
        'dp_midtrans_id',
        'final_midtrans_id',
        'payment_status',
        'payment_method',
        'payment_proof',
        'status',
    ];

    protected $casts = [
        'pickup_date' => 'date',
        'return_date' => 'date',
        'bus_requests' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBus()
    {
        return $this->belongsTo(Bus::class, 'assigned_bus_id');
    }

    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'charter_booking_bus', 'charter_booking_id', 'bus_id')->withTimestamps();
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function checkAndCancelIfExpired()
    {
        if ($this->payment_status === 'unpaid' && $this->status !== 'cancelled' && $this->created_at->copy()->addHours(24)->isPast()) {
            $this->update([
                'status' => 'cancelled',
                'notes' => $this->notes . "\n[Sistem] Dibatalkan otomatis karena batas waktu pembayaran (24 jam) habis."
            ]);
            return true;
        }
        return false;
    }
}
