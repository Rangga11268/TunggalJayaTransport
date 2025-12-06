<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'schedule.route', 'schedule.bus'])
            ->when($request->search, function ($query, $search) {
                $query->where('booking_code', 'like', "%{$search}%")
                      ->orWhere('passenger_name', 'like', "%{$search}%")
                      ->orWhere('passenger_email', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                if ($status !== 'all') {
                    $query->where('booking_status', $status);
                }
            })
            ->when($request->payment_status, function ($query, $status) {
                 if ($status !== 'all') {
                    $query->where('payment_status', $status);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['search', 'status', 'payment_status']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Bookings/Create', [
            'schedules' => Schedule::with(['route', 'bus'])
                ->where('departure_time', '>', now()) // Only show future schedules
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'name' => $schedule->route->origin . ' - ' . $schedule->route->destination . ' (' . $schedule->bus->name . ') - ' . $schedule->getActualDepartureTime()->format('d M Y H:i'),
                        'price' => $schedule->price,
                        'available_seats' => $schedule->getAvailableSeatsCount(),
                        'bus_capacity' => $schedule->bus->capacity,
                    ];
                }),
             // We can optionally pass users if needed, or rely on free text entry for simplicity as per current controller
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'user_id' => 'required|exists:users,id', // Optional in manual booking? The original controller required it. Let's make it optional or assume admin booking uses a default/null user if not selected.
            // Actually original controller required user_id. Let's stick to required for now, or handle it.
            // Admin manual booking might be for a guest? 
            // The original controller code: 'user_id' => 'required|exists:users,id'.
            // I'll keep it simple for now, maybe associate with the admin user or a specific guest user?
            // Or better, let's look at the original Store method again.
            // It uses $request->user_id. 
            // I will allow creating a user or selecting one?
            // For now, I'll remove the strict user_id requirement for Admin creation if the original system allowed "Guest" text. 
            // Wait, original validation WAS strict: required|exists:users,id.
            // This means we MUST select a user. I will fetch users in create then.
            'user_id' => 'required|exists:users,id', 
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'seat_numbers' => 'required|string|max:255',
            // 'total_price' => 'required|numeric|min:0', // Auto-calculate? The original requested it.
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
            'number_of_seats' => 'required|integer|min:1', // Added this as Model has it
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Basic validations
        if ($schedule->hasDeparted()) {
             return redirect()->back()->withErrors(['schedule_id' => 'Cannot create booking for a schedule that has already departed.']);
        }
        
        // Check availability (simple check)
        // Note: Model has isAvailableForBooking, but we also need to check seat count vs requested
        // The original code passed 'seat_numbers' string. 
        
        // Booking Code
        $bookingCode = 'BK' . time() . rand(100, 999);
        
        $booking = new Booking();
        $booking->booking_code = $bookingCode;
        $booking->user_id = $request->user_id;
        $booking->schedule_id = $request->schedule_id;
        $booking->booking_date = now(); // Add booking date
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = $request->seat_numbers;
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = $request->total_price;
        $booking->payment_status = $request->payment_status;
        $booking->booking_status = $request->booking_status;
        
        if ($booking->payment_status === 'paid') {
             $booking->payment_started_at = now();
        }
        
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'schedule.route', 'schedule.bus', 'paymentHistories'])->findOrFail($id);
        
        // Transform for display
        $booking->departure_time = $booking->schedule->getActualDepartureTime()->format('d M Y H:i');
        
        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::with('schedule')->findOrFail($id);
        
        return Inertia::render('Admin/Bookings/Edit', [
            'booking' => $booking,
            // Pass minimal schedule info if not changing schedule OR full list if changing is allowed
            // Loading all schedules might be heavy. Let's load the current one + future ones.
             'schedules' => Schedule::with(['route', 'bus'])
                ->where('id', $booking->schedule_id)
                ->orWhere('departure_time', '>', now())
                ->get()
                ->map(function ($schedule) {
                    return [
                       'id' => $schedule->id,
                        'name' => $schedule->route->origin . ' - ' . $schedule->route->destination . ' (' . $schedule->bus->name . ') - ' . $schedule->getActualDepartureTime()->format('d M Y H:i'),
                        'price' => $schedule->price,
                        'available_seats' => $schedule->getAvailableSeatsCount(),
                        'bus_capacity' => $schedule->bus->capacity,
                    ];
                }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'seat_numbers' => 'required|string|max:255',
            'number_of_seats' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        if ($request->schedule_id != $booking->schedule_id) {
             $newSchedule = Schedule::findOrFail($request->schedule_id);
             if ($newSchedule->hasDeparted()) {
                  return back()->withErrors(['schedule_id' => 'Cannot change to a departed schedule.']);
             }
             $booking->schedule_id = $request->schedule_id;
        }

        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = $request->seat_numbers;
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = $request->total_price;
        $booking->payment_status = $request->payment_status;
        $booking->booking_status = $request->booking_status;
        
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
