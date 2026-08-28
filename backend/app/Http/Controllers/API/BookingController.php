<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @OA\Get(
     *      path="/bookings",
     *      operationId="getUserBookings",
     *      tags={"Booking Tiket AKAP"},
     *      summary="Riwayat Pemesanan Tiket User",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Daftar tiket pengguna")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $bookings = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/bookings/{id}",
     *      operationId="getBookingDetail",
     *      tags={"Booking Tiket AKAP"},
     *      summary="Detail Pemesanan Tiket",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Detail tiket"),
     *      @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show($id): JsonResponse
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', Auth::id())
            ->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->has('number_of_seats') && $request->has('seat_numbers') && is_array($request->seat_numbers)) {
            $request->merge(['number_of_seats' => count($request->seat_numbers)]);
        }

        $validator = Validator::make($request->all(), [
            'schedule_id' => ['required', 'exists:schedules,id'],
            'date' => ['nullable', 'date'],
            'passenger_name' => ['required', 'string', 'max:255'],
            'passenger_email' => ['required', 'string', 'email', 'max:255'],
            'passenger_phone' => ['required', 'string', 'max:20'],
            'number_of_seats' => ['required', 'integer', 'min:1', 'max:10'],
            'seat_numbers' => ['nullable', 'array'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $schedule = Schedule::with('bus')->findOrFail($request->schedule_id);

        $bookingDate = $request->date
            ? Carbon::parse($request->date)
            : ($schedule->is_daily ? Carbon::today('Asia/Jakarta') : $schedule->departure_time);

        $booking = Booking::create([
            'user_id' => Auth::id() ?? 2,
            'schedule_id' => $schedule->id,
            'booking_date' => $bookingDate->format('Y-m-d'),
            'passenger_name' => $request->passenger_name,
            'passenger_email' => $request->passenger_email,
            'passenger_phone' => $request->passenger_phone,
            'number_of_seats' => $request->number_of_seats,
            'seat_numbers' => $request->seat_numbers ? (is_array($request->seat_numbers) ? implode(',', $request->seat_numbers) : $request->seat_numbers) : null,
            'total_price' => $schedule->price * $request->number_of_seats,
            'booking_code' => 'TJ-BK' . rand(1000, 9999),
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dibuat',
            'data' => $booking->load('schedule.route', 'schedule.bus'),
        ], 201);
    }

    public function selectSeats(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required', 'exists:bookings,id'],
            'seat_numbers' => ['required', 'array', 'min:1'],
            'seat_numbers.*' => ['integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                $booking = Booking::lockForUpdate()
                    ->where('user_id', Auth::id())
                    ->findOrFail($request->booking_id);

                Gate::authorize('update', $booking);

                $schedule = Schedule::lockForUpdate()->with('bus')->find($booking->schedule_id);

                if (!$schedule) {
                    return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
                }

                if ($schedule->hasDeparted()) {
                    return response()->json(['success' => false, 'message' => 'Bus sudah berangkat'], 400);
                }

                if (!$schedule->isAvailableForBooking()) {
                    return response()->json(['success' => false, 'message' => 'Jadwal tidak tersedia'], 400);
                }

                if (count($request->seat_numbers) != $booking->number_of_seats) {
                    return response()->json(['success' => false, 'message' => 'Pilih ' . $booking->number_of_seats . ' kursi'], 400);
                }

                $availableSeats = $schedule->getAvailableSeatsCount($booking->booking_date);
                if (count($request->seat_numbers) > $availableSeats) {
                    return response()->json(['success' => false, 'message' => "Sisa kursi cuma {$availableSeats}"], 400);
                }

                $occupiedSeats = $schedule->getBookedSeatNumbers($booking->booking_date);
                $selectedSeats = array_map('strval', $request->seat_numbers);
                $conflictingSeats = array_intersect($selectedSeats, $occupiedSeats);

                if (!empty($conflictingSeats)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kursi nomor ' . implode(', ', $conflictingSeats) . ' sudah dipesan orang lain',
                    ], 400);
                }

                if (count($selectedSeats) != count(array_unique($selectedSeats))) {
                    return response()->json(['success' => false, 'message' => 'Tidak boleh memilih kursi yang sama'], 400);
                }

                $busCapacity = $schedule->bus->capacity;
                foreach ($request->seat_numbers as $seat) {
                    if ($seat > $busCapacity) {
                        return response()->json(['success' => false, 'message' => "Kursi nomor {$seat} tidak valid"], 400);
                    }
                }

                $booking->update([
                    'seat_numbers' => implode(',', $request->seat_numbers),
                ]);

                foreach ($request->seat_numbers as $seat) {
                    broadcast(new \App\Events\SeatLocked($schedule->id, $seat, true));
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Kursi berhasil dipilih',
                    'data' => $booking->fresh(),
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memilih kursi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function processPayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => ['required', 'exists:bookings,id'],
            'payment_method' => ['required', 'string', 'in:gopay,shopeepay,qris,dana,linkaja,credit_card,bank_transfer,echannel'],
            'promo_code_id' => ['nullable', 'exists:promo_codes,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $booking = Booking::where('user_id', Auth::id())->findOrFail($request->booking_id);

            Gate::authorize('pay', $booking);

            if ($booking->isPaymentExpired()) {
                return response()->json(['success' => false, 'message' => 'Waktu pembayaran telah habis'], 400);
            }

            if ($booking->schedule->hasDeparted()) {
                return response()->json(['success' => false, 'message' => 'Jadwal sudah berangkat'], 400);
            }

            if (empty($booking->seat_numbers)) {
                return response()->json(['success' => false, 'message' => 'Pilih kursi terlebih dahulu'], 400);
            }

            if ($request->filled('promo_code_id')) {
                $promoCode = \App\Models\PromoCode::find($request->promo_code_id);
                $basePrice = $booking->original_total_price ?? $booking->total_price;

                if ($promoCode && $promoCode->isValid()) {
                    if ($basePrice < $promoCode->min_purchase_amount) {
                        return response()->json(['success' => false, 'message' => 'Minimal pembelian tidak terpenuhi'], 400);
                    }

                    $discount = $promoCode->calculateDiscount($basePrice);
                    $booking->original_total_price = (float) $basePrice;
                    $booking->discount_amount = (float) $discount;
                    $booking->total_price = (float) max(0, $basePrice - $discount);
                    $booking->promo_code_id = $promoCode->id;
                    $booking->save();
                }
            }

            $result = $this->paymentService->processPayment($booking->id, $request->payment_method);

            if ($result['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil dibuat',
                    'data' => [
                        'snap_token' => $result['snap_token'],
                        'redirect_url' => $result['redirect_url'],
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Gagal memproses pembayaran',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran',
            ], 500);
        }
    }
}
