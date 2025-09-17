<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Handle any duplicate employee_id values
        $duplicateEmployeeIds = DB::table('drivers')
            ->select('employee_id')
            ->groupBy('employee_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('employee_id');
            
        foreach ($duplicateEmployeeIds as $employeeId) {
            // For duplicate employee_id, append a suffix to make them unique
            $drivers = DB::table('drivers')
                ->where('employee_id', $employeeId)
                ->orderBy('id')
                ->get();
                
            // Skip the first one (keep as is), update the rest
            for ($i = 1; $i < count($drivers); $i++) {
                DB::table('drivers')
                    ->where('id', $drivers[$i]->id)
                    ->update([
                        'employee_id' => $employeeId . '_' . $i
                    ]);
            }
        }
        
        // Handle any duplicate license_number values
        $duplicateLicenseNumbers = DB::table('drivers')
            ->select('license_number')
            ->groupBy('license_number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('license_number');
            
        foreach ($duplicateLicenseNumbers as $licenseNumber) {
            // For duplicate license_number, append a suffix to make them unique
            $drivers = DB::table('drivers')
                ->where('license_number', $licenseNumber)
                ->orderBy('id')
                ->get();
                
            // Skip the first one (keep as is), update the rest
            for ($i = 1; $i < count($drivers); $i++) {
                DB::table('drivers')
                    ->where('id', $drivers[$i]->id)
                    ->update([
                        'license_number' => $licenseNumber . '_' . $i
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to do here
    }
};
