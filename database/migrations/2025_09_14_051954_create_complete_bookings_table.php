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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->date('booking_date')->nullable();
            $table->string('booking_code')->unique();
            $table->string('passenger_name');
            $table->string('passenger_phone');
            $table->string('passenger_email');
            $table->string('seat_numbers')->nullable();
            $table->integer('number_of_seats')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('payment_started_at')->nullable();
            $table->timestamps();
            
            // Create an index for better performance when querying by schedule and date
            $table->index(['schedule_id', 'booking_date'], 'idx_schedule_booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};