<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:update-statuses {--dry-run : Show what would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update booking statuses based on schedule departure times';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating booking statuses based on schedule departure times...');
        
        // Log the start of the process
        Log::info('Booking status update process started', [
            'dry_run' => $this->option('dry-run'),
            'timestamp' => Carbon::now()
        ]);
        
        // Get all confirmed bookings for schedules that have departed
        $departedBookings = Booking::with('schedule')
            ->where('booking_status', 'confirmed')
            ->whereHas('schedule', function ($query) {
                $query->where('departure_time', '<=', Carbon::now());
            })
            ->get();
        
        if ($departedBookings->isEmpty()) {
            $this->info('No bookings found that need status updates.');
            Log::info('No bookings found that need status updates');
            return 0;
        }
        
        $this->info("Found {$departedBookings->count()} bookings that may need status updates.");
        
        $updatedCount = 0;
        $errors = 0;
        
        foreach ($departedBookings as $booking) {
            try {
                // Only update bookings that are paid
                if ($booking->payment_status === 'paid') {
                    if ($this->option('dry-run')) {
                        $this->info("  Would update booking {$booking->booking_code} to completed status");
                        $updatedCount++;
                    } else {
                        // Update booking status to completed
                        $booking->update([
                            'booking_status' => 'completed'
                        ]);
                        
                        $updatedCount++;
                        
                        // Log the update
                        Log::info("Booking status updated to completed", [
                            'booking_id' => $booking->id,
                            'booking_code' => $booking->booking_code,
                            'passenger' => $booking->passenger_name,
                            'schedule_id' => $booking->schedule->id,
                            'departure_time' => $booking->schedule->departure_time
                        ]);
                        
                        $this->info("  Updated booking {$booking->booking_code} to completed status");
                    }
                } else if ($booking->payment_status === 'pending') {
                    // For pending payments on departed schedules, they should be handled by CleanupInvalidBookingsCommand
                    $this->info("  Booking {$booking->booking_code} has pending payment and will be handled by cleanup command");
                }
            } catch (\Exception $e) {
                $this->error("  Error updating booking {$booking->booking_code}: " . $e->getMessage());
                Log::error("Error updating booking status", [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errors++;
            }
        }
        
        $this->info("Updated {$updatedCount} booking statuses.");
        
        if ($errors > 0) {
            $this->warn("Encountered {$errors} errors during status updates.");
        }
        
        // Log completion
        Log::info('Booking status update process completed', [
            'bookings_updated' => $updatedCount,
            'dry_run' => $this->option('dry-run'),
            'errors' => $errors,
            'timestamp' => Carbon::now()
        ]);
        
        $this->info('Booking status update process completed successfully!');
        
        return 0;
    }
}