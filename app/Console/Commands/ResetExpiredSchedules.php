<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use Carbon\Carbon;

class ResetExpiredSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:reset-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset expired schedules and prepare them for next day/week';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Resetting expired schedules...');

        // Get all daily schedules that have already departed today
        $expiredDailySchedules = Schedule::daily()
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

        // Get all weekly schedules that have already departed this week
        $expiredWeeklySchedules = Schedule::weekly()
            ->where('departure_time', '<', Carbon::now())
            ->get();

        $this->info("Found {$expiredWeeklySchedules->count()} expired weekly schedules.");

        // Reset expired weekly schedules for next week
        $weeklyResetCount = 0;
        foreach ($expiredWeeklySchedules as $schedule) {
            // Calculate next occurrence based on day of week
            $nextDeparture = Carbon::now()->next($schedule->day_of_week);
            $nextArrival = $nextDeparture->copy()->addHours(
                $schedule->arrival_time->diffInHours($schedule->departure_time)
            );

            // Create new schedule for next week
            $newSchedule = $schedule->replicate();
            $newSchedule->departure_time = $nextDeparture;
            $newSchedule->arrival_time = $nextArrival;
            $newSchedule->save();

            $this->info("Reset weekly schedule ID {$schedule->id} to " . $nextDeparture->format('Y-m-d H:i'));
            $weeklyResetCount++;
        }

        $this->info("Schedule reset process completed. Reset {$dailyResetCount} daily schedules and {$weeklyResetCount} weekly schedules.");
    }
}
