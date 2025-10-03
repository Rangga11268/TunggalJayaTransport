<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    /**
     * Display a listing of the user's booking history.
     */
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

        return view('booking-history.index', compact('bookings'));
    }

    /**
     * Display the specified booking details.
     */
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

        return view('booking-history.show', compact('booking'));
    }
}