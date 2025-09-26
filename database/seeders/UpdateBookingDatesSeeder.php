<?php

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;

// This script will update existing bookings with appropriate booking dates
class UpdateBookingDatesSeeder extends Seeder
{
    public function run()
    {
        // Get all bookings that don't have a booking_date set
        $bookings = Booking::whereNull('booking_date')->get();
        
        foreach ($bookings as $booking) {
            $schedule = $booking->schedule;
            
            if (!$schedule) {
                continue; // Skip if schedule doesn't exist
            }
            
            $bookingDate = null;
            
            if ($schedule->is_weekly) {
                // For weekly schedules, determine the most likely date based on when the booking was created
                // A heuristic approach: if the schedule is weekly, find the next occurrence after booking creation
                $createdAt = $booking->created_at;
                
                // Get the day of the week for the schedule
                $scheduleDay = $schedule->day_of_week;
                
                // Find the next occurrence of that day after the booking was created
                $date = Carbon::parse($createdAt)->startOfDay();
                while ($date->dayOfWeek != $scheduleDay) {
                    $date->addDay();
                }
                
                $bookingDate = $date->toDateString();
            } elseif ($schedule->is_daily) {
                // For daily recurring schedules, use the created_at date
                $bookingDate = $booking->created_at->toDateString();
            } else {
                // For regular schedules, use the departure_time date
                $bookingDate = $schedule->departure_time->toDateString();
            }
            
            if ($bookingDate) {
                $booking->update(['booking_date' => $bookingDate]);
            }
        }
    }
}