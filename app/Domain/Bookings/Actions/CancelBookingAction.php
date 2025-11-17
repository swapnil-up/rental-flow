<?php

namespace Domain\Bookings\Actions;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\Transitions\CancelBookingTransition;

class CancelBookingAction
{
    public function __construct(
        private CancelBookingTransition $transition
    ) {}

    public function execute(Booking $booking): Booking
    {
        return $this->transition->execute($booking);
    }
}