<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CharterBooking;
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
                'email' => trim($booking->passenger_email), // Ensure no trailing spaces
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
        $orderData['enabled_payments'] = $this->getEnabledPayments($paymentMethod);

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

    public function processCharterPayment($charterId, $paymentMethod = 'gopay', $type = 'dp')
    {
        $charter = CharterBooking::with('user', 'assignedBus')->findOrFail($charterId);

        if ($type === 'dp' && ($charter->payment_status === 'paid' || $charter->payment_status === 'dp_paid' || $charter->payment_status === 'partial')) {
            return [
                'status' => 'error',
                'message' => 'DP has already been paid'
            ];
        }

        if ($type === 'pelunasan' && $charter->payment_status === 'paid') {
            return [
                'status' => 'error',
                'message' => 'Booking has already been fully paid'
            ];
        }

        if ($charter->down_payment <= 0 && $type === 'dp') {
            return [
                'status' => 'error',
                'message' => 'DP amount not set by admin'
            ];
        }

        $amount = 0;
        $orderIdPrefix = '';
        $itemName = '';

        if ($type === 'dp') {
            $amount = $charter->down_payment;
            $orderIdPrefix = '_DP_';
            $itemName = 'DP Sewa Bus - ';
        } elseif ($type === 'pelunasan') {
            $amount = $charter->total_price - $charter->down_payment;
            $orderIdPrefix = '_PELUNASAN_';
            $itemName = 'Pelunasan Sewa Bus - ';
        } elseif ($type === 'full') {
            $amount = $charter->total_price;
            $orderIdPrefix = '_FULL_';
            $itemName = 'Bayar Penuh Sewa Bus - ';
        }

        $phone = preg_replace('/[^0-9]/', '', $charter->user->phone ?? '08000000000');
        $phone = substr($phone, 0, 19);

        $orderData = [
            'transaction_details' => [
                'order_id' => $charter->charter_code . $orderIdPrefix . time(),
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => substr($charter->user->name, 0, 50),
                'email' => trim($charter->user->email),
                'phone' => $phone,
            ],
            'item_details' => [
                [
                    'id' => strtoupper($type) . '-' . substr((string)$charter->id, 0, 46),
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => $itemName . substr($charter->destination, 0, 30),
                ]
            ],
            'custom_field1' => (string)$charter->id,
            'custom_field2' => 'charter_' . $type
        ];

        $orderData['enabled_payments'] = $this->getEnabledPayments($paymentMethod);
        
        $result = $this->midtransService->createTransaction($orderData);

        if ($result['status'] === 'success') {
            $paymentHistory = PaymentHistory::create([
                'charter_booking_id' => $charter->id,
                'transaction_id' => $orderData['transaction_details']['order_id'],
                'payment_method' => $paymentMethod,
                'gross_amount' => $amount,
                'transaction_status' => 'pending',
                'fraud_status' => 'accept',
                'payment_url' => $result['redirect_url'],
                'metadata' => json_encode([
                    'charter_code' => $charter->charter_code,
                    'user_id' => $charter->user_id,
                    'type' => 'charter_' . $type
                ])
            ]);

            $updateData = ['payment_status' => 'pending'];
            if ($type === 'dp') {
                $updateData['dp_midtrans_id'] = $orderData['transaction_details']['order_id'];
            } else {
                $updateData['final_midtrans_id'] = $orderData['transaction_details']['order_id'];
            }
            $charter->update($updateData);

            return [
                'status' => 'success',
                'snap_token' => $result['snap_token'],
                'redirect_url' => $result['redirect_url'],
                'booking' => $charter,
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
    /**
     * Ambil metode pembayaran yang aktif
     * 
     * @param string $paymentMethod
     * @return array|null
     */
    private function getEnabledPayments($paymentMethod)
    {
        if (!$paymentMethod || $paymentMethod === 'all') {
            return null; // Biarin default dari Midtrans
        }

        if ($paymentMethod === 'e_wallet') {
            return ['gopay', 'shopeepay', 'qris', 'dana', 'linkaja'];
        }

        if ($paymentMethod === 'bank_transfer') {
            return ['bca_va', 'bni_va', 'bri_va', 'permata_va'];
        }

        if (in_array($paymentMethod, ['gopay', 'shopeepay', 'dana', 'linkaja', 'qris'])) {
             // Kalo pilih e-wallet spefisik, kasih opsi lain juga buat jaga-jaga
             return array_unique([$paymentMethod, 'gopay', 'qris', 'other_qris']);
        }

        return [$paymentMethod];
    }
}
