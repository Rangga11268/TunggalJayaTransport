<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CharterBooking;
use App\Models\Bus;
use Illuminate\Http\Request;
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

    public function show($id)
    {
        $charter = CharterBooking::with(['user', 'assignedBus'])->findOrFail($id);
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
