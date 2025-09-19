<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupInvalidBookingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup-invalid {--dry-run : Show what would be cleaned without actually cleaning} {--date= : Specific date to check for departures (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up invalid bookings such as those for departed schedules or with invalid statuses';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of invalid bookings...');
        
        // Log the start of the process
        Log::info('Booking cleanup process started', [
            'dry_run' => $this->option('dry-run'),
            'date_filter' => $this->option('date'),
            'timestamp' => Carbon::now()
        ]);
        
        // Get all bookings that are potentially invalid
        // We focus on bookings that are not paid yet but confirmed for departed schedules
        $query = Booking::where('booking_status', 'confirmed')
            ->where('payment_status', 'pending');
            
        if ($this->option('date')) {
            // Filter by specific date
            $date = Carbon::createFromFormat('Y-m-d', $this->option('date'));
            $query->whereHas('schedule', function ($subQuery) use ($date) {
                $subQuery->whereDate('departure_time', $date);
            });
        } else {
            // Normal operation - schedules that have already departed
            $query->whereHas('schedule', function ($subQuery) {
                $subQuery->where('departure_time', '<=', Carbon::now());
            });
        }
        
        $invalidBookings = $query->get();
        
        if ($invalidBookings->isEmpty()) {
            $this->info('No invalid bookings found.');
            Log::info('No invalid bookings found for cleanup');
            return 0;
        }
        
        $this->info("Found {$invalidBookings->count()} potentially invalid bookings.");
        
        $cleanedCount = 0;
        $errors = 0;
        
        foreach ($invalidBookings as $booking) {
            try {
                $schedule = $booking->schedule;
                
                // Double-check that the schedule has actually departed
                if ($schedule && $schedule->hasDeparted()) {
                    if ($this->option('dry-run')) {
                        $this->info("  Would clean booking {$booking->booking_code} for departed schedule");
                        $cleanedCount++;
                    } else {
                        // Cancel the booking
                        $booking->update([
                            'booking_status' => 'cancelled',
                            'payment_status' => 'failed',
                            'seat_numbers' => null
                        ]);
                        
                        $cleanedCount++;
                        
                        // Log cancellation
                        Log::info("Invalid booking {$booking->booking_code} cleaned up", [
                            'booking_id' => $booking->id,
                            'schedule_id' => $schedule->id,
                            'reason' => 'Schedule departed',
                            'timestamp' => Carbon::now()
                        ]);
                        
                        $this->info("  Cleaned booking {$booking->booking_code} for departed schedule");
                    }
                }
            } catch (\Exception $e) {
                $this->error("  Error processing booking {$booking->booking_code}: " . $e->getMessage());
                Log::error("Error cleaning booking {$booking->booking_code}", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errors++;
            }
        }
        
        $this->info("Cleaned up {$cleanedCount} invalid bookings.");
        
        if ($errors > 0) {
            $this->warn("Encountered {$errors} errors during cleanup.");
        }
        
        // Log completion
        Log::info('Booking cleanup completed', [
            'bookings_cleaned' => $cleanedCount,
            'dry_run' => $this->option('dry-run'),
            'date_filter' => $this->option('date'),
            'errors' => $errors,
            'timestamp' => Carbon::now()
        ]);
        
        $this->info('Booking cleanup completed successfully!');
        
        return 0;
    }
}
