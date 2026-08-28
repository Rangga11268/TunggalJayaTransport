<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds based on real Tunggal Jaya routes.
     */
    public function run(): void
    {
        $routes = [
            [
                'name' => 'Kuningan - Jakarta (Kalideres)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Kalideres)',
                'distance' => 250.0,
                'duration' => 360,
                'description' => 'Rute resmi via Luragung, Oleced, Cirendang, Plumbon, Pesing, Jelambar, Kalideres.',
            ],
            [
                'name' => 'Kuningan - Jakarta (Roxy / Jembatan 5)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Roxy)',
                'distance' => 240.0,
                'duration' => 330,
                'description' => 'Rute resmi via Ciawi, Oleced, Cirendang, Ciperna, Duri Selatan, Roxy, Jembatan 5, Season City.',
            ],
            [
                'name' => 'Kuningan - Rangkasbitung (Banten)',
                'origin' => 'Kuningan',
                'destination' => 'Rangkasbitung',
                'distance' => 280.0,
                'duration' => 420,
                'description' => 'Rute via Luragung, Cirendang, Ciperna, Cikande, Balaraja, Bitung, Tol JORR.',
            ],
            [
                'name' => 'Cirebon - Jakarta (Pangkalan Asem)',
                'origin' => 'Cirebon',
                'destination' => 'Jakarta (Pang. Asem)',
                'distance' => 220.0,
                'duration' => 300,
                'description' => 'Rute via Ciledug, Pabuaran, Kr. Sembung, Jatibening, Cikarang, Pang. Asem.',
            ],
            [
                'name' => 'Kuningan - Jakarta (Pulogebang)',
                'origin' => 'Kuningan',
                'destination' => 'Jakarta (Pulogebang)',
                'distance' => 210.0,
                'duration' => 240,
                'description' => 'Rute via Cirendang, Cilimus, Ciperna, Tol Cipali, Terminal Pulogebang.',
            ],
            [
                'name' => 'Jakarta - Kuningan (Pulogebang - Cirendang)',
                'origin' => 'Jakarta',
                'destination' => 'Kuningan',
                'distance' => 210.0,
                'duration' => 240,
                'description' => 'Rute balik Jakarta menuju Kuningan via Tol Cipali.',
            ],
        ];

        foreach ($routes as $route) {
            Route::updateOrCreate(
                ['name' => $route['name']],
                $route
            );
        }
    }
}
