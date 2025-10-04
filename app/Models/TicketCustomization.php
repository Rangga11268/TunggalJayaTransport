<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketCustomization extends Model
{
    protected $fillable = [
        'layout_type',
        'paper_size',
        'show_company_logo',
        'show_passenger_photo',
        'show_barcode',
        'show_qr_code',
        'show_route_map',
        'show_terms',
        'color_scheme',
        'font_settings',
        'enable_watermark',
        'watermark_text',
        'custom_fields',
    ];

    protected $casts = [
        'color_scheme' => 'array',
        'font_settings' => 'array',
        'custom_fields' => 'array',
        'show_company_logo' => 'boolean',
        'show_passenger_photo' => 'boolean',
        'show_barcode' => 'boolean',
        'show_qr_code' => 'boolean',
        'show_route_map' => 'boolean',
        'show_terms' => 'boolean',
        'enable_watermark' => 'boolean',
    ];

    public static function getDefaultSettings()
    {
        return [
            'layout_type' => 'landscape',
            'paper_size' => 'A4',
            'show_company_logo' => true,
            'show_passenger_photo' => false,
            'show_barcode' => true,
            'show_qr_code' => true,
            'show_route_map' => false,
            'show_terms' => true,
            'color_scheme' => [
                'primary' => '#1e40af',      // blue-800
                'secondary' => '#3b82f6',   // blue-500
                'accent' => '#10b981',      // emerald-500
                'background' => '#ffffff'   // white
            ],
            'font_settings' => [
                'family' => 'Arial, sans-serif',
                'size' => 12,
                'headings' => 16
            ],
            'enable_watermark' => false,
            'watermark_text' => null,
            'custom_fields' => null,
        ];
    }
}
