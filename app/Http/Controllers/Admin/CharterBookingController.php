<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CharterBooking;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CharterBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $charters = CharterBooking::with(['user', 'assignedBus'])
            ->when($search, function ($query, $search) {
                $query->where('charter_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        foreach ($charters as $c) {
            $c->checkAndCancelIfExpired();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'charters' => $charters
            ]);
        }

        return Inertia::render('Admin/CharterBookings/Index', [
            'charters' => $charters,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $buses = Bus::where('status', 'active')->where('bus_category', 'pariwisata')->get();
        $users = User::orderBy('name')->get();

        return Inertia::render('Admin/CharterBookings/Create', [
            'buses' => $buses,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required_without:user_id|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'required_if:user_id,null|nullable|string|max:20',
            'institution_name' => 'nullable|string|max:255',
            'assigned_bus_ids' => 'nullable|array',
            'assigned_bus_ids.*' => 'exists:buses,id',
            'bus_count' => 'nullable|integer|min:1',
            'bus_type_requested' => 'nullable|string',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|string',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,quoted,confirmed,completed,cancelled',
            'payment_status' => 'required|in:unpaid,dp_paid,fully_paid,failed',
            'payment_method' => 'nullable|in:system,manual',
            'notes' => 'nullable|string',
        ]);

        $pickupDate = $validated['pickup_date'];
        $returnDate = $validated['return_date'];

        // Limit bus_count logic
        $overlappingBookingsCount = CharterBooking::where('status', '!=', 'cancelled')
            ->where(function ($query) use ($pickupDate, $returnDate) {
                $query->whereBetween('pickup_date', [$pickupDate, $returnDate])
                    ->orWhereBetween('return_date', [$pickupDate, $returnDate])
                    ->orWhere(function ($q) use ($pickupDate, $returnDate) {
                        $q->where('pickup_date', '<=', $pickupDate)
                          ->where('return_date', '>=', $returnDate);
                    });
            })
            ->sum('bus_count');

        $totalPariwisataBuses = Bus::where('bus_category', 'pariwisata')->where('status', 'active')->count();
        $availableBuses = max(0, $totalPariwisataBuses - $overlappingBookingsCount);

        $requestedCount = $validated['bus_count'] ?? (!empty($validated['assigned_bus_ids']) ? count($validated['assigned_bus_ids']) : 1);
        if ($requestedCount > $availableBuses) {
            return back()->withErrors(['bus_count' => "Maaf, saat ini hanya tersedia {$availableBuses} unit bus pariwisata pada tanggal tersebut. Total bus yang diminta ({$requestedCount}) melebihi ketersediaan."])->withInput();
        }

        if (!empty($validated['assigned_bus_ids'])) {
            $overlapping = CharterBooking::where('id', '!=', 0)
                ->whereHas('buses', function($q) use ($validated) {
                    $q->whereIn('bus_id', $validated['assigned_bus_ids']);
                })
                ->where(function($q) {
                    $q->whereIn('payment_status', ['dp_paid', 'fully_paid', 'paid', 'partial'])
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
                return back()->withErrors(['assigned_bus_ids' => 'Satu atau lebih bus ini tidak bisa dipilih karena sudah dipesan oleh penyewa lain pada rentang tanggal tersebut.'])->withInput();
            }
        } 

        $userId = $validated['user_id'] ?? null;

        // If no user selected, create a new dummy user or find by email/phone
        if (!$userId) {
            $existingUser = null;
            if (!empty($validated['customer_email'])) {
                $existingUser = User::where('email', $validated['customer_email'])->first();
            }
            if (!$existingUser && !empty($validated['customer_phone'])) {
                $existingUser = User::where('phone', $validated['customer_phone'])->first();
            }

            if ($existingUser) {
                $userId = $existingUser->id;
            } else {
                $newUser = User::create([
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'] ?: (Str::slug($validated['customer_name']) . rand(100, 999) . '@offline.com'),
                    'phone' => $validated['customer_phone'],
                    'password' => Hash::make(Str::random(12)), // Random password
                ]);
                $newUser->assignRole('user');
                $userId = $newUser->id;
            }
        }

        $busTypes = $validated['bus_type_requested'] ?? '';
        if (empty($busTypes) && !empty($validated['assigned_bus_ids'])) {
            $buses = Bus::whereIn('id', $validated['assigned_bus_ids'])->get();
            $busTypes = $buses->map(function($b) { return $b->name . ' - ' . $b->capacity . ' Seat'; })->implode(', ');
        }
        
        $charterBooking = CharterBooking::create([
            'charter_code' => 'CHRT-' . strtoupper(Str::random(8)),
            'user_id' => $userId,
            'institution_name' => $validated['institution_name'] ?? null,
            'bus_type_requested' => $busTypes ?: 'Big Bus',
            'bus_count' => $validated['bus_count'] ?? (!empty($validated['assigned_bus_ids']) ? count($validated['assigned_bus_ids']) : 1),
            'pickup_date' => $validated['pickup_date'],
            'pickup_time' => $validated['pickup_time'],
            'return_date' => $validated['return_date'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'total_price' => $validated['total_price'],
            'down_payment' => $validated['down_payment'] ?? 0,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? 'manual',
            'notes' => $validated['notes'],
        ]);

        if (!empty($validated['assigned_bus_ids'])) {
            $charterBooking->buses()->sync($validated['assigned_bus_ids']);
        }

        return redirect()->route('admin.charter-bookings.index')->with('success', 'Data sewa pariwisata berhasil ditambahkan.');
    }

    public function show($id)
    {
        $charter = CharterBooking::with(['user', 'buses'])->findOrFail($id);
        $charter->checkAndCancelIfExpired();
        
        $buses = Bus::where('status', 'active')->where('bus_category', 'pariwisata')->get();

        return Inertia::render('Admin/CharterBookings/Show', [
            'charter' => $charter,
            'buses' => $buses,
        ]);
    }

    public function update(Request $request, $id)
    {
        $charter = CharterBooking::findOrFail($id);
        
        $validated = $request->validate([
            'total_price' => 'nullable|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'assigned_bus_ids' => 'nullable|array',
            'assigned_bus_ids.*' => 'exists:buses,id',
            'status' => 'required|in:pending,quoted,confirmed,completed,cancelled',
            'payment_method' => 'nullable|in:system,manual',
            'payment_status' => 'nullable|in:unpaid,dp_paid,fully_paid,failed',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if (!empty($validated['assigned_bus_ids'])) {
            $overlapping = CharterBooking::where('id', '!=', $id)
                ->whereHas('buses', function($q) use ($validated) {
                    $q->whereIn('bus_id', $validated['assigned_bus_ids']);
                })
                ->where(function($q) {
                    $q->whereIn('payment_status', ['dp_paid', 'fully_paid', 'paid', 'partial'])
                      ->orWhereIn('status', ['confirmed', 'completed']);
                })
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($charter) {
                    $query->whereBetween('pickup_date', [$charter->pickup_date, $charter->return_date])
                        ->orWhereBetween('return_date', [$charter->pickup_date, $charter->return_date])
                        ->orWhere(function ($q) use ($charter) {
                            $q->where('pickup_date', '<=', $charter->pickup_date)
                              ->where('return_date', '>=', $charter->return_date);
                        });
                })
                ->exists();

            if ($overlapping) {
                return back()->withErrors(['assigned_bus_ids' => 'Satu atau lebih bus tidak bisa dipilih karena sudah dipesan (DP Lunas) oleh penyewa lain pada tanggal tersebut.']);
            }
        }

        // If price is set, automatically change status to quoted if it was pending
        if ($charter->status === 'pending' && isset($validated['total_price']) && $validated['total_price'] > 0) {
            $validated['status'] = 'quoted';
        }

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'proof_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/proofs'), $filename);
            $validated['payment_proof'] = 'uploads/proofs/' . $filename;
        }

        unset($validated['assigned_bus_ids']);
        $charter->update($validated);
        
        if ($request->has('assigned_bus_ids')) {
            $charter->buses()->sync($request->input('assigned_bus_ids', []));
        }

        return back()->with('success', 'Data sewa pariwisata berhasil diperbarui.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }
        CharterBooking::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' data berhasil dihapus.']);
    }
}
