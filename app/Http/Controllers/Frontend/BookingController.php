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
        // Get all unique origins and destinations for the dropdowns
        $origins = BusRoute::pluck('origin')->unique()->values();
        $destinations = BusRoute::pluck('destination')->unique()->values();
        
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $date = $request->get('date');
        
        $schedules = collect();
        $validPair = false;
        
        if ($origin && $destination) {
            // Check if this is a valid route pair (in either direction)
            $validRoutes = BusRoute::where(function($query) use ($origin, $destination) {
                $query->where('origin', $origin)
                      ->where('destination', $destination);
            })->orWhere(function($query) use ($origin, $destination) {
                $query->where('origin', $destination)
                      ->where('destination', $origin);
            })->get();
            
            if ($validRoutes->count() > 0) {
                $validPair = true;
                // Get schedules for these routes
                $routeIds = $validRoutes->pluck('id');
                $schedules = Schedule::whereIn('route_id', $routeIds)
                    ->with('route', 'bus')
                    ->get();
            }
        }
        
        return view('frontend.booking.index', compact('schedules', 'origins', 'destinations', 'validPair', 'origin', 'destination'));
    }
    
    public function show($id)
    {
        $schedule = Schedule::with('route', 'bus')->findOrFail($id);
        
        return view('frontend.booking.show', compact('schedule'));
    }
}