<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BookingFilterService
{
    /**
     * Apply booking filters to schedules query
     * 
     * Unifies filter logic across all endpoints (index, schedules, etc)
     * to prevent divergent bugs when one method is modified but not the other.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters Associative array with keys: origin, destination, date, class, time
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilters($query, array $filters)
    {
        // Normalize input: trim strings, filter empty values
        $origin = isset($filters['origin']) ? trim($filters['origin']) : null;
        $destination = isset($filters['destination']) ? trim($filters['destination']) : null;
        $date = isset($filters['date']) ? trim($filters['date']) : null;
        $classList = isset($filters['class']) && is_string($filters['class'])
            ? array_filter(array_map('trim', explode(',', $filters['class'])))
            : [];
        $timeList = isset($filters['time']) && is_string($filters['time'])
            ? array_filter(array_map('trim', explode(',', $filters['time'])))
            : [];

        // Initialize date context
        $effectiveDate = null;
        if ($date) {
            try {
                $effectiveDate = Carbon::parse($date);
            } catch (\Exception $e) {
                $effectiveDate = null;
            }
        }
        // Use effective date if provided, otherwise default to today
        $referenceDate = $effectiveDate ?? Carbon::today();

        // Filter by origin (via route)
        if ($origin) {
            $query->whereHas('route', function ($q) use ($origin) {
                $q->where('origin', 'LIKE', '%' . $origin . '%');
            });
        }

        // Filter by destination (via route)
        if ($destination) {
            $query->whereHas('route', function ($q) use ($destination) {
                $q->where('destination', 'LIKE', '%' . $destination . '%');
            });
        }

        // Filter by bus class
        if (!empty($classList)) {
            $query->whereHas('bus', function ($q) use ($classList) {
                $q->whereIn('class', $classList);
            });
        }

        // Filter by bus (join with count of booked seats)
        $query->with('bus', 'route')
            ->withSum(['bookings as booked_seats_count' => function ($q) use ($referenceDate) {
                $q->where('booking_status', 'confirmed')
                    ->where('payment_status', 'paid')
                    ->whereDate('booking_date', $referenceDate->toDateString());
            }], 'number_of_seats')
            ->where('is_active', true);

        return $query;
    }

    /**
     * Filter schedules collection by date and time
     * Applied AFTER database query to avoid complex SQL
     * 
     * @param Collection $schedules
     * @param string|null $date
     * @param array $timeList
     * @return Collection
     */
    public function filterByDateAndTime(Collection $schedules, $date = null, array $timeList = [])
    {
        // Parse effective date
        $effectiveDate = null;
        if ($date) {
            try {
                $effectiveDate = Carbon::parse($date);
            } catch (\Exception $e) {
                $effectiveDate = null;
            }
        }

        return $schedules->filter(function ($schedule) use ($effectiveDate, $timeList) {
            // === Date Filter ===
            if ($effectiveDate) {
                if ($schedule->is_daily) {
                    // Daily schedule: check if route operates on this weekday
                    $dayName = $effectiveDate->format('l'); // e.g., "Monday"
                    $daysOfWeek = json_decode($schedule->days_of_week, true) ?? [];
                    if (!in_array($dayName, $daysOfWeek)) {
                        return false;
                    }
                } else {
                    // Non-daily schedule: must match specific departure date
                    try {
                        $departureDate = $schedule->departure_time instanceof Carbon
                            ? $schedule->departure_time->copy()->startOfDay()
                            : Carbon::parse($schedule->departure_time)->startOfDay();
                    } catch (\Exception $e) {
                        return false;
                    }

                    if (!$departureDate->isSameDay($effectiveDate)) {
                        return false;
                    }

                    // Also block if already departed
                    if ($departureDate->isPast()) {
                        return false;
                    }
                }
            } else {
                // No date selected: exclude non-daily schedules that have departed
                if (!$schedule->is_daily) {
                    try {
                        $departureDate = $schedule->departure_time instanceof Carbon
                            ? $schedule->departure_time
                            : Carbon::parse($schedule->departure_time);
                    } catch (\Exception $e) {
                        return false;
                    }

                    if ($departureDate->isPast()) {
                        return false;
                    }
                }
            }

            // === Time Filter ===
            if (!empty($timeList)) {
                try {
                    $departureTime = $schedule->departure_time instanceof Carbon
                        ? $schedule->departure_time->format('H:i:s')
                        : Carbon::parse($schedule->departure_time)->format('H:i:s');
                } catch (\Exception $e) {
                    return false;
                }

                // Check if departure time hour is in selected time windows
                $hour = intval(substr($departureTime, 0, 2));
                $timeMatched = false;

                foreach ($timeList as $timeWindow) {
                    if (strpos($timeWindow, '-') !== false) {
                        [$startHour, $endHour] = explode('-', $timeWindow);
                        $startHour = intval(trim($startHour));
                        $endHour = intval(trim($endHour));

                        // e.g., "00-06", "06-12", "12-18", "18-24"
                        if ($hour >= $startHour && $hour < $endHour) {
                            $timeMatched = true;
                            break;
                        }
                    }
                }

                if (!$timeMatched) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Get available seats for a specific schedule and date
     * 
     * @param Schedule $schedule
     * @param Carbon|string|null $date
     * @return int
     */
    public function getAvailableSeats(Schedule $schedule, $date = null): int
    {
        try {
            $bookingDate = $date instanceof Carbon
                ? $date
                : ($date ? Carbon::parse($date) : Carbon::today());
        } catch (\Exception $e) {
            $bookingDate = Carbon::today();
        }

        $bookedCount = $schedule->bookings()
            ->where('booking_status', 'confirmed')
            ->where('payment_status', 'paid')
            ->whereDate('booking_date', $bookingDate->toDateString())
            ->sum('number_of_seats');

        return max(0, $schedule->bus->capacity - ($bookedCount ?? 0));
    }
}
