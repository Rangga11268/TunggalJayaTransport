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
        'departure_time', // For weekly: time only, for daily: datetime
        'arrival_time',   // For weekly: time only, for daily: datetime
        'price',
        'status',
        'is_weekly',
        'day_of_week',
        'is_daily', // New field for daily recurring schedules
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'is_weekly' => 'boolean',
        'is_daily' => 'boolean',
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
     * Get the actual departure datetime for display/booking purposes
     * For weekly schedules, calculates the next occurrence
     * For daily recurring schedules, calculates the next occurrence
     * For daily schedules, returns the stored datetime
     */
    public function getActualDepartureTime()
    {
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, calculate the next occurrence
            $nextDate = $this->getNextAvailableDate();
            if ($nextDate) {
                // Combine the next date with the stored time
                return $nextDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            }
            // Fallback to today with the time
            return Carbon::today()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
        }
        
        if ($this->is_daily) {
            // For daily recurring schedules, we need to find the next available date
            $today = Carbon::today();
            $now = Carbon::now();
            
            // Get today's departure time for comparison
            $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            
            // If departure time hasn't passed yet today, return today
            if ($todayDeparture->isFuture()) {
                return $todayDeparture;
            }
            
            // Otherwise, return tomorrow's departure time
            return $today->copy()->addDay()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
        }
        
        // For daily schedules, return the stored datetime
        return $this->departure_time;
    }

    /**
     * Get the actual arrival datetime for display/booking purposes
     * For weekly schedules, calculates the next occurrence
     * For daily recurring schedules, calculates the next occurrence
     * For daily schedules, returns the stored datetime
     */
    public function getActualArrivalTime()
    {
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, calculate the next occurrence
            $nextDate = $this->getNextAvailableDate();
            if ($nextDate) {
                // Combine the next date with the stored time
                return $nextDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            }
            // Fallback to today with the time
            return Carbon::today()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
        }
        
        if ($this->is_daily) {
            // For daily recurring schedules, we need to find the next available date
            $today = Carbon::today();
            $now = Carbon::now();
            
            // Get today's departure time for comparison
            $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            
            // If departure time hasn't passed yet today, return today's arrival time
            if ($todayDeparture->isFuture()) {
                return $today->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            }
            
            // Otherwise, return tomorrow's arrival time
            return $today->copy()->addDay()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
        }
        
        // For daily schedules, return the stored datetime
        return $this->arrival_time;
    }

    /**
     * Check if the schedule departure time has passed
     * For weekly schedules, checks if the next occurrence has passed
     * For daily recurring schedules, checks if the next occurrence has passed
     */
    public function hasDeparted()
    {
        $now = Carbon::now();
        
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, check if the next occurrence has passed
            $actualDeparture = $this->getActualDepartureTime();
            return $actualDeparture->isPast();
        }
        
        if ($this->is_daily) {
            // For daily recurring schedules, check if the next occurrence has passed
            $actualDeparture = $this->getActualDepartureTime();
            return $actualDeparture->isPast();
        }
        
        // For daily schedules, check against stored departure time
        return $this->departure_time->isPast();
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
        
        $departureTime = $this->getActualDepartureTime();
        return $departureTime->setTimezone($timezone);
    }

    /**
     * Check if the schedule is available for booking
     */
    public function isAvailableForBooking()
    {
        // Check if schedule is active
        if ($this->status !== 'active') {
            return false;
        }

        // Check if the schedule has already departed
        if ($this->hasDeparted()) {
            return false;
        }

        // Check if there are available seats
        return $this->getAvailableSeatsCount() > 0;
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

        $today = Carbon::today();
        $now = Carbon::now();
        
        // Get today's departure time for comparison
        $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
        
        // If today is the scheduled day
        if ($today->dayOfWeek == $this->day_of_week) {
            // If departure time hasn't passed yet today, return today
            if ($todayDeparture->isFuture()) {
                return $today;
            }
            // If departure time has passed today, return next week's date
            return $today->copy()->addWeek();
        }
        
        // If today is not the scheduled day, get the next occurrence
        // next() returns the next occurrence of the given day of week
        $nextDate = $today->copy()->next($this->day_of_week);
        return $nextDate;
    }

    /**
     * Get all upcoming dates for a weekly schedule within a date range
     */
    public function getUpcomingDates($startDate = null, $endDate = null, $limit = 10)
    {
        // Only applicable for weekly schedules
        if (!$this->is_weekly || $this->day_of_week === null) {
            return collect();
        }

        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::today();
        $endDate = $endDate ? Carbon::parse($endDate) : $startDate->copy()->addMonths(3); // Default 3 months
        
        $dates = collect();
        $currentDate = $startDate->copy();
        $count = 0;
        
        // Find the first occurrence
        if ($currentDate->dayOfWeek == $this->day_of_week) {
            // If today is the scheduled day, check if departure time hasn't passed yet
            $todayDeparture = $currentDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            if ($todayDeparture->isFuture() || $todayDeparture->isSameAs('H:i:s', Carbon::now()->format('H:i:s'))) {
                $dates->push($currentDate->copy());
                $count++;
            }
            $currentDate->addDay();
        }
        
        // Continue finding occurrences until we reach the limit or end date
        while ($currentDate->lte($endDate) && $count < $limit) {
            // Find the next occurrence of the scheduled day
            $nextDate = $currentDate->copy()->next($this->day_of_week);
            
            // If the next date is within our range, add it
            if ($nextDate->lte($endDate)) {
                $dates->push($nextDate);
                $count++;
                $currentDate = $nextDate->copy()->addDay();
            } else {
                break;
            }
        }
        
        return $dates;
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
        return $query->where('status', 'active')
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
    
    /**
     * Scope for daily recurring schedules
     */
    public function scopeDailyRecurring($query)
    {
        return $query->where('is_daily', true);
    }
    
    /**
     * Get formatted schedule information for display
     */
    public function getDisplayInfo()
    {
        $departure = $this->getActualDepartureTime();
        $arrival = $this->getActualArrivalTime();
        
        return [
            'departure' => $departure->format('H:i'),
            'arrival' => $arrival->format('H:i'),
            'date' => $departure->format('M j'),
            'full_departure' => $departure->format('Y-m-d H:i:s'),
            'full_arrival' => $arrival->format('Y-m-d H:i:s'),
            'is_weekly' => $this->is_weekly,
            'is_daily' => $this->is_daily,
            'day_of_week' => $this->is_weekly ? $this->day_of_week : null,
        ];
    }
}
