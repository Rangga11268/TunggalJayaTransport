<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_price');
        $activeRoutes = Schedule::where('status', 'active')->distinct('route_id')->count('route_id');
        $registeredUsers = User::count();
        
        // Format revenue as currency
        $formattedRevenue = 'Rp. ' . number_format($totalRevenue, 0, ',', '.');
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard', compact('totalBookings', 'formattedRevenue', 'activeRoutes', 'registeredUsers', 'recentActivities'));
    }
    
    private function getRecentActivities()
    {
        $activities = new Collection();
        
        // Get recent bookings
        $recentBookings = Booking::with('user')->latest()->limit(5)->get();
        foreach ($recentBookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'description' => 'New booking created: ' . $booking->booking_code,
                'user' => $booking->user ? $booking->user->name : 'Guest',
                'time' => $booking->created_at->diffForHumans(),
                'created_at' => $booking->created_at
            ]);
        }
        
        // Get recent news articles
        $recentNews = NewsArticle::latest()->limit(5)->get();
        foreach ($recentNews as $news) {
            $activities->push([
                'type' => 'news',
                'description' => 'News article published: ' . $news->title,
                'user' => $news->author ? $news->author->name : 'Unknown',
                'time' => $news->created_at->diffForHumans(),
                'created_at' => $news->created_at
            ]);
        }
        
        // Get recent users
        $recentUsers = User::latest()->limit(5)->get();
        foreach ($recentUsers as $user) {
            $activities->push([
                'type' => 'user',
                'description' => 'New user registered: ' . $user->name,
                'user' => $user->name,
                'time' => $user->created_at->diffForHumans(),
                'created_at' => $user->created_at
            ]);
        }
        
        // Sort all activities by created_at and take the most recent 10
        return $activities->sortByDesc('created_at')->take(10);
    }
}
