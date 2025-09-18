<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    protected $fillable = [
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'price',
        'status',
        'is_weekly',
        'day_of_week',
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

    /**
     * Check if the schedule departure time has passed
     */
    public function hasDeparted()
    {
        return $this->departure_time->isPast();
    }

    /**
     * Check if the schedule is available for booking
     */
    public function isAvailableForBooking()
    {
        // If schedule has already departed, it's not available
        if ($this->hasDeparted()) {
            return false;
        }

        // If it's a weekly schedule, check if it's the correct day
        if ($this->is_weekly && $this->day_of_week !== null) {
            $today = Carbon::now()->dayOfWeek;
            if ($today != $this->day_of_week) {
                return false;
            }
        }

        // Check if there are available seats
        return $this->getAvailableSeatsCount() > 0;
    }

    /**
     * Scope for available schedules only
     */
    public function scopeAvailable($query)
    {
        return $query->where('departure_time', '>', Carbon::now())
            ->whereHas('bus')
            ->whereHas('route');
    }

    /**
     * Scope for weekly schedules
     */
    public function scopeWeekly($query)
    {
        return $query->where('is_weekly', true);
    }

    /**
     * Scope for daily schedules
     */
    public function scopeDaily($query)
    {
        return $query->where('is_weekly', false);
    }
}
