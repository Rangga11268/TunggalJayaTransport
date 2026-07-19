<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE charter_bookings MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'partial_paid', 'fully_paid', 'failed') DEFAULT 'unpaid'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE charter_bookings MODIFY COLUMN payment_status ENUM('unpaid', 'partial_paid', 'fully_paid', 'failed') DEFAULT 'unpaid'");
    }
};
