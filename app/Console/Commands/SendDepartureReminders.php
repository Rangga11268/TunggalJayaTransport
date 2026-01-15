<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendDepartureReminders extends Command
{
    protected $signature = 'bookings:send-reminders';
    protected $description = 'Send WhatsApp reminders to customers for departures tomorrow';

    public function handle(WhatsAppNotificationService $waService)
    {
        $tomorrow = Carbon::tomorrow('Asia/Jakarta');
        
        $this->info("Sending reminders for departures on: " . $tomorrow->format('Y-m-d'));
        
        // Find all confirmed & paid bookings departing tomorrow
        $bookings = Booking::with(['schedule.route', 'schedule.bus'])
            ->where('payment_status', 'paid')
            ->where('booking_status', 'confirmed')
            ->whereDate('booking_date', $tomorrow)
            ->get();
        
        $this->info("Found {$bookings->count()} bookings to remind.");
        
        $sent = 0;
        $failed = 0;
        
        foreach ($bookings as $booking) {
            try {
                $result = $waService->sendDepartureReminder($booking);
                
                if ($result) {
                    $sent++;
                    $this->line("✓ Sent reminder to {$booking->passenger_phone} (#{$booking->booking_code})");
                } else {
                    $failed++;
                    $this->warn("✗ Failed to send to {$booking->passenger_phone} (#{$booking->booking_code})");
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Reminder command error: " . $e->getMessage());
                $this->error("✗ Error for {$booking->booking_code}: " . $e->getMessage());
            }
            
            // Rate limiting - wait 1 second between messages
            sleep(1);
        }
        
        $this->info("Done! Sent: {$sent}, Failed: {$failed}");
        
        return Command::SUCCESS;
    }
}
