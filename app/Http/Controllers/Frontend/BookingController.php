<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Route as BusRoute;
use App\Services\TicketPdfService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data asal & tujuan buat dropdown
        $origins = \Illuminate\Support\Facades\Cache::remember('route_origins', 60, function () {
            return BusRoute::pluck('origin')->unique()->values();
        });
        $destinations = \Illuminate\Support\Facades\Cache::remember('route_destinations', 60, function () {
            return BusRoute::pluck('destination')->unique()->values();
        });

        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $date = $request->get('date');
        $classes = $request->get('class') ? explode(',', $request->get('class')) : [];
        $times = $request->get('time') ? explode(',', $request->get('time')) : [];
        $searchDate = $date ? Carbon::parse($date) : null;

        $schedules = collect();
        $validPair = false;

        if ($origin && $destination) {
            // Cek rutenya valid ga, bolak balik aman
            $validRoutes = BusRoute::where(function ($query) use ($origin, $destination) {
                $query->where('origin', $origin)
                    ->where('destination', $destination);
            })->orWhere(function ($query) use ($origin, $destination) {
                $query->where('origin', $destination)
                    ->where('destination', $origin);
            })->get();

            if ($validRoutes->count() > 0) {
                $validPair = true;
                // Ambil ID rutenya
                $routeIds = $validRoutes->pluck('id');

                // Query jadwal, sekalian load relasi biar ga lemot (N+1 issue)
                // Itung sekalian yang udah booking biar gak query ulang di loop
                $query = Schedule::whereIn('route_id', $routeIds)
                        ->with('route', 'bus')
                        ->withSum(['bookings as booked_seats_count' => function ($q) use ($searchDate) {
                            $q->where('booking_status', 'confirmed')
                              ->where('payment_status', 'paid');
                            
                            if ($searchDate) {
                                $q->whereDate('booking_date', $searchDate);
                            } else {
                                // Kalo ga pilih tanggal, anggep buat hari ini kedepan
                                $q->whereDate('booking_date', '>=', Carbon::today());
                            }
                        }], 'number_of_seats')
                        ->available();

                $allSchedules = $query->get();

                // Filter schedules
                $schedules = $allSchedules->filter(function ($schedule) use ($searchDate, $classes, $times) {
                    // 1. Availability Check
                    
                    // Use the eager loaded count instead of querying DB
                    $bookedSeats = $schedule->booked_seats_count ?? 0;
                    $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);
                    $hasSeats = $availableSeats > 0;

                    $active = $schedule->status === 'active';
                    if (!$active) return false;

                    if ($searchDate) {
                        if (!$schedule->is_daily) {
                            $dateMatch = $schedule->departure_time->toDateString() === $searchDate->toDateString();
                            // For specific date search:
                            // We SHOW departed schedules so users know they missed it (with visual cue),
                            // BUT we might want to hide them if they are from previous days (which is handled by dateMatch).
                            // If it's today and departed, show it.
                            
                            if (!$dateMatch) return false;
                        } 
                        // For daily, it matches every day.
                    }

                    // 2. Class Filter
                    if (!empty($classes)) {
                        if (!in_array($schedule->bus->bus_type, $classes)) {
                            return false;
                        }
                    }

                    // 3. Time Filter
                    if (!empty($times)) {
                        $departureTime = $schedule->getActualDepartureTime($searchDate);
                        $hour = $departureTime->hour;
                        $timeMatch = false;

                        foreach ($times as $time) {
                            if ($time === 'morning' && $hour >= 0 && $hour < 12) $timeMatch = true;
                            if ($time === 'afternoon' && $hour >= 12 && $hour < 18) $timeMatch = true;
                            if ($time === 'evening' && $hour >= 18 && $hour <= 23) $timeMatch = true;
                        }

                        if (!$timeMatch) return false;
                    }

                    return true;
                });
            }
        } else {
            // Default: Fetch all available schedules if no specific search
            $validPair = true;
            // For default view, still eager load to prevent N+1
             $query = Schedule::with('route', 'bus')
                ->withSum(['bookings as booked_seats_count' => function ($q) {
                        $q->where('booking_status', 'confirmed')
                          ->where('payment_status', 'paid')
                          ->whereDate('booking_date', '>=', Carbon::today());
                }], 'number_of_seats')
                ->available()
                ->orderBy('departure_time');
            
            $allSchedules = $query->get();

            $schedules = $allSchedules->filter(function ($schedule) use ($classes, $times, $searchDate) {
                // Simplified manual check using pre-loaded data
                $bookedSeats = $schedule->booked_seats_count ?? 0;
                $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);
                if ($availableSeats <= 0) return false;

                // Hide non-daily schedules that are in the past
                if (!$schedule->is_daily) {
                    $departure = $schedule->departure_time instanceof Carbon ? $schedule->departure_time : Carbon::parse($schedule->departure_time);
                    if ($departure->isPast()) return false;
                }

                // Class Filter
                if (!empty($classes)) {
                    if (!in_array($schedule->bus->bus_type, $classes)) {
                        return false;
                    }
                }

                // Time Filter
                if (!empty($times)) {
                    $departureTime = $schedule->getActualDepartureTime(); 
                    $hour = $departureTime->hour;
                    $timeMatch = false;

                    foreach ($times as $time) {
                        if ($time === 'morning' && $hour >= 0 && $hour < 12) $timeMatch = true;
                        if ($time === 'afternoon' && $hour >= 12 && $hour < 18) $timeMatch = true;
                        if ($time === 'evening' && $hour >= 18 && $hour <= 23) $timeMatch = true;
                    }

                    if (!$timeMatch) return false;
                }

                return true;
            });
        }

        // Transform schedules for frontend
        $schedules = $schedules->map(function ($schedule) use ($date) {
            $checkDate = $date ? Carbon::parse($date) : null;
            
            // Calculate available seats using the eager loaded value
            $bookedSeats = $schedule->booked_seats_count ?? 0;
            $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);
            
            // Check departure status
            $hasDeparted = $schedule->hasDeparted($checkDate);

            return [
                'id' => $schedule->id,
                'price' => $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($checkDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($checkDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'available_seats' => $availableSeats, // Use pre-calculated value
                'has_departed' => $hasDeparted,
                'bus' => [
                    'name' => $schedule->bus->name,
                    'bus_type' => $schedule->bus->bus_type,
                    'plate_number' => $schedule->bus->plate_number,
                    'capacity' => $schedule->bus->capacity,
                ],
                'route' => $schedule->route,
            ];
        });

        return \Inertia\Inertia::render('Frontend/Booking/Index', [
            'schedules' => $schedules->values(), // Re-index array keys
            'origins' => $origins,
            'destinations' => $destinations,
            'validPair' => $validPair,
            'filters' => $request->only(['origin', 'destination', 'date', 'class', 'time']),
        ]);
    }

    public function schedules(Request $request)
    {
        // Get all unique origins and destinations for the dropdowns
        $origins = \Illuminate\Support\Facades\Cache::remember('route_origins', 60, function () {
            return BusRoute::pluck('origin')->unique()->values();
        });
        $destinations = \Illuminate\Support\Facades\Cache::remember('route_destinations', 60, function () {
            return BusRoute::pluck('destination')->unique()->values();
        });

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
            );
        }

        $query = Schedule::with(['bus', 'route'])
            ->where('is_active', true);
        
        // Filter by origin
        if ($request->has('origin') && $request->origin != '') {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('origin', $request->origin);
            });
        }

        // Filter by destination
        if ($request->has('destination') && $request->destination != '') {
            $query->whereHas('route', function ($q) use ($request) {
                $q->where('destination', $request->destination);
            });
        }

        // Filter by date (if provided, otherwise show defaults)
        if ($request->has('date') && $request->date != '') {
            // Note: In a real app, you might want to filter schedules that run on this specific date
            // For now, we'll just pass it through as per existing logic or enhance if needed
        }

        $schedules = $query->get()->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'price' => $schedule->price,
                'departure_time' => $schedule->departure_time->format('H:i'),
                'arrival_time' => $schedule->arrival_time->format('H:i'),
                'duration' => $schedule->duration,
                'is_daily' => $schedule->is_daily,
                'bus' => [
                    'name' => $schedule->bus->name,
                    'type' => $schedule->bus->type,
                    'capacity' => $schedule->bus->capacity,
                    'plate_number' => $schedule->bus->plate_number,
                    'bus_type' => $schedule->bus->bus_type, 
                ],
                'route' => [
                    'origin' => $schedule->route->origin,
                    'destination' => $schedule->route->destination,
                ],
                'available_seats' => $schedule->getAvailableSeatsCount(),
            ];
        });

        $origins = \App\Models\Route::distinct()->pluck('origin');
        $destinations = \App\Models\Route::distinct()->pluck('destination');

        // Check if origin and destination pair is valid
        $validPair = true;
        if ($request->has('origin') && $request->has('destination') && $request->origin && $request->destination) {
            $validPair = \App\Models\Route::where('origin', $request->origin)
                ->where('destination', $request->destination)
                ->exists();
        }

        return \Inertia\Inertia::render('Frontend/Booking/Index', [
            'schedules' => $schedules,
            'origins' => $origins,
            'destinations' => $destinations,
            'validPair' => $validPair,
            'filters' => $request->only(['origin', 'destination', 'date']),
        ]);
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

        // Overwrite departure_time and arrival_time with actual dates for display
        // This ensures the frontend shows the correct date (e.g., Today/Tomorrow) instead of the base date (2000-01-01)
        $schedule->departure_time = $schedule->getActualDepartureTime($checkDate);
        $schedule->arrival_time = $schedule->getActualArrivalTime($checkDate);

        // Pass the date parameter to the view
        return \Inertia\Inertia::render('Frontend/Booking/Show', [
            'schedule' => $schedule,
            'selectedDate' => $selectedDate,
        ]);
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
            $bookingDate = $bookingDate->format('Y-m-d');
        } else {
             // Ensure it's a valid date string even if it came as string
             $bookingDate = Carbon::parse($bookingDate)->format('Y-m-d');
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

        // Send notification to admins
        $admins = \App\Models\User::role('admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewBookingNotification($booking));

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

        // Get occupied seats for this schedule on the specific booking date
        $occupiedSeats = $booking->schedule->getBookedSeatNumbers($booking->booking_date);

        return \Inertia\Inertia::render('Frontend/Booking/SeatSelection', [
            'booking' => $booking,
            'occupiedSeats' => $occupiedSeats,
        ]);
    }

    public function selectSeats(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'seat_numbers' => 'required|array|min:1',
            'seat_numbers.*' => 'integer|min:1|max:40'
        ]);

        try {
            // Pake transaction biar aman, ga ada drama kursi ganda pas rame
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // Kunci datanya biar user lain ngantri bentar
                $booking = Booking::lockForUpdate()->findOrFail($request->booking_id);
                
                // Kunci juga jadwalnya, ini paling penting biar ga overbooking
                $schedule = Schedule::lockForUpdate()->with('bus')->find($booking->schedule_id);

                if (!$schedule) {
                     return response()->json(['success' => false, 'message' => 'Jadwal ga ketemu entah kemana.']);
                }

                // Cek klo busnya udah jalan
                if ($schedule->hasDeparted()) {
                    return response()->json(['success' => false, 'message' => 'Yah, busnya udah berangkat bos.']);
                }

                // Masih bisa dibooking ga?
                if (!$schedule->isAvailableForBooking()) {
                    return response()->json(['success' => false, 'message' => 'Jadwal ini udah ga bisa dibooking lagi.']);
                }

                // Pastikan jumlah kursi yg dipilih sama
                if (count($request->seat_numbers) != $booking->number_of_seats) {
                    return response()->json(['success' => false, 'message' => 'Pilih ' . $booking->number_of_seats . ' kursi ya, jangan lebih jangan kurang.']);
                }

                // Hitung sisa kursi real-time
                $availableSeats = $schedule->getAvailableSeatsCount($booking->booking_date);
                
                if (count($request->seat_numbers) > $availableSeats) {
                    return response()->json(['success' => false, 'message' => "Sisa kursi cuma {$availableSeats} nih untuk tanggal segitu."]);
                }

                // Cek apakah kursi yg dipilih udah ada yg punya
                $occupiedSeats = $schedule->getBookedSeatNumbers($booking->booking_date);
                $selectedSeats = array_map('strval', $request->seat_numbers);

                // Cari yang bentrok
                $conflictingSeats = array_intersect($selectedSeats, $occupiedSeats);
                if (!empty($conflictingSeats)) {
                    return response()->json(['success' => false, 'message' => 'Kursi nomor ' . implode(', ', $conflictingSeats) . ' udah keduluan orang lain. Cari yg lain ya.']);
                }

                // Cek duplikat input
                if (count($selectedSeats) != count(array_unique($selectedSeats))) {
                    return response()->json(['success' => false, 'message' => 'Jangan pilih kursi yang sama dua kali dong.']);
                }

                $seatNumbers = implode(',', $request->seat_numbers);

                $booking->seat_numbers = $seatNumbers;
                $booking->save();

                return response()->json(['success' => true, 'message' => 'Sip, kursi berhasil diamankan']);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal pilih kursi: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ada masalah pas milih kursi, coba lagi bentar.']);
        }
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
                'payment_method' => 'required|string|in:gopay,shopeepay,qris,dana,linkaja,credit_card,bank_transfer,echannel'
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

            // Use PaymentService to process payment with Midtrans
            $paymentMethod = $validatedData['payment_method'];

            $result = $this->paymentService->processPayment($booking->id, $paymentMethod);

            if ($result['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment created successfully',
                    'snap_token' => $result['snap_token'],
                    'redirect_url' => $result['redirect_url']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to process payment with Midtrans'
                ]);
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
        // Allow both 'confirmed' and 'pending' booking status
        // 'pending' is set by PaymentService when payment is initiated
        if (!in_array($booking->booking_status, ['confirmed', 'pending'])) {
            abort(404, 'Invalid booking');
        }

        // Allow access for both 'paid' and 'pending' payment statuses
        // The webhook will update the status to 'paid' once payment is confirmed
        if (!in_array($booking->payment_status, ['paid', 'pending'])) {
            return redirect()->route('frontend.booking.confirmation', ['booking' => $booking->id])
                ->withErrors(['payment' => 'Payment has not been initiated yet.']);
        }

        // Auto-check payment status from Midtrans if still pending
        // This is crucial for localhost development where webhooks don't work
        if ($booking->payment_status === 'pending' && $booking->midtrans_transaction_id) {
            try {
                // Query Midtrans for the latest transaction status
                $result = $this->paymentService->midtransService->getTransactionStatus($booking->midtrans_transaction_id);

                if ($result['status'] === 'success') {
                    Log::info('Payment status checked on success page', [
                        'booking_id' => $booking->id,
                        'transaction_status' => $result['data']->transaction_status ?? 'unknown'
                    ]);

                    // Reload booking to get updated status
                    $booking->refresh();
                }
            } catch (\Exception $e) {
                // Log error but don't fail - just show current status
                Log::error('Failed to check payment status on success page', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            return redirect()->route('frontend.booking.index')
                ->withErrors(['schedule' => 'The schedule for this booking has already departed.'])
                ->withInput();
        }

        return \Inertia\Inertia::render('Frontend/Booking/Success', [
        'booking' => $booking
    ]);
    }

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function downloadTicket($id, TicketPdfService $ticketPdfService)
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
        // Allow both 'confirmed' and 'pending' status as payment might still be processing
        $validBookingStatuses = ['confirmed', 'pending'];
        $validPaymentStatuses = ['paid', 'pending'];

        if (
            !in_array($booking->booking_status, $validBookingStatuses) ||
            !in_array($booking->payment_status, $validPaymentStatuses)
        ) {
            abort(404, 'Ticket not available. Invalid booking status.');
        }

        // Generate PDF ticket using the service
        $pdf = $ticketPdfService->generateTicketPdf($booking);

        return $pdf->download('ticket-' . $booking->booking_code . '.pdf');
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
        $route = BusRoute::where(function ($query) use ($origin, $destination) {
            $query->where('origin', $origin)
                ->where('destination', $destination);
        })->orWhere(function ($query) use ($origin, $destination) {
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
            $query->whereHas('bus', function ($q) use ($busType) {
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
