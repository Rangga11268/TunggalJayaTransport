<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

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
        'is_weekly' => 'boolean',
        'day_of_week' => 'integer',
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
        // Count seats for confirmed bookings that are paid and not cancelled
        return $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->sum('number_of_seats');
    }
    
    public function getAvailableSeatsCount()
    {
        // Only count confirmed bookings that are paid
        $bookedSeats = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->sum('number_of_seats');
            
        $available = $this->bus->capacity - $bookedSeats;
        return max(0, $available);
    }
    
    public function getBookedSeatNumbers()
    {
        // Get seat numbers for confirmed bookings that are paid
        $bookings = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
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
        // Use system timezone for comparison
        $now = Carbon::now();
        return $this->departure_time->setTimezone($now->timezone)->isPast();
    }

    /**
     * Get departure time in a specific timezone
     */
    public function getDepartureTimeInTimezone($timezone = null)
    {
        if ($timezone === null) {
            // Use system timezone
            $timezone = Carbon::now()->timezone;
        }
        
        return $this->departure_time->setTimezone($timezone);
    }

    /**
     * Check if the schedule is available for booking
     */
    public function isAvailableForBooking()
    {
        // If it's a weekly schedule, check if it's the correct day (regardless of time)
        // Weekly schedules are continuously available on their designated day
        if ($this->is_weekly && $this->day_of_week !== null) {
            $today = Carbon::now()->dayOfWeek;
            if ($today != $this->day_of_week) {
                return false;
            }
            
            // For weekly schedules, we don't check if the time has passed
            // because they represent a recurring pattern
        } else {
            // For daily schedules, check if schedule has already departed
            if ($this->hasDeparted()) {
                return false;
            }
        }

        // Check if there are available seats
        return $this->getAvailableSeatsCount() > 0;
    }

    /**
     * Check if the schedule is available for weekly booking
     */
    public function isAvailableForWeeklyBooking()
    {
        // For weekly schedules, check if it's the correct day
        if ($this->is_weekly && $this->day_of_week !== null) {
            $today = Carbon::now()->dayOfWeek;
            return $today == $this->day_of_week;
        }
        
        // For non-weekly schedules, always return false for this check
        return false;
    }

    /**
     * Get the next available date for a weekly schedule
     */
    public function getNextAvailableDate()
    {
        // Only applicable for weekly schedules
        if (!$this->is_weekly || $this->day_of_week === null) {
            return null;
        }

        $today = Carbon::now();
        $nextDate = $today->copy()->next($this->day_of_week);
        
        // If today is the scheduled day, check if departure time has passed
        if ($today->dayOfWeek == $this->day_of_week) {
            $departureToday = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            if ($departureToday->isPast()) {
                // Return next week's date
                $nextDate = $today->copy()->addWeek()->next($this->day_of_week);
            } else {
                // Today is still available
                $nextDate = $today->copy();
            }
        }
        
        return $nextDate;
    }

    /**
     * Get bookings that should be cancelled when schedule departs
     */
    public function getBookingsToCancel()
    {
        return $this->bookings()
            ->where('booking_status', '!=', 'cancelled') // Not already cancelled
            ->where('payment_status', 'pending') // Only pending payments
            ->get();
    }

    /**
     * Scope for available schedules only
     */
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            // For weekly schedules, check if they have a valid day_of_week
            $q->where(function($q2) {
                $q2->where('is_weekly', true)
                  ->whereNotNull('day_of_week');
            })
            // For daily schedules, check if departure_time is in the future
            ->orWhere(function($q2) {
                $q2->where('is_weekly', false)
                  ->where('departure_time', '>', Carbon::now());
            });
        })
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
