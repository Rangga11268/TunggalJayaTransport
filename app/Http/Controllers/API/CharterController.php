<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CharterBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CharterController extends Controller
{
    /**
     * Get the authenticated user's charter bookings history.
     */
    public function index(Request $request)
    {
        $charters = CharterBooking::with('assignedBus')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $charters,
        ]);
    }

    /**
     * Submit a new charter request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'bus_type_requested' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $charterCode = 'CHRT-' . strtoupper(Str::random(8));

        $charter = CharterBooking::create([
            'charter_code' => $charterCode,
            'user_id' => $request->user()->id,
            'bus_type_requested' => $validated['bus_type_requested'] ?? 'Big Bus',
            'pickup_date' => $validated['pickup_date'],
            'return_date' => $validated['return_date'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Permintaan sewa pariwisata berhasil dikirim. Admin akan segera memberikan penawaran harga.',
            'data' => $charter,
        ], 201);
    }
}
