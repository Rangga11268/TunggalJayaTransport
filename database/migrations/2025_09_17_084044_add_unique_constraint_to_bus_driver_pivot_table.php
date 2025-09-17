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
        Schema::table('bus_driver', function (Blueprint $table) {
            // Menambahkan constraint unik untuk memastikan satu driver hanya bisa diassign ke satu bus
            $table->unique(['driver_id'], 'unique_driver_per_bus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus_driver', function (Blueprint $table) {
            $table->dropUnique('unique_driver_per_bus');
        });
    }
};
