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
        'pickup_date',
        'return_date',
        'pickup_location',
        'destination',
        'notes',
        'total_price',
        'down_payment',
        'dp_midtrans_id',
        'final_midtrans_id',
        'payment_status',
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
}
