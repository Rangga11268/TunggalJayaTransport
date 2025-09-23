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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When retrieving the model, convert times to WIB
        static::retrieved(function ($schedule) {
            if ($schedule->departure_time) {
                $schedule->departure_time = $schedule->departure_time->setTimezone('Asia/Jakarta');
            }
            if ($schedule->arrival_time) {
                $schedule->arrival_time = $schedule->arrival_time->setTimezone('Asia/Jakarta');
            }
        });
    }

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
     * Returns times in WIB timezone for display
     */
    public function getActualDepartureTime($forDate = null)
    {
        $departureTime = null;
        
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, calculate the next occurrence
            $nextDate = $this->getNextAvailableDate();
            if ($nextDate) {
                // Combine the next date with the stored time
                $departureTime = $nextDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            } else {
                // Fallback to today with the time
                $departureTime = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            }
        } else if ($this->is_daily) {
            // For daily recurring schedules, we show the time for the specified date or today
            if ($forDate) {
                $departureTime = $forDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            } else {
                $today = \Carbon\Carbon::today('Asia/Jakarta');
                // Get today's departure time for comparison
                $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                
                // If departure time hasn't passed yet today, return today
                if ($todayDeparture->isFuture()) {
                    $departureTime = $todayDeparture;
                } else {
                    // Otherwise, return tomorrow's departure time
                    $departureTime = $today->copy()->addDay()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                }
            }
        } else {
            // For daily schedules, return the stored datetime
            $departureTime = $this->departure_time;
        }
        
        // Convert to WIB timezone for display
        return $departureTime->setTimezone('Asia/Jakarta');
    }

    /**
     * Get the actual arrival datetime for display/booking purposes
     * For weekly schedules, calculates the next occurrence
     * For daily recurring schedules, calculates the next occurrence
     * For daily schedules, returns the stored datetime
     * Returns times in WIB timezone for display
     */
    public function getActualArrivalTime($forDate = null)
    {
        $arrivalTime = null;
        
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, calculate the next occurrence
            $nextDate = $this->getNextAvailableDate();
            if ($nextDate) {
                // Combine the next date with the stored time
                $arrivalTime = $nextDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            } else {
                // Fallback to today with the time
                $arrivalTime = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            }
        } else if ($this->is_daily) {
            // For daily recurring schedules, we show the time for the specified date or today
            if ($forDate) {
                $arrivalTime = $forDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            } else {
                $today = \Carbon\Carbon::today('Asia/Jakarta');
                
                // Get today's departure time for comparison
                $todayDeparture = $today->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                
                // If departure time hasn't passed yet today, return today's arrival time
                if ($todayDeparture->isFuture()) {
                    $arrivalTime = $today->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                } else {
                    // Otherwise, return tomorrow's arrival time
                    $arrivalTime = $today->copy()->addDay()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                }
            }
        } else {
            // For daily schedules, return the stored datetime
            $arrivalTime = $this->arrival_time;
        }
        
        // Convert to WIB timezone for display
        return $arrivalTime->setTimezone('Asia/Jakarta');
    }

    /**
     * Check if the schedule departure time has passed
     * For weekly schedules, checks if the next occurrence has passed
     * For daily recurring schedules, checks if today's occurrence has passed (but they're always available for future dates)
     * For daily schedules, checks if the stored departure time has passed
     * 
     * Note: All times are stored in UTC in the database and converted to 
     * Asia/Jakarta timezone (WIB) for comparison with current time.
     */
    public function hasDeparted($forDate = null)
    {
        // Use local timezone (WIB) for checking departure time
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, check if the next occurrence has passed
            $actualDeparture = $this->getActualDepartureTime();
            // Convert to local timezone for comparison
            $actualDepartureLocal = $actualDeparture->setTimezone('Asia/Jakarta');
            return $actualDepartureLocal->isPast();
        }
        
        if ($this->is_daily) {
            // For daily recurring schedules, behavior depends on context:
            // 1. If checking for a specific date, only consider that date
            // 2. If checking generally (for availability), they never "depart" as they recur daily
            if ($forDate) {
                // Check if the schedule has departed for the specific date
                $checkDate = \Carbon\Carbon::parse($forDate)->setTimezone('Asia/Jakarta');
                $departureTime = $checkDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                return $departureTime->isPast() && $now->gt($departureTime);
            }
            
            // For general checks, daily recurring schedules are never considered "departed"
            // as they are available every day
            return false;
        }
        
        // For daily schedules, check against stored departure time
        // Make sure we're comparing with the actual datetime
        if ($this->departure_time instanceof \Carbon\Carbon) {
            // Convert to local timezone for comparison
            $departureTimeLocal = $this->departure_time->setTimezone('Asia/Jakarta');
            return $departureTimeLocal->isPast();
        }
        
        // If it's not a Carbon instance, try to parse it
        try {
            $departureTime = \Carbon\Carbon::parse($this->departure_time);
            // Convert to local timezone for comparison
            $departureTimeLocal = $departureTime->setTimezone('Asia/Jakarta');
            return $departureTimeLocal->isPast();
        } catch (\Exception $e) {
            // If parsing fails, fallback to basic comparison
            return false;
        }
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
    
    /*-------------------------------------------------------------------------
     * WIB Time Conversion Methods
     *-------------------------------------------------------------------------*/
    
    /**
     * Get departure time in WIB timezone for display purposes
     */
    public function getDepartureTimeWIB()
    {
        if ($this->departure_time instanceof Carbon) {
            return $this->departure_time->setTimezone('Asia/Jakarta');
        }
        
        try {
            $departureTime = Carbon::parse($this->departure_time);
            return $departureTime->setTimezone('Asia/Jakarta');
        } catch (\Exception $e) {
            return $this->departure_time;
        }
    }
    
    /**
     * Get arrival time in WIB timezone for display purposes
     */
    public function getArrivalTimeWIB()
    {
        if ($this->arrival_time instanceof Carbon) {
            return $this->arrival_time->setTimezone('Asia/Jakarta');
        }
        
        try {
            $arrivalTime = Carbon::parse($this->arrival_time);
            return $arrivalTime->setTimezone('Asia/Jakarta');
        } catch (\Exception $e) {
            return $this->arrival_time;
        }
    }

    /**
     * Check if the schedule is available for booking
     */
    public function isAvailableForBooking($forDate = null)
    {
        // Check if schedule is active
        if ($this->status !== 'active') {
            return false;
        }

        // Check if the schedule has already departed
        if ($this->hasDeparted($forDate)) {
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

        $today = \Carbon\Carbon::today('Asia/Jakarta');
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        
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
        // For daily recurring schedules, return a range of dates
        if ($this->is_daily) {
            $startDate = $startDate ? \Carbon\Carbon::parse($startDate) : \Carbon\Carbon::today('Asia/Jakarta');
            $endDate = $endDate ? \Carbon\Carbon::parse($endDate) : $startDate->copy()->addMonths(3); // Default 3 months
            
            $dates = collect();
            $currentDate = $startDate->copy();
            $count = 0;
            
            // Generate dates for each day in the range
            while ($currentDate->lte($endDate) && $count < $limit) {
                $dates->push($currentDate->copy());
                $currentDate->addDay();
                $count++;
            }
            
            return $dates;
        }
        
        // Only applicable for weekly schedules
        if (!$this->is_weekly || $this->day_of_week === null) {
            return collect();
        }

        $startDate = $startDate ? \Carbon\Carbon::parse($startDate) : \Carbon\Carbon::today('Asia/Jakarta');
        $endDate = $endDate ? \Carbon\Carbon::parse($endDate) : $startDate->copy()->addMonths(3); // Default 3 months
        
        $dates = collect();
        $currentDate = $startDate->copy();
        $count = 0;
        
        // Find the first occurrence
        if ($currentDate->dayOfWeek == $this->day_of_week) {
            // If today is the scheduled day, check if departure time hasn't passed yet
            $todayDeparture = $currentDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            if ($todayDeparture->isFuture() || $todayDeparture->isSameAs('H:i:s', \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s'))) {
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
