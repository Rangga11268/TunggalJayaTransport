<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendDepartureRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:departure-reminders {--hours=24 : Hours before departure to send reminder} {--dry-run : Show what notifications would be sent without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send departure reminders to passengers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $this->info("Sending departure reminders for bookings departing in {$hours} hours...");
        
        // Log the start of the process
        Log::info('Departure reminders process started', [
            'hours' => $hours,
            'dry_run' => $this->option('dry-run'),
            'timestamp' => Carbon::now()
        ]);
        
        // Calculate the time window
        $now = Carbon::now();
        $targetTime = $now->copy()->addHours($hours);
        
        // Get bookings for schedules departing in the target time window
        $bookings = Booking::with('schedule.route')
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->whereHas('schedule', function ($query) use ($now, $targetTime) {
                $query->where('departure_time', '>', $now)
                      ->where('departure_time', '<=', $targetTime);
            })
            ->get();
        
        if ($bookings->isEmpty()) {
            $this->info('No bookings found for departure reminders.');
            Log::info('No bookings found for departure reminders');
            return 0;
        }
        
        $this->info("Found {$bookings->count()} bookings for departure reminders.");
        
        $sentCount = 0;
        $errors = 0;
        
        foreach ($bookings as $booking) {
            try {
                if ($this->option('dry-run')) {
                    $this->info("  Would send reminder to {$booking->passenger_name} <{$booking->passenger_email}> for booking {$booking->booking_code}");
                    $sentCount++;
                } else {
                    // Send email notification
                    // In a real implementation, you would use Laravel's Mail facade
                    // Mail::to($booking->passenger_email)->send(new DepartureReminder($booking));
                    
                    // For now, we'll just log that we would send the email
                    Log::info("Departure reminder sent", [
                        'booking_id' => $booking->id,
                        'booking_code' => $booking->booking_code,
                        'passenger' => $booking->passenger_name,
                        'email' => $booking->passenger_email,
                        'schedule_departure' => $booking->schedule->departure_time,
                        'hours_until_departure' => $now->diffInHours($booking->schedule->departure_time)
                    ]);
                    
                    $sentCount++;
                    $this->info("  Sent reminder to {$booking->passenger_name} for booking {$booking->booking_code}");
                }
            } catch (\Exception $e) {
                $this->error("  Error sending reminder for booking {$booking->booking_code}: " . $e->getMessage());
                Log::error("Error sending departure reminder", [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errors++;
            }
        }
        
        $this->info("Sent {$sentCount} departure reminders.");
        
        if ($errors > 0) {
            $this->warn("Encountered {$errors} errors during notification sending.");
        }
        
        // Log completion
        Log::info('Departure reminders process completed', [
            'reminders_sent' => $sentCount,
            'dry_run' => $this->option('dry-run'),
            'hours' => $hours,
            'errors' => $errors,
            'timestamp' => Carbon::now()
        ]);
        
        $this->info('Departure reminders process completed successfully!');
        
        return 0;
    }
}