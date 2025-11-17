<?php

namespace Domain\Bookings\Actions;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\Transitions\ConfirmBookingTransition;

class ConfirmBookingAction
{
    public function __construct(
        private ConfirmBookingTransition $transition
    ) {}

    public function execute(Booking $booking): Booking
    {
        return $this->transition->execute($booking);
    }
}