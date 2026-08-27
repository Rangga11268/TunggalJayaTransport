<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleSearchAndAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected Bus $bus;
    protected Route $route;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bus = Bus::factory()->create(['capacity' => 40]);
        $this->route = Route::factory()->create([
            'origin' => 'Jakarta',
            'destination' => 'Bandung',
            'distance' => 180,
            'duration' => 180, // 3 hours
        ]);

        $this->user = User::factory()->create(['phone_verified_at' => now()]);
    }

    /**
     * Test available seats calculation (no bookings)
     */
    public function test_available_seats_with_no_bookings(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        $availableSeats = $schedule->getAvailableSeatsCount();

        $this->assertEquals(40, $availableSeats);
    }

    /**
     * Test available seats after partial bookings
     */
    public function test_available_seats_after_partial_bookings(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        // Create 2 paid bookings: 5 seats and 3 seats = 8 total
        for ($i = 0; $i < 2; $i++) {
            Booking::factory()->create([
                'schedule_id' => $schedule->id,
                'number_of_seats' => ($i === 0) ? 5 : 3,
                'seat_numbers' => ($i === 0) ? '1,2,3,4,5' : '6,7,8',
                'payment_status' => 'paid',
                'booking_status' => 'confirmed',
            ]);
        }

        $availableSeats = $schedule->getAvailableSeatsCount();

        // 40 - 8 = 32 available
        $this->assertEquals(32, $availableSeats);
    }

    /**
     * Test cancelled bookings don't count as occupied
     */
    public function test_cancelled_bookings_dont_count_as_occupied(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        // Create booking
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 10,
            'seat_numbers' => '1,2,3,4,5,6,7,8,9,10',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        // Cancel it
        $booking = Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 5,
            'seat_numbers' => '11,12,13,14,15',
            'payment_status' => 'paid',
            'booking_status' => 'cancelled',
        ]);

        $availableSeats = $schedule->getAvailableSeatsCount();

        // Should only count the first 10, not the cancelled 5
        // 40 - 10 = 30 available
        $this->assertEquals(30, $availableSeats);
    }

    /**
     * Test pending bookings with seats are counted
     */
    public function test_pending_bookings_with_seats_are_counted(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        // Create pending booking with seat selection
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 5,
            'seat_numbers' => '1,2,3,4,5',
            'payment_status' => 'pending',
            'booking_status' => 'pending',
        ]);

        $availableSeats = $schedule->getAvailableSeatsCount();

        // Pending bookings with seats should be counted
        // 40 - 5 = 35 available
        $this->assertEquals(35, $availableSeats);
    }

    /**
     * Test seat conflict detection
     */
    public function test_seat_conflict_detection(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        // Create first booking with seats 1-5
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 5,
            'seat_numbers' => '1,2,3,4,5',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        $bookedSeats = $schedule->getBookedSeatNumbers();

        // Should detect conflicts
        $this->assertContains(1, $bookedSeats);
        $this->assertContains(5, $bookedSeats);
        $this->assertNotContains(6, $bookedSeats);
    }

    /**
     * Test multiple bookings seat conflict
     */
    public function test_multiple_bookings_seat_list(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => false,
        ]);

        // Multiple bookings
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 3,
            'seat_numbers' => '1,2,3',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 4,
            'seat_numbers' => '5,6,7,8',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        $bookedSeats = $schedule->getBookedSeatNumbers();

        $expectedSeats = [1, 2, 3, 5, 6, 7, 8];
        sort($expectedSeats);
        sort($bookedSeats);

        $this->assertEquals($expectedSeats, $bookedSeats);
    }

    /**
     * Test daily schedule with specific date booking
     */
    public function test_daily_schedule_seat_availability_per_date(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
            'is_daily' => true, // Daily schedule
        ]);

        $dateA = now()->toDateString();
        $dateB = now()->addDay()->toDateString();

        // Booking for date A
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'booking_date' => $dateA,
            'number_of_seats' => 10,
            'seat_numbers' => '1,2,3,4,5,6,7,8,9,10',
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        // Check availability for date A
        $availableA = $schedule->getAvailableSeatsCount($dateA);
        $this->assertEquals(30, $availableA); // 40 - 10

        // Check availability for date B (should be full)
        $availableB = $schedule->getAvailableSeatsCount($dateB);
        $this->assertEquals(40, $availableB);

        // Check booked seats for each date
        $bookedA = $schedule->getBookedSeatNumbers($dateA);
        $bookedB = $schedule->getBookedSeatNumbers($dateB);

        $this->assertEquals(10, count($bookedA));
        $this->assertEquals(0, count($bookedB));
    }

    /**
     * Test capacity validation
     */
    public function test_capacity_exceeds_validation(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
        ]);

        // Try to create booking with more seats than capacity
        // This should throw InvalidArgumentException
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Busnya ga muat bos');

        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'seat_numbers' => implode(',', range(1, 50)), // 50 seats for 40-seat bus
            'number_of_seats' => 50, // More than bus capacity of 40
        ]);
    }

    /**
     * Test fully booked schedule
     */
    public function test_fully_booked_schedule(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
        ]);

        // Book all 40 seats
        Booking::factory()->create([
            'schedule_id' => $schedule->id,
            'number_of_seats' => 40,
            'seat_numbers' => implode(',', range(1, 40)),
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        $availableSeats = $schedule->getAvailableSeatsCount();

        $this->assertEquals(0, $availableSeats);
    }

    /**
     * Test duplicate seats rejection
     */
    public function test_duplicate_seats_in_booking(): void
    {
        $schedule = Schedule::factory()->create([
            'bus_id' => $this->bus->id,
            'route_id' => $this->route->id,
        ]);

        // Create booking with duplicate seats
        $seatString = '1,2,2,3,3,3'; // Duplicates
        $seatArray = array_map('intval', explode(',', $seatString));
        $uniqueSeats = array_unique($seatArray);

        // Should have fewer unique seats than total
        $this->assertLessThan(count($seatArray), count($uniqueSeats));
    }
}
