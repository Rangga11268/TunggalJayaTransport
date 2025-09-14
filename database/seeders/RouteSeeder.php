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
                'name' => 'Jakarta - Bandung',
                'origin' => 'Jakarta',
                'destination' => 'Bandung',
                'distance' => 180.5,
                'duration' => 240,
                'description' => 'Popular route connecting the capital city with the fashion capital of Indonesia',
            ],
            [
                'name' => 'Surabaya - Malang',
                'origin' => 'Surabaya',
                'destination' => 'Malang',
                'distance' => 95.2,
                'duration' => 150,
                'description' => 'Scenic route through East Java',
            ],
            [
                'name' => 'Yogyakarta - Solo',
                'origin' => 'Yogyakarta',
                'destination' => 'Solo',
                'distance' => 60.8,
                'duration' => 90,
                'description' => 'Short journey between two cultural cities',
            ],
        ];
        
        foreach ($routes as $route) {
            Route::create($route);
        }
    }
}
