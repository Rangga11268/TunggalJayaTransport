<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TicketCustomization;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class TicketPdfService
{
    protected $settings;
    
    public function __construct()
    {
        // Get the default ticket customization settings
        $this->settings = TicketCustomization::find(1);
        if (!$this->settings) {
            $this->settings = TicketCustomization::create(TicketCustomization::getDefaultSettings());
        }
    }

    /**
     * Generate a PDF ticket for a booking
     * 
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function generateTicket(Booking $booking)
    {
        // Prepare the data for the view
        $data = [
            'booking' => $booking,
            'settings' => $this->settings,
        ];

        // Load the appropriate view based on layout type
        $view = $this->getViewForLayout($this->settings->layout_type);
        
        // Generate the PDF
        $pdf = Pdf::loadView($view, $data)
            ->setPaper($this->getPaperSize(), $this->settings->layout_type)
            ->setOptions([
                'defaultFont' => $this->settings->font_settings['family'] ?? 'Arial',
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);

        return $pdf;
    }

    /**
     * Get the appropriate view based on the layout type
     * 
     * @param string $layoutType
     * @return string
     */
    protected function getViewForLayout($layoutType)
    {
        if ($layoutType === 'portrait') {
            return 'frontend.booking.ticket-pdf-portrait';
        }
        
        // Default to the enhanced landscape view (the original view has been enhanced)
        return 'frontend.booking.ticket-pdf';
    }

    /**
     * Get the paper size in the correct format for dompdf
     * 
     * @return array|string
     */
    protected function getPaperSize()
    {
        $paperSizes = [
            'A4' => 'A4',
            'A5' => 'A5',
            'letter' => 'letter',
            'legal' => 'legal',
            'A3' => 'A3',
        ];

        $size = $this->settings->paper_size ?? 'A4';
        
        // Default to A4 if the size is not supported
        return $paperSizes[$size] ?? 'A4';
    }

    /**
     * Update the ticket customization settings
     * 
     * @param array $data
     * @return bool
     */
    public function updateSettings(array $data)
    {
        // Validate the inputs to prevent invalid data
        $validated = $this->validateSettings($data);
        
        return $this->settings->update($validated);
    }

    /**
     * Validate settings data
     * 
     * @param array $data
     * @return array
     */
    protected function validateSettings(array $data)
    {
        $validated = [];
        
        // Layout type validation
        if (isset($data['layout_type']) && in_array($data['layout_type'], ['landscape', 'portrait'])) {
            $validated['layout_type'] = $data['layout_type'];
        }
        
        // Paper size validation
        if (isset($data['paper_size']) && in_array($data['paper_size'], ['A4', 'A5', 'letter', 'legal', 'A3'])) {
            $validated['paper_size'] = $data['paper_size'];
        }
        
        // Boolean fields validation
        $booleanFields = [
            'show_company_logo',
            'show_passenger_photo',
            'show_barcode',
            'show_qr_code',
            'show_route_map',
            'show_terms',
            'enable_watermark'
        ];
        
        foreach ($booleanFields as $field) {
            if (isset($data[$field])) {
                $validated[$field] = (bool) $data[$field];
            }
        }
        
        // JSON fields validation
        if (isset($data['color_scheme']) && is_array($data['color_scheme'])) {
            $validated['color_scheme'] = $data['color_scheme'];
        }
        
        if (isset($data['font_settings']) && is_array($data['font_settings'])) {
            $validated['font_settings'] = $data['font_settings'];
        }
        
        if (isset($data['custom_fields']) && is_array($data['custom_fields'])) {
            $validated['custom_fields'] = $data['custom_fields'];
        }
        
        if (isset($data['watermark_text'])) {
            $validated['watermark_text'] = $data['watermark_text'];
        }
        
        return $validated;
    }
}