<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE charter_bookings MODIFY COLUMN payment_status ENUM('unpaid', 'pending', 'dp_paid', 'paid', 'partial_paid', 'fully_paid', 'failed') DEFAULT 'unpaid'");
        } else {
            Schema::table('charter_bookings', function (Blueprint $table) {
                $table->string('payment_status')->default('unpaid')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE charter_bookings MODIFY COLUMN payment_status ENUM('unpaid', 'partial_paid', 'fully_paid', 'failed') DEFAULT 'unpaid'");
        }
    }
};
