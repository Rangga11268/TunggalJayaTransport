<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can only view their own bookings
     */
    public function test_user_cannot_view_other_users_booking(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create(['phone_verified_at' => now()]);
        /** @var User $user2 */
        $user2 = User::factory()->create(['phone_verified_at' => now()]);

        $booking = Booking::factory()->create(['user_id' => $user2->id]);

        $this->actingAs($user1)
            ->get(route('booking-history.show', $booking->id))
            ->assertStatus(404);
    }

    /**
     * Test user can view their own booking
     */
    public function test_user_can_view_own_booking(): void
    {
        /** @var User $user */
        $user = User::factory()->create(['phone_verified_at' => now()]);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('booking-history.show', $booking->id))
            ->assertStatus(200);
    }

    /**
     * Test admin can view any booking
     */
    public function test_admin_can_view_any_booking(): void
    {
        $admin = User::factory()->admin()->create(['phone_verified_at' => now()]);
        $user = User::factory()->create(['phone_verified_at' => now()]);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin)
            ->get(route('admin.bookings.show', $booking->id))
            ->assertStatus(200);
    }
}
