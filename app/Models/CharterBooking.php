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
        'assigned_bus_id',
        'bus_type_requested',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedBus()
    {
        return $this->belongsTo(Bus::class, 'assigned_bus_id');
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }
}
