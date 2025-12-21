<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Services\MidtransService;
use App\Models\Booking;
use App\Models\PaymentHistory;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $midtransService;

    public function __construct(PaymentService $paymentService, MidtransService $midtransService)
    {
        $this->paymentService = $paymentService;
        $this->midtransService = $midtransService;
    }

    /**
     * Process payment for a booking
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'payment_method' => 'required|string|in:gopay,shopeepay,qris,dana,linkaja,credit_card,bank_transfer,echannel'
        ]);

        $result = $this->paymentService->processPayment(
            $request->booking_id,
            $request->payment_method
        );

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'snap_token' => $result['snap_token'],
                'redirect_url' => $result['redirect_url'],
                'message' => 'Payment created successfully'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'] ?? 'Failed to process payment'
        ], 400);
    }

    /**
     * Get payment status
     * 
     * @param string $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($orderId)
    {
        $paymentHistory = PaymentHistory::where('transaction_id', $orderId)->first();
        $booking = null;

        // Coba cek di tabel Booking kalo history ga ketemu, kali aja nyelip
        if (!$paymentHistory) {
            $booking = Booking::where('midtrans_transaction_id', $orderId)->first();
            
            if (!$booking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transaction not found'
                ], 404);
            }
        } else {
            $booking = $paymentHistory->booking;
        }

        // 1. Cek status Order ID yang diminta ke Midtrans
        $result = $this->midtransService->getTransactionStatus($orderId);

        // Kalo sukses yaudah balikin aja langsung
        if ($result['status'] === 'success' && 
           ($result['transaction_status'] == 'settlement' || $result['transaction_status'] == 'capture')) {
            return response()->json([
                'status' => 'success',
                'data' => $result['data'],
                'transaction_status' => $result['transaction_status']
            ]);
        }

        // 2. Smart Recovery: Kalo ID ini belom dibayar (pending/not_found), 
        // coba cek ID lain siapa tau user iseng bikin banyak tapi bayar yang lama.
        // Ini buat jaga-jaga kalo user klik "Bayar" berkali-kali (bikin ID baru) tapi bayar pake Snap token yang lama.
        
        if ($booking) {
            // Ambil semua ID transaksi buat booking ini dari history
            $allTransactions = PaymentHistory::where('booking_id', $booking->id)
                ->where('transaction_id', '!=', $orderId) // Jangan cek yang tadi udah dicek
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($allTransactions as $history) {
                // Cekin satu-satu statusnya
                $recoveryResult = $this->midtransService->getTransactionStatus($history->transaction_id);
                
                if ($recoveryResult['status'] === 'success' && 
                   ($recoveryResult['transaction_status'] == 'settlement' || $recoveryResult['transaction_status'] == 'capture')) {
                    
                    // Nah ketemu yang udah LUNAS!
                    // Pake ID yang ini aja, update bookingnya biar bener
                    $booking->update([
                        'payment_status' => 'paid',
                        'midtrans_transaction_id' => $history->transaction_id
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'data' => $recoveryResult['data'],
                        'transaction_status' => $recoveryResult['transaction_status'],
                        'message' => 'Payment recovered from previous attempt'
                    ]);
                }
            }
        }
        
        // Kalo ga ketemu alias not_found, yaudah bilang aja nunggu pembayaran
        if ($result['status'] === 'not_found') {
             return response()->json([
                'status' => 'success', // Tetep success biar frontend ga panik
                'transaction_status' => 'not_found', 
                'message' => $result['message']
            ]);
        }

        if ($result['status'] === 'success') {
             return response()->json([
                'status' => 'success',
                'data' => $result['data'],
                'transaction_status' => $result['transaction_status'] ?? 'unknown'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message']
        ], 500);
    }

    /**
     * Handle Midtrans webhook
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        // Validasi payload dulu
        $payload = $request->all();
        
        // Validasi notifikasinya beneran dari Midtrans ga
        $isValid = $this->midtransService->validateNotification($payload);
        
        if (!$isValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid notification'
            ], 400);
        }

        // Proses webhooknya
        $result = $this->midtransService->handleWebhook($payload);

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'message' => $result['message']
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message']
        ], 500);
    }
}
