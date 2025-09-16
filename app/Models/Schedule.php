<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'price',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    
    public function getBookedSeatsCount()
    {
        // Count seats for confirmed bookings that haven't failed payment
        return $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', '!=', 'failed')
            ->sum('number_of_seats');
    }
    
    public function getAvailableSeatsCount()
    {
        $bookedSeats = $this->getBookedSeatsCount();
        $available = $this->bus->capacity - $bookedSeats;
        return max(0, $available);
    }
    
    public function getBookedSeatNumbers()
    {
        // Get seat numbers for confirmed bookings that haven't failed payment
        $bookings = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', '!=', 'failed')
            ->whereNotNull('seat_numbers')
            ->pluck('seat_numbers')
            ->toArray();
            
        $seatNumbers = [];
        foreach ($bookings as $seatString) {
            if ($seatString) {
                $seats = explode(',', $seatString);
                $seatNumbers = array_merge($seatNumbers, $seats);
            }
        }
        
        return array_map('trim', $seatNumbers);
    }
}
