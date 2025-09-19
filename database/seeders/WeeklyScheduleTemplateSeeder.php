<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeeklyScheduleTemplate;
use App\Models\Bus;
use App\Models\Route;

class WeeklyScheduleTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some buses and routes for templates
        $buses = Bus::limit(3)->get();
        $routes = Route::limit(3)->get();
        
        if ($buses->isEmpty() || $routes->isEmpty()) {
            echo "No buses or routes found. Please run other seeders first.\n";
            return;
        }
        
        // Create sample weekly schedule templates
        $templates = [
            [
                'name' => 'Monday Morning Express',
                'bus_id' => $buses->first()->id,
                'route_id' => $routes->first()->id,
                'departure_time' => '08:00:00',
                'arrival_time' => '12:00:00',
                'price' => 150000,
                'day_of_week' => 1, // Monday
                'status' => 'active',
            ],
            [
                'name' => 'Friday Evening Route',
                'bus_id' => $buses->last()->id,
                'route_id' => $routes->last()->id,
                'departure_time' => '18:00:00',
                'arrival_time' => '22:00:00',
                'price' => 200000,
                'day_of_week' => 5, // Friday
                'status' => 'active',
            ],
            [
                'name' => 'Sunday Family Trip',
                'bus_id' => $buses->first()->id,
                'route_id' => $routes->last()->id,
                'departure_time' => '09:00:00',
                'arrival_time' => '15:00:00',
                'price' => 250000,
                'day_of_week' => 0, // Sunday
                'status' => 'active',
            ],
        ];
        
        foreach ($templates as $templateData) {
            WeeklyScheduleTemplate::create($templateData);
        }
        
        echo "Created " . count($templates) . " weekly schedule templates.\n";
    }
}
