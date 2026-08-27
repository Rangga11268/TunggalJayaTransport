<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Driver;
use Illuminate\Http\Request;

class FleetController extends Controller
{
    public function index()
    {
        $buses = Bus::with('media')->get();
        
        return \Inertia\Inertia::render('Frontend/Fleet/Index', [
            'buses' => $buses,
            'facilities' => Bus::getStandardFacilities()
        ]);
    }

    public function show($id)
    {
        $bus = Bus::with(['media', 'schedules.route'])->findOrFail($id);
        
        $relatedBuses = Bus::where('id', '!=', $id)
            ->where('bus_category', $bus->bus_category)
            ->limit(3)
            ->get();

        return \Inertia\Inertia::render('Frontend/Fleet/Show', [
            'bus' => $bus,
            'relatedBuses' => $relatedBuses,
            'facilities' => Bus::getStandardFacilities(),
        ]);
    }
}