<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
                // Get schedules for these routes that are available for booking
                $routeIds = $validRoutes->pluck('id');
                $schedules = Schedule::whereIn('route_id', $routeIds)
                    ->with('route', 'bus')
                    ->available()
                    ->get()
                    ->filter(function ($schedule) {
                        return $schedule->isAvailableForBooking();
                    });
            }
        }
        
        return view('frontend.booking.index', compact('schedules', 'origins', 'destinations', 'validPair', 'origin', 'destination'));
    }
    
    public function schedules(Request $request)
    {
        // Get all unique origins and destinations for the dropdowns
        $origins = BusRoute::pluck('origin')->unique()->values();
        $destinations = BusRoute::pluck('destination')->unique()->values();
        
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $date = $request->get('date');
        
        // Validate request parameters
        $request->validate([
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);
        
        // Get all schedules with their relations that are available for booking
        $query = Schedule::with('route', 'bus')->available();
        
        // Apply filters if provided
        if ($origin) {
            $query->whereHas('route', function ($q) use ($origin) {
                $q->where('origin', $origin);
            });
        }
        
        if ($destination) {
            $query->whereHas('route', function ($q) use ($destination) {
                $q->where('destination', $destination);
            });
        }
        
        if ($date) {
            $query->whereDate('departure_time', $date);
        }
        
        // Order by departure time
        $schedules = $query->orderBy('departure_time')->paginate(10);
        
        // Filter out schedules that are not available for booking
        $schedules->setCollection(
            $schedules->getCollection()->filter(function ($schedule) {
                return $schedule->isAvailableForBooking();
            })
        );
        
        return view('frontend.booking.schedules', compact('schedules', 'origins', 'destinations', 'origin', 'destination', 'date'));
    }
    
    public function show($id)
    {
        $schedule = Schedule::with('route', 'bus')->findOrFail($id);
        
        // Check if schedule is available for booking
        if (!$schedule->isAvailableForBooking()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule is no longer available for booking.'])
                ->withInput();
        }
        
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
        
        $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);
        
        // Check if schedule is available for booking
        if (!$schedule->isAvailableForBooking()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule is no longer available for booking.'])
                ->withInput();
        }
        
        // Check if there are enough seats available
        $availableSeats = $schedule->getAvailableSeatsCount();
        
        if ($request->number_of_seats > $availableSeats) {
            return redirect()->back()->withErrors([
                'number_of_seats' => "Only {$availableSeats} seats are available for this schedule. Please select fewer seats."
            ])->withInput();
        }
        
        // Double check that number of seats doesn't exceed bus capacity
        if ($request->number_of_seats > $schedule->bus->capacity) {
            return redirect()->back()->withErrors([
                'number_of_seats' => "Maximum capacity for this bus is {$schedule->bus->capacity} seats."
            ])->withInput();
        }
        
        // Create booking
        $booking = new Booking();
        $booking->user_id = auth()->check() ? auth()->id() : null;
        $booking->schedule_id = $schedule->id;
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_email = $request->passenger_email;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->seat_numbers = null; // Will be set later during seat selection
        $booking->number_of_seats = $request->number_of_seats;
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
        
        // Get occupied seats for this schedule
        $occupiedSeats = $booking->schedule->getBookedSeatNumbers();
        
        return view('frontend.booking.confirmation', compact('booking', 'occupiedSeats'));
    }
    
    public function selectSeats(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'seat_numbers' => 'required|array|min:1',
            'seat_numbers.*' => 'integer|min:1|max:40'
        ]);
        
        $booking = Booking::with('schedule.bus')->findOrFail($request->booking_id);
        
        // Validate that the number of selected seats matches the requested number
        if (count($request->seat_numbers) != $booking->number_of_seats) {
            return response()->json(['success' => false, 'message' => 'Please select exactly ' . $booking->number_of_seats . ' seats.']);
        }
        
        // Check if there are enough seats available
        $availableSeats = $booking->schedule->getAvailableSeatsCount();
        // Add back the current booking's seats as they are being reselected
        $availableSeats += $booking->number_of_seats;
        
        if (count($request->seat_numbers) > $availableSeats) {
            return response()->json(['success' => false, 'message' => "Only {$availableSeats} seats are available for this schedule. Please select fewer seats."]);
        }
        
        // Check if any of the selected seats are already booked
        $occupiedSeats = $booking->schedule->getBookedSeatNumbers();
        $selectedSeats = array_map('strval', $request->seat_numbers);
        
        // Check for conflicts
        $conflictingSeats = array_intersect($selectedSeats, $occupiedSeats);
        if (!empty($conflictingSeats)) {
            return response()->json(['success' => false, 'message' => 'Some seats are already booked: ' . implode(', ', $conflictingSeats)]);
        }
        
        // Validate that all seat numbers are unique
        if (count($selectedSeats) != count(array_unique($selectedSeats))) {
            return response()->json(['success' => false, 'message' => 'Please select unique seats.']);
        }
        
        $seatNumbers = implode(',', $request->seat_numbers);
        
        $booking->seat_numbers = $seatNumbers;
        $booking->save();
        
        return response()->json(['success' => true, 'message' => 'Seats selected successfully']);
    }
    
    public function processPayment(Request $request)
    {
        try {
            // Log request details for debugging
            \Log::info('Payment processing request', [
                'all_request_data' => $request->all(),
                'headers' => $request->headers->all(),
                'content_type' => $request->header('Content-Type'),
                'user_id' => auth()->id()
            ]);
            
            // Validate the request
            $validatedData = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'payment_method' => 'required|string|in:credit_card,bank_transfer,e_wallet'
            ]);
            
            \Log::info('Validation passed', ['validated_data' => $validatedData]);
            
            $booking = Booking::findOrFail($validatedData['booking_id']);
            
            // Check if seat selection has been completed
            if (empty($booking->seat_numbers)) {
                return response()->json(['success' => false, 'message' => 'Please select and save your seats before proceeding to payment.']);
            }
            
            // For demo purposes, we'll just mark the payment as paid
            $booking->payment_status = 'paid';
            
            if ($booking->save()) {
                return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to process payment. Please try again.']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Payment validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Validation failed: ' . json_encode($e->errors())]);
        } catch (\Exception $e) {
            \Log::error('Payment processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    public function success($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        // Ensure the booking is valid for success page
        if ($booking->booking_status !== 'confirmed') {
            abort(404, 'Invalid booking');
        }
        
        // For demo purposes, we ensure the booking is marked as completed
        if ($booking->payment_status !== 'paid') {
            // In a real scenario, you might want to verify with payment gateway
            // For demo, we'll just mark it as completed if it's confirmed and has seat numbers
            if ($booking->booking_status === 'confirmed' && !empty($booking->seat_numbers)) {
                $booking->payment_status = 'paid';
                $booking->save();
            }
        }
        
        return view('frontend.booking.success', compact('booking'));
    }
    
    public function downloadTicket($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        // Ensure the booking has seat numbers
        if (empty($booking->seat_numbers)) {
            abort(404, 'Ticket not available. Please select seats first.');
        }
        
        // Load the ticket view and return it as PDF
        $pdf = Pdf::loadView('frontend.booking.ticket-pdf', [
            'booking' => $booking
        ]);
        
        return $pdf->download('ticket-' . $booking->booking_code . '.pdf');
    }
    
    public function viewTicket($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        // Ensure the booking has seat numbers
        if (empty($booking->seat_numbers)) {
            abort(404, 'Ticket not available. Please select seats first.');
        }
        
        // Return the ticket view for online viewing
        return view('frontend.booking.ticket-preview', compact('booking'));
    }
}