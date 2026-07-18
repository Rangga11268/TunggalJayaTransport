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
            
        $bookings = CharterBooking::whereIn('payment_status', ['dp_paid', 'paid'])
            ->orWhereIn('status', ['confirmed', 'completed'])
            ->whereNotNull('assigned_bus_id')
            ->get(['assigned_bus_id', 'pickup_date', 'return_date']);

        $bookedDates = [];
        foreach ($bookings as $booking) {
            $bookedDates[] = [
                'bus_id' => $booking->assigned_bus_id,
                'pickup_date' => $booking->pickup_date->toDateString(),
                'return_date' => $booking->return_date->toDateString(),
            ];
        }

        return Inertia::render('Frontend/Charter/Index', [
            'pariwisataBuses' => $pariwisataBuses,
            'bookedDates' => $bookedDates,
        ]);
    }

    public function storeStep1(Request $request)
    {
        $minDate = now()->toDateString();

        $validated = $request->validate([
            'pickup_date' => 'required|date|after_or_equal:' . $minDate,
            'pickup_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'bus_type_requested' => 'nullable|string|max:255',
            'bus_id' => 'nullable|exists:buses,id',
        ]);

        if (!empty($validated['bus_id'])) {
            $overlapping = CharterBooking::where('assigned_bus_id', $validated['bus_id'])
                ->where(function($q) {
                    $q->whereIn('payment_status', ['dp_paid', 'paid'])
                      ->orWhereIn('status', ['confirmed', 'completed']);
                })
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('pickup_date', [$validated['pickup_date'], $validated['return_date']])
                        ->orWhereBetween('return_date', [$validated['pickup_date'], $validated['return_date']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('pickup_date', '<=', $validated['pickup_date'])
                              ->where('return_date', '>=', $validated['return_date']);
                        });
                })
                ->exists();

            if ($overlapping) {
                return back()->withErrors(['bus_id' => 'Bus yang Anda pilih sudah disewa (DP Lunas) oleh pelanggan lain pada rentang tanggal tersebut. Silakan pilih tanggal lain atau cek ketersediaan armada lain.'])->withInput();
            }
        }

        $request->session()->put('charter_step1', $validated);

        return redirect()->route('frontend.charter.details');
    }

    public function details(Request $request)
    {
        $bookingData = $request->session()->get('charter_step1');

        if (!$bookingData) {
            return redirect()->route('frontend.charter.index')->with('error', 'Silakan mulai dari pengisian form dasar.');
        }

        return Inertia::render('Frontend/Charter/Details', [
            'bookingData' => $bookingData
        ]);
    }

    public function store(Request $request)
    {
        $minDate = now()->toDateString();

        $validated = $request->validate([
            'pickup_date' => 'required|date|after_or_equal:' . $minDate,
            'pickup_time' => 'required|date_format:H:i',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'pickup_lat' => 'nullable|numeric',
            'pickup_lng' => 'nullable|numeric',
            'pickup_address' => 'nullable|string',
            'destination' => 'required|string|max:255',
            'destination_lat' => 'nullable|numeric',
            'destination_lng' => 'nullable|numeric',
            'destination_address' => 'nullable|string',
            'bus_type_requested' => 'nullable|string|max:255',
            'bus_id' => 'nullable|exists:buses,id',
            'passenger_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $charterCode = 'CHRT-' . strtoupper(Str::random(8));

        CharterBooking::create([
            'charter_code' => $charterCode,
            'user_id' => $request->user()->id,
            'bus_type_requested' => $validated['bus_type_requested'] ?? 'Big Bus',
            'assigned_bus_id' => $validated['bus_id'] ?? null,
            'passenger_count' => $validated['passenger_count'],
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],
            'return_date' => $validated['return_date'],
            'pickup_location' => $validated['pickup_location'],
            'pickup_lat' => $validated['pickup_lat'],
            'pickup_lng' => $validated['pickup_lng'],
            'pickup_address' => $validated['pickup_address'],
            'destination' => $validated['destination'],
            'destination_lat' => $validated['destination_lat'],
            'destination_lng' => $validated['destination_lng'],
            'destination_address' => $validated['destination_address'],
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        $request->session()->forget('charter_step1');

        return redirect()->route('frontend.charter.success')->with('success', 'Permintaan sewa pariwisata berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
    
    public function success()
    {
        return Inertia::render('Frontend/Charter/Success');
    }
}
