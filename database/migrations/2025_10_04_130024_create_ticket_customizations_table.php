<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ticket_customizations', function (Blueprint $table) {
            $table->id();
            $table->string('layout_type', 20)->default('landscape'); // landscape or portrait
            $table->string('paper_size', 10)->default('A4'); // A4, A5, letter, etc.
            $table->boolean('show_company_logo')->default(true);
            $table->boolean('show_passenger_photo')->default(false);
            $table->boolean('show_barcode')->default(true);
            $table->boolean('show_qr_code')->default(true);
            $table->boolean('show_route_map')->default(false);
            $table->boolean('show_terms')->default(true);
            $table->json('color_scheme'); // No default for JSON
            $table->json('font_settings'); // No default for JSON
            $table->boolean('enable_watermark')->default(false);
            $table->string('watermark_text')->nullable();
            $table->json('custom_fields')->nullable(); // For additional fields
            $table->timestamps();
        });
        
        // Insert default settings
        \DB::table('ticket_customizations')->insert([
            'id' => 1, // Default settings will have ID 1
            'layout_type' => 'landscape',
            'paper_size' => 'A4',
            'show_company_logo' => true,
            'show_passenger_photo' => false,
            'show_barcode' => true,
            'show_qr_code' => true,
            'show_route_map' => false,
            'show_terms' => true,
            'color_scheme' => json_encode([
                'primary' => '#1e40af',
                'secondary' => '#3b82f6',
                'accent' => '#10b981',
                'background' => '#ffffff'
            ]),
            'font_settings' => json_encode([
                'family' => 'Arial, sans-serif',
                'size' => 12,
                'headings' => 16
            ]),
            'enable_watermark' => false,
            'watermark_text' => null,
            'custom_fields' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_customizations');
    }
};
