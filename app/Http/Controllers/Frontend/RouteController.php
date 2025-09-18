<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = BusRoute::all();
        
        return view('frontend.routes.index', compact('routes'));
    }
    
    public function show($id)
    {
        $route = BusRoute::with(['schedules.bus' => function($query) {
            $query->orderBy('plate_number');
        }])->findOrFail($id);
        
        // Filter schedules to only show available ones
        $availableSchedules = $route->schedules->filter(function ($schedule) {
            return $schedule->isAvailableForBooking();
        });
        
        // Add the filtered schedules to the route object
        $route->availableSchedules = $availableSchedules;
        
        return view('frontend.routes.show', compact('route'));
    }
}