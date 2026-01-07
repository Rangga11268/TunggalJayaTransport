<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pemesanan Tiket Baru - ' . $this->booking->booking_code)
            ->greeting('Halo Admin!')
            ->line('Ada pemesanan tiket baru dari ' . $this->booking->passenger_name)
            ->line('Rute: ' . $this->booking->schedule->route->origin . ' ke ' . $this->booking->schedule->route->destination)
            ->line('Total Bayar: Rp ' . number_format($this->booking->total_price, 0, ',', '.'))
            ->action('Lihat Detail Pemesanan', url('/admin/bookings/' . $this->booking->id))
            ->line('Terima kasih telah menggunakan sistem kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'message' => 'Pesanan baru dari ' . $this->booking->passenger_name,
            'route' => $this->booking->schedule->route->origin . ' - ' . $this->booking->schedule->route->destination,
            'amount' => $this->booking->total_price,
            'type' => 'booking'
        ];
    }
}
