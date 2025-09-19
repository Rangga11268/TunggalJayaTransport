<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Booking;
use Carbon\Carbon;

class ShowDepartedSchedulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedules:show-departed {--days=7 : Number of days to look back}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show departed schedules and their booking status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Showing departed schedules from the last {$days} days:");
        $this->line('');
        
        // Get departed schedules
        $departedSchedules = Schedule::with('bookings', 'route', 'bus')
            ->where('departure_time', '<=', Carbon::now())
            ->where('departure_time', '>=', $cutoffDate)
            ->orderBy('departure_time', 'desc')
            ->get();
            
        if ($departedSchedules->isEmpty()) {
            $this->info('No departed schedules found in the specified period.');
            return 0;
        }
        
        $this->table(
            ['Schedule ID', 'Route', 'Bus', 'Departure Time', 'Total Bookings', 'Paid Bookings', 'Pending Bookings'],
            $departedSchedules->map(function ($schedule) {
                $totalBookings = $schedule->bookings->count();
                $paidBookings = $schedule->bookings->where('payment_status', 'paid')->count();
                $pendingBookings = $schedule->bookings->where('payment_status', 'pending')->count();
                
                return [
                    $schedule->id,
                    $schedule->route->origin . ' → ' . $schedule->route->destination,
                    $schedule->bus->name,
                    $schedule->departure_time->format('d M Y H:i'),
                    $totalBookings,
                    $paidBookings,
                    $pendingBookings
                ];
            })->toArray()
        );
        
        // Show summary
        $totalSchedules = $departedSchedules->count();
        $totalBookings = $departedSchedules->sum(function ($schedule) {
            return $schedule->bookings->count();
        });
        $totalPaidBookings = $departedSchedules->sum(function ($schedule) {
            return $schedule->bookings->where('payment_status', 'paid')->count();
        });
        $totalPendingBookings = $departedSchedules->sum(function ($schedule) {
            return $schedule->bookings->where('payment_status', 'pending')->count();
        });
        
        $this->line('');
        $this->info('Summary:');
        $this->line("Total Departed Schedules: {$totalSchedules}");
        $this->line("Total Bookings: {$totalBookings}");
        $this->line("Paid Bookings: {$totalPaidBookings}");
        $this->line("Pending Bookings (will be cancelled): {$totalPendingBookings}");
        
        return 0;
    }
}