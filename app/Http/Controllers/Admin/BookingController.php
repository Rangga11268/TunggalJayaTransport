<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
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

        if ($request->wantsJson()) {
            return response()->json([
                'bookings' => $bookings
            ]);
        }

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
            'booking_date' => 'required|date',
            'passenger_name' => 'required|string|max:255',
            'passenger_phone' => 'required|string|max:20',
            'passenger_email' => 'required|email|max:255',
            'seat_numbers' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'booking_status' => 'required|in:pending,confirmed,cancelled,completed',
            'number_of_seats' => 'required|integer|min:1',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        $bookingDate = $request->booking_date ? \Carbon\Carbon::parse($request->booking_date)->startOfDay() : now()->startOfDay();

        // Validasi dasar: Schedule sudah berangkat?
        if ($schedule->hasDeparted($bookingDate)) {
            return redirect()->back()->withErrors(['schedule_id' => 'Cannot create booking for a schedule that has already departed.']);
        }

        // ===== ENHANCED SEAT VALIDATION =====

        // 1. Parse dan validasi format seat_numbers (harus comma-separated integers)
        $seatNumbersString = trim($request->seat_numbers);
        if (empty($seatNumbersString)) {
            return redirect()->back()->withErrors(['seat_numbers' => 'Seat numbers tidak boleh kosong']);
        }

        $seatNumbers = array_map('intval', array_filter(
            array_map('trim', explode(',', $seatNumbersString)),
            function ($val) {
                return $val !== '';
            }
        ));

        if (empty($seatNumbers)) {
            return redirect()->back()->withErrors(['seat_numbers' => 'Format seat numbers tidak valid. Gunakan format: 1,2,3']);
        }

        // 2. Validasi jumlah kursi sesuai number_of_seats
        if (count($seatNumbers) != $request->number_of_seats) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Jumlah kursi (' . count($seatNumbers) . ') tidak sesuai number_of_seats (' . $request->number_of_seats . ')'
            ]);
        }

        // 3. Validasi kapasitas bus
        foreach ($seatNumbers as $seat) {
            if ($seat > $schedule->bus->capacity) {
                return redirect()->back()->withErrors([
                    'seat_numbers' => "Nomor kursi {$seat} melebihi kapasitas bus ({$schedule->bus->capacity} kursi)"
                ]);
            }
            if ($seat < 1) {
                return redirect()->back()->withErrors([
                    'seat_numbers' => "Nomor kursi harus lebih dari 0"
                ]);
            }
        }

        // 4. Cek duplikat dalam input
        $uniqueSeats = array_unique($seatNumbers);
        if (count($uniqueSeats) != count($seatNumbers)) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Ada duplikat nomor kursi dalam input'
            ]);
        }

        // 5. Cek kursi sudah dipesan (collision check)
        $occupiedSeats = $schedule->getBookedSeatNumbers($bookingDate);
        $conflictingSeats = array_intersect($seatNumbers, array_map(
            'intval',
            is_array($occupiedSeats) ? $occupiedSeats : json_decode($occupiedSeats ?? '[]', true)
        ));

        if (!empty($conflictingSeats)) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Kursi berikut sudah dipesan: ' . implode(', ', $conflictingSeats)
            ]);
        }

        // 6. Validasi kapasitas tersedia
        $availableSeats = $schedule->getAvailableSeatsCount($bookingDate);
        if (count($seatNumbers) > $availableSeats) {
            return redirect()->back()->withErrors([
                'seat_numbers' => "Hanya {$availableSeats} kursi tersedia untuk tanggal ini"
            ]);
        }

        // ===== JIKA SEMUA VALIDASI LOLOS =====

        $bookingCode = 'BK' . time() . rand(100, 999);

        $booking = new Booking();
        $booking->booking_code = $bookingCode;
        $booking->user_id = $request->user_id;
        $booking->schedule_id = $request->schedule_id;
        $booking->booking_date = $bookingDate;
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = implode(',', $seatNumbers); // Simpan sebagai comma-separated integers
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = (float) $request->total_price;
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

        // Get schedule untuk validasi
        $schedule = Schedule::findOrFail($request->schedule_id);
        $bookingDate = $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->startOfDay() : now()->startOfDay();

        if ($request->schedule_id != $booking->schedule_id) {
            $newSchedule = Schedule::findOrFail($request->schedule_id);
            if ($newSchedule->hasDeparted($bookingDate)) {
                return back()->withErrors(['schedule_id' => 'Cannot change to a departed schedule.']);
            }
            $booking->schedule_id = $request->schedule_id;
            $schedule = $newSchedule;
        }

        // ===== ENHANCED SEAT VALIDATION (sama seperti store) =====

        // 1. Parse dan validasi format seat_numbers
        $seatNumbersString = trim($request->seat_numbers);
        if (empty($seatNumbersString)) {
            return redirect()->back()->withErrors(['seat_numbers' => 'Seat numbers tidak boleh kosong']);
        }

        $seatNumbers = array_map('intval', array_filter(
            array_map('trim', explode(',', $seatNumbersString)),
            function ($val) {
                return $val !== '';
            }
        ));

        if (empty($seatNumbers)) {
            return redirect()->back()->withErrors(['seat_numbers' => 'Format seat numbers tidak valid. Gunakan format: 1,2,3']);
        }

        // 2. Validasi jumlah kursi sesuai number_of_seats
        if (count($seatNumbers) != $request->number_of_seats) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Jumlah kursi (' . count($seatNumbers) . ') tidak sesuai number_of_seats (' . $request->number_of_seats . ')'
            ]);
        }

        // 3. Validasi kapasitas bus
        foreach ($seatNumbers as $seat) {
            if ($seat > $schedule->bus->capacity) {
                return redirect()->back()->withErrors([
                    'seat_numbers' => "Nomor kursi {$seat} melebihi kapasitas bus ({$schedule->bus->capacity} kursi)"
                ]);
            }
            if ($seat < 1) {
                return redirect()->back()->withErrors([
                    'seat_numbers' => "Nomor kursi harus lebih dari 0"
                ]);
            }
        }

        // 4. Cek duplikat dalam input
        $uniqueSeats = array_unique($seatNumbers);
        if (count($uniqueSeats) != count($seatNumbers)) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Ada duplikat nomor kursi dalam input'
            ]);
        }

        // 5. Cek kursi sudah dipesan (collision check), EXCLUDE booking ini sendiri
        $occupiedSeats = $schedule->getBookedSeatNumbers($bookingDate);
        // Hapus kursi yang sebelumnya dipesan oleh booking ini sendiri
        $currentBookingSeats = array_map(
            'intval',
            explode(',', $booking->seat_numbers ?? '')
        );
        $otherOccupiedSeats = array_diff(
            array_map('intval', is_array($occupiedSeats) ? $occupiedSeats : json_decode($occupiedSeats ?? '[]', true)),
            $currentBookingSeats
        );

        $conflictingSeats = array_intersect($seatNumbers, $otherOccupiedSeats);

        if (!empty($conflictingSeats)) {
            return redirect()->back()->withErrors([
                'seat_numbers' => 'Kursi berikut sudah dipesan oleh booking lain: ' . implode(', ', $conflictingSeats)
            ]);
        }

        // ===== UPDATE BOOKING =====
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_phone = $request->passenger_phone;
        $booking->passenger_email = $request->passenger_email;
        $booking->seat_numbers = implode(',', $seatNumbers);
        $booking->number_of_seats = $request->number_of_seats;
        $booking->total_price = (float) $request->total_price;
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

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }
        Booking::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' data berhasil dihapus.']);
    }
}
