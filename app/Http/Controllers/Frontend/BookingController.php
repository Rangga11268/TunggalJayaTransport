<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use App\Models\Schedule;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $date = $request->get('date');
        
        $schedules = collect();
        
        if ($origin && $destination) {
            // Find routes that match the origin and destination
            $routes = BusRoute::where('origin', $origin)
                ->where('destination', $destination)
                ->get();
                
            if ($routes->count() > 0) {
                // Get schedules for these routes
                $routeIds = $routes->pluck('id');
                $schedules = Schedule::whereIn('route_id', $routeIds)
                    ->with('route', 'bus')
                    ->get();
            }
        }
        
        return view('frontend.booking.index', compact('schedules'));
    }
    
    public function show($id)
    {
        $schedule = Schedule::with('route', 'bus')->findOrFail($id);
        
        return view('frontend.booking.show', compact('schedule'));
    }
}