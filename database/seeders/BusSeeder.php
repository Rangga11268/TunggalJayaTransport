<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bus;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buses = [
            [
                'name' => 'Executive Class Bus 1',
                'plate_number' => 'B 1234 XYZ',
                'bus_type' => 'Executive',
                'capacity' => 40,
                'description' => 'Luxury bus with extra legroom and premium amenities',
                'status' => 'active',
            ],
            [
                'name' => 'Business Class Bus 1',
                'plate_number' => 'B 5678 ABC',
                'bus_type' => 'Business',
                'capacity' => 35,
                'description' => 'Comfortable bus with reclining seats and entertainment',
                'status' => 'active',
            ],
            [
                'name' => 'Economy Class Bus 1',
                'plate_number' => 'B 9012 DEF',
                'bus_type' => 'Economy',
                'capacity' => 30,
                'description' => 'Affordable travel with basic comfort',
                'status' => 'active',
            ],
        ];
        
        foreach ($buses as $bus) {
            Bus::create($bus);
        }
    }
}
