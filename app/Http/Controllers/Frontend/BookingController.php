<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Get all unique origins and destinations for the dropdowns
        $origins = BusRoute::pluck('origin')->unique()->values();
        $destinations = BusRoute::pluck('destination')->unique()->values();
        
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $date = $request->get('date');
        
        $schedules = collect();
        $validPair = false;
        
        if ($origin && $destination) {
            // Check if this is a valid route pair (in either direction)
            $validRoutes = BusRoute::where(function($query) use ($origin, $destination) {
                $query->where('origin', $origin)
                      ->where('destination', $destination);
            })->orWhere(function($query) use ($origin, $destination) {
                $query->where('origin', $destination)
                      ->where('destination', $origin);
            })->get();
            
            if ($validRoutes->count() > 0) {
                $validPair = true;
                // Get schedules for these routes
                $routeIds = $validRoutes->pluck('id');
                $schedules = Schedule::whereIn('route_id', $routeIds)
                    ->with('route', 'bus')
                    ->get();
            }
        }
        
        return view('frontend.booking.index', compact('schedules', 'origins', 'destinations', 'validPair', 'origin', 'destination'));
    }
    
    public function show($id)
    {
        $schedule = Schedule::with('route', 'bus')->findOrFail($id);
        
        return view('frontend.booking.show', compact('schedule'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'number_of_seats' => 'required|integer|min:1|max:5',
            'terms' => 'required|accepted',
        ]);
        
        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Create booking
        $booking = new Booking();
        $booking->user_id = auth()->check() ? auth()->id() : null;
        $booking->schedule_id = $schedule->id;
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_email = $request->passenger_email;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->seat_numbers = null; // Will be set later during seat selection
        $booking->total_price = $schedule->price * $request->number_of_seats;
        $booking->booking_code = 'BK' . strtoupper(uniqid());
        $booking->payment_status = 'pending';
        $booking->booking_status = 'confirmed';
        $booking->save();
        
        // Redirect to confirmation page with booking details
        return redirect()->route('frontend.booking.confirmation', ['booking' => $booking->id]);
    }
    
    public function confirmation($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        return view('frontend.booking.confirmation', compact('booking'));
    }
    
    public function selectSeats(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'seat_numbers' => 'required|array|min:1',
            'seat_numbers.*' => 'integer|min:1|max:40'
        ]);
        
        $booking = Booking::findOrFail($request->booking_id);
        $seatNumbers = implode(',', $request->seat_numbers);
        
        $booking->seat_numbers = $seatNumbers;
        $booking->save();
        
        return response()->json(['success' => true, 'message' => 'Seats selected successfully']);
    }
    
    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string|in:credit_card,bank_transfer,e_wallet'
        ]);
        
        $booking = Booking::findOrFail($request->booking_id);
        
        // In a real application, you would integrate with a payment gateway here
        // For now, we'll just mark the payment as completed
        $booking->payment_status = 'completed';
        $booking->save();
        
        return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
    }
    
    public function success($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        return view('frontend.booking.success', compact('booking'));
    }
}