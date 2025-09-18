<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;

class RouteCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Jakarta - Kuningan via Tol Trans Jawa
        $jakartaKuningan = Route::where('origin', 'Jakarta')
            ->where('destination', 'kuningan')
            ->first();
            
        if ($jakartaKuningan) {
            $jakartaKuningan->update([
                'origin_lat' => -6.200000,
                'origin_lng' => 106.816666,
                'destination_lat' => -6.973333,
                'destination_lng' => 108.483333,
                'waypoints' => json_encode([
                    ['lat' => -6.200000, 'lng' => 106.816666], // Jakarta
                    ['lat' => -6.300000, 'lng' => 106.900000], // Cikampek
                    ['lat' => -6.500000, 'lng' => 107.200000], // Cirebon
                    ['lat' => -6.973333, 'lng' => 108.483333], // Kuningan
                ])
            ]);
        }
        
        // Jakarta via sindang laut - Kuningan
        $jakartaViaSindang = Route::where('origin', 'Jakarta via sindang laut')
            ->where('destination', 'kuningan')
            ->first();
            
        if ($jakartaViaSindang) {
            $jakartaViaSindang->update([
                'origin_lat' => -6.200000,
                'origin_lng' => 106.816666,
                'destination_lat' => -6.973333,
                'destination_lng' => 108.483333,
            ]);
        }
        
        // Jakarta via X deres - Kuningan
        $jakartaViaXDeres = Route::where('origin', 'Jakarta via X deres')
            ->where('destination', 'kuningan')
            ->first();
            
        if ($jakartaViaXDeres) {
            $jakartaViaXDeres->update([
                'origin_lat' => -6.200000,
                'origin_lng' => 106.816666,
                'destination_lat' => -6.973333,
                'destination_lng' => 108.483333,
            ]);
        }
        
        // Kuningan - Palembang
        $kuninganPalembang = Route::where('origin', 'kuningan')
            ->where('destination', 'palembang')
            ->first();
            
        if ($kuninganPalembang) {
            $kuninganPalembang->update([
                'origin_lat' => -6.973333,
                'origin_lng' => 108.483333,
                'destination_lat' => -2.976074,
                'destination_lng' => 104.775431,
                'waypoints' => json_encode([
                    ['lat' => -6.973333, 'lng' => 108.483333], // Kuningan
                    ['lat' => -6.700000, 'lng' => 108.500000], // Majalengka
                    ['lat' => -6.500000, 'lng' => 108.500000], // Sumedang
                    ['lat' => -6.300000, 'lng' => 108.400000], // Garut
                    ['lat' => -6.100000, 'lng' => 108.300000], // Tasikmalaya
                    ['lat' => -6.000000, 'lng' => 108.200000], // Ciamis
                    ['lat' => -5.800000, 'lng' => 108.100000], // Banjar
                    ['lat' => -5.500000, 'lng' => 108.000000], // Purwakarta (Lampung)
                    ['lat' => -4.500000, 'lng' => 105.000000], // Lampung
                    ['lat' => -3.500000, 'lng' => 104.900000], // Palembang area
                    ['lat' => -2.976074, 'lng' => 104.775431], // Palembang
                ])
            ]);
        }
        
        // Kuningan - Jakarta
        $kuninganJakarta = Route::where('origin', 'kuningan')
            ->where('destination', 'Jakarta')
            ->first();
            
        if ($kuninganJakarta) {
            $kuninganJakarta->update([
                'origin_lat' => -6.973333,
                'origin_lng' => 108.483333,
                'destination_lat' => -6.200000,
                'destination_lng' => 106.816666,
                'waypoints' => json_encode([
                    ['lat' => -6.973333, 'lng' => 108.483333], // Kuningan
                    ['lat' => -6.500000, 'lng' => 107.200000], // Cirebon
                    ['lat' => -6.300000, 'lng' => 106.900000], // Cikampek
                    ['lat' => -6.200000, 'lng' => 106.816666], // Jakarta
                ])
            ]);
        }
        
        echo "Route coordinates have been updated.\n";
    }
}
