<?php

// This is a backup of the Schedule model methods before implementing the daily recurring schedule fix

/*
OLD isAvailableForBooking method:

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
*/

/*
OLD getActualDepartureTime method:

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
*/

/*
OLD getActualArrivalTime method:

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
*/