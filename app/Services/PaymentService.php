<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Process payment for a booking
     * 
     * @param int $bookingId
     * @param string $paymentMethod
     * @return array
     */
    public function processPayment($bookingId, $paymentMethod = 'gopay')
    {
        $booking = Booking::with('schedule.bus', 'user')->findOrFail($bookingId);

        // Check if booking already paid
        if ($booking->payment_status === 'paid') {
            return [
                'status' => 'error',
                'message' => 'Booking has already been paid'
            ];
        }

        // Prepare order details for Midtrans
        $orderData = [
            'transaction_details' => [
                'order_id' => $booking->booking_code . '_' . time(),
                'gross_amount' => $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->passenger_name,
                'email' => $booking->passenger_email,
                'phone' => $booking->passenger_phone,
            ],
            'item_details' => [
                [
                    'id' => $booking->id,
                    'price' => $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Bus Ticket - ' . $booking->schedule->route->name,
                ]
            ],
            'enabled_payments' => [$paymentMethod],
        ];

        // For QRIS, we need to specify additional parameters
        if ($paymentMethod === 'qris') {
            $orderData['enabled_payments'] = ['qris'];
        }

        // Create transaction in Midtrans
        $result = $this->midtransService->createTransaction($orderData);

        if ($result['status'] === 'success') {
            // Save payment history
            $paymentHistory = PaymentHistory::create([
                'booking_id' => $booking->id,
                'transaction_id' => $orderData['transaction_details']['order_id'],
                'payment_method' => $paymentMethod,
                'gross_amount' => $booking->total_price,
                'transaction_status' => 'pending',
                'fraud_status' => 'accept',
                'payment_url' => $result['redirect_url'],
                'metadata' => json_encode([
                    'booking_code' => $booking->booking_code,
                    'schedule_id' => $booking->schedule_id,
                    'user_id' => $booking->user_id,
                ])
            ]);

            // Update booking status
            $booking->update([
                'payment_status' => 'pending',
                'booking_status' => 'pending',
                'midtrans_transaction_id' => $orderData['transaction_details']['order_id']
            ]);

            return [
                'status' => 'success',
                'snap_token' => $result['snap_token'],
                'redirect_url' => $result['redirect_url'],
                'booking' => $booking,
                'payment_history' => $paymentHistory
            ];
        }

        return $result;
    }

    /**
     * Update booking payment status
     * 
     * @param int $bookingId
     * @param string $status
     * @return bool
     */
    public function updateBookingPaymentStatus($bookingId, $status)
    {
        $booking = Booking::find($bookingId);
        
        if (!$booking) {
            return false;
        }

        // Only update if the new status is different from current status
        if ($booking->payment_status !== $status) {
            $booking->update(['payment_status' => $status]);
        }

        return true;
    }
}