<?php

namespace Domain\Bookings\Transitions;

use Domain\Bookings\Models\Booking;
use Illuminate\Support\Facades\Log;

class ConfirmBookingTransition extends BookingTransition
{
    public function execute(Booking $booking): Booking
    {
        $this->validate($booking);

        $booking->status = 'confirmed';
        $booking->save();

        $this->afterTransition($booking);

        return $booking;
    }

    protected function validate(Booking $booking): void
    {
        if (!$booking->state->canBeConfirmed()) {
            throw new \DomainException(
                "Cannot confirm booking in {$booking->status} state"
            );
        }

        // Additional business rules
        if ($booking->check_in->isPast()) {
            throw new \DomainException(
                "Cannot confirm booking with past check-in date"
            );
        }
    }

    protected function afterTransition(Booking $booking): void
    {
        Log::info('Booking confirmed', [
            'booking_id' => $booking->id,
            'property_id' => $booking->property_id,
        ]);

        // send confirmation email, update calendar, etc.
    }
}