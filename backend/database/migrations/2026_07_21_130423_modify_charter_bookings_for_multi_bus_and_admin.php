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
            $table->json('bus_requests')->nullable()->after('bus_type_requested');
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->string('customer_email')->nullable()->after('customer_phone');
            
            // Modify user_id to be nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charter_bookings', function (Blueprint $table) {
            $table->dropColumn(['bus_requests', 'customer_name', 'customer_phone', 'customer_email']);
            // Reverting user_id to not null might cause errors if there are nulls, so we skip it or leave it nullable
        });
    }
};
