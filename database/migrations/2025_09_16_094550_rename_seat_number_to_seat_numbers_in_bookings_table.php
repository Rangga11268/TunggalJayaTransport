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
            // If seat_number column exists, rename it to seat_numbers
            if (Schema::hasColumn('bookings', 'seat_number') && !Schema::hasColumn('bookings', 'seat_numbers')) {
                $table->renameColumn('seat_number', 'seat_numbers');
            }
            
            // If seat_numbers column exists, make sure it's nullable
            if (Schema::hasColumn('bookings', 'seat_numbers')) {
                $table->string('seat_numbers')->nullable()->change();
            }
            
            // If neither column exists, create seat_numbers
            if (!Schema::hasColumn('bookings', 'seat_number') && !Schema::hasColumn('bookings', 'seat_numbers')) {
                $table->string('seat_numbers')->nullable();
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
