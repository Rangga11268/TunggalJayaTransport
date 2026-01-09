<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with their stats.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Group bookings by passenger email to get customer stats
        $customersQuery = Booking::select(
            'passenger_email',
            'passenger_name',
            'passenger_phone',
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_price) as total_spent'),
            DB::raw('MAX(created_at) as last_booking_at')
        )
        ->where('payment_status', 'paid') // Only count paid bookings
        ->groupBy('passenger_email', 'passenger_name', 'passenger_phone');

        // Apply search filter
        if ($search) {
            $customersQuery->where(function ($query) use ($search) {
                $query->where('passenger_name', 'like', "%{$search}%")
                    ->orWhere('passenger_email', 'like', "%{$search}%")
                    ->orWhere('passenger_phone', 'like', "%{$search}%");
            });
        }

        $customers = $customersQuery
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Customers/Index', [
            'customers' => $customers,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Display the specified customer with full booking history.
     */
    public function show($email)
    {
        // Get customer stats
        $customerStats = Booking::select(
            'passenger_email',
            'passenger_name',
            'passenger_phone',
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_price) as total_spent'),
            DB::raw('COUNT(CASE WHEN payment_status = "paid" THEN 1 END) as paid_bookings'),
            DB::raw('COUNT(CASE WHEN payment_status = "pending" THEN 1 END) as pending_bookings'),
            DB::raw('COUNT(CASE WHEN booking_status = "cancelled" THEN 1 END) as cancelled_bookings'),
            DB::raw('MAX(created_at) as last_booking_at'),
            DB::raw('MIN(created_at) as first_booking_at')
        )
        ->where('passenger_email', $email)
        ->groupBy('passenger_email', 'passenger_name', 'passenger_phone')
        ->first();

        if (!$customerStats) {
            abort(404, 'Customer not found');
        }

        // Get full booking history with related data
        $bookings = Booking::with(['schedule.route', 'schedule.bus'])
            ->where('passenger_email', $email)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate route preferences (top 3 most booked routes)
        $routePreferences = Booking::select(
            'routes.name as route_name',
            DB::raw('COUNT(*) as booking_count')
        )
        ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
        ->join('routes', 'schedules.route_id', '=', 'routes.id')
        ->where('bookings.passenger_email', $email)
        ->where('bookings.payment_status', 'paid')
        ->groupBy('routes.id', 'routes.name')
        ->orderBy('booking_count', 'desc')
        ->limit(3)
        ->get();

        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customerStats,
            'bookings' => $bookings,
            'routePreferences' => $routePreferences,
        ]);
    }
}
