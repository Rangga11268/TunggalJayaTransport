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
        Schema::table('routes', function (Blueprint $table) {
            $table->decimal('origin_lat', 10, 8)->nullable()->after('description');
            $table->decimal('origin_lng', 11, 8)->nullable()->after('origin_lat');
            $table->decimal('destination_lat', 10, 8)->nullable()->after('origin_lng');
            $table->decimal('destination_lng', 11, 8)->nullable()->after('destination_lat');
            $table->json('waypoints')->nullable()->after('destination_lng');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn(['origin_lat', 'origin_lng', 'destination_lat', 'destination_lng', 'waypoints']);
        });
    }
};
