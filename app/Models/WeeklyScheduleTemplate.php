<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WeeklyScheduleTemplate extends Model
{
    protected $fillable = [
        'name',
        'bus_id',
        'route_id',
        'departure_time',
        'arrival_time',
        'price',
        'day_of_week',
        'status',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'day_of_week' => 'integer',
        'status' => 'string',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the day name for this template
     */
    public function getDayName()
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday'
        ];
        
        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Create actual schedules from this template for a given date range
     */
    public function createSchedulesForDateRange($startDate, $endDate)
    {
        $createdSchedules = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        // Loop through each week in the date range
        while ($currentDate->lte($endDate)) {
            // Get the date for this template's day of week in the current week
            $scheduleDate = $currentDate->copy()->next($this->day_of_week);
            
            // If the schedule date is within our range, create a schedule
            if ($scheduleDate->lte($endDate)) {
                $schedule = new Schedule();
                $schedule->bus_id = $this->bus_id;
                $schedule->route_id = $this->route_id;
                $schedule->departure_time = $scheduleDate->copy()->setTimeFromTimeString($this->departure_time->format('H:i:s'));
                $schedule->arrival_time = $scheduleDate->copy()->setTimeFromTimeString($this->arrival_time->format('H:i:s'));
                $schedule->price = $this->price;
                $schedule->status = $this->status;
                $schedule->is_weekly = false; // These are actual daily schedules created from template
                $schedule->day_of_week = null;
                $schedule->save();
                
                $createdSchedules[] = $schedule;
            }
            
            // Move to next week
            $currentDate->addWeek();
        }
        
        return $createdSchedules;
    }

    /**
     * Scope for active templates only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}