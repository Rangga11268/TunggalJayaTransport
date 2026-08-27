<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('otp_codes', function (Blueprint $table) {
            // Generic identifier: can be phone number or email address
            $table->string('identifier')->nullable()->after('phone');
            // Method used: 'whatsapp' or 'email'
            $table->string('method')->default('whatsapp')->after('identifier');
            // Track wrong attempt count (invalidate after MAX_ATTEMPTS)
            $table->unsignedTinyInteger('attempts')->default(0)->after('used');
            // IP address for audit trail
            $table->string('ip_address', 45)->nullable()->after('attempts');

            $table->index('identifier');
        });
    }

    public function down(): void
    {
        Schema::table('otp_codes', function (Blueprint $table) {
            $table->dropIndex(['identifier']);
            $table->dropColumn(['identifier', 'method', 'attempts', 'ip_address']);
        });
    }
};
