<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charter_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('charter_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_bus_id')->nullable()->constrained('buses')->nullOnDelete();
            
            // Requirements
            $table->string('bus_type_requested')->default('Big Bus');
            $table->date('pickup_date');
            $table->date('return_date');
            $table->string('pickup_location');
            $table->string('destination');
            $table->text('notes')->nullable();
            
            // Financials
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('down_payment', 12, 2)->default(0);
            
            // Midtrans IDs
            $table->string('dp_midtrans_id')->nullable();
            $table->string('final_midtrans_id')->nullable();
            
            // Statuses
            $table->enum('payment_status', ['unpaid', 'partial_paid', 'fully_paid', 'failed'])->default('unpaid');
            $table->enum('status', ['pending', 'quoted', 'confirmed', 'completed', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charter_bookings');
    }
};
