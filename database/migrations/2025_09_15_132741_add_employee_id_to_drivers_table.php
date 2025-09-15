<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the employee_id column already exists
        if (!Schema::hasColumn('drivers', 'employee_id')) {
            Schema::table('drivers', function (Blueprint $table) {
                $table->string('employee_id')->nullable()->after('name');
            });
        }
        
        // Update existing records with a default employee_id if they are null or empty
        DB::table('drivers')->where(function($query) {
            $query->whereNull('employee_id')
                  ->orWhere('employee_id', '=', '');
        })->update([
            'employee_id' => DB::raw("CONCAT('DRV', id)")
        ]);
        
        // Make sure the column is non-nullable and unique
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'employee_id')) {
                $table->string('employee_id', 255)->nullable(false)->unique()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            if (Schema::hasColumn('drivers', 'employee_id')) {
                $table->dropUnique(['employee_id']);
            }
        });
    }
};
