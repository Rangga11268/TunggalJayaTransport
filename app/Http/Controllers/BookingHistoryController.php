<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your booking history.');
        }
        
        // Get all bookings for the authenticated user
        $bookings = Booking::where('user_id', $user->id)
            ->with(['schedule.route', 'schedule.bus']) // Load related data for display
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results for better UI

        $charterBookings = \App\Models\CharterBooking::where('user_id', $user->id)
            ->with('assignedBus')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($charterBookings as $cb) {
            $cb->checkAndCancelIfExpired();
        }

        return \Inertia\Inertia::render('Frontend/BookingHistory/Index', [
            'bookings' => $bookings,
            'charter_bookings' => $charterBookings
        ]);
    }

    
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view booking details.');
        }

        // Get specific booking for the authenticated user
        $booking = Booking::where('user_id', $user->id)
            ->with(['schedule.route', 'schedule.bus'])
            ->findOrFail($id);

        return \Inertia\Inertia::render('Frontend/BookingHistory/Show', [
            'booking' => $booking
        ]);
    }

    public function showCharter($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view booking details.');
        }

        $charter = \App\Models\CharterBooking::where('user_id', $user->id)
            ->with('assignedBus')
            ->findOrFail($id);

        $charter->checkAndCancelIfExpired();

        return \Inertia\Inertia::render('Frontend/BookingHistory/CharterShow', [
            'charter' => $charter
        ]);
    }

    public function payCharter(Request $request, $id, PaymentService $paymentService)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Please login'], 401);
        }

        $charter = \App\Models\CharterBooking::where('user_id', $user->id)->findOrFail($id);

        $charter->checkAndCancelIfExpired();
        if ($charter->status === 'cancelled') {
            return response()->json([
                'status' => 'error',
                'message' => 'Pemesanan sudah dibatalkan otomatis karena melewati batas waktu pembayaran.'
            ], 400);
        }

        try {
            $paymentMethod = $request->input('payment_method', 'gopay');
            $type = $request->input('type', 'dp'); // dp, pelunasan, full
            $result = $paymentService->processCharterPayment($charter->id, $paymentMethod, $type);

            if ($result['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $result['snap_token'],
                    'redirect_url' => $result['redirect_url']
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => $result['message'] ?? 'Gagal memproses pembayaran.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}