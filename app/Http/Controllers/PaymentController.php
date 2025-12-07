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

        // Fallback: Check Booking table if PaymentHistory not found
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

        // 1. Check status of the requested Order ID
        $result = $this->midtransService->getTransactionStatus($orderId);

        // If explicitly successful, return immediately
        if ($result['status'] === 'success' && 
           ($result['transaction_status'] == 'settlement' || $result['transaction_status'] == 'capture')) {
            return response()->json([
                'status' => 'success',
                'data' => $result['data'],
                'transaction_status' => $result['transaction_status']
            ]);
        }

        // 2. Smart Recovery: If the requested ID is not paid (pending/not_found/etc), 
        // check IF ANY OTHER transaction for this booking was paid.
        // This handles the case where user clicked "Pay" multiple times (generating new IDs) 
        // but paid for an OLDER Snap token.
        
        if ($booking) {
            // Get all transaction IDs associated with this booking from PaymentHistory
            $allTransactions = PaymentHistory::where('booking_id', $booking->id)
                ->where('transaction_id', '!=', $orderId) // Exclude current one we already checked
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($allTransactions as $history) {
                // Check status of these other transactions
                $recoveryResult = $this->midtransService->getTransactionStatus($history->transaction_id);
                
                if ($recoveryResult['status'] === 'success' && 
                   ($recoveryResult['transaction_status'] == 'settlement' || $recoveryResult['transaction_status'] == 'capture')) {
                    
                    // Found a PAID transaction!
                    // Update the booking to point to this valid transaction instead
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
        
        // Handle "not_found" specifically (waiting for payment creation)
        if ($result['status'] === 'not_found') {
             return response()->json([
                'status' => 'success', // Retain success for frontend logic
                'transaction_status' => 'not_found', // Custom status for frontend
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
