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
        Schema::table('bus_conductor', function (Blueprint $table) {
            // Menambahkan constraint unik untuk memastikan satu conductor hanya bisa diassign ke satu bus
            $table->unique(['conductor_id'], 'unique_conductor_per_bus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus_conductor', function (Blueprint $table) {
            $table->dropUnique('unique_conductor_per_bus');
        });
    }
};
