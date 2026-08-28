<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PromoCode;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'success',
                'data' => [],
                'unread_count' => 0,
            ]);
        }

        $dbNotifications = $user->notifications()->latest()->take(20)->get()->map(function ($n) {
            $data = is_array($n->data) ? $n->data : json_decode($n->data, true) ?? [];
            return [
                'id' => (string) $n->id,
                'title' => $data['title'] ?? 'Notifikasi PO Tunggal Jaya',
                'message' => $data['message'] ?? '',
                'type' => $data['type'] ?? 'info', // booking, reminder, promo, system
                'link' => $data['link'] ?? null,
                'is_read' => $n->read_at !== null,
                'created_at' => $n->created_at->toISOString(),
                'created_at_human' => $n->created_at->diffForHumans(),
            ];
        });

        // If no DB notifications exist yet, generate helpful context-aware notifications
        if ($dbNotifications->isEmpty()) {
            $synthetic = [];

            // Check latest booking
            $latestBooking = Booking::with(['schedule.bus', 'schedule.route'])
                ->where('user_id', $user->id)
                ->latest()
                ->first();

            if ($latestBooking) {
                $synthetic[] = [
                    'id' => 'booking-' . $latestBooking->id,
                    'title' => 'E-Tiket Siap Digunakan',
                    'message' => 'Tiket bus ' . ($latestBooking->schedule?->bus?->name ?? 'Resi Bisma') . ' rute ' . ($latestBooking->schedule?->route?->origin ?? 'Kuningan') . ' → ' . ($latestBooking->schedule?->route?->destination ?? 'Jakarta') . ' telah terbit.',
                    'type' => 'booking',
                    'booking_id' => $latestBooking->id,
                    'is_read' => false,
                    'created_at' => Carbon::now()->subMinutes(15)->toISOString(),
                    'created_at_human' => '15 menit yang lalu',
                ];
            }

            // Promo broadcast notification
            $synthetic[] = [
                'id' => 'promo-tjberkah',
                'title' => 'Voucher Promo Diskon 10%',
                'message' => 'Gunakan kode voucher "TJBERKAH" untuk mendapatkan potongan tarif tiket bus AKAP semua rute.',
                'type' => 'promo',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(2)->toISOString(),
                'created_at_human' => '2 jam yang lalu',
            ];

            // Service guarantee notification
            $synthetic[] = [
                'id' => 'service-welcome',
                'title' => 'Selamat Datang di Tunggal Jaya',
                'message' => 'Nikmati kenyamanan armada Hino RM 280 Air Suspension dan servis makan prasmanan gratis di Tol Cipali KM 166.',
                'type' => 'system',
                'is_read' => false,
                'created_at' => Carbon::now()->subDays(1)->toISOString(),
                'created_at_human' => '1 hari yang lalu',
            ];

            return response()->json([
                'status' => 'success',
                'data' => $synthetic,
                'unread_count' => count(array_filter($synthetic, fn($item) => !$item['is_read'])),
            ]);
        }

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'status' => 'success',
            'data' => $dbNotifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        if ($user) {
            $notification = $user->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->unreadNotifications()->update(['read_at' => Carbon::now()]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi ditandai telah dibaca.',
        ]);
    }

    /**
     * Get unread notification count
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $count = $user ? $user->unreadNotifications()->count() : 0;

        return response()->json([
            'status' => 'success',
            'unread_count' => $count > 0 ? $count : 2, // default 2 active notifications
        ]);
    }
}
