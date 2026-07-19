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
        $buses = Bus::where('status', 'active')->get();
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
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required_without:user_id|string|max:20',
            
            'assigned_bus_id' => 'required|exists:buses,id',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required|string',
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,quoted,confirmed,completed,cancelled',
            'payment_status' => 'required|in:unpaid,pending,partial,dp_paid,paid,failed',
            'payment_method' => 'nullable|in:system,manual',
            'notes' => 'nullable|string',
        ]);

        $overlapping = CharterBooking::where('assigned_bus_id', $validated['assigned_bus_id'])
            ->where(function($q) {
                $q->where('payment_status', 'dp_paid')
                  ->orWhere('payment_status', 'paid')
                  ->orWhere('payment_status', 'partial')
                  ->orWhere('status', 'confirmed')
                  ->orWhere('status', 'completed');
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
            return back()->withErrors(['assigned_bus_id' => 'Bus ini tidak bisa dipilih karena sudah dipesan (DP/Lunas) oleh penyewa lain pada rentang tanggal tersebut.'])->withInput();
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

        $bus = Bus::find($validated['assigned_bus_id']);
        
        $charterBooking = CharterBooking::create([
            'charter_code' => 'CHRT-' . strtoupper(Str::random(8)),
            'user_id' => $userId,
            'assigned_bus_id' => $validated['assigned_bus_id'],
            'bus_type_requested' => $bus->name . ' - ' . $bus->capacity . ' Seat',
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

        return redirect()->route('admin.charter-bookings.index')->with('success', 'Data sewa pariwisata berhasil ditambahkan.');
    }

    public function show($id)
    {
        $charter = CharterBooking::with(['user', 'assignedBus'])->findOrFail($id);
        $charter->checkAndCancelIfExpired();
        
        $buses = Bus::where('status', 'active')->get();

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
            'assigned_bus_id' => 'nullable|exists:buses,id',
            'status' => 'required|in:pending,quoted,confirmed,completed,cancelled',
            'payment_method' => 'nullable|in:system,manual',
            'payment_status' => 'nullable|in:unpaid,pending,partial,dp_paid,paid,failed',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if (!empty($validated['assigned_bus_id'])) {
            $overlapping = CharterBooking::where('id', '!=', $id)
                ->where('assigned_bus_id', $validated['assigned_bus_id'])
                ->where(function($q) {
                    $q->where('payment_status', 'dp_paid')
                      ->orWhere('payment_status', 'paid')
                      ->orWhere('payment_status', 'partial')
                      ->orWhere('status', 'confirmed')
                      ->orWhere('status', 'completed');
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
                return back()->withErrors(['assigned_bus_id' => 'Bus ini tidak bisa dipilih karena sudah dipesan (DP Lunas) oleh penyewa lain pada tanggal tersebut.']);
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

        $charter->update($validated);

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
