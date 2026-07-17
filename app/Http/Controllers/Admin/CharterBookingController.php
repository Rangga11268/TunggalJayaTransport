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
        ]);

        // If price is set, automatically change status to quoted if it was pending
        if ($charter->status === 'pending' && $validated['total_price'] > 0) {
            $validated['status'] = 'quoted';
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
