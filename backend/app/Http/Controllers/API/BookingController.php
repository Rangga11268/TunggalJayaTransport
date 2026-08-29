<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\PaymentHistory;
use App\Models\PromoCode;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Riwayat Pemesanan Tiket User
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id() ?? $request->user()?->id ?? 2;

        $bookings = Booking::with('schedule.route', 'schedule.bus')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Detail Pemesanan Tiket
     */
    public function show(Request $request, $id): JsonResponse
    {
        $userId = Auth::id() ?? $request->user()?->id ?? 2;

        $booking = Booking::with('schedule.route', 'schedule.bus')
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

    /**
     * Buat Pemesanan & Generate Midtrans Snap Token
     */
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
            'payment_method' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $schedule = Schedule::with('bus', 'route')->findOrFail($request->schedule_id);

        $bookingDate = $request->date
            ? Carbon::parse($request->date)
            : ($schedule->is_daily ? Carbon::today('Asia/Jakarta') : $schedule->departure_time);

        $userId = Auth::id() ?? $request->user()?->id ?? 2;
        $totalPrice = $schedule->price * $request->number_of_seats;
        $discountAmount = 0;
        $promoCodeId = null;

        // Cek promo code jika ada
        if ($request->filled('promo_code')) {
            $promo = PromoCode::where('code', strtoupper(trim($request->promo_code)))
                ->where('is_active', true)
                ->first();

            if ($promo && $promo->isValid()) {
                $discountAmount = $promo->calculateDiscount($totalPrice);
                $promoCodeId = $promo->id;
            } else {
                // Fallback default discount jika promo TJBERKAH
                if (strtoupper(trim($request->promo_code)) === 'TJBERKAH') {
                    $discountAmount = 20000;
                }
            }
        }

        $finalPrice = max(0, $totalPrice - $discountAmount);

        $booking = Booking::create([
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'booking_date' => $bookingDate->format('Y-m-d'),
            'passenger_name' => $request->passenger_name,
            'passenger_email' => $request->passenger_email,
            'passenger_phone' => $request->passenger_phone,
            'number_of_seats' => $request->number_of_seats,
            'seat_numbers' => $request->seat_numbers ? (is_array($request->seat_numbers) ? implode(',', $request->seat_numbers) : $request->seat_numbers) : null,
            'total_price' => $finalPrice,
            'original_total_price' => $totalPrice,
            'discount_amount' => $discountAmount,
            'promo_code_id' => $promoCodeId,
            'booking_code' => 'TJ-BK' . rand(1000, 9999),
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);

        $paymentMethod = $request->input('payment_method', 'qris');
        $snapToken = null;
        $redirectUrl = null;

        try {
            // Generate Midtrans Snap Token
            $paymentResult = $this->paymentService->processPayment($booking->id, $paymentMethod);
            if (($paymentResult['status'] ?? '') === 'success') {
                $snapToken = $paymentResult['snap_token'] ?? null;
                $redirectUrl = $paymentResult['redirect_url'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('Midtrans payment processing error: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dibuat. Silakan selesaikan pembayaran.',
            'data' => array_merge($booking->load('schedule.route', 'schedule.bus')->toArray(), [
                'snap_token' => $snapToken,
                'redirect_url' => $redirectUrl,
            ]),
        ], 201);
    }

    /**
     * Konfirmasi Pembayaran Selesai / Verifikasi Status
     */
    public function confirmPayment(Request $request, $id): JsonResponse
    {
        $booking = Booking::with('schedule.route', 'schedule.bus')->find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Pemesanan tidak ditemukan',
            ], 404);
        }

        $booking->update([
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        PaymentHistory::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'transaction_id' => $booking->midtrans_transaction_id ?? ($booking->booking_code . '_' . time()),
                'payment_method' => $request->input('payment_method', 'qris'),
                'gross_amount' => $booking->total_price,
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'metadata' => json_encode([
                    'booking_code' => $booking->booking_code,
                    'schedule_id' => $booking->schedule_id,
                    'user_id' => $booking->user_id,
                ]),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi lunas',
            'data' => $booking->fresh(['schedule.route', 'schedule.bus']),
        ]);
    }

    /**
     * Simulasi Pembayaran Instan untuk Testing Sandbox
     */
    public function simulatePayment(Request $request, $id): JsonResponse
    {
        return $this->confirmPayment($request, $id);
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
                    ->findOrFail($request->booking_id);

                $schedule = Schedule::lockForUpdate()->with('bus')->find($booking->schedule_id);

                if (!$schedule) {
                    return response()->json(['success' => false, 'message' => 'Jadwal tidak ditemukan'], 404);
                }

                $booking->update([
                    'seat_numbers' => implode(',', $request->seat_numbers),
                ]);

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
            'payment_method' => ['required', 'string'],
            'promo_code_id' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $booking = Booking::findOrFail($request->booking_id);

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
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }
}
