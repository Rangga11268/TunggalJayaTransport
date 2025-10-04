<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Route as BusRoute;
use App\Models\NewsArticle;
use App\Models\Booking;
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
        
        // Get fleet data for display
        $fleet = Bus::limit(6)->get();
        
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
        
        // Get recommended routes based on user's location or popular routes
        $recommendedRoutes = $this->getRecommendedRoutes();
        
        // For logged-in users, generate personalized recommendations based on booking history
        $personalizedRecommendations = collect();
        if (auth()->check()) {
            $personalizedRecommendations = $this->getPersonalizedRecommendations();
        }
        
        return view('frontend.home', compact(
            'featuredRoutes',
            'latestNews',
            'fleetCount',
            'fleet',
            'routeCount',
            'customerCount',
            'origins',
            'destinations',
            'favoriteRoutes',
            'recommendedRoutes',
            'personalizedRecommendations'
        ));
    }
    
    private function getRecommendedRoutes()
    {
        // For now, return popular routes based on booking count
        // In the future, this could be enhanced with location-based recommendations
        return BusRoute::join('schedules', 'routes.id', '=', 'schedules.route_id')
            ->join('bookings', 'schedules.id', '=', 'bookings.schedule_id')
            ->select('routes.*')
            ->groupBy('routes.id')
            ->orderByRaw('COUNT(bookings.id) DESC')
            ->limit(3)
            ->get();
    }
    
    /**
     * Get personalized recommendations based on user's booking history
     */
    private function getPersonalizedRecommendations()
    {
        $userId = auth()->id();
        $personalizedRecommendations = collect();
        
        // Get the user's last completed booking
        $lastBooking = Booking::where('user_id', $userId)
            ->where('booking_status', 'completed')
            ->with(['schedule.route'])
            ->latest()
            ->first();
            
        if ($lastBooking && $lastBooking->schedule && $lastBooking->schedule->route) {
            $origin = $lastBooking->schedule->route->destination;
            
            // 1. Rekomendasi berdasarkan destinasi sebelumnya (content-based)
            $routesFromOrigin = BusRoute::where('origin', $origin)->get();
            foreach ($routesFromOrigin as $route) {
                $schedules = \App\Models\Schedule::where('route_id', $route->id)
                    ->where('status', 'active')
                    ->with(['bus'])
                    ->get();
                    
                foreach ($schedules as $schedule) {
                    $score = $this->calculateRecommendationScore($route, $schedule, $origin);
                    
                    $personalizedRecommendations->push([
                        'route' => $route,
                        'schedule' => $schedule,
                        'score' => $score,
                        'type' => 'content-based' // Based on previous destination
                    ]);
                }
            }
            
            // If no content-based recommendations found, try recommendations based on similar origins
            if ($personalizedRecommendations->isEmpty()) {
                // Try to find routes with similar origin names
                $similarOriginRoutes = BusRoute::where('origin', 'LIKE', '%' . trim($origin) . '%')
                    ->orWhere('destination', 'LIKE', '%' . trim($origin) . '%')
                    ->get();
                    
                foreach ($similarOriginRoutes as $route) {
                    $schedules = \App\Models\Schedule::where('route_id', $route->id)
                        ->where('status', 'active')
                        ->with(['bus'])
                        ->get();
                        
                    foreach ($schedules as $schedule) {
                        $score = $this->calculateRecommendationScore($route, $schedule, $origin);
                        
                        $personalizedRecommendations->push([
                            'route' => $route,
                            'schedule' => $schedule,
                            'score' => $score,
                            'type' => 'content-based-similar' // Based on similar destination
                        ]);
                    }
                }
            }
            
            // 2. Rekomendasi kolaboratif - cari pengguna dengan pola pemesanan serupa
            $collaborativeRecommendations = $this->getCollaborativeRecommendations($origin);
            $personalizedRecommendations = $personalizedRecommendations->merge($collaborativeRecommendations);
        }
        
        // 3. Rekomendasi berdasarkan destinasi populer jika tidak ada rekomendasi sebelumnya
        if ($personalizedRecommendations->isEmpty()) {
            $popularRecommendations = $this->getPopularRecommendations();
            $personalizedRecommendations = $personalizedRecommendations->merge($popularRecommendations);
        }
        
        // Urutkan berdasarkan skor tertinggi
        $personalizedRecommendations = $personalizedRecommendations->sortByDesc('score')->take(3);
        
        return $personalizedRecommendations;
    }
    
    /**
     * Calculate recommendation score based on multiple factors
     */
    private function calculateRecommendationScore($route, $schedule, $origin = null)
    {
        $score = 0;
        
        // Faktor 1: Popularitas (jumlah booking yang diterima untuk rute ini)
        $bookingCount = Booking::where('schedule_id', $schedule->id)
            ->where('booking_status', 'completed')
            ->count();
        $score += $bookingCount * 15; // Beri bobot 15 untuk setiap booking selesai
        
        // Faktor 2: Harga - semakin terjangkau semakin baik
        $maxPrice = \App\Models\Schedule::max('price');
        if ($maxPrice && $maxPrice > 0) {
            $priceScore = (1 - ($schedule->price / $maxPrice)) * 200; // Maksimal 200 poin
            $score += $priceScore;
        }
        
        // Faktor 3: Durasi perjalanan - semakin pendek semakin baik
        if ($route->duration) {
            $maxDuration = BusRoute::max('duration');
            if ($maxDuration && $maxDuration > 0) {
                $durationScore = (1 - ($route->duration / $maxDuration)) * 100; // Maksimal 100 poin
                $score += $durationScore;
            }
        }
        
        // Faktor 4: Jenis bus - bus eksekutif diberi nilai lebih
        if (preg_match('/(executive|premium|bisnis)/i', $schedule->bus->bus_type)) {
            $score += 75; // Tambahkan skor untuk bus nyaman
        }
        
        // Faktor 5: Rute yang mirip dengan asal sebelumnya (jika ada)
        if ($origin && stripos($route->destination, $origin) !== false) {
            $score += 50; // Bonus jika destinasi mengandung nama lokasi sebelumnya
        }
        
        // Faktor 6: Jumlah seat yang tersedia - lebih banyak tersedia berarti lebih bisa diandalkan
        $availableSeats = $schedule->getAvailableSeatsCount();
        $busCapacity = $schedule->bus->capacity;
        if ($busCapacity > 0) {
            $availabilityScore = ($availableSeats / $busCapacity) * 50;
            $score += $availabilityScore;
        }
        
        return $score;
    }
    
    /**
     * Get collaborative recommendations based on similar users
     */
    private function getCollaborativeRecommendations($origin)
    {
        $recommendations = collect();
        
        // Find users with similar booking patterns
        $similarUsers = $this->findSimilarUsers($origin);
        
        if ($similarUsers->isNotEmpty()) {
            // Get routes frequently booked by similar users but not yet booked by current user
            $similarBookings = Booking::whereIn('user_id', $similarUsers->pluck('id'))
                ->whereHas('schedule', function($q) {
                    $q->where('status', 'active');
                })
                ->with(['schedule.route', 'schedule.bus'])
                ->get();
            
            foreach ($similarBookings as $booking) {
                $route = $booking->schedule->route;
                $schedule = $booking->schedule;
                
                if ($route && $schedule) {
                    // Calculate a collaborative filtering score based on similarity 
                    $similarityScore = $similarUsers->firstWhere('id', $booking->user_id)['similarity'] ?? 1;
                    $collaborativeScore = $similarityScore * 50; // Base collaborative score
                    
                    $recommendations->push([
                        'route' => $route,
                        'schedule' => $schedule,
                        'score' => $collaborativeScore,
                        'type' => 'collaborative'
                    ]);
                }
            }
        }
        
        return $recommendations;
    }
    
    /**
     * Find users with similar booking patterns
     */
    private function findSimilarUsers($origin)
    {
        // This is a simplified version - in a real implementation you'd use more sophisticated similarity algorithms
        $currentUserBookings = Booking::where('user_id', auth()->id())
            ->pluck('schedule.route_id')
            ->toArray();
            
        // Find users who have booked similar routes
        $similarUsers = collect();

        if (!empty($currentUserBookings)) {
            $usersWithSimilarBookings = Booking::whereHas('schedule', function($q) use ($currentUserBookings) {
                    $q->whereIn('route_id', $currentUserBookings)
                      ->where('status', 'active');
                })
                ->where('user_id', '!=', auth()->id())
                ->with(['schedule.route'])
                ->get()
                ->groupBy('user_id');
            
            foreach ($usersWithSimilarBookings as $userId => $bookings) {
                $similarity = count(array_intersect(
                    $bookings->pluck('schedule.route_id')->toArray(), 
                    $currentUserBookings
                )) / count($currentUserBookings);
                
                $similarUsers->push([
                    'id' => $userId,
                    'similarity' => $similarity
                ]);
            }
        }
        
        return $similarUsers->sortByDesc('similarity');
    }
    
    /**
     * Get popular recommendations for users with no history
     */
    private function getPopularRecommendations()
    {
        $recommendations = collect();
        
        // Get most popular routes overall
        $popularRoutes = Booking::whereHas('schedule', function($q) {
                $q->where('status', 'active');
            })
            ->with(['schedule.route', 'schedule.bus'])
            ->selectRaw('schedule_id, count(*) as booking_count')
            ->groupBy('schedule_id')
            ->orderByDesc('booking_count')
            ->limit(20)
            ->get();
        
        foreach ($popularRoutes as $booking) {
            $schedule = $booking->schedule;
            if ($schedule && $schedule->route) {
                $score = $this->calculateRecommendationScore($schedule->route, $schedule) + 50; // Additional popularity bonus
                
                $recommendations->push([
                    'route' => $schedule->route,
                    'schedule' => $schedule,
                    'score' => $score,
                    'type' => 'popular'
                ]);
            }
        }
        
        return $recommendations;
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