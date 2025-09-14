<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function sales()
    {
        // Get sales data for the last 30 days
        $salesData = Booking::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return view('admin.reports.sales', compact('salesData'));
    }
    
    public function occupancy()
    {
        // Get occupancy data
        $occupancyData = [];
        
        return view('admin.reports.occupancy', compact('occupancyData'));
    }
}
