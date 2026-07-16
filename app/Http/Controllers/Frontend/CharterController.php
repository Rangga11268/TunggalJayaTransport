<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\CharterBooking;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CharterController extends Controller
{
    public function index()
    {
        $pariwisataBuses = Bus::where('bus_category', 'pariwisata')
            ->select('id', 'name', 'bus_type', 'capacity', 'description')
            ->get();

        return Inertia::render('Frontend/Charter/Index', [
            'pariwisataBuses' => $pariwisataBuses,
        ]);
    }

    public function store(Request $request)
    {
        $minDate = now()->addDays(3)->toDateString();

        $validated = $request->validate([
            'pickup_date' => 'required|date|after_or_equal:' . $minDate,
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'bus_type_requested' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $charterCode = 'CHRT-' . strtoupper(Str::random(8));

        CharterBooking::create([
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

        return redirect()->route('frontend.booking-history')->with('success', 'Permintaan sewa pariwisata berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
