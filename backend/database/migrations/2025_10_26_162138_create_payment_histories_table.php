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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id');
            $table->string('payment_method');
            $table->decimal('gross_amount', 10, 2);
            $table->string('transaction_status');
            $table->string('fraud_status');
            $table->text('payment_url')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index('transaction_id');
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
