<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ResetExpiredSchedules::class,
        Commands\ResetDepartedTicketsCommand::class,
        Commands\CleanupInvalidBookingsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Reset expired schedules daily at 1:00 AM
        $schedule->command('schedules:reset-expired')->dailyAt('01:00');
        
        // Reset departed tickets hourly
        $schedule->command('tickets:reset-departed')->hourly();
        
        // Cleanup invalid bookings daily at 2:00 AM
        $schedule->command('bookings:cleanup-invalid')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}