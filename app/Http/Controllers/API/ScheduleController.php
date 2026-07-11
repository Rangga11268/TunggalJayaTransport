<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $origin = $request->get('origin');
        $destination = $request->get('destination');
        $dateParam = $request->get('date');
        $searchDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();

        $query = Schedule::with(['bus', 'route'])
            ->available()
            ->withSum(['bookings as booked_seats_count' => function ($q) use ($searchDate) {
                $q->where('booking_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereDate('booking_date', $searchDate);
            }], 'number_of_seats');

        if ($origin) {
            $query->whereHas('route', fn($q) => $q->where('origin', $origin));
        }

        if ($destination) {
            $query->whereHas('route', fn($q) => $q->where('destination', $destination));
        }

        if ($dateParam) {
            $query->where(function ($q) use ($searchDate) {
                $q->where(function ($sub) use ($searchDate) {
                    $sub->where('is_daily', false)
                        ->whereDate('departure_time', $searchDate->toDateString());
                })->orWhere('is_daily', true);
            });
        }

        $schedules = $query->get()->filter(function ($schedule) use ($searchDate) {
            if ($schedule->hasDeparted($searchDate)) return false;
            if (!$schedule->isAvailableForBooking($searchDate)) return false;

            if ($schedule->is_daily && !empty($schedule->days_of_week)) {
                $dayName = $searchDate->format('l');
                $allowedDays = is_string($schedule->days_of_week)
                    ? json_decode($schedule->days_of_week, true)
                    : $schedule->days_of_week;
                if (is_array($allowedDays) && !in_array($dayName, $allowedDays)) return false;
            }

            return true;
        })->values()->map(function ($schedule) use ($searchDate) {
            $bookedSeats = $schedule->booked_seats_count ?? 0;
            $availableSeats = max(0, $schedule->bus->capacity - $bookedSeats);

            return [
                'id' => $schedule->id,
                'price' => (float) $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($searchDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($searchDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'available_seats' => $availableSeats,
                'bus' => [
                    'name' => $schedule->bus->name,
                    'type' => $schedule->bus->bus_type,
                    'capacity' => $schedule->bus->capacity,
                    'plate_number' => $schedule->bus->plate_number,
                ],
                'route' => [
                    'id' => $schedule->route->id,
                    'origin' => $schedule->route->origin,
                    'destination' => $schedule->route->destination,
                    'name' => $schedule->route->name,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $schedules,
        ]);
    }

    public function show($id, Request $request): JsonResponse
    {
        $schedule = Schedule::with('route', 'bus')->find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan',
            ], 404);
        }

        $dateParam = $request->get('date');
        $checkDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today();

        if ($schedule->hasDeparted($checkDate)) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal sudah berangkat',
            ], 400);
        }

        $bookedSeats = $schedule->getBookedSeatNumbers($checkDate);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $schedule->id,
                'price' => (float) $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($checkDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($checkDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'route' => $schedule->route,
                'bus' => $schedule->bus,
                'occupied_seats' => $bookedSeats,
            ],
        ]);
    }
}
