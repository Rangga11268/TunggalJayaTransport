<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured routes (limit to 3)
        $featuredRoutes = BusRoute::limit(3)->get();
        
        // Get latest news (limit to 3)
        $latestNews = NewsArticle::where('is_published', true)
            ->latest()
            ->limit(3)
            ->get();
            
        // Get fleet count
        $fleetCount = Bus::count();
        
        // Get route count
        $routeCount = BusRoute::count();
        
        // For customer count, we'll use a placeholder value
        // In a real application, you might count bookings or users
        $customerCount = 10000;
        
        // Get unique origins and destinations for auto-complete
        $origins = BusRoute::pluck('origin')->unique()->values()->all();
        $destinations = BusRoute::pluck('destination')->unique()->values()->all();
        
        // Get user's favorite routes if logged in
        $favoriteRoutes = collect();
        if (auth()->check()) {
            $userId = auth()->id();
            $favoriteRoutes = BusRoute::join('schedules', 'routes.id', '=', 'schedules.route_id')
                ->join('bookings', 'schedules.id', '=', 'bookings.schedule_id')
                ->where('bookings.user_id', $userId)
                ->select('routes.*')
                ->groupBy('routes.id')
                ->orderByRaw('COUNT(bookings.id) DESC')
                ->limit(3)
                ->get();
        }
        
        return view('frontend.home', compact(
            'featuredRoutes',
            'latestNews',
            'fleetCount',
            'routeCount',
            'customerCount',
            'origins',
            'destinations',
            'favoriteRoutes'
        ));
    }
    
    // Method to get origins and destinations for autocomplete (can be used via AJAX)
    public function getOriginsAndDestinations()
    {
        $origins = BusRoute::pluck('origin')->unique()->values()->all();
        $destinations = BusRoute::pluck('destination')->unique()->values()->all();
        
        return response()->json([
            'origins' => $origins,
            'destinations' => $destinations
        ]);
    }
}