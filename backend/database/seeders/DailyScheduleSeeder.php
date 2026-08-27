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
        $buses = Bus::all();
        $routes = Route::all();
        
        if ($buses->isEmpty() || $routes->isEmpty()) {
            echo "No buses or routes found. Please run other seeders first.\n";
            return;
        }

        $schedules = [];

        // Kuningan - Jakarta (Pulogebang)
        $route = Route::where('name', 'like', '%Pulogebang%')->first();
        if ($route) {
            $schedules[] = [
                'bus_id' => $buses->where('name', 'Resi Bisma')->first()?->id ?? $buses->first()->id,
                'route_id' => $route->id,
                'departure_time' => '07:00:00',
                'arrival_time' => '11:00:00',
                'price' => 130000,
                'status' => 'active',
                'is_daily' => true,
            ];
        }

        // Kuningan - Jakarta (Lebak Bulus)
        $route = Route::where('name', 'like', '%Lebak Bulus%')->first();
        if ($route) {
            $schedules[] = [
                'bus_id' => $buses->where('name', 'Primadona')->first()?->id ?? $buses->first()->id,
                'route_id' => $route->id,
                'departure_time' => '08:00:00',
                'arrival_time' => '13:00:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ];
        }

        // Kuningan - Rangkasbitung
        $route = Route::where('name', 'like', '%Rangkasbitung%')->first();
        if ($route) {
            $schedules[] = [
                'bus_id' => $buses->where('name', 'Bentas')->first()?->id ?? $buses->first()->id,
                'route_id' => $route->id,
                'departure_time' => '06:30:00',
                'arrival_time' => '13:30:00',
                'price' => 150000,
                'status' => 'active',
                'is_daily' => true,
            ];
        }
        
        foreach ($schedules as $scheduleData) {
            $baseDate = '2000-01-01';
            $scheduleData['departure_time'] = $baseDate . ' ' . $scheduleData['departure_time'];
            $scheduleData['arrival_time'] = $baseDate . ' ' . $scheduleData['arrival_time'];
            
            Schedule::create($scheduleData);
        }
        
        echo "Created " . count($schedules) . " daily recurring schedules.\n";
    }
}
