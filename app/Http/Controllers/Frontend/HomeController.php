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
            
            // If user has no booking history, provide new user recommendations
            if ($personalizedRecommendations->isEmpty()) {
                $personalizedRecommendations = $this->getRecommendationsForNewUsers();
            }
        } else {
            // For logged-out users, provide general recommendations
            $personalizedRecommendations = $this->getRecommendationsForNewUsers();
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
            $similarBookings = Booking::join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
                ->join('routes', 'schedules.route_id', '=', 'routes.id')
                ->join('buses', 'schedules.bus_id', '=', 'buses.id')
                ->whereIn('bookings.user_id', $similarUsers->pluck('id'))
                ->where('schedules.status', 'active')
                ->select('bookings.user_id as booking_user_id', 'schedules.*', 'routes.*', 'buses.*', 'bookings.schedule_id')
                ->get();
            
            foreach ($similarBookings as $bookingData) {
                // Create temporary objects to match expected structure
                $route = new \App\Models\Route();
                $route->id = $bookingData->route_id;
                $route->name = $bookingData->name;
                $route->origin = $bookingData->origin;
                $route->destination = $bookingData->destination;
                $route->distance = $bookingData->distance;
                $route->duration = $bookingData->duration;
                $route->description = $bookingData->description;
                
                $bus = new \App\Models\Bus();
                $bus->id = $bookingData->bus_id;
                $bus->name = $bookingData->name;
                $bus->bus_type = $bookingData->bus_type;
                $bus->capacity = $bookingData->capacity;
                $bus->facilities = $bookingData->facilities;
                
                $schedule = new \App\Models\Schedule();
                $schedule->id = $bookingData->schedule_id;
                $schedule->bus_id = $bookingData->bus_id;
                $schedule->route_id = $bookingData->route_id;
                $schedule->price = $bookingData->price;
                $schedule->status = $bookingData->status;
                $schedule->bus = $bus;
                $schedule->route = $route;
                
                if ($route && $schedule) {
                    // Calculate a collaborative filtering score based on similarity 
                    $similarityScore = $similarUsers->firstWhere('id', $bookingData->booking_user_id)['similarity'] ?? 1;
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
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->pluck('schedules.route_id')
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
        $popularSchedules = Booking::join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->where('schedules.status', 'active')
            ->selectRaw('schedules.id as schedule_id, count(*) as booking_count')
            ->groupBy('schedules.id')
            ->orderByDesc('booking_count')
            ->limit(20)
            ->get(['schedules.id']);
        
        foreach ($popularSchedules as $scheduleData) {
            $schedule = \App\Models\Schedule::with(['route', 'bus'])->find($scheduleData->schedule_id);
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
    
    /**
     * Get recommendations for users with no booking history
     */
    private function getRecommendationsForNewUsers()
    {
        $recommendations = collect();
        
        // 1. Get seasonally popular routes (if we can determine current season)
        $seasonalRecommendations = $this->getSeasonalRecommendations();
        $recommendations = $recommendations->merge($seasonalRecommendations);
        
        // 2. Add trending routes (recently increasing bookings)
        if ($recommendations->count() < 3) {
            $trendingRecommendations = $this->getTrendingRecommendations();
            $recommendations = $recommendations->merge($trendingRecommendations);
        }
        
        // 3. Get diverse popular routes (for variety)
        if ($recommendations->count() < 3) {
            $diverseRecommendations = $this->getDiversePopularRecommendations();
            $recommendations = $recommendations->merge($diverseRecommendations);
        }
        
        return $recommendations->sortByDesc('score')->take(3);
    }

    /**
     * Get seasonal recommendations based on current time
     */
    private function getSeasonalRecommendations()
    {
        $recommendations = collect();
        $currentMonth = now()->month;
        
        // Example: if certain routes are more popular in certain seasons
        $seasonalSchedules = Booking::join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->join('bookings AS b2', function($join) use ($currentMonth) {
                $join->on('b2.schedule_id', '=', 'schedules.id')
                     ->whereRaw('MONTH(b2.created_at) = ?', [$currentMonth]);
            })
            ->where('schedules.status', 'active')
            ->selectRaw('schedules.id as schedule_id, count(*) as booking_count')
            ->groupBy('schedules.id')
            ->orderByDesc('booking_count')
            ->limit(10)
            ->get(['schedules.id']);
        
        foreach ($seasonalSchedules as $scheduleData) {
            $schedule = \App\Models\Schedule::with(['route', 'bus'])->find($scheduleData->schedule_id);
            if ($schedule && $schedule->route) {
                $score = $this->calculateRecommendationScore($schedule->route, $schedule) + 40; // Seasonal bonus
                
                $recommendations->push([
                    'route' => $schedule->route,
                    'schedule' => $schedule,
                    'score' => $score,
                    'type' => 'seasonal'
                ]);
            }
        }
        
        return $recommendations;
    }

    /**
     * Get trending recommendations (routes with increasing bookings)
     */
    private function getTrendingRecommendations()
    {
        $recommendations = collect();
        
        // Get routes with significantly increasing booking counts compared to previous period
        $trendingSchedules = Booking::join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->where('bookings.created_at', '>', now()->subMonth())  // Bookings from last month
            ->selectRaw('schedules.id as schedule_id, count(*) as recent_booking_count')
            ->groupBy('schedules.id')
            ->orderByDesc('recent_booking_count')
            ->limit(10)
            ->get(['schedules.id']);
        
        foreach ($trendingSchedules as $scheduleData) {
            $schedule = \App\Models\Schedule::with(['route', 'bus'])->find($scheduleData->schedule_id);
            if ($schedule && $schedule->route) {
                $score = $this->calculateRecommendationScore($schedule->route, $schedule) + 30; // Trend bonus
                
                $recommendations->push([
                    'route' => $schedule->route,
                    'schedule' => $schedule,
                    'score' => $score,
                    'type' => 'trending'
                ]);
            }
        }
        
        return $recommendations;
    }

    /**
     * Get diverse popular recommendations to avoid showing the same routes repeatedly
     */
    private function getDiversePopularRecommendations()
    {
        $recommendations = collect();
        
        // Get popular routes but ensure diversity (not just the same top routes)
        $popularSchedules = Booking::join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('routes', 'schedules.route_id', '=', 'routes.id')
            ->where('schedules.status', 'active')
            ->selectRaw('routes.origin, schedules.id as schedule_id, count(*) as booking_count')
            ->groupBy('routes.origin', 'schedules.id')  // Group by origin to ensure diversity
            ->orderBy('routes.origin')
            ->orderByDesc('booking_count')
            ->limit(20)
            ->get(['schedules.id']);
        
        foreach ($popularSchedules as $scheduleData) {
            $schedule = \App\Models\Schedule::with(['route', 'bus'])->find($scheduleData->schedule_id);
            if ($schedule && $schedule->route) {
                $score = $this->calculateRecommendationScore($schedule->route, $schedule) + 20; // Diverse popularity bonus
                
                $recommendations->push([
                    'route' => $schedule->route,
                    'schedule' => $schedule,
                    'score' => $score,
                    'type' => 'diverse-popular'
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