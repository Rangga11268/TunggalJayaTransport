<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            [
                'name' => 'Kuningan - Jakarta (Pulogebang)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Pulogebang)',
                'distance' => 210.0,
                'duration' => 240,
                'description' => 'Rute Kuningan menuju Terminal Pulogebang via Tol Cipali.',
            ],
            [
                'name' => 'Kuningan - Jakarta (Lebak Bulus)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Lebak Bulus)',
                'distance' => 230.0,
                'duration' => 300,
                'description' => 'Rute Kuningan menuju Lebak Bulus via Tol Cipali dan JORR.',
            ],
            [
                'name' => 'Kuningan - Jakarta (Kalideres)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Kalideres)',
                'distance' => 250.0,
                'duration' => 360,
                'description' => 'Rute Kuningan menuju Terminal Kalideres via Tol Cipali.',
            ],
            [
                'name' => 'Kuningan - Rangkasbitung',
                'origin' => 'Kuningan',
                'destination' => 'Rangkasbitung',
                'distance' => 280.0,
                'duration' => 420,
                'description' => 'Rute antar provinsi Kuningan menuju Rangkasbitung.',
            ],
            [
                'name' => 'Jakarta - Kuningan',
                'origin' => 'Jakarta',
                'destination' => 'Kuningan',
                'distance' => 220.0,
                'duration' => 240,
                'description' => 'Rute balik dari Jakarta menuju Kuningan.',
            ],
        ];

        foreach ($routes as $route) {
            Route::create($route);
        }
    }
}
