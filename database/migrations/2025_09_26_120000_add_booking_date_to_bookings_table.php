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
        Schema::table('bookings', function (Blueprint $table) {
            // Add booking date field to track which specific date instance the booking is for
            $table->date('booking_date')->nullable()->after('schedule_id');
            
            // Create an index for better performance when querying by schedule and date
            $table->index(['schedule_id', 'booking_date'], 'idx_schedule_booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['idx_schedule_booking_date']);
            $table->dropColumn('booking_date');
        });
    }
};