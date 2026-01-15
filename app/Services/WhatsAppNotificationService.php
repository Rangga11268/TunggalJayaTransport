<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    /**
     * Send E-Ticket confirmation via WhatsApp after successful payment.
     */
    public function sendBookingConfirmation(Booking $booking): bool
    {
        $booking->load('schedule.route', 'schedule.bus');
        
        $phone = $this->formatPhone($booking->passenger_phone);
        if (!$phone) {
            Log::warning("Invalid phone number for booking {$booking->booking_code}");
            return false;
        }

        $route = $booking->schedule?->route;
        $bus = $booking->schedule?->bus;
        
        $message = $this->buildConfirmationMessage($booking, $route, $bus);
        
        return $this->send($phone, $message);
    }

    /**
     * Send departure reminder (typically H-1 before departure).
     */
    public function sendDepartureReminder(Booking $booking): bool
    {
        $booking->load('schedule.route', 'schedule.bus');
        
        $phone = $this->formatPhone($booking->passenger_phone);
        if (!$phone) {
            return false;
        }

        $route = $booking->schedule?->route;
        $departureTime = $booking->departure_time ?? $booking->schedule?->departure_time;
        
        $message = "*🚌 PENGINGAT KEBERANGKATAN*\n\n";
        $message .= "Halo *{$booking->passenger_name}*,\n\n";
        $message .= "Ini adalah pengingat bahwa perjalanan Anda akan berangkat *BESOK*:\n\n";
        $message .= "📍 *Rute:* " . ($route ? "{$route->origin} → {$route->destination}" : "-") . "\n";
        $message .= "🕐 *Waktu:* " . ($departureTime ? $departureTime->format('d M Y, H:i') : "-") . " WIB\n";
        $message .= "💺 *Kursi:* {$booking->seat_numbers}\n";
        $message .= "🎫 *Kode:* {$booking->booking_code}\n\n";
        $message .= "Pastikan Anda tiba di lokasi keberangkatan *30 menit* sebelum jadwal.\n\n";
        $message .= "Terima kasih telah memilih *TUNGGAL JAYA TRANSPORT*! 🙏";
        
        return $this->send($phone, $message);
    }

    /**
     * Send payment success notification.
     */
    public function sendPaymentSuccess(Booking $booking): bool
    {
        $phone = $this->formatPhone($booking->passenger_phone);
        if (!$phone) {
            return false;
        }

        $message = "*✅ PEMBAYARAN BERHASIL*\n\n";
        $message .= "Halo *{$booking->passenger_name}*,\n\n";
        $message .= "Pembayaran untuk booking *{$booking->booking_code}* telah berhasil.\n";
        $message .= "Total: *Rp " . number_format($booking->total_price, 0, ',', '.') . "*\n\n";
        $message .= "E-Tiket akan dikirimkan melalui pesan berikutnya.\n\n";
        $message .= "Terima kasih! 🙏";
        
        return $this->send($phone, $message);
    }

    /**
     * Build confirmation message with detailed ticket info.
     */
    private function buildConfirmationMessage(Booking $booking, $route, $bus): string
    {
        $departureTime = $booking->departure_time ?? $booking->schedule?->departure_time;
        
        $message = "*🎫 E-TICKET TUNGGAL JAYA TRANSPORT*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "👤 *Penumpang:* {$booking->passenger_name}\n";
        $message .= "📱 *Telepon:* {$booking->passenger_phone}\n\n";
        
        $message .= "📍 *Rute:*\n";
        $message .= ($route ? "   {$route->origin} → {$route->destination}" : "   -") . "\n\n";
        
        $message .= "🚌 *Bus:* " . ($bus ? $bus->name : "-") . "\n";
        $message .= "📅 *Tanggal:* " . ($departureTime ? $departureTime->format('d M Y') : "-") . "\n";
        $message .= "🕐 *Jam:* " . ($departureTime ? $departureTime->format('H:i') : "-") . " WIB\n";
        $message .= "💺 *Kursi:* {$booking->seat_numbers}\n\n";
        
        $message .= "💰 *Total Bayar:* Rp " . number_format($booking->total_price, 0, ',', '.') . "\n";
        
        if ($booking->discount_amount > 0) {
            $message .= "🏷️ *Hemat:* Rp " . number_format($booking->discount_amount, 0, ',', '.') . "\n";
        }
        
        $message .= "\n━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "🎫 *KODE BOOKING:* *{$booking->booking_code}*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $message .= "📌 *PENTING:*\n";
        $message .= "• Tunjukkan kode booking saat check-in\n";
        $message .= "• Hadir 30 menit sebelum keberangkatan\n";
        $message .= "• Simpan pesan ini sebagai bukti\n\n";
        
        $message .= "Terima kasih telah memilih *TUNGGAL JAYA TRANSPORT*! 🙏\n";
        $message .= "Selamat perjalanan! 🚌✨";
        
        return $message;
    }

    /**
     * Send WhatsApp message via Fonnte API.
     */
    private function send(string $phone, string $message): bool
    {
        $token = config('services.fonnte.token');
        
        if (!$token) {
            Log::error("Fonnte token not configured");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                $body = $response->json();
                if (isset($body['status']) && $body['status'] === true) {
                    Log::info("WhatsApp sent successfully to {$phone}");
                    return true;
                }
                Log::warning("Fonnte response: " . $response->body());
            } else {
                Log::error("Fonnte API Error: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Format phone number to international format.
     */
    private function formatPhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        // Remove spaces, dashes, etc.
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xxx to 628xxx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Validate minimum length
        if (strlen($phone) < 10) {
            return null;
        }

        return $phone;
    }
}
