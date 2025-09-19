<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup-expired {--dry-run : Show what would be cleaned without actually cleaning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up bookings with expired payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of bookings with expired payments...');
        
        // Log the start of the process
        Log::info('Expired payments cleanup process started', [
            'dry_run' => $this->option('dry-run'),
            'timestamp' => Carbon::now()
        ]);
        
        // Get all bookings with pending payments that have expired
        $expiredBookings = Booking::where('payment_status', 'pending')
            ->whereNotNull('payment_started_at')
            ->get()
            ->filter(function ($booking) {
                return $booking->isPaymentExpired();
            });
        
        if ($expiredBookings->isEmpty()) {
            $this->info('No bookings with expired payments found.');
            Log::info('No bookings with expired payments found for cleanup');
            return 0;
        }
        
        $this->info("Found {$expiredBookings->count()} bookings with expired payments.");
        
        $cleanedCount = 0;
        $errors = 0;
        
        foreach ($expiredBookings as $booking) {
            try {
                if ($this->option('dry-run')) {
                    $this->info("  Would clean booking {$booking->booking_code} with expired payment");
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
                    Log::info("Booking {$booking->booking_code} cleaned up due to expired payment", [
                        'booking_id' => $booking->id,
                        'schedule_id' => $booking->schedule_id,
                        'reason' => 'Payment expired',
                        'timestamp' => Carbon::now()
                    ]);
                    
                    $this->info("  Cleaned booking {$booking->booking_code} with expired payment");
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
        
        $this->info("Cleaned up {$cleanedCount} bookings with expired payments.");
        
        if ($errors > 0) {
            $this->warn("Encountered {$errors} errors during cleanup.");
        }
        
        // Log completion
        Log::info('Expired payments cleanup completed', [
            'bookings_cleaned' => $cleanedCount,
            'errors' => $errors,
            'timestamp' => Carbon::now()
        ]);
        
        $this->info('Expired payments cleanup completed successfully!');
        
        return 0;
    }
}