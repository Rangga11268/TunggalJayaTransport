<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Air Conditioning',
                'icon' => 'fas fa-wind',
                'description' => 'Climate control system for comfortable travel',
            ],
            [
                'name' => 'Wi-Fi',
                'icon' => 'fas fa-wifi',
                'description' => 'Free wireless internet access throughout the journey',
            ],
            [
                'name' => 'Entertainment System',
                'icon' => 'fas fa-tv',
                'description' => 'Onboard entertainment with movies and music',
            ],
            [
                'name' => 'Restroom',
                'icon' => 'fas fa-toilet',
                'description' => 'Clean restroom facilities available',
            ],
            [
                'name' => 'Reclining Seats',
                'icon' => 'fas fa-couch',
                'description' => 'Comfortable seats with adjustable recline',
            ],
        ];
        
        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
