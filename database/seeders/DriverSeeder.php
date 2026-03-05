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
                'name' => 'Pak Haji Agus',
                'employee_id' => 'DRV001',
                'license_number' => 'DL777888999',
                'phone' => '081234567891',
                'email' => 'agus@tunggaljaya.com',
                'address' => 'Jl. Siliwangi No. 10, Kuningan',
                'status' => 'active',
            ],
            [
                'name' => 'Bang Jago',
                'employee_id' => 'DRV002',
                'license_number' => 'DL111222333',
                'phone' => '081298765433',
                'email' => 'jago@tunggaljaya.com',
                'address' => 'Jl. Veteran No. 22, Kuningan',
                'status' => 'active',
            ],
            [
                'name' => 'Mas Reno',
                'employee_id' => 'DRV003',
                'license_number' => 'DL444555666',
                'phone' => '081245678913',
                'email' => 'reno@tunggaljaya.com',
                'address' => 'Jl. Sudirman No. 33, Kuningan',
                'status' => 'active',
            ],
        ];
        
        foreach ($drivers as $driver) {
            Driver::create($driver);
        }
    }
}
