<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Facility;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        $facilities = Facility::all();
        
        return view('frontend.fleet.index', compact('buses', 'facilities'));
    }
}