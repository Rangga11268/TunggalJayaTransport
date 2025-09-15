<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\Facility;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        $facilities = Facility::all();
        $drivers = Driver::where('status', 'active')->get();
        
        return view('frontend.fleet.index', compact('buses', 'facilities', 'drivers'));
    }
}