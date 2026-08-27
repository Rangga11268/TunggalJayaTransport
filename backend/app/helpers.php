<?php

if (!function_exists('generateBookingBarcode')) {
    
    function generateBookingBarcode($bookingCode)
    {
        return base64_encode($bookingCode);
    }
}

if (!function_exists('formatCurrency')) {
    
    function formatCurrency($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatDate')) {
    
    function formatDate($date, $format = 'd F Y')
    {
        return \Carbon\Carbon::parse($date)->isoFormat($format);
    }
}

if (!function_exists('formatTime')) {
    
    function formatTime($time)
    {
        return \Carbon\Carbon::parse($time)->format('H:i');
    }
}

if (!function_exists('getBookingStatusBadge')) {
    
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
