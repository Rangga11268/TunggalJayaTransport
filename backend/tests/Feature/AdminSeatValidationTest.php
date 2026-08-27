<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSeatValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user;
    protected Bus $bus;
    protected Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create(['phone_verified_at' => now()]);
        $this->user = User::factory()->create(['phone_verified_at' => now()]);
        $this->bus = Bus::factory()->create(['capacity' => 40]);
        $this->schedule = Schedule::factory()->create(['bus_id' => $this->bus->id]);
    }

    /**
     * Test admin cannot create booking with mismatched seat count
     */
    public function test_seat_count_must_match_number_of_seats(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.bookings.store'), [
                'user_id' => $this->user->id,
                'schedule_id' => $this->schedule->id,
                'booking_date' => now()->toDateString(),
                'seat_numbers' => '1,2,3,4,5', // 5 seats
                'number_of_seats' => 3, // But claiming 3
                'passenger_name' => 'John Doe',
                'passenger_phone' => '08123456789',
                'passenger_email' => 'john@example.com',
                'total_price' => 150000,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ])
            ->assertSessionHasErrors('seat_numbers');
    }

    /**
     * Test seat number cannot exceed bus capacity
     */
    public function test_seat_number_cannot_exceed_capacity(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.bookings.store'), [
                'user_id' => $this->user->id,
                'schedule_id' => $this->schedule->id,
                'booking_date' => now()->toDateString(),
                'seat_numbers' => '42', // Bus only has 40 seats
                'number_of_seats' => 1,
                'passenger_name' => 'John Doe',
                'passenger_phone' => '08123456789',
                'passenger_email' => 'john@example.com',
                'total_price' => 30000,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ])
            ->assertSessionHasErrors('seat_numbers');
    }

    /**
     * Test cannot book already reserved seats
     */
    public function test_cannot_book_already_reserved_seats(): void
    {
        // Create existing booking with seat 5
        Booking::factory()->create([
            'schedule_id' => $this->schedule->id,
            'booking_date' => now(),
            'seat_numbers' => '5',
            'number_of_seats' => 1,
            'payment_status' => 'paid',
            'booking_status' => 'confirmed',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.bookings.store'), [
                'user_id' => $this->user->id,
                'schedule_id' => $this->schedule->id,
                'booking_date' => now()->toDateString(),
                'seat_numbers' => '4,5,6', // Seat 5 is taken
                'number_of_seats' => 3,
                'passenger_name' => 'John Doe',
                'passenger_phone' => '08123456789',
                'passenger_email' => 'john@example.com',
                'total_price' => 90000,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ])
            ->assertSessionHasErrors('seat_numbers');
    }

    /**
     * Test duplicate seats in input are rejected
     */
    public function test_duplicate_seats_rejected(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.bookings.store'), [
                'user_id' => $this->user->id,
                'schedule_id' => $this->schedule->id,
                'booking_date' => now()->toDateString(),
                'seat_numbers' => '5,5,6', // Seat 5 duplicated
                'number_of_seats' => 3,
                'passenger_name' => 'John Doe',
                'passenger_phone' => '08123456789',
                'passenger_email' => 'john@example.com',
                'total_price' => 90000,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ])
            ->assertSessionHasErrors('seat_numbers');
    }

    /**
     * Test valid booking is accepted
     */
    public function test_valid_booking_is_accepted(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.bookings.store'), [
                'user_id' => $this->user->id,
                'schedule_id' => $this->schedule->id,
                'booking_date' => now()->toDateString(),
                'seat_numbers' => '5,6,7',
                'number_of_seats' => 3,
                'passenger_name' => 'John Doe',
                'passenger_phone' => '08123456789',
                'passenger_email' => 'john@example.com',
                'total_price' => 90000,
                'payment_status' => 'pending',
                'booking_status' => 'pending',
            ])
            ->assertRedirect(route('admin.bookings.index'));

        $this->assertDatabaseHas('bookings', [
            'user_id' => $this->user->id,
            'schedule_id' => $this->schedule->id,
            'seat_numbers' => '5,6,7',
        ]);
    }
}
