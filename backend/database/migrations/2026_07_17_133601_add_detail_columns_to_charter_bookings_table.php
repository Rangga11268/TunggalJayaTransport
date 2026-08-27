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
        Schema::table('charter_bookings', function (Blueprint $table) {
            $table->decimal('pickup_lat', 10, 8)->nullable()->after('pickup_location');
            $table->decimal('pickup_lng', 11, 8)->nullable()->after('pickup_lat');
            $table->text('pickup_address')->nullable()->after('pickup_lng');
            $table->decimal('destination_lat', 10, 8)->nullable()->after('destination');
            $table->decimal('destination_lng', 11, 8)->nullable()->after('destination_lat');
            $table->text('destination_address')->nullable()->after('destination_lng');
            $table->integer('passenger_count')->nullable()->after('bus_type_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charter_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_lat',
                'pickup_lng',
                'pickup_address',
                'destination_lat',
                'destination_lng',
                'destination_address',
                'passenger_count'
            ]);
        });
    }
};
