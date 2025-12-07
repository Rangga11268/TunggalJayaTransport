<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.environment') === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Log::info('Midtrans Config Loaded', [
            'is_production' => Config::$isProduction,
            'environment_config' => config('midtrans.environment'),
            'server_key_prefix' => substr(Config::$serverKey, 0, 5) . '...',
            'client_key_prefix' => substr(Config::$clientKey, 0, 5) . '...',
        ]);
    }

    /**
     * Create a new transaction in Midtrans
     * 
     * @param array $orderData
     * @return array
     */
    public function createTransaction($orderData)
    {
        try {
            // Add callbacks configuration if not already present
            if (!isset($orderData['callbacks'])) {
                $bookingId = $orderData['booking_id'] ?? $orderData['custom_field1'] ?? '';
                $callbackUrl = route('frontend.booking.success', ['id' => $bookingId]);

                // Log the callback URL for debugging
                \Log::info('Midtrans callback URL generated', [
                    'booking_id' => $bookingId,
                    'callback_url' => $callbackUrl
                ]);

                $orderData['callbacks'] = [
                    'finish' => $callbackUrl,
                ];
            }

            // Create snap token
            $snapToken = Snap::createTransaction($orderData)->token;

            return [
                'status' => 'success',
                'snap_token' => $snapToken,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v3/redirections/' . $snapToken
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Get transaction status from Midtrans
     * 
     * @param string $orderId
     * @return array
     */

    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);

            // Log the raw status for debugging
            Log::info('Midtrans Transaction Status Raw:', ['order_id' => $orderId, 'status' => (array)$status]);

            // Safely get properties whether it's an object or array
            $transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'];
            $fraudStatus = is_object($status) ? ($status->fraud_status ?? 'accept') : ($status['fraud_status'] ?? 'accept');
            $paymentType = is_object($status) ? ($status->payment_type ?? 'unknown') : ($status['payment_type'] ?? 'unknown');
            $grossAmount = is_object($status) ? ($status->gross_amount ?? 0) : ($status['gross_amount'] ?? 0);

            // Find the payment history record
            $paymentHistory = PaymentHistory::where('transaction_id', $orderId)->first();

            if ($paymentHistory) {
                // Update payment history
                $paymentHistory->update([
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'payment_method' => $paymentType,
                    'gross_amount' => $grossAmount
                ]);

                // Update booking status based on payment result
                $booking = $paymentHistory->booking;
            } else {
                // Fallback: If PaymentHistory missing, try to find booking by order_id (booking_code)
                // Assuming order_id format is typically BOOKINGCODE_TIMESTAMP
                $parts = explode('_', $orderId);
                $bookingCode = $parts[0];
                $booking = \App\Models\Booking::where('booking_code', $bookingCode)->first();
                
                if ($booking) {
                    Log::warning('PaymentHistory not found for order ' . $orderId . ', but Booking found: ' . $booking->id);
                    // Create missing PaymentHistory? Optional, but at least update Booking.
                } else {
                    Log::error('PaymentHistory AND Booking not found for order: ' . $orderId);
                    return [
                        'status' => 'error',
                        'message' => 'Booking not found for this transaction'
                    ];
                }
            }
            
            if ($booking) {
                 if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                    // Payment successful
                    $booking->update([
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed', // Ensure booking is confirmed
                        'midtrans_transaction_id' => $orderId
                    ]);
                } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'expire' || $transactionStatus === 'deny') {
                    // Payment failed/expired
                    $booking->update([
                        'payment_status' => 'failed',
                        'midtrans_transaction_id' => $orderId
                    ]);
                } elseif ($transactionStatus === 'pending') {
                    // Waiting for payment
                    $booking->update([
                        'payment_status' => 'pending',
                        'midtrans_transaction_id' => $orderId
                    ]);
                }
            }

            return [
                'status' => 'success',
                'data' => $status,
                'transaction_status' => $transactionStatus // Expose specifically for frontend
            ];
        } catch (\Exception $e) {
            // Check if it's a 404 (Transaction doesn't exist)
            if (strpos($e->getMessage(), '404') !== false || $e->getCode() == 404) {
                 return [
                    'status' => 'not_found',
                    'message' => 'Transaction has not been created yet (waiting for payment)'
                ];
            }

            Log::error('Midtrans Get Status Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle webhook from Midtrans
     * 
     * @param array $payload
     * @return array
     */
    public function handleWebhook($payload)
    {
        $orderId = $payload['order_id'];
        $transactionStatus = $payload['transaction_status'];
        $fraudStatus = $payload['fraud_status'] ?? 'accept';
        $paymentType = $payload['payment_type'] ?? 'unknown';
        $grossAmount = $payload['gross_amount'] ?? 0;

        // Find the payment history record
        $paymentHistory = PaymentHistory::where('transaction_id', $orderId)->first();

        if (!$paymentHistory) {
            return [
                'status' => 'error',
                'message' => 'Payment history not found'
            ];
        }

        // Update payment history
        $paymentHistory->update([
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_method' => $paymentType,
            'gross_amount' => $grossAmount
        ]);

        // Update booking status based on payment result
        $booking = $paymentHistory->booking;
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            // Payment successful
            $booking->update([
                'payment_status' => 'paid',
                'midtrans_transaction_id' => $orderId
            ]);
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'expire') {
            // Payment failed/expired
            $booking->update([
                'payment_status' => 'failed',
                'midtrans_transaction_id' => $orderId
            ]);
        } elseif ($transactionStatus === 'pending') {
            // Waiting for payment
            $booking->update([
                'payment_status' => 'pending',
                'midtrans_transaction_id' => $orderId
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Webhook handled successfully'
        ];
    }

    /**
     * Validate notification from Midtrans
     * 
     * @param array $notification
     * @return bool
     */
    public function validateNotification($notification)
    {
        try {
            $orderId = $notification['order_id'];
            $result = Transaction::status($orderId);

            // Verify the status matches
            if ($result->transaction_status === $notification['transaction_status']) {
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Midtrans validation error: ' . $e->getMessage());
        }

        return false;
    }
}
