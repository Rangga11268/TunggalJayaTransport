<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;
use Carbon\Carbon;

class DailyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some buses and routes for schedules
        $buses = Bus::limit(2)->get();
        $routes = Route::limit(2)->get();
        
        if ($buses->isEmpty() || $routes->isEmpty()) {
            echo "No buses or routes found. Please run other seeders first.\n";
            return;
        }
        
        // Create sample daily recurring schedules
        $schedules = [
            [
                'bus_id' => $buses->first()->id,
                'route_id' => $routes->first()->id,
                'departure_time' => '08:00:00',
                'arrival_time' => '12:00:00',
                'price' => 150000,
                'status' => 'active',
                'is_weekly' => false,
                'is_daily' => true,
                'day_of_week' => null,
            ],
            [
                'bus_id' => $buses->last()->id,
                'route_id' => $routes->last()->id,
                'departure_time' => '18:00:00',
                'arrival_time' => '22:00:00',
                'price' => 200000,
                'status' => 'active',
                'is_weekly' => false,
                'is_daily' => true,
                'day_of_week' => null,
            ],
        ];
        
        foreach ($schedules as $scheduleData) {
            // For daily recurring schedules, we store just the time without a specific date
            $baseDate = '2000-01-01';
            $scheduleData['departure_time'] = $baseDate . ' ' . $scheduleData['departure_time'];
            $scheduleData['arrival_time'] = $baseDate . ' ' . $scheduleData['arrival_time'];
            
            Schedule::create($scheduleData);
        }
        
        echo "Created " . count($schedules) . " daily recurring schedules.\n";
    }
}
