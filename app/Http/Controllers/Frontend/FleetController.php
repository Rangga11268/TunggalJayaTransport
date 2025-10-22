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
        $buses = Bus::all();
        $drivers = Driver::where('status', 'active')->get();
        
        // Hardcoded facilities
        $facilities = collect([
            [
                'name' => 'AC',
                'icon' => 'fas fa-snowflake',
                'description' => 'Pendingin ruangan untuk kenyamanan penumpang'
            ],
            [
                'name' => 'TV',
                'icon' => 'fas fa-tv',
                'description' => 'Televisi untuk hiburan selama perjalanan'
            ],
            [
                'name' => 'WiFi',
                'icon' => 'fas fa-wifi',
                'description' => 'Koneksi internet gratis untuk penumpang'
            ],
            [
                'name' => 'Toilet',
                'icon' => 'fas fa-toilet',
                'description' => 'Toilet bersih dan nyaman di dalam bus'
            ],
            [
                'name' => 'Recliner Seat',
                'icon' => 'fas fa-chair',
                'description' => 'Kursi yang bisa dimiringkan untuk kenyamanan'
            ],
            [
                'name' => 'Charger',
                'icon' => 'fas fa-plug',
                'description' => 'Port pengisian daya untuk perangkat elektronik'
            ],
            [
                'name' => 'Snack',
                'icon' => 'fas fa-utensils',
                'description' => 'Makanan ringan tersedia selama perjalanan'
            ],
            [
                'name' => 'Air Minum',
                'icon' => 'fas fa-glass-whiskey',
                'description' => 'Air mineral gratis untuk penumpang'
            ]
        ]);
        
        return view('frontend.fleet.index', compact('buses', 'facilities', 'drivers'));
    }
}