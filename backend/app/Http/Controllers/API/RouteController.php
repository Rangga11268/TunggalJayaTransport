<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Route as BusRoute;
use Illuminate\Http\JsonResponse;

class RouteController extends Controller
{
    /**
     * @OA\Get(
     *      path="/routes",
     *      operationId="getRoutes",
     *      tags={"Master Data"},
     *      summary="Daftar Rute Bus AKAP",
     *      description="Mendapatkan daftar seluruh rute beserta estimasi harga dasar",
     *      @OA\Response(response=200, description="Berhasil mengambil rute")
     * )
     */
    public function index(): JsonResponse
    {
        $routes = BusRoute::select('id', 'name', 'origin', 'destination', 'duration', 'distance')
            ->withMin('schedules', 'price')
            ->orderBy('origin')
            ->get()
            ->map(function($route) {
                $route->base_price = $route->schedules_min_price;
                return $route;
            });

        return response()->json([
            'success' => true,
            'data' => $routes,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/routes/{id}",
     *      operationId="getRouteDetail",
     *      tags={"Master Data"},
     *      summary="Detail Rute Bus",
     *      @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *      @OA\Response(response=200, description="Berhasil mengambil detail rute"),
     *      @OA\Response(response=404, description="Rute tidak ditemukan")
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/routes/origins-destinations",
     *      operationId="getOriginsDestinations",
     *      tags={"Master Data"},
     *      summary="Autocomplete Kota Asal & Tujuan",
     *      description="Mendapatkan daftar unik nama kota asal dan kota tujuan untuk form pencarian",
     *      @OA\Response(response=200, description="Berhasil mengambil daftar kota")
     * )
     */
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
