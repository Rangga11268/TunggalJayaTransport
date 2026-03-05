<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\Route as BusRoute;
use App\Services\TicketPdfService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
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

        // Normalize & validate filter inputs
        $origin = trim($request->get('origin') ?? '');
        $destination = trim($request->get('destination') ?? '');
        $date = $request->get('date');
        $classFilters = $request->get('class') ? array_filter(explode(',', $request->get('class'))) : [];
        $timeFilters = $request->get('time') ? array_filter(explode(',', $request->get('time'))) : [];

        // Set effective date untuk consistent filtering
        $effectiveDate = null;
        if ($date) {
            try {
                $effectiveDate = Carbon::parse($date);
            } catch (\Exception $e) {
                $effectiveDate = null;
            }
        }

        // If no date selected, use today as baseline untuk availability check
        $referenceDate = $effectiveDate ?? Carbon::today();

        $schedules = collect();
        $validPair = false;

        if ($origin && $destination) {
            // Cek rute arah persis yang dipilih user (origin → destination)
            $forwardRoutes = BusRoute::where('origin', $origin)
                ->where('destination', $destination)
                ->get();

            // Cek juga arah balik untuk validasi pasangan kota (tapi bukan untuk fetch jadwal)
            $reverseRoutes = BusRoute::where('origin', $destination)
                ->where('destination', $origin)
                ->get();

            $validPair = $forwardRoutes->count() > 0 || $reverseRoutes->count() > 0;

            if ($validPair) {
                // Hanya pakai route arah yang diminta user, bukan sebaliknya
                $routeIds = $forwardRoutes->pluck('id');

                $query = Schedule::whereIn('route_id', $routeIds)
                    ->with('route', 'bus')
                    ->withSum(['bookings as booked_seats_count' => function ($q) use ($referenceDate) {
                        $q->where('booking_status', 'confirmed')
                            ->where('payment_status', 'paid')
                            ->whereDate('booking_date', $referenceDate->toDateString());
                    }], 'number_of_seats')
                    ->available();

                $allSchedules = $query->get();

                // Filter schedules
                $schedules = $allSchedules->filter(function ($schedule) use ($effectiveDate, $referenceDate, $classFilters, $timeFilters) {
                    // Check basic status
                    if ($schedule->status !== 'active') {
                        return false;
                    }

                    // Calculate available seats using the eager loaded count
                    $bookedSeats = $schedule->booked_seats_count ?? 0;
                    $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);
                    if ($availableSeats <= 0) {
                        return false;
                    }

                    // Date matching logic
                    if ($effectiveDate) {
                        // User memilih tanggal spesifik
                        if ($schedule->is_daily) {
                            // Daily schedule: cek days_of_week
                            if (!empty($schedule->days_of_week)) {
                                $dayName = $effectiveDate->format('l');
                                $allowedDays = is_string($schedule->days_of_week)
                                    ? json_decode($schedule->days_of_week, true)
                                    : $schedule->days_of_week;

                                if (is_array($allowedDays) && !in_array($dayName, $allowedDays)) {
                                    return false;
                                }
                            }
                        } else {
                            // Non-daily: must match departure date exactly
                            $departureDate = $schedule->departure_time instanceof Carbon
                                ? $schedule->departure_time
                                : Carbon::parse($schedule->departure_time);

                            if (!$departureDate->isSameDay($effectiveDate)) {
                                return false;
                            }

                            // Non-daily jadwal harus belum berangkat
                            if ($departureDate->isPast()) {
                                return false;
                            }
                        }
                    } else {
                        // Tanpa tanggal spesifik: hanya tampilkan jadwal yang belum lewat
                        if (!$schedule->is_daily) {
                            $departure = $schedule->departure_time instanceof Carbon
                                ? $schedule->departure_time
                                : Carbon::parse($schedule->departure_time);
                            if ($departure->isPast()) {
                                return false;
                            }
                        }
                    }

                    // Class filter
                    if (!empty($classFilters)) {
                        if (!in_array($schedule->bus->bus_type, $classFilters)) {
                            return false;
                        }
                    }

                    // Time filter - gunakan referenceDate untuk getActualDepartureTime
                    if (!empty($timeFilters)) {
                        $departureTime = $schedule->getActualDepartureTime($referenceDate);
                        $hour = $departureTime->hour;
                        $timeMatch = false;

                        foreach ($timeFilters as $time) {
                            if ($time === 'morning' && $hour >= 0 && $hour < 12) $timeMatch = true;
                            if ($time === 'afternoon' && $hour >= 12 && $hour < 18) $timeMatch = true;
                            if ($time === 'evening' && $hour >= 18 && $hour <= 23) $timeMatch = true;
                        }

                        if (!$timeMatch) {
                            return false;
                        }
                    }

                    return true;
                });
            }
        } else {
            // Default: Fetch all available schedules jika salah satu atau keduanya kosong
            $validPair = true;

            $query = Schedule::with('route', 'bus')
                ->withSum(['bookings as booked_seats_count' => function ($q) use ($referenceDate) {
                    $q->where('booking_status', 'confirmed')
                        ->where('payment_status', 'paid')
                        ->whereDate('booking_date', $referenceDate->toDateString());
                }], 'number_of_seats')
                ->available()
                ->orderBy('departure_time');

            $allSchedules = $query->get();

            $schedules = $allSchedules->filter(function ($schedule) use ($effectiveDate, $referenceDate, $classFilters, $timeFilters) {
                // Check status
                if ($schedule->status !== 'active') {
                    return false;
                }

                // Calculate available seats
                $bookedSeats = $schedule->booked_seats_count ?? 0;
                $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);
                if ($availableSeats <= 0) {
                    return false;
                }

                // Date matching logic (same as paired search)
                if ($effectiveDate) {
                    if ($schedule->is_daily) {
                        if (!empty($schedule->days_of_week)) {
                            $dayName = $effectiveDate->format('l');
                            $allowedDays = is_string($schedule->days_of_week)
                                ? json_decode($schedule->days_of_week, true)
                                : $schedule->days_of_week;

                            if (is_array($allowedDays) && !in_array($dayName, $allowedDays)) {
                                return false;
                            }
                        }
                    } else {
                        $departureDate = $schedule->departure_time instanceof Carbon
                            ? $schedule->departure_time
                            : Carbon::parse($schedule->departure_time);

                        if (!$departureDate->isSameDay($effectiveDate)) {
                            return false;
                        }

                        if ($departureDate->isPast()) {
                            return false;
                        }
                    }
                } else {
                    // Tanpa tanggal: hanya jadwal yang belum berangkat
                    if (!$schedule->is_daily) {
                        $departure = $schedule->departure_time instanceof Carbon
                            ? $schedule->departure_time
                            : Carbon::parse($schedule->departure_time);
                        if ($departure->isPast()) {
                            return false;
                        }
                    }
                }

                // Class filter
                if (!empty($classFilters)) {
                    if (!in_array($schedule->bus->bus_type, $classFilters)) {
                        return false;
                    }
                }

                // Time filter
                if (!empty($timeFilters)) {
                    $departureTime = $schedule->getActualDepartureTime($referenceDate);
                    $hour = $departureTime->hour;
                    $timeMatch = false;

                    foreach ($timeFilters as $time) {
                        if ($time === 'morning' && $hour >= 0 && $hour < 12) $timeMatch = true;
                        if ($time === 'afternoon' && $hour >= 12 && $hour < 18) $timeMatch = true;
                        if ($time === 'evening' && $hour >= 18 && $hour <= 23) $timeMatch = true;
                    }

                    if (!$timeMatch) {
                        return false;
                    }
                }

                return true;
            });
        }

        // Transform schedules for frontend
        $schedules = $schedules->map(function ($schedule) use ($effectiveDate, $referenceDate) {
            // Calculate available seats using the eager loaded value
            $bookedSeats = $schedule->booked_seats_count ?? 0;
            $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);

            // Check departure status with proper context date
            $checkDate = $effectiveDate ?? $referenceDate;
            $hasDeparted = $schedule->hasDeparted($checkDate);

            return [
                'id' => $schedule->id,
                'price' => $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($checkDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($checkDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'available_seats' => $availableSeats,
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

        // If request wants JSON (axios search), return data directly without Inertia
        if ($request->wantsJson()) {
            return response()->json([
                'schedules' => $schedules->values(),
                'origins' => $origins,
                'destinations' => $destinations,
                'validPair' => $validPair,
            ]);
        }

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
        // Validate request parameters
        $request->validate([
            'origin' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'class' => 'nullable|string',
            'time' => 'nullable|string',
        ]);

        // Get filter parameters
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $dateParam = $request->get('date');
        $searchDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();
        $classes = $request->get('class') ? explode(',', $request->get('class')) : [];
        $times = $request->get('time') ? explode(',', $request->get('time')) : [];

        // Build single query pipeline with eager loading to prevent N+1
        $query = Schedule::with(['bus', 'route'])
            ->available()
            ->withSum(['bookings as booked_seats_count' => function ($q) use ($searchDate) {
                $q->where('booking_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereDate('booking_date', $searchDate);
            }], 'number_of_seats');

        // Apply origin filter
        if ($origin) {
            $query->whereHas('route', function ($q) use ($origin) {
                $q->where('origin', $origin);
            });
        }

        // Apply destination filter
        if ($destination) {
            $query->whereHas('route', function ($q) use ($destination) {
                $q->where('destination', $destination);
            });
        }

        // Apply date filter (handles both daily and non-daily)
        if ($dateParam) {
            $query->where(function ($q) use ($searchDate) {
                $q->where(function ($sub) use ($searchDate) {
                    // Non-daily schedules: match exact departure date
                    $sub->where('is_daily', false)
                        ->whereDate('departure_time', $searchDate->toDateString());
                })
                    ->orWhere('is_daily', true); // Daily schedules: available every day
            });
        }

        // Get all matching schedules
        $allSchedules = $query->get();

        // Apply collection filters (in-memory for complex logic like days_of_week, time ranges)
        $filteredSchedules = $allSchedules->filter(function ($schedule) use ($searchDate, $classes, $times) {
            // 1. Check if schedule has departed
            if ($schedule->hasDeparted($searchDate)) {
                return false;
            }

            // 2. Check availability for booking
            if (!$schedule->isAvailableForBooking($searchDate)) {
                return false;
            }

            // 3. For daily schedules with specific days_of_week, check if today is allowed
            if ($schedule->is_daily && !empty($schedule->days_of_week)) {
                $dayName = $searchDate->format('l');
                $allowedDays = is_string($schedule->days_of_week)
                    ? json_decode($schedule->days_of_week, true)
                    : $schedule->days_of_week;

                if (is_array($allowedDays) && !in_array($dayName, $allowedDays)) {
                    return false;
                }
            }

            // 4. Apply class/bus type filter
            if (!empty($classes) && !in_array($schedule->bus->bus_type, $classes)) {
                return false;
            }

            // 5. Apply time range filter
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

        // Transform filtered schedules for response
        $transformedSchedules = $filteredSchedules->map(function ($schedule) use ($searchDate) {
            $bookedSeats = $schedule->booked_seats_count ?? 0;
            $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);

            return [
                'id' => $schedule->id,
                'price' => $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($searchDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($searchDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'is_daily' => $schedule->is_daily,
                'available_seats' => $availableSeats,
                'bus' => [
                    'name' => $schedule->bus->name,
                    'type' => $schedule->bus->bus_type,
                    'capacity' => $schedule->bus->capacity,
                    'plate_number' => $schedule->bus->plate_number,
                    'bus_type' => $schedule->bus->bus_type,
                ],
                'route' => [
                    'origin' => $schedule->route->origin,
                    'destination' => $schedule->route->destination,
                ],
            ];
        })->values();

        // Manual pagination
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedSchedules = new LengthAwarePaginator(
            $transformedSchedules->slice(($currentPage - 1) * $perPage, $perPage)->values(),
            $transformedSchedules->count(),
            $perPage,
            $currentPage,
            ['path' => route('frontend.booking.schedules')]
        );

        // Get distinct origins and destinations
        $origins = \App\Models\Route::distinct()->pluck('origin');
        $destinations = \App\Models\Route::distinct()->pluck('destination');

        // Validate origin-destination pair
        $validPair = true;
        if ($origin && $destination) {
            $validPair = \App\Models\Route::where('origin', $origin)
                ->where('destination', $destination)
                ->exists();
        }

        return \Inertia\Inertia::render('Frontend/Booking/Index', [
            'schedules' => $paginatedSchedules,
            'origins' => $origins,
            'destinations' => $destinations,
            'validPair' => $validPair,
            'filters' => $request->only(['origin', 'destination', 'date', 'class', 'time']),
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
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to make a booking.');
        }

        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date' => 'nullable|date|after_or_equal:today',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => ['required', 'string', 'email', 'max:255', 'regex:/^.+@.+\..+$/i'],
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
        $booking->user_id = \Illuminate\Support\Facades\Auth::id(); // User is guaranteed to be authenticated at this point
        $booking->schedule_id = $schedule->id;
        $booking->booking_date = \Carbon\Carbon::createFromFormat('Y-m-d', $bookingDate);
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_email = $request->passenger_email;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->seat_numbers = null; // Will be set later during seat selection
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = (float) ($schedule->price * $request->number_of_seats);
        $booking->booking_code = 'BK' . strtoupper(uniqid());
        $booking->payment_status = 'pending';
        $booking->booking_status = 'pending'; // Start as pending, confirm after payment succeeds
        $booking->save();           // Persist booking FIRST
        $booking->startPayment();   // Then start the payment timer

        // Send notification to admins (non-critical — catch mail errors)
        try {
            $admins = \App\Models\User::role('admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewBookingNotification($booking));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Booking notification failed: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
            ]);
        }

        // Redirect to confirmation page with booking details
        return redirect()->route('frontend.booking.confirmation', ['booking' => $booking->id]);
    }

    public function confirmation($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        // Authorization check: Pastikan user punya akses ke booking ini
        Gate::authorize('view', $booking);
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
            'bookingExpiresAt' => $booking->created_at->addMinutes(30)->toIso8601String(),
        ]);
    }

    public function selectSeats(Request $request)
    {
        // Validasi awal cuma cek tipe data aja
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'seat_numbers' => 'required|array|min:1',
            'seat_numbers.*' => 'integer|min:1' // Max kita cek manual nanti sesuai kapasitas bus
        ]);

        try {
            // Pake transaction biar aman, ga ada drama kursi ganda pas rame
            return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                // Kunci datanya biar user lain ngantri bentar
                $booking = Booking::lockForUpdate()
                    ->where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->findOrFail($request->booking_id);

                // Authorization check: Pastikan user punya akses ke booking ini
                Gate::authorize('update', $booking);

                // Kunci juga jadwalnya, ini paling penting biar ga overbooking
                $schedule = Schedule::lockForUpdate()->with('bus')->find($booking->schedule_id);

                if (!$schedule) {
                    return response()->json(['success' => false, 'message' => 'Jadwal ga ketemu entah kemana.']);
                }

                // VALIDASI DINAMIS: Cek kapasitas bus
                $busCapacity = $schedule->bus->capacity;
                foreach ($request->seat_numbers as $seat) {
                    if ($seat > $busCapacity) {
                        return response()->json([
                            'success' => false,
                            'message' => "Kursi nomor {$seat} ga valid bos. Kapasitas bus cuma {$busCapacity} kursi."
                        ]);
                    }
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
            // Validate the request
            $validatedData = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'payment_method' => 'required|string|in:gopay,shopeepay,qris,dana,linkaja,credit_card,bank_transfer,echannel'
            ]);

            $booking = Booking::where('user_id', \Illuminate\Support\Facades\Auth::id())
                ->findOrFail($validatedData['booking_id']);

            // Authorization check: Pastikan user bisa bayar booking ini
            Gate::authorize('pay', $booking);

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

            // HANDLE PROMO CODE
            if ($request->filled('promo_code_id')) {
                $promoCode = \App\Models\PromoCode::find($request->promo_code_id);

                // Base price determination (handle re-attempts)
                $basePrice = $booking->original_total_price ?? $booking->total_price;

                if ($promoCode && $promoCode->isValid()) {
                    // Check minimum purchase
                    if ($basePrice < $promoCode->min_purchase_amount) {
                        return response()->json(['success' => false, 'message' => 'Minimal pembelian tidak terpenuhi untuk kode promo ini.']);
                    }

                    // Calculate discount
                    $discount = $promoCode->calculateDiscount($basePrice);

                    // Update Booking
                    $booking->original_total_price = (float) $basePrice;
                    $booking->discount_amount = (float) $discount;
                    $booking->total_price = (float) max(0, $basePrice - $discount);
                    $booking->promo_code_id = $promoCode->id;
                    $booking->save();

                    // NOTE: usage_count will be incremented after payment is SETTLED
                    // (see MidtransService::handleWebhook), not here to prevent abuse
                } else {
                    return response()->json(['success' => false, 'message' => 'Kode promo tidak valid atau kadaluarsa.']);
                }
            } else {
                // If no promo code sent but booking has one (user removed it?), reset price
                if ($booking->promo_code_id) {
                    $booking->total_price = $booking->original_total_price ?? $booking->total_price;
                    $booking->discount_amount = 0;
                    $booking->promo_code_id = null;
                    $booking->original_total_price = null;
                    $booking->save();
                }
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
                'booking_id' => $request->booking_id,
                'errors' => $e->errors()
            ]);

            return response()->json(['success' => false, 'message' => 'Validation failed.']);
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'booking_id' => $request->booking_id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'An error occurred.']);
        }
    }

    public function success($id)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        if (!in_array($booking->booking_status, ['confirmed', 'pending'])) {
            abort(404, 'Invalid booking');
        }

        if (!in_array($booking->payment_status, ['paid', 'pending'])) {
            return redirect()->route('frontend.booking.confirmation', ['booking' => $booking->id])
                ->withErrors(['payment' => 'Payment has not been initiated yet.']);
        }

        if ($booking->payment_status === 'pending' && $booking->midtrans_transaction_id) {
            try {
                // Query Midtrans for the latest transaction status
                $result = $this->paymentService->midtransService->getTransactionStatus($booking->midtrans_transaction_id);

                if ($result['status'] === 'success') {
                    Log::info('Payment status checked on success page', [
                        'booking_id' => $booking->id,
                        'transaction_status' => $result['data']->transaction_status ?? 'unknown'
                    ]);

                    // Reload booking to get updated status WITH relationships
                    $booking = Booking::with('schedule.route', 'schedule.bus')->findOrFail($id);
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
            'booking' => $booking->load('schedule.route', 'schedule.bus')
        ]);
    }

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function downloadTicket($id, TicketPdfService $ticketPdfService)
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->findOrFail($id);

        // Ensure the booking has seat numbers
        if (empty($booking->seat_numbers)) {
            abort(404, 'Ticket not available. Please select seats first.');
        }

        // Check if the schedule has already departed
        if ($booking->schedule->hasDeparted()) {
            abort(404, 'Ticket not available. The schedule has already departed.');
        }

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
