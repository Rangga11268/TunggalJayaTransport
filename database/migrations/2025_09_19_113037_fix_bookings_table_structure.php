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
            // Add the missing payment_started_at column
            $table->timestamp('payment_started_at')->nullable()->after('booking_status');
            
            // Add number_of_seats column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'number_of_seats')) {
                $table->integer('number_of_seats')->default(1)->after('seat_numbers');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_started_at');
            $table->dropColumn('number_of_seats');
        });
    }
};
