<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = BusRoute::withCount('schedules')->get()->append('formatted_duration');
        
        return \Inertia\Inertia::render('Frontend/Routes/Index', [
            'routes' => $routes
        ]);
    }
    
    public function show($id)
    {
        $route = BusRoute::with(['schedules.bus.media' => function($query) {
             // Eager load bus media for the schedule list
        }])->findOrFail($id)->append('formatted_duration');
        
        // Filter schedules to only show available ones
        $availableSchedules = $route->schedules->filter(function ($schedule) {
        return $schedule->isAvailableForBooking();
        })->map(function ($schedule) {
            // Overwrite departure_time and arrival_time with actual dates for display
            // This ensures the frontend shows the correct date (e.g., Today/Tomorrow) instead of the base date (2000-01-01)
            $schedule->departure_time = $schedule->getActualDepartureTime();
            $schedule->arrival_time = $schedule->getActualArrivalTime();
            return $schedule;
        });
        
        // Re-index the collection to array for JSON response
        $route->available_schedules = $availableSchedules->values();
        
        return \Inertia\Inertia::render('Frontend/Routes/Show', [
            'routeModel' => $route
        ]);
    }
}