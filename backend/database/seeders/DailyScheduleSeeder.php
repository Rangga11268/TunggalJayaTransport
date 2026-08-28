<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;

class DailyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds based on real Tunggal Jaya routes and fleet.
     */
    public function run(): void
    {
        $buses = Bus::all();
        $routes = Route::all();

        if ($buses->isEmpty() || $routes->isEmpty()) {
            return;
        }

        $baseDate = '2000-01-01';

        $schedules = [
            // 1. Bentas-01 Salamina: Kuningan - Kalideres (Pagi 06:50 WIB)
            [
                'bus_name' => 'Bentas-01 (Salamina)',
                'route_name' => 'Kuningan - Jakarta (Kalideres)',
                'departure_time' => $baseDate . ' 06:50:00',
                'arrival_time' => $baseDate . ' 13:30:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 2. Resi Bisma: Kuningan - Kalideres (07:45 WIB)
            [
                'bus_name' => 'Resi Bisma (Bentas-02)',
                'route_name' => 'Kuningan - Jakarta (Kalideres)',
                'departure_time' => $baseDate . ' 07:45:00',
                'arrival_time' => $baseDate . ' 14:30:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 3. Dewi Fortuna: Kuningan - Rangkasbitung (10:00 WIB)
            [
                'bus_name' => 'Dewi Fortuna',
                'route_name' => 'Kuningan - Rangkasbitung (Banten)',
                'departure_time' => $baseDate . ' 10:00:00',
                'arrival_time' => $baseDate . ' 17:30:00',
                'price' => 150000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 4. Primadona: Kuningan - Roxy / Jembatan 5 (12:40 WIB)
            [
                'bus_name' => 'Primadona (Bentas-05)',
                'route_name' => 'Kuningan - Jakarta (Roxy / Jembatan 5)',
                'departure_time' => $baseDate . ' 12:40:00',
                'arrival_time' => $baseDate . ' 19:15:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 5. Semar Mesem: Kuningan - Kalideres (14:20 WIB)
            [
                'bus_name' => 'Semar Mesem (Bentas-03)',
                'route_name' => 'Kuningan - Jakarta (Kalideres)',
                'departure_time' => $baseDate . ' 14:20:00',
                'arrival_time' => $baseDate . ' 21:00:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 6. Resi Bisma: Jakarta - Kuningan (Sore 18:00 WIB)
            [
                'bus_name' => 'Resi Bisma (Bentas-02)',
                'route_name' => 'Jakarta - Kuningan (Pulogebang - Cirendang)',
                'departure_time' => $baseDate . ' 18:00:00',
                'arrival_time' => $baseDate . ' 23:45:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
            // 7. Bentas-01: Cirebon - Pang. Asem (07:00 WIB)
            [
                'bus_name' => 'Bentas-01 (Salamina)',
                'route_name' => 'Cirebon - Jakarta (Pangkalan Asem)',
                'departure_time' => $baseDate . ' 07:00:00',
                'arrival_time' => $baseDate . ' 13:00:00',
                'price' => 140000,
                'status' => 'active',
                'is_daily' => true,
            ],
        ];

        foreach ($schedules as $data) {
            $bus = $buses->firstWhere('name', $data['bus_name']) ?? $buses->first();
            $route = $routes->firstWhere('name', $data['route_name']) ?? $routes->first();

            Schedule::updateOrCreate(
                [
                    'bus_id' => $bus->id,
                    'route_id' => $route->id,
                    'departure_time' => $data['departure_time'],
                ],
                [
                    'arrival_time' => $data['arrival_time'],
                    'price' => $data['price'],
                    'status' => $data['status'],
                    'is_daily' => $data['is_daily'],
                ]
            );
        }
    }
}
