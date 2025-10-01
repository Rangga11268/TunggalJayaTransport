<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Route as BusRoute;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

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
                
                // For date-specific searches, we need to handle weekly schedules specially
                if ($date) {
                    $searchDate = Carbon::parse($date);
                    
                    // Get all active schedules for these routes
                    $allSchedules = Schedule::whereIn('route_id', $routeIds)
                        ->with('route', 'bus')
                        ->available()
                        ->get();
                    
                    // Filter schedules based on the search date
                    $schedules = $allSchedules->filter(function ($schedule) use ($searchDate) {
                        // For daily schedules, check if the date matches
                        if (!$schedule->is_daily) {
                            return $schedule->departure_time->toDateString() === $searchDate->toDateString() 
                                && $schedule->isAvailableForBooking($searchDate);
                        }
                        
                        // For daily recurring schedules, they are available every day
                        if ($schedule->is_daily) {
                            // Daily recurring schedules are always available regardless of the search date
                            return $schedule->isAvailableForBooking($searchDate);
                        }
                        
                        return false;
                    });
                } else {
                    // For non-date-specific searches, get all available schedules
                    $schedules = Schedule::whereIn('route_id', $routeIds)
                        ->with('route', 'bus')
                        ->available()
                        ->get()
                        ->filter(function ($schedule) {
                            // All schedules must pass the isAvailableForBooking check
                            return $schedule->isAvailableForBooking();
                        });
                }
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
        
        // For date-specific searches, we need to handle weekly schedules specially
        if ($date) {
            $searchDate = Carbon::parse($date);
            
            // Get all active schedules
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
            
            // Get all schedules first
            $allSchedules = $query->get();
            
            // Filter schedules based on the search date
            $filteredSchedules = $allSchedules->filter(function ($schedule) use ($searchDate) {
                // For daily schedules, check if the date matches
                if (!$schedule->is_daily) {
                    return $schedule->departure_time->toDateString() === $searchDate->toDateString() 
                        && $schedule->isAvailableForBooking($searchDate);
                }
                
                // For daily recurring schedules, they are available every day
                if ($schedule->is_daily) {
                    // Daily recurring schedules are always available regardless of the search date
                    // We just need to ensure the time hasn't passed yet on the search date
                    return $schedule->isAvailableForBooking($searchDate);
                }
                
                return false;
            });
            
            // Create a paginator manually
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentPageItems = $filteredSchedules->slice(($currentPage - 1) * $perPage, $perPage)->values();
            
            $schedules = new LengthAwarePaginator(
                $currentPageItems,
                $filteredSchedules->count(),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'pageName' => 'page',
                ]
            );
        } else {
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
            
            // Order by departure time
            $schedules = $query->orderBy('departure_time')->paginate(10);
            
            // Filter out schedules that have already departed or are not available for booking
            // For date filtering, we need to check the actual departure time
            $schedules->setCollection(
                $schedules->getCollection()->filter(function ($schedule) {
                    // All schedules must pass the isAvailableForBooking check
                    return $schedule->isAvailableForBooking();
                })
            );
        }
        
        return view('frontend.booking.schedules', compact('schedules', 'origins', 'destinations', 'origin', 'destination', 'date'));
    }
    
    public function show($id, Request $request)
    {
        $schedule = Schedule::with('route', 'bus')->findOrFail($id);
        
        // Get the selected date from the request
        $selectedDate = $request->get('date');
        
        // Check if schedule has departed for the selected date (or today if no specific date)
        $checkDate = $selectedDate ? Carbon::parse($selectedDate) : null;
        if ($schedule->hasDeparted($checkDate)) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule has already departed and is no longer available for booking.'])
                ->withInput();
        }
        
        // Check if schedule is available for booking on the selected date
        if (!$schedule->isAvailableForBooking($checkDate)) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule is no longer available for booking.'])
                ->withInput();
        }
        
        // Pass the date parameter to the view
        return view('frontend.booking.show', compact('schedule', 'selectedDate'));
    }
    
    public function store(Request $request)
    {
        // Ensure user is authenticated before creating a booking
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to make a booking.');
        }
        
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email|max:255',
            'passenger_phone' => 'required|string|max:20',
            'number_of_seats' => 'required|integer|min:1|max:5',
            'terms' => 'required|accepted',
        ]);
        
        $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);
        
        // Additional check: if schedule has already departed, redirect with error
        if ($schedule->hasDeparted()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule has already departed and is no longer available for booking.'])
                ->withInput();
        }
        
        // Check if schedule is available for booking
        if (!$schedule->isAvailableForBooking()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'This schedule is no longer available for booking.'])
                ->withInput();
        }
        
        // If the request came with a specific date, use it
        if ($request->date) {
            $bookingDate = Carbon::parse($request->date);
        } else {
            // For daily recurring schedules, calculate the next available date
            if ($schedule->is_daily) {
                $bookingDate = Carbon::today();
                // If today's departure time hasn't passed, use today; otherwise use tomorrow
                $todayDeparture = $bookingDate->copy()->setTimeFromTimeString($schedule->departure_time->format('H:i:s'));
                if ($todayDeparture->isPast()) {
                    $bookingDate = $bookingDate->addDay();
                }
            } else {
                // For regular schedules, use the schedule's departure date
                $bookingDate = $schedule->departure_time;
            }
        }
        
        // Check if there are enough seats available for the specific date
        $availableSeats = $schedule->getAvailableSeatsCount($bookingDate);
        
        if ($request->number_of_seats > $availableSeats) {
            $dateStr = $bookingDate ? $bookingDate->toDateString() : 'the date';
            return redirect()->back()->withErrors([
                'number_of_seats' => "Only {$availableSeats} seats are available for this schedule on {$dateStr}. Please select fewer seats."
            ])->withInput();
        }
        
        // Double check that number of seats doesn't exceed bus capacity
        if ($request->number_of_seats > $schedule->bus->capacity) {
            return redirect()->back()->withErrors([
                'number_of_seats' => "Maximum capacity for this bus is {$schedule->bus->capacity} seats."
            ])->withInput();
        }
        
        // Convert bookingDate to string for database storage if it's a Carbon instance
        if ($bookingDate instanceof Carbon) {
            $bookingDate = $bookingDate->toDateString();
        }
        
        // Create booking
        $booking = new Booking();
        $booking->user_id = auth()->id(); // User is guaranteed to be authenticated at this point
        $booking->schedule_id = $schedule->id;
        $booking->booking_date = $bookingDate; // Set the specific booking date
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_email = $request->passenger_email;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->seat_numbers = null; // Will be set later during seat selection
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = $schedule->price * $request->number_of_seats;
        $booking->booking_code = 'BK' . strtoupper(uniqid());
        $booking->payment_status = 'pending';
        $booking->booking_status = 'confirmed'; // For immediate confirmation
        $booking->startPayment(); // Start payment timer
        $booking->save();
        
        // Redirect to confirmation page with booking details
        return redirect()->route('frontend.booking.confirmation', ['booking' => $booking->id]);
    }
    
    public function confirmation($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        // Check if the current user owns this booking or is authenticated
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to access this booking.');
        }
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'The schedule for this booking has already departed.'])
                ->withInput();
        }
        
        // Check if the schedule is still available for booking
        if (!$booking->schedule->isAvailableForBooking()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'The schedule for this booking is no longer available.'])
                ->withInput();
        }
        
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
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            return response()->json(['success' => false, 'message' => 'The schedule for this booking has already departed.']);
        }
        
        // Check if the schedule is still available for booking
        if (!$booking->schedule->isAvailableForBooking()) {
            return response()->json(['success' => false, 'message' => 'The schedule for this booking is no longer available.']);
        }
        
        // Validate that the number of selected seats matches the requested number
        if (count($request->seat_numbers) != $booking->number_of_seats) {
            return response()->json(['success' => false, 'message' => 'Please select exactly ' . $booking->number_of_seats . ' seats.']);
        }
        
        // Check if there are enough seats available for the booking date
        $availableSeats = $booking->schedule->getAvailableSeatsCount($booking->booking_date);
        // Add back the current booking's seats as they are being reselected
        $availableSeats += $booking->number_of_seats;
        
        if (count($request->seat_numbers) > $availableSeats) {
            return response()->json(['success' => false, 'message' => "Only {$availableSeats} seats are available for this schedule on {$booking->booking_date}. Please select fewer seats."]);
        }
        
        // Check if any of the selected seats are already booked for the same date
        $occupiedSeats = $booking->schedule->getBookedSeatNumbers($booking->booking_date);
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
            Log::info('Payment processing request', [
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
            
            Log::info('Validation passed', ['validated_data' => $validatedData]);
            
            $booking = Booking::findOrFail($validatedData['booking_id']);
            
            // Check if payment has expired
            if ($booking->isPaymentExpired()) {
                return response()->json(['success' => false, 'message' => 'Payment time has expired. Please restart the booking process.']);
            }
            
            // Check if the schedule has already departed
            if ($booking->schedule->hasDeparted()) {
                return response()->json(['success' => false, 'message' => 'The schedule for this booking has already departed. Payment cannot be processed.']);
            }
            
            // Check if the schedule is still available for booking
            if (!$booking->schedule->isAvailableForBooking()) {
                return response()->json(['success' => false, 'message' => 'The schedule for this booking is no longer available. Payment cannot be processed.']);
            }
            
            // Check if seat selection has been completed
            if (empty($booking->seat_numbers)) {
                return response()->json(['success' => false, 'message' => 'Please select and save your seats before proceeding to payment.']);
            }
            
            // For demo purposes, we'll just mark the payment as paid
            $booking->payment_status = 'paid';
            $booking->payment_started_at = null; // Clear payment timer
            
            if ($booking->save()) {
                return response()->json(['success' => true, 'message' => 'Payment processed successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to process payment. Please try again.']);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Payment validation error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Validation failed: ' . json_encode($e->errors())]);
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
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
        
        // Check if the current user owns this booking or is authenticated
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to access this booking.');
        }
        
        // Ensure the booking is valid for success page
        if ($booking->booking_status !== 'confirmed') {
            abort(404, 'Invalid booking');
        }
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'The schedule for this booking has already departed.'])
                ->withInput();
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
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'The schedule for this booking has already departed.'])
                ->withInput();
        }
        
        return view('frontend.booking.success', compact('booking'));
    }
    
    public function downloadTicket($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
        
        // Check if the current user owns this booking or is authenticated
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to access this booking.');
        }
        
        // Ensure the booking has seat numbers
        if (empty($booking->seat_numbers)) {
            abort(404, 'Ticket not available. Please select seats first.');
        }
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            abort(404, 'Ticket not available. The schedule has already departed.');
        }
        
        // Check if the booking is valid
        if ($booking->booking_status !== 'confirmed' || $booking->payment_status !== 'paid') {
            abort(404, 'Ticket not available. Invalid booking status.');
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
        
        // Check if the current user owns this booking or is authenticated
        if (auth()->check() && $booking->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to access this booking.');
        }
        
        // Ensure the booking has seat numbers
        if (empty($booking->seat_numbers)) {
            abort(404, 'Ticket not available. Please select seats first.');
        }
        
        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            abort(404, 'Ticket not available. The schedule has already departed.');
        }
        
        // Check if the booking is valid
        if ($booking->booking_status !== 'confirmed' || $booking->payment_status !== 'paid') {
            abort(404, 'Ticket not available. Invalid booking status.');
        }
        
        // Return the ticket view for online viewing
        return view('frontend.booking.ticket-preview', compact('booking'));
    }
    
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'date' => 'required|date',
            'bus_type' => 'nullable|string|max:255'
        ]);
        
        $origin = $request->origin;
        $destination = $request->destination;
        $date = $request->date;
        $busType = $request->bus_type;
        
        // Find routes matching the origin and destination
        $route = BusRoute::where(function($query) use ($origin, $destination) {
            $query->where('origin', $origin)
                  ->where('destination', $destination);
        })->orWhere(function($query) use ($origin, $destination) {
            $query->where('origin', $destination)
                  ->where('destination', $origin);
        })->first();
        
        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'No route found between origin and destination',
                'available_seats' => 0
            ]);
        }
        
        // Parse the date for availability check
        $searchDate = Carbon::parse($date);
        
        // Find schedules matching the route and date
        $query = Schedule::where('route_id', $route->id)->available();
        
        // Apply bus type filter if specified
        if ($busType && $busType !== 'all') {
            $query->whereHas('bus', function($q) use ($busType) {
                $q->where('type', $busType);
            });
        }
        
        // Get all schedules for this route
        $allSchedules = $query->with('route', 'bus')->get();
        
        // Filter schedules based on the search date
        $schedules = $allSchedules->filter(function ($schedule) use ($searchDate) {
            // For daily schedules, check if the date matches
            if (!$schedule->is_daily) {
                return $schedule->departure_time->toDateString() === $searchDate->toDateString() 
                    && $schedule->isAvailableForBooking($searchDate);
            }
            
            // For daily recurring schedules, they are available every day
            if ($schedule->is_daily) {
                // Daily recurring schedules are always available regardless of the search date
                // We just need to ensure the time hasn't passed yet on the search date
                return $schedule->isAvailableForBooking($searchDate);
            }
            
            return false;
        });
        
        $totalAvailableSeats = 0;
        
        // Calculate total available seats from all matching schedules
        foreach ($schedules as $schedule) {
            $availableSeats = $schedule->getAvailableSeatsCount($searchDate);
            $totalAvailableSeats += $availableSeats;
        }
        
        return response()->json([
            'success' => true,
            'available_seats' => $totalAvailableSeats,
            'message' => $totalAvailableSeats > 0 ? 'Seats available' : 'No seats available'
        ]);
    }
}