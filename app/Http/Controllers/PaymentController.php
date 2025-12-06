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

        if (!$paymentHistory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment history not found'
            ], 404);
        }

        $result = $this->midtransService->getTransactionStatus($orderId);

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
        // Validate payload first
        $payload = $request->all();
        
        // Validate the notification from Midtrans
        $isValid = $this->midtransService->validateNotification($payload);
        
        if (!$isValid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid notification'
            ], 400);
        }

        // Process the webhook
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
