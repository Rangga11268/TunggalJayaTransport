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
            if ($schedule->departure_time instanceof Carbon) {
                $schedule->departure_time = $schedule->departure_time->setTimezone('Asia/Jakarta');
            }
            if ($schedule->arrival_time instanceof Carbon) {
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
    
    public function getBookedSeatsCount($forDate = null)
    {
        $query = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('booking_status', '!=', 'cancelled');
            
        // If checking for a specific date, we need to filter by booking_date
        if ($forDate) {
            $query->whereDate('booking_date', $forDate);
        } else {
            // For recurring schedules, if no specific date provided, we might want to count upcoming bookings
            // This will help maintain existing behavior for cases where no date is specified
            if ($this->is_weekly || $this->is_daily) {
                // For recurring schedules, count bookings for future dates only
                $query->whereDate('booking_date', '>=', Carbon::today());
            }
        }
        
        return $query->sum('number_of_seats');
    }
    
    public function getAvailableSeatsCount($forDate = null)
    {
        // Only count confirmed bookings that are paid and not cancelled
        $bookedSeats = $this->getBookedSeatsCount($forDate);
        
        $available = $this->bus->capacity - $bookedSeats;
        return max(0, $available);
    }
    
    public function getBookedSeatNumbers($forDate = null)
    {
        // Get seat numbers for confirmed bookings that are paid and not cancelled
        $query = $this->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->where('booking_status', '!=', 'cancelled') // Exclude cancelled bookings
            ->whereNotNull('seat_numbers');
            
        // Filter by date if specified
        if ($forDate) {
            $query->whereDate('booking_date', $forDate);
        } elseif ($this->is_weekly || $this->is_daily) {
            // For recurring schedules without a specific date, consider future bookings only
            $query->whereDate('booking_date', '>=', Carbon::today());
        }
        
        $bookings = $query->pluck('seat_numbers')->toArray();
            
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
            // For weekly schedules, calculate the departure for the specified date
            if ($forDate) {
                // Use the provided date to find the specific occurrence
                $targetDate = $forDate->copy();
                // Make sure the day of week matches the schedule
                while ($targetDate->dayOfWeek != $this->day_of_week) {
                    $targetDate->addDay();
                }
                $departureTime = $targetDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            } else {
                // For backward compatibility, calculate the next occurrence from today
                $nextDate = $this->getNextAvailableDate();
                if ($nextDate) {
                    // Combine the next date with the stored time
                    $departureTime = $nextDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                } else {
                    // Fallback to today with the time
                    $departureTime = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                }
            }
        } else if ($this->is_daily) {
            // For daily recurring schedules, we show the time for the specified date or the next available time
            if ($forDate) {
                $departureTime = $forDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            } else {
                $now = \Carbon\Carbon::now('Asia/Jakarta');
                $today = \Carbon\Carbon::today('Asia/Jakarta');
                // Get today's departure time
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
            // For weekly schedules, calculate the arrival for the specified date
            if ($forDate) {
                // Use the provided date to find the specific occurrence
                $targetDate = $forDate->copy();
                // Make sure the day of week matches the schedule
                while ($targetDate->dayOfWeek != $this->day_of_week) {
                    $targetDate->addDay();
                }
                $arrivalTime = $targetDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            } else {
                // For backward compatibility, calculate the next occurrence from today
                $nextDate = $this->getNextAvailableDate();
                if ($nextDate) {
                    // Combine the next date with the stored time
                    $arrivalTime = $nextDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                } else {
                    // Fallback to today with the time
                    $arrivalTime = \Carbon\Carbon::today('Asia/Jakarta')->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                }
            }
        } else if ($this->is_daily) {
            // For daily recurring schedules, we show the time for the specified date or the next available time
            if ($forDate) {
                $arrivalTime = $forDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
            } else {
                $now = \Carbon\Carbon::now('Asia/Jakarta');
                $today = \Carbon\Carbon::today('Asia/Jakarta');
                
                // Get today's departure time
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
     * Note: All times are now stored and retrieved in Asia/Jakarta timezone (WIB)
     */
    public function hasDeparted($forDate = null)
    {
        // Use local timezone (WIB) for checking departure time
        $now = \Carbon\Carbon::now('Asia/Jakarta');
        
        if ($this->is_weekly && $this->day_of_week !== null) {
            // For weekly schedules, check if the specified occurrence has passed
            $actualDeparture = $this->getActualDepartureTime($forDate);
            // Already in local timezone, no need to convert
            return $actualDeparture->isPast();
        }
        
        if ($this->is_daily) {
            // For daily recurring schedules, check if the specified day's departure has passed
            $checkDate = $forDate ?: \Carbon\Carbon::today('Asia/Jakarta');
            $checkDeparture = $checkDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
            
            // Check if the specified day's departure time has passed
            return $checkDeparture->isPast();
        }
        
        // For daily schedules, check against stored departure time
        // Make sure we're comparing with the actual datetime
        if ($this->departure_time instanceof \Carbon\Carbon) {
            // Already in local timezone, no need to convert
            return $this->departure_time->isPast();
        }
        
        // If it's not a Carbon instance, try to parse it
        try {
            $departureTime = \Carbon\Carbon::parse($this->departure_time);
            // Already in local timezone, no need to convert
            return $departureTime->isPast();
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
        // For daily recurring schedules, this checks if the current day's departure has passed
        if ($this->hasDeparted($forDate)) {
            return false;
        }

        // Check if there are available seats for the specific date
        return $this->getAvailableSeatsCount($forDate) > 0;
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
        // For daily recurring schedules, return ALL non-cancelled bookings
        // since the seats need to be available again the next day
        if ($this->is_daily) {
            return $this->bookings()
                ->where('booking_status', '!=', 'cancelled') // Not already cancelled
                ->get();
        } else {
            // For regular schedules, only return pending payments
            return $this->bookings()
                ->where('booking_status', '!=', 'cancelled') // Not already cancelled
                ->where('payment_status', 'pending') // Only pending payments
                ->get();
        }
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
