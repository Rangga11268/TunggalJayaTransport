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
    
    public function index(Request $request)
    {
        $bookings = Booking::with(['user', 'schedule.route', 'schedule.bus'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('booking_code', 'like', "%{$search}%")
                      ->orWhere('passenger_name', 'like', "%{$search}%")
                      ->orWhere('passenger_email', 'like', "%{$search}%");
                });
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

    
    public function create()
    {
        // Get all active schedules and filter by availability
        $schedules = Schedule::with(['route', 'bus'])
            ->where('status', 'active')
            ->get()
            ->filter(function ($schedule) {
                // Only show schedules that are available for booking
                return $schedule->isAvailableForBooking();
            })
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'name' => $schedule->route->origin . ' - ' . $schedule->route->destination . ' (' . $schedule->bus->name . ') - ' . $schedule->getActualDepartureTime()->format('d M Y H:i'),
                    'price' => $schedule->price,
                    'available_seats' => $schedule->getAvailableSeatsCount(),
                    'bus_capacity' => $schedule->bus->capacity,
                ];
            })
            ->values(); // Reindex array

        return Inertia::render('Admin/Bookings/Create', [
            'schedules' => $schedules,
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            // Harus pilih user biar jelas siapa yg booking
            'user_id' => 'required|exists:users,id', 
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'seat_numbers' => 'required|string|max:255',
            // Total harga harus diisi
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
            'number_of_seats' => 'required|integer|min:1', // Penting nih, jangan lupa
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Validasi dasar dulu bos
        if ($schedule->hasDeparted()) {
             return redirect()->back()->withErrors(['schedule_id' => 'Cannot create booking for a schedule that has already departed.']);
        }
        
        
        // Bikin Kode Booking biar keren
        $bookingCode = 'BK' . time() . rand(100, 999);
        
        $booking = new Booking();
        $booking->booking_code = $bookingCode;
        $booking->user_id = $request->user_id;
        $booking->schedule_id = $request->schedule_id;
        $booking->booking_date = now(); // Tanggal booking hari ini
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

    
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'schedule.route', 'schedule.bus', 'paymentHistories'])->findOrFail($id);
        
        // Format dulu buat tampilan
        $booking->departure_time = $booking->schedule->getActualDepartureTime()->format('d M Y H:i');
        
        
        // Get occupied seats for this schedule on the booking date
        $occupiedSeats = $booking->schedule->getBookedSeatNumbers($booking->booking_date);
        
        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking,
            'occupiedSeats' => $occupiedSeats,
        ]);
    }

    
    public function edit(string $id)
    {
        $booking = Booking::with('schedule')->findOrFail($id);
        
        return Inertia::render('Admin/Bookings/Edit', [
            'booking' => $booking,
            // Load semua jadwal berat bos. Load yang sekarang + yang akan datang aja
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

    
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
