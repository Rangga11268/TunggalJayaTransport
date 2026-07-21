<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('charter_booking_bus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('charter_booking_id')->constrained('charter_bookings')->onDelete('cascade');
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->timestamps();
        });

        // Migrate existing assigned_bus_id
        $bookings = DB::table('charter_bookings')->whereNotNull('assigned_bus_id')->get();
        foreach ($bookings as $booking) {
            DB::table('charter_booking_bus')->insert([
                'charter_booking_id' => $booking->id,
                'bus_id' => $booking->assigned_bus_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charter_booking_bus');
    }
};
