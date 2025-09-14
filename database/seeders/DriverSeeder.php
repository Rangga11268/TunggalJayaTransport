<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Driver;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'name' => 'Budi Santoso',
                'license_number' => 'DL123456789',
                'phone' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'address' => 'Jl. Merdeka No. 123, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Ahmad Riyadi',
                'license_number' => 'DL987654321',
                'phone' => '081298765432',
                'email' => 'ahmad.riyadi@example.com',
                'address' => 'Jl. Sudirman No. 456, Jakarta',
                'status' => 'active',
            ],
            [
                'name' => 'Siti Nurhaliza',
                'license_number' => 'DL456789123',
                'phone' => '081245678912',
                'email' => 'siti.nurhaliza@example.com',
                'address' => 'Jl. Thamrin No. 789, Jakarta',
                'status' => 'active',
            ],
        ];
        
        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
