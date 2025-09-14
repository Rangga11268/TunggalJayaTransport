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
        $route = BusRoute::with('schedules.bus')->findOrFail($id);
        
        return view('frontend.routes.show', compact('route'));
    }
}