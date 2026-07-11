<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\JsonResponse;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        $routes = BusRoute::select('id', 'name', 'origin', 'destination', 'duration', 'distance')
            ->orderBy('origin')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $routes,
        ]);
    }

    public function show($id): JsonResponse
    {
        $route = BusRoute::find($id);

        if (!$route) {
            return response()->json([
                'success' => false,
                'message' => 'Rute tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $route,
        ]);
    }

    public function originsDestinations(): JsonResponse
    {
        $origins = BusRoute::distinct()->pluck('origin')->values();
        $destinations = BusRoute::distinct()->pluck('destination')->values();

        return response()->json([
            'success' => true,
            'data' => [
                'origins' => $origins,
                'destinations' => $destinations,
            ],
        ]);
    }
}
