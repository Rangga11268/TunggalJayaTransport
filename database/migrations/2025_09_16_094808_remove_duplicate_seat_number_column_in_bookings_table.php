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
            // Remove seat_number column if it exists (keep only seat_numbers)
            if (Schema::hasColumn('bookings', 'seat_number')) {
                $table->dropColumn('seat_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // We won't reverse this migration to avoid data loss
        });
    }
};
