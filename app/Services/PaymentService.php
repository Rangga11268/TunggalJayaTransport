<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public $midtransService;

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

        // Sanitize phone number (digits only, max 20)
        $phone = preg_replace('/[^0-9]/', '', $booking->passenger_phone);
        $phone = substr($phone, 0, 19);

        // Sanitize item name (max 50 chars)
        $itemName = 'Bus Ticket - ' . $booking->schedule->route->name;
        if (strlen($itemName) > 50) {
            $itemName = substr($itemName, 0, 47) . '...';
        }

        // Prepare order details for Midtrans
        $orderData = [
            'transaction_details' => [
                'order_id' => $booking->booking_code . '_' . time(), // Ensure uniqueness
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => substr($booking->passenger_name, 0, 50), // Safe limit
                'email' => $booking->passenger_email,
                'phone' => $phone,
            ],
            'item_details' => [
                [
                    'id' => substr((string)$booking->id, 0, 50),
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => $itemName,
                ]
            ],
            'custom_field1' => (string)$booking->id,
        ];

        // Handle enabled payments
        if ($paymentMethod && $paymentMethod !== 'all') {
            if ($paymentMethod === 'e_wallet') {
                // If generic e_wallet, allow common ones or don't restrict
                $orderData['enabled_payments'] = ['gopay', 'shopeepay', 'qris', 'dana', 'linkaja'];
            } elseif ($paymentMethod === 'bank_transfer') {
                $orderData['enabled_payments'] = ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
            } elseif (in_array($paymentMethod, ['gopay', 'shopeepay', 'dana', 'linkaja', 'qris'])) {
                 // For specific e-wallets, allow alternatives/fallback to ensure Snap opens successfully
                 // often 'dana' requires 'gopay' or 'qris' to be active if direct 'dana' isn't configured
                 $orderData['enabled_payments'] = array_unique([$paymentMethod, 'gopay', 'qris', 'other_qris']);
            } else {
                $orderData['enabled_payments'] = [$paymentMethod];
            }
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
