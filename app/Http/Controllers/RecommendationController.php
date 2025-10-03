<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    /**
     * Display recommendations based on user's last booking or a specific origin.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $origin = $request->query('origin');
        
        // Jika user terotentikasi dan tidak ada origin dari query, gunakan destinasi terakhir dari booking user
        if ($user && !$origin) {
            $lastBooking = Booking::where('user_id', $user->id)
                ->where('booking_status', 'completed')
                ->with(['schedule.route'])
                ->latest()
                ->first();
                
            if ($lastBooking && $lastBooking->schedule && $lastBooking->schedule->route) {
                $origin = $lastBooking->schedule->route->destination;
            }
        }
        
        if (!$origin) {
            return redirect()->route('schedules.index')->with('error', 'No origin location specified for recommendations.');
        }
        
        // Cari rute dengan asal yang sama dengan destinasi terakhir user
        $recommendedRoutes = $this->getRecommendations($origin);
        
        return view('recommendations.index', compact('recommendedRoutes', 'origin'));
    }

    /**
     * Get recommendations based on algorithms
     */
    private function getRecommendations($origin)
    {
        // Ambil semua rute yang asalnya sama dengan destinasi yang diberikan
        $routesFromOrigin = Route::where('origin', 'LIKE', '%' . $origin . '%')
            ->orWhere('destination', 'LIKE', '%' . $origin . '%')
            ->get();
            
        // Ambil semua rute dengan destinasi yang sama (untuk algoritma kolaboratif)
        $routesWithSameDestination = Route::where('destination', 'LIKE', '%' . $origin . '%')->get();
        
        $recommendedRoutes = collect();
        
        // Tambahkan rute-rute yang asalnya sama dengan destinasi user
        foreach ($routesWithSameDestination as $route) {
            // Dapatkan jadwal untuk rute ini
            $schedules = Schedule::where('route_id', $route->id)
                ->where('status', 'active')
                ->with('bus')
                ->get();
                
            foreach ($schedules as $schedule) {
                // Hitung skor berdasarkan beberapa faktor
                $score = $this->calculateRecommendationScore($route, $schedule);
                
                $recommendedRoutes->push([
                    'route' => $route,
                    'schedule' => $schedule,
                    'score' => $score
                ]);
            }
        }
        
        // Urutkan berdasarkan skor tertinggi
        $recommendedRoutes = $recommendedRoutes->sortByDesc('score')->take(10);
        
        return $recommendedRoutes;
    }
    
    /**
     * Calculate recommendation score based on multiple factors
     */
    private function calculateRecommendationScore($route, $schedule)
    {
        $score = 0;
        
        // Faktor 1: Popularitas (jumlah booking yang diterima untuk rute ini)
        $bookingCount = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', 'completed')
            ->count();
        $score += $bookingCount * 10; // Beri bobot 10 untuk setiap booking
        
        // Faktor 2: Harga (harga yang terjangkau dapat meningkatkan skor)
        // Semakin rendah harganya, semakin tinggi skornya (tapi tidak negatif)
        $priceScore = max(0, 1000 - ($schedule->price / 1000));
        $score += $priceScore;
        
        // Faktor 3: Durasi perjalanan (durasi pendek mungkin lebih disukai)
        if ($route->duration) {
            // Beri skor lebih tinggi untuk durasi yang lebih pendek
            $durationScore = max(0, 100 - ($route->duration / 6)); // 6 menit = 1 poin
            $score += $durationScore;
        }
        
        // Faktor 4: Jenis bus (jika ada preferensi berdasarkan kenyamanan)
        if (strpos(strtolower($schedule->bus->bus_type), 'executive') !== false || 
            strpos(strtolower($schedule->bus->bus_type), 'premium') !== false) {
            $score += 50; // Tambahkan skor untuk bus eksekutif
        }
        
        return $score;
    }
}