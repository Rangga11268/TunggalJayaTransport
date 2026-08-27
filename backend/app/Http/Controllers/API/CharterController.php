<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CharterBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CharterController extends Controller
{
    /**
     * @OA\Get(
     *      path="/charter/history",
     *      operationId="getCharterHistory",
     *      tags={"Sewa Pariwisata"},
     *      summary="Riwayat Sewa Bus Pariwisata",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200, description="Daftar pengajuan sewa")
     * )
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
     * @OA\Post(
     *      path="/charter/request",
     *      operationId="requestCharter",
     *      tags={"Sewa Pariwisata"},
     *      summary="Pengajuan Sewa Bus Pariwisata Baru",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"pickup_date","return_date","pickup_location","destination","bus_requests"},
     *              @OA\Property(property="pickup_location", type="string", example="Kuningan, Jawa Barat"),
     *              @OA\Property(property="destination", type="string", example="Bali, Nusa Tenggara"),
     *              @OA\Property(property="pickup_date", type="string", format="date", example="2026-08-10"),
     *              @OA\Property(property="return_date", type="string", format="date", example="2026-08-15")
     *          )
     *      ),
     *      @OA\Response(response=201, description="Pengajuan sewa berhasil dibuat")
     * )
     */
    public function store(Request $request)
    {
        $minDate = now()->addDays(3)->toDateString();

        $validated = $request->validate([
            'institution_name' => 'nullable|string|max:255',
            'pickup_date' => 'required|date|after_or_equal:' . $minDate,
            'return_date' => 'required|date|after_or_equal:pickup_date',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'bus_requests' => 'required|array|min:1',
            'bus_requests.*.type' => 'required|string',
            'bus_requests.*.count' => 'required|integer|min:1',
            'bus_requests.*.with_legrest' => 'boolean',
            'bus_requests.*.seat_configuration' => 'nullable|string',
            'passenger_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $pickupDate = $validated['pickup_date'];
        $returnDate = $validated['return_date'];
        
        $overlappingBookingsCount = \App\Models\CharterBooking::where('status', '!=', 'cancelled')
            ->where(function ($query) use ($pickupDate, $returnDate) {
                $query->whereBetween('pickup_date', [$pickupDate, $returnDate])
                    ->orWhereBetween('return_date', [$pickupDate, $returnDate])
                    ->orWhere(function ($q) use ($pickupDate, $returnDate) {
                        $q->where('pickup_date', '<=', $pickupDate)
                          ->where('return_date', '>=', $returnDate);
                    });
            })
            ->sum('bus_count');

        $totalPariwisataBuses = \App\Models\Bus::where('bus_category', 'pariwisata')->where('status', 'active')->count();
        $availableBuses = max(0, $totalPariwisataBuses - $overlappingBookingsCount);

        $requestedCount = collect($validated['bus_requests'])->sum('count');
        if ($requestedCount > $availableBuses) {
            return response()->json([
                'status' => 'error',
                'message' => "Maaf, saat ini hanya tersedia {$availableBuses} unit bus pariwisata pada tanggal tersebut.",
            ], 400);
        }

        $charterCode = 'CHRT-' . strtoupper(Str::random(8));

        $charter = CharterBooking::create([
            'charter_code' => $charterCode,
            'user_id' => $request->user()->id,
            'institution_name' => $validated['institution_name'] ?? null,
            'bus_requests' => $validated['bus_requests'],
            'bus_count' => $requestedCount,
            'passenger_count' => $validated['passenger_count'] ?? null,
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

    /**
     * Cancel a charter booking before departure.
     */
    public function cancel(Request $request, $id)
    {
        $charter = CharterBooking::where('user_id', $request->user()->id)->findOrFail($id);
        
        // Aturan: pembatalan maksimal sebelum jam 8 pagi pada hari keberangkatan (H)
        $cutoffTime = \Carbon\Carbon::parse($charter->pickup_date)->startOfDay()->addHours(8); // Jam 08:00 hari H
        
        if (now()->greaterThanOrEqualTo($cutoffTime)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembatalan hanya bisa dilakukan sebelum jam 08:00 pada hari keberangkatan.',
            ], 400);
        }

        if (in_array($charter->status, ['cancelled', 'completed'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak dapat dibatalkan pada status ini.',
            ], 400);
        }

        $charter->update(['status' => 'cancelled']);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesanan charter berhasil dibatalkan.',
        ]);
    }
}
