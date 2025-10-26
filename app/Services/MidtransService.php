<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\PaymentHistory;

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
            return [
                'status' => 'success',
                'data' => $status
            ];
        } catch (\Exception $e) {
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