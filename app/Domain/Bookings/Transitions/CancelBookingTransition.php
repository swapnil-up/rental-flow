<?php

namespace Domain\Bookings\Transitions;

use Domain\Bookings\Models\Booking;
use Illuminate\Support\Facades\Log;

class CancelBookingTransition extends BookingTransition
{
    public function execute(Booking $booking): Booking
    {
        $this->validate($booking);

        $booking->status = 'cancelled';
        $booking->save();

        $this->afterTransition($booking);

        return $booking;
    }

    protected function validate(Booking $booking): void
    {
        if (!$booking->state->canBeCancelled()) {
            throw new \DomainException(
                "Cannot cancel booking in {$booking->status} state"
            );
        }
    }

    protected function afterTransition(Booking $booking): void
    {
        Log::info('Booking cancelled', [
            'booking_id' => $booking->id,
            'property_id' => $booking->property_id,
            'cancelled_at' => now(),
        ]);

        // process refund, send cancellation email, etc.
    }
}