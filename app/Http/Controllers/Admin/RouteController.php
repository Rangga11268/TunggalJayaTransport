<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RouteController extends Controller
{

    public function index(Request $request)
    {
        $routes = BusRoute::when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('origin', 'like', "%{$search}%")
                ->orWhere('destination', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'routes' => $routes
            ]);
        }

        return Inertia::render('Admin/Routes/Index', [
            'routes' => $routes,
            'filters' => $request->only(['search'])
        ]);
    }


    public function create()
    {
        return Inertia::render('Admin/Routes/Create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'origin_lat' => 'nullable|numeric|between:-90,90',
            'origin_lng' => 'nullable|numeric|between:-180,180',
            'destination_lat' => 'nullable|numeric|between:-90,90',
            'destination_lng' => 'nullable|numeric|between:-180,180',
            'waypoints' => 'nullable|array',
            'waypoints.*.name' => 'nullable|string|max:255',
            'waypoints.*.lat' => 'nullable|numeric|between:-90,90',
            'waypoints.*.lng' => 'nullable|numeric|between:-180,180',
            'distance' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        BusRoute::create($this->normalizeRoutePayload($request));

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dibuat.');
    }


    public function show(string $id)
    {
        // For now, redirect to edit as we don't have a dedicated show page yet
        return redirect()->route('admin.routes.edit', $id);
    }


    public function edit(string $id)
    {
        $busRoute = BusRoute::findOrFail($id);
        return Inertia::render('Admin/Routes/Edit', compact('busRoute'));
    }


    public function update(Request $request, string $id)
    {
        $route = BusRoute::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'origin' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'origin_lat' => 'nullable|numeric|between:-90,90',
            'origin_lng' => 'nullable|numeric|between:-180,180',
            'destination_lat' => 'nullable|numeric|between:-90,90',
            'destination_lng' => 'nullable|numeric|between:-180,180',
            'waypoints' => 'nullable|array',
            'waypoints.*.name' => 'nullable|string|max:255',
            'waypoints.*.lat' => 'nullable|numeric|between:-90,90',
            'waypoints.*.lng' => 'nullable|numeric|between:-180,180',
            'distance' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $route->update($this->normalizeRoutePayload($request));

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $route = BusRoute::findOrFail($id);
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Rute berhasil dihapus.');
    }

    public function geocode(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        $response = Http::withHeaders([
            'User-Agent' => 'TunggalJayaTransport/1.0',
            'Accept' => 'application/json',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $request->query('query'),
            'format' => 'jsonv2',
            'limit' => 1,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil koordinat dari layanan peta.',
            ], 502);
        }

        $result = $response->json()[0] ?? null;

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'display_name' => $result['display_name'] ?? $request->query('query'),
                'lat' => isset($result['lat']) ? (float) $result['lat'] : null,
                'lng' => isset($result['lon']) ? (float) $result['lon'] : null,
            ],
        ]);
    }

    private function normalizeRoutePayload(Request $request): array
    {
        return [
            'name' => $request->string('name')->toString(),
            'origin' => $request->string('origin')->toString(),
            'destination' => $request->string('destination')->toString(),
            'origin_lat' => $this->normalizeCoordinate($request->input('origin_lat')),
            'origin_lng' => $this->normalizeCoordinate($request->input('origin_lng')),
            'destination_lat' => $this->normalizeCoordinate($request->input('destination_lat')),
            'destination_lng' => $this->normalizeCoordinate($request->input('destination_lng')),
            'waypoints' => $this->normalizeWaypoints($request->input('waypoints', [])),
            'distance' => $this->normalizeCoordinate($request->input('distance')),
            'duration' => $this->normalizeInteger($request->input('duration')),
            'description' => $request->input('description'),
        ];
    }

    private function normalizeCoordinate(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function normalizeInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function normalizeWaypoints(mixed $waypoints): array
    {
        if (!is_array($waypoints)) {
            return [];
        }

        return collect($waypoints)
            ->filter(function ($waypoint) {
                return is_array($waypoint) && (
                    !empty($waypoint['name']) ||
                    isset($waypoint['lat']) ||
                    isset($waypoint['lng'])
                );
            })
            ->map(function ($waypoint) {
                return [
                    'name' => isset($waypoint['name']) ? trim((string) $waypoint['name']) : null,
                    'lat' => isset($waypoint['lat']) && $waypoint['lat'] !== '' ? (float) $waypoint['lat'] : null,
                    'lng' => isset($waypoint['lng']) && $waypoint['lng'] !== '' ? (float) $waypoint['lng'] : null,
                ];
            })
            ->values()
            ->all();
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada data dipilih.'], 400);
        }
        BusRoute::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => count($ids) . ' data berhasil dihapus.']);
    }
}
