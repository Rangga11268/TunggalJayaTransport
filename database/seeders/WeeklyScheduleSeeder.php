<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;
use Carbon\Carbon;

class WeeklyScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample buses and routes
        $buses = Bus::limit(3)->get();
        $routes = Route::limit(3)->get();
        
        if ($buses->count() == 0 || $routes->count() == 0) {
            echo "No buses or routes found. Please run other seeders first.\n";
            return;
        }
        
        // Create sample weekly schedules for each day of the week
        $daysOfWeek = [
            Carbon::SUNDAY => 'Sunday',
            Carbon::MONDAY => 'Monday',
            Carbon::TUESDAY => 'Tuesday',
            Carbon::WEDNESDAY => 'Wednesday',
            Carbon::THURSDAY => 'Thursday',
            Carbon::FRIDAY => 'Friday',
            Carbon::SATURDAY => 'Saturday',
        ];
        
        foreach ($daysOfWeek as $dayNumber => $dayName) {
            // Create a schedule for next occurrence of this day
            $nextDate = Carbon::now()->next($dayNumber);
            
            Schedule::create([
                'bus_id' => $buses->first()->id,
                'route_id' => $routes->first()->id,
                'departure_time' => $nextDate->setTime(8, 0, 0), // 8:00 AM
                'arrival_time' => $nextDate->setTime(12, 0, 0),  // 12:00 PM
                'price' => 150000,
                'status' => 'active',
                'is_weekly' => true,
                'day_of_week' => $dayNumber,
            ]);
            
            echo "Created weekly schedule for {$dayName}\n";
        }
    }
}
