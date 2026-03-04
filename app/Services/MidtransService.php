<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use App\Models\PaymentHistory;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Setting dulu config Midtrans-nya
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
            // Tambah config callback kalo belum ada
            if (!isset($orderData['callbacks'])) {
                $bookingId = $orderData['booking_id'] ?? $orderData['custom_field1'] ?? '';
                $callbackUrl = route('frontend.booking.success', ['id' => $bookingId]);

                // Log URL callback buat debug nanti
                \Illuminate\Support\Facades\Log::info('Midtrans callback URL generated', [
                    'booking_id' => $bookingId,
                    'callback_url' => $callbackUrl
                ]);

                $orderData['callbacks'] = [
                    'finish' => $callbackUrl,
                ];
            }

            // Bikin token Snap biar bisa pop-up
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

            // Log status mentahannya dulu
            Log::info('Midtrans Transaction Status Raw:', ['order_id' => $orderId, 'status' => (array)$status]);

            // Ambil property-nya pelan-pelan biar ga error (kadang object kadang array)
            $transactionStatus = is_object($status) ? $status->transaction_status : $status['transaction_status'];
            $fraudStatus = is_object($status) ? ($status->fraud_status ?? 'accept') : ($status['fraud_status'] ?? 'accept');
            $paymentType = is_object($status) ? ($status->payment_type ?? 'unknown') : ($status['payment_type'] ?? 'unknown');
            $grossAmount = is_object($status) ? ($status->gross_amount ?? 0) : ($status['gross_amount'] ?? 0);

            // Cari history pembayarannya
            $paymentHistory = PaymentHistory::where('transaction_id', $orderId)->first();

            if ($paymentHistory) {
                // Update history pembayaran
                $paymentHistory->update([
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $fraudStatus,
                    'payment_method' => $paymentType,
                    'gross_amount' => $grossAmount
                ]);

                // Update status booking sesuai hasil bayar
                $booking = $paymentHistory->booking;
            } else {
                // Jaga-jaga: Kalo history ga ada, coba cari booking pake order_id (booking_code)
                // Asumsi format order_id biasanya KODBOOKING_TIMESTAMP
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
                    // Pembayaran sukses mantap
                    $booking->update([
                        'payment_status' => 'paid',
                        'booking_status' => 'confirmed', // Ensure booking is confirmed
                        'midtrans_transaction_id' => $orderId
                    ]);

                    // Kirim notifikasi WhatsApp e-ticket
                    try {
                        $waService = app(WhatsAppNotificationService::class);
                        $waService->sendBookingConfirmation($booking);
                    } catch (\Exception $e) {
                        Log::error('Failed to send WA notification: ' . $e->getMessage());
                    }
                } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'expire' || $transactionStatus === 'deny') {
                    // Pembayaran gagal atau kadaluarsa
                    $booking->update([
                        'payment_status' => 'failed',
                        'midtrans_transaction_id' => $orderId
                    ]);
                } elseif ($transactionStatus === 'pending') {
                    // Masih nunggu dibayar
                    $booking->update([
                        'payment_status' => 'pending',
                        'midtrans_transaction_id' => $orderId
                    ]);
                }
            }

            return [
                'status' => 'success',
                'data' => $status,
                'transaction_status' => $transactionStatus // Kasih tau statusnya buat frontend
            ];
        } catch (\Exception $e) {
            // Cek kalo 404 (Transaksi ga ada)
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

        // Cari history pembayarannya
        $paymentHistory = PaymentHistory::where('transaction_id', $orderId)->first();

        if (!$paymentHistory) {
            return [
                'status' => 'error',
                'message' => 'Payment history not found'
            ];
        }

        // Update history pembayaran
        $paymentHistory->update([
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_method' => $paymentType,
            'gross_amount' => $grossAmount
        ]);

        // Update status booking sesuai hasil bayar
        $booking = $paymentHistory->booking;
        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            // Pembayaran sukses
            $booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
                'midtrans_transaction_id' => $orderId
            ]);

            // Increment promo code usage count ONLY after payment is settled
            // This prevents abuse (user spam apply promo then cancel)
            if ($booking->promo_code_id) {
                $booking->promoCode()->increment('usage_count');
            }

            // Kirim notifikasi WhatsApp e-ticket
            try {
                $waService = app(WhatsAppNotificationService::class);
                $waService->sendBookingConfirmation($booking);
            } catch (\Exception $e) {
                Log::error('Failed to send WA notification: ' . $e->getMessage());
            }
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'expire') {
            // Pembayaran gagal/kadaluarsa
            $booking->update([
                'payment_status' => 'failed',
                'midtrans_transaction_id' => $orderId
            ]);
        } elseif ($transactionStatus === 'pending') {
            // Masih nunggu dibayar
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
     * Validate notification from Midtrans using SHA512 signature verification
     * 
     * @param array $notification
     * @return bool
     */
    public function validateNotification($notification)
    {
        try {
            // SHA512 signature verification logic
            // signature_key = hash("sha512", order_id + status_code + gross_amount + server_key)
            $orderId = $notification['order_id'];
            $statusCode = $notification['status_code'];
            $grossAmount = $notification['gross_amount'];
            $serverKey = config('midtrans.server_key');
            $signatureKey = $notification['signature_key'];

            $calculatedSignature = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signatureKey !== $calculatedSignature) {
                \Illuminate\Support\Facades\Log::error('Midtrans signature key mismatch!', [
                    'order_id' => $orderId,
                    'provided_signature' => $signatureKey,
                    'calculated_signature' => $calculatedSignature
                ]);
                return false;
            }

            // Optional: Backup verification via Midtrans Transaction Status API
            // to ensure the status hasn't been tampered with
            $result = (object) Transaction::status($orderId);

            if ($result->transaction_status === $notification['transaction_status']) {
                return true;
            }

            \Illuminate\Support\Facades\Log::error('Midtrans status mismatch between payload and API query', [
                'order_id' => $orderId,
                'payload_status' => $notification['transaction_status'],
                'api_status' => $result->transaction_status
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans validation error: ' . $e->getMessage());
        }

        return false;
    }
}
