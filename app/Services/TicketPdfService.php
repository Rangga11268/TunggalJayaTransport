<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketPdfService
{
    public function generateTicketPdf(Booking $booking)
    {
        // Define default settings for the ticket PDF
        $settings = (object) [
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'font_settings' => [
                'family' => 'Arial, sans-serif',
                'size' => 12,
                'headings' => 16
            ],
            'color_scheme' => [
                'primary' => '#1e40af',
                'secondary' => '#3b82f6',
                'accent' => '#10b981',
                'background' => '#ffffff'
            ],
            'enable_watermark' => false, // We're not using watermark in the new design
            'watermark_text' => 'TUNGGAL JAYA',
            'show_company_logo' => true,
            'show_barcode' => false, // We're using QR code in the new design
            'show_qr_code' => true
        ];

        // Generate PDF ticket using the view
        $pdf = Pdf::loadView('frontend.booking.ticket-pdf', compact('booking', 'settings'));

        return $pdf;
    }
}