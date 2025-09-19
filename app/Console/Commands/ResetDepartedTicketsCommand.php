<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Schedule;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResetDepartedTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:reset-departed {--force : Force reset even if not departed yet} {--dry-run : Show what would be reset without actually resetting} {--date= : Specific date to check for departures (Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically reset bookings for schedules that have departed, freeing up seats for future bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting automatic ticket reset for departed schedules...');
            
            // Log the start of the process
            Log::info('Automatic ticket reset process started', [
                'force_mode' => $this->option('force'),
                'dry_run' => $this->option('dry-run'),
                'date_filter' => $this->option('date'),
                'timestamp' => Carbon::now()
            ]);
            
            // Get schedules that have departed (or force reset if --force option is used)
            $query = Schedule::with('bookings', 'bus');
            
            if (!$this->option('force')) {
                if ($this->option('date')) {
                    // Filter by specific date
                    $date = Carbon::createFromFormat('Y-m-d', $this->option('date'));
                    $query->whereDate('departure_time', $date);
                } else {
                    // Normal operation - schedules that have already departed
                    $query->where('departure_time', '<=', Carbon::now());
                }
            }
            
            $departedSchedules = $query->get();
            
            if ($departedSchedules->isEmpty()) {
                $this->info('No departed schedules found.');
                Log::info('No departed schedules found for ticket reset');
                return 0;
            }
            
            $this->info("Found {$departedSchedules->count()} schedules to process.");
            
            $totalBookingsCancelled = 0;
            $totalSchedulesProcessed = 0;
            $errors = 0;
            
            foreach ($departedSchedules as $schedule) {
                try {
                    if ($this->option('dry-run')) {
                        $this->processScheduleDryRun($schedule, $totalBookingsCancelled);
                    } else {
                        $this->processSchedule($schedule, $totalBookingsCancelled);
                    }
                    $totalSchedulesProcessed++;
                } catch (\Exception $e) {
                    $this->error("Error processing schedule ID {$schedule->id}: " . $e->getMessage());
                    Log::error("Error processing schedule ID {$schedule->id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $errors++;
                }
            }
            
            $this->info("Processed {$totalSchedulesProcessed} schedules.");
            if ($this->option('dry-run')) {
                $this->info("Would cancel {$totalBookingsCancelled} bookings (dry run).");
            } else {
                $this->info("Cancelled {$totalBookingsCancelled} bookings.");
            }
            
            if ($errors > 0) {
                $this->warn("Encountered {$errors} errors during processing.");
            }
            
            $this->info('Automatic ticket reset completed successfully!');
            
            // Log the completion
            Log::info('Automatic ticket reset completed', [
                'schedules_processed' => $totalSchedulesProcessed,
                'bookings_cancelled' => $totalBookingsCancelled,
                'dry_run' => $this->option('dry-run'),
                'date_filter' => $this->option('date'),
                'errors' => $errors,
                'timestamp' => Carbon::now()
            ]);
            
            return 0; // Success
        } catch (\Exception $e) {
            $this->error('Fatal error during ticket reset: ' . $e->getMessage());
            Log::critical('Fatal error during ticket reset', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'timestamp' => Carbon::now()
            ]);
            
            return 1; // Failure
        }
    }
    
    /**
     * Process a single schedule for ticket reset
     */
    private function processSchedule(Schedule $schedule, &$totalBookingsCancelled)
    {
        $this->info("Processing schedule ID {$schedule->id}: {$schedule->route->origin} → {$schedule->route->destination}");
        
        // Check if the schedule has actually departed
        if (!$schedule->hasDeparted() && !$this->option('force')) {
            $this->info("  Schedule has not departed yet. Skipping...");
            return;
        }
        
        // For weekly schedules, check if it's the correct day
        if ($schedule->is_weekly && $schedule->day_of_week !== null) {
            // For weekly schedules, we only reset if it's the correct day of the week
            $today = Carbon::now()->dayOfWeek;
            if ($today != $schedule->day_of_week) {
                $this->info("  Weekly schedule but not the correct day. Skipping...");
                return;
            }
        }
        
        try {
            // Get all bookings for this schedule that are not yet paid or confirmed
            // We only cancel bookings that are pending payment or not yet confirmed
            $bookingsToCancel = $schedule->bookings()
                ->where('booking_status', '!=', 'confirmed') // Not confirmed bookings
                ->orWhere(function ($query) {
                    // Or confirmed bookings that haven't been paid yet
                    $query->where('booking_status', 'confirmed')
                          ->where('payment_status', 'pending');
                })
                ->get();
                
            if ($bookingsToCancel->isEmpty()) {
                $this->info("  No bookings to cancel for this schedule.");
                return;
            }
            
            $cancelledCount = 0;
            foreach ($bookingsToCancel as $booking) {
                try {
                    // Cancel the booking
                    $booking->update([
                        'booking_status' => 'cancelled',
                        'payment_status' => 'failed', // Using 'failed' as it's in the enum
                        'seat_numbers' => null // Clear seat numbers
                    ]);
                    
                    $cancelledCount++;
                    $totalBookingsCancelled++;
                    
                    // Log cancellation
                    Log::info("Booking {$booking->booking_code} cancelled due to schedule departure", [
                        'booking_id' => $booking->id,
                        'schedule_id' => $schedule->id,
                        'passenger' => $booking->passenger_name,
                        'timestamp' => Carbon::now()
                    ]);
                } catch (\Exception $e) {
                    $this->error("  Error cancelling booking ID {$booking->id}: " . $e->getMessage());
                    Log::error("Error cancelling booking ID {$booking->id}", [
                        'error' => $e->getMessage(),
                        'booking_id' => $booking->id,
                        'schedule_id' => $schedule->id,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
            
            $this->info("  Cancelled {$cancelledCount} bookings for this schedule.");
            
            // Log schedule processing
            Log::info("Schedule processed for ticket reset", [
                'schedule_id' => $schedule->id,
                'route' => "{$schedule->route->origin} → {$schedule->route->destination}",
                'departure_time' => $schedule->departure_time,
                'bookings_cancelled' => $cancelledCount,
                'timestamp' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            $this->error("  Error retrieving bookings for schedule ID {$schedule->id}: " . $e->getMessage());
            Log::error("Error retrieving bookings for schedule ID {$schedule->id}", [
                'error' => $e->getMessage(),
                'schedule_id' => $schedule->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw to be caught by the outer try-catch
        }
    }
    
    /**
     * Process a single schedule for ticket reset (dry run)
     */
    private function processScheduleDryRun(Schedule $schedule, &$totalBookingsCancelled)
    {
        $this->info("Processing schedule ID {$schedule->id}: {$schedule->route->origin} → {$schedule->route->destination}");
        
        // Check if the schedule has actually departed
        if (!$schedule->hasDeparted() && !$this->option('force')) {
            $this->info("  Schedule has not departed yet. Skipping...");
            return;
        }
        
        // For weekly schedules, check if it's the correct day
        if ($schedule->is_weekly && $schedule->day_of_week !== null) {
            // For weekly schedules, we only reset if it's the correct day of the week
            $today = Carbon::now()->dayOfWeek;
            if ($today != $schedule->day_of_week) {
                $this->info("  Weekly schedule but not the correct day. Skipping...");
                return;
            }
        }
        
        try {
            // Get all bookings for this schedule that are not yet paid or confirmed
            // We only cancel bookings that are pending payment or not yet confirmed
            $bookingsToCancel = $schedule->getBookingsToCancel();
                
            if ($bookingsToCancel->isEmpty()) {
                $this->info("  No bookings to cancel for this schedule.");
                return;
            }
            
            $cancelledCount = 0;
            foreach ($bookingsToCancel as $booking) {
                $this->info("  Would cancel booking ID {$booking->id} ({$booking->booking_code}) for passenger {$booking->passenger_name}");
                $cancelledCount++;
                $totalBookingsCancelled++;
            }
            
            $this->info("  Would cancel {$cancelledCount} bookings for this schedule.");
            
            // Log schedule processing
            Log::info("Schedule processed for ticket reset (dry run)", [
                'schedule_id' => $schedule->id,
                'route' => "{$schedule->route->origin} → {$schedule->route->destination}",
                'departure_time' => $schedule->departure_time,
                'bookings_cancelled' => $cancelledCount,
                'timestamp' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            $this->error("  Error retrieving bookings for schedule ID {$schedule->id}: " . $e->getMessage());
            Log::error("Error retrieving bookings for schedule ID {$schedule->id}", [
                'error' => $e->getMessage(),
                'schedule_id' => $schedule->id,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e; // Re-throw to be caught by the outer try-catch
        }
    }
}
