<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    /**
     * Determine if user can view the booking.
     * Ownership check: hanya owner atau admin yang bisa view
     */
    public function view(User $user, Booking $booking): bool
    {
        // Owner bisa view
        if ($user->id === $booking->user_id) {
            return true;
        }

        // Admin bisa view semua
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can update the booking.
     * Ownership check: hanya owner booking yang boleh update (sebelum payment/departure)
     */
    public function update(User $user, Booking $booking): bool
    {
        // Owner bisa update sebelum confirmation/payment
        if ($user->id === $booking->user_id) {
            // Cegah update jika sudah di-confirm atau sudah berangkat
            if (in_array($booking->booking_status, ['confirmed', 'departed', 'completed', 'cancelled'])) {
                return false;
            }
            return true;
        }

        // Admin bisa update anytime (untuk correction)
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can process payment untuk booking ini.
     * Ownership check: hanya owner booking yang boleh bayar
     */
    public function pay(User $user, Booking $booking): bool
    {
        // Owner booking bisa bayar
        if ($user->id === $booking->user_id) {
            // Cegah bayar jika sudah lunas atau cancelled
            if (in_array($booking->payment_status, ['paid', 'cancelled'])) {
                return false;
            }

            // Cegah bayar jika jadwal sudah berangkat
            if ($booking->booking_status === 'departed') {
                return false;
            }

            return true;
        }

        // Admin bisa proses payment untuk user manapun (untuk manual correction)
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Owner bisa cancel selama belum berangkat
        if ($user->id === $booking->user_id) {
            if (in_array($booking->booking_status, ['departed', 'completed'])) {
                return false;
            }
            return true;
        }

        // Admin bisa cancel anytime
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can delete the booking.
     * Hanya admin yang bisa hard-delete
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can restore the booking.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if user can permanently delete the booking.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return $user->hasRole('admin');
    }
}
