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
        Schema::create('bus_conductor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('conductor_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint to ensure a conductor is assigned to only one bus
            $table->unique(['conductor_id'], 'unique_conductor_per_bus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_conductor');
    }
};