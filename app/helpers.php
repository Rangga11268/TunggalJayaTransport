<?php

if (!function_exists('generateBookingBarcode')) {
    /**
     * Generate a barcode for a booking
     *
     * @param string $bookingCode
     * @return string
     */
    function generateBookingBarcode($bookingCode)
    {
        return base64_encode($bookingCode);
    }
}

if (!function_exists('formatCurrency')) {
    /**
     * Format currency for IDR
     *
     * @param float $amount
     * @return string
     */
    function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatDate')) {
    /**
     * Format date for Indonesian format
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    function formatDate($date, $format = 'd F Y')
    {
        return \Carbon\Carbon::parse($date)->isoFormat($format);
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time
     *
     * @param string $time
     * @return string
     */
    function formatTime($time)
    {
        return \Carbon\Carbon::parse($time)->format('H:i');
    }
}

if (!function_exists('getBookingStatusBadge')) {
    /**
     * Get badge class for booking status
     *
     * @param string $status
     * @return string
     */
    function getBookingStatusBadge($status)
    {
        $badges = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'cancelled' => 'bg-danger',
            'completed' => 'bg-info',
        ];

        return $badges[$status] ?? 'bg-secondary';
    }
}
