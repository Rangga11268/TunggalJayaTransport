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
        
        return view('frontend.home', compact(
            'featuredRoutes',
            'latestNews',
            'fleetCount',
            'routeCount',
            'customerCount'
        ));
    }
}