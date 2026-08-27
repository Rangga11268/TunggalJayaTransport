<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class ResetExpiredSchedules extends Command
{

    protected $signature = 'schedules:reset-expired';


    protected $description = 'Reset expired schedules and prepare them for next day';


    public function handle()
    {
        $this->info('Resetting expired schedules...');

        // Get all non-recurring schedules that have already departed today
        $expiredDailySchedules = Schedule::nonRecurring()
            ->where('departure_time', '<', Carbon::now())
            ->get();

        $this->info("Found {$expiredDailySchedules->count()} expired daily schedules.");

        // Reset expired daily schedules for next day
        $dailyResetCount = 0;
        foreach ($expiredDailySchedules as $schedule) {
            // Create a new schedule for tomorrow with the same details
            $newDepartureTime = $schedule->departure_time->copy()->addDay();
            $newArrivalTime = $schedule->arrival_time->copy()->addDay();

            // Only create new schedule if it's in the future
            if ($newDepartureTime->isFuture()) {
                $newSchedule = $schedule->replicate();
                $newSchedule->departure_time = $newDepartureTime;
                $newSchedule->arrival_time = $newArrivalTime;
                $newSchedule->save();

                $this->info("Reset daily schedule ID {$schedule->id} to " . $newDepartureTime->format('Y-m-d H:i'));
                $dailyResetCount++;
            }
        }

        // Get all daily recurring schedules
        $dailyRecurringSchedules = Schedule::dailyRecurring()->get();
        $this->info("Found {$dailyRecurringSchedules->count()} daily recurring schedules.");

        foreach ($dailyRecurringSchedules as $schedule) {
            $this->info("Processed daily recurring schedule ID {$schedule->id} - available every day");
        }



        $this->info("Schedule reset process completed. Reset {$dailyResetCount} daily schedules and processed {$dailyRecurringSchedules->count()} daily recurring schedules.");
    }
}
