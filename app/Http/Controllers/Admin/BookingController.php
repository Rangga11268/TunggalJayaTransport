<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'schedule.route', 'schedule.bus'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bookings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'seat_numbers' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Check if the schedule has already departed
        $schedule = \App\Models\Schedule::findOrFail($request->schedule_id);
        if ($schedule->hasDeparted()) {
            return redirect()->back()
                ->withErrors(['schedule_id' => 'Cannot create booking for a schedule that has already departed. Please select another schedule.'])
                ->withInput();
        }

        // Check if schedule is available for booking
        if (!$schedule->isAvailableForBooking()) {
            return redirect()->back()
                ->withErrors(['schedule_id' => 'This schedule is not available for booking. Please select another schedule.'])
                ->withInput();
        }

        // Generate booking code
        $bookingCode = 'BK' . time() . rand(100, 999);
        
        $booking = new Booking();
        $booking->booking_code = $bookingCode;
        $booking->user_id = $request->user_id;
        $booking->schedule_id = $request->schedule_id;
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = $request->seat_numbers;
        $booking->total_price = $request->total_price;
        $booking->payment_status = $request->payment_status;
        $booking->booking_status = $request->booking_status;
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('create_success', 'Booking created successfully. Please note that the schedule departs on ' . $schedule->getActualDepartureTime()->format('d M Y H:i'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'schedule.route', 'schedule.bus'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.bookings.edit', compact('booking'));
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
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Check if trying to change to a schedule that has already departed
        if ($request->has('schedule_id') && $request->schedule_id != $booking->schedule_id) {
            $newSchedule = \App\Models\Schedule::findOrFail($request->schedule_id);
            if ($newSchedule->hasDeparted()) {
                return redirect()->back()
                    ->withErrors(['schedule_id' => 'Cannot change to a schedule that has already departed. Please select another schedule.'])
                    ->withInput();
            }
            
            // Check if new schedule is available for booking
            if (!$newSchedule->isAvailableForBooking()) {
                return redirect()->back()
                    ->withErrors(['schedule_id' => 'The new schedule is not available for booking. Please select another schedule.'])
                    ->withInput();
            }
        }

        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = $request->seat_numbers;
        $booking->total_price = $request->total_price;
        $booking->payment_status = $request->payment_status;
        $booking->booking_status = $request->booking_status;
        
        // Only update schedule_id if provided and valid
        if ($request->has('schedule_id')) {
            $booking->schedule_id = $request->schedule_id;
        }
        
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('update_success', 'Booking updated successfully. Please note that the schedule departs on ' . $booking->schedule->getActualDepartureTime()->format('d M Y H:i'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('delete_success', 'Pemesanan berhasil dihapus.');
    }
}
