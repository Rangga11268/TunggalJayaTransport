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
        $searchDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today('Asia/Jakarta');

        $query = Schedule::with(['bus', 'route'])
            ->available()
            ->withSum(['bookings as booked_seats_count' => function ($q) use ($searchDate) {
                $q->where('booking_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereDate('booking_date', $searchDate);
            }], 'number_of_seats');

        if ($origin) {
            $query->whereHas('route', fn($q) => $q->where('origin', $origin));
        if ($origin && $origin !== 'Semua' && $origin !== 'all') {
            $cleanOrigin = trim(explode('(', $origin)[0]);
            $query->whereHas('route', fn($q) => $q->where('origin', 'like', "%{$cleanOrigin}%"));
        }

        if ($destination) {
            $query->whereHas('route', fn($q) => $q->where('destination', $destination));
        if ($destination && $destination !== 'Semua' && $destination !== 'all') {
            $cleanDest = trim(explode('(', $destination)[0]);
            $query->whereHas('route', fn($q) => $q->where('destination', 'like', "%{$cleanDest}%"));
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
            if ($schedule->status !== 'active') return false;

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
            $hasDeparted = $schedule->hasDeparted($searchDate);
            $nextDate = $searchDate->copy()->addDay();

            return [
                'id' => $schedule->id,
                'price' => (float) $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($searchDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($searchDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'available_seats' => $availableSeats,
                'is_departed' => $hasDeparted,
                'has_departed' => $hasDeparted,
                'selected_date' => $searchDate->toDateString(),
                'next_departure_date' => $nextDate->toDateString(),
                'next_departure_formatted' => 'Besok, ' . $nextDate->translatedFormat('d M Y') . ' • ' . $schedule->departure_time->format('H:i') . ' WIB',
                'bus' => [
                    'name' => $schedule->bus->name,
                    'type' => $schedule->bus->bus_type,
                    'capacity' => $schedule->bus->capacity,
                    'plate_number' => $schedule->bus->plate_number,
                    'image_url' => $schedule->bus->image_url,
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
        $checkDate = $dateParam ? Carbon::parse($dateParam) : Carbon::today('Asia/Jakarta');
        $hasDeparted = $schedule->hasDeparted($checkDate);
        $nextDate = $checkDate->copy()->addDay();

        $bookedSeats = $schedule->getBookedSeatNumbers($hasDeparted ? $nextDate : $checkDate);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $schedule->id,
                'price' => (float) $schedule->price,
                'departure_time' => $schedule->getActualDepartureTime($checkDate)->format('H:i'),
                'arrival_time' => $schedule->getActualArrivalTime($checkDate)->format('H:i'),
                'duration' => $schedule->route->formatted_duration,
                'is_departed' => $hasDeparted,
                'has_departed' => $hasDeparted,
                'selected_date' => $checkDate->toDateString(),
                'next_departure_date' => $nextDate->toDateString(),
                'next_departure_formatted' => 'Besok, ' . $nextDate->translatedFormat('d M Y') . ' • ' . $schedule->departure_time->format('H:i') . ' WIB',
                'route' => $schedule->route,
                'bus' => $schedule->bus,
                'occupied_seats' => $bookedSeats,
            ],
        ]);
    }
}
