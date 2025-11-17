<?php

namespace Domain\Bookings\Transitions;

use Domain\Bookings\Models\Booking;

abstract class BookingTransition
{
    /**
     * Execute the transition
     */
    abstract public function execute(Booking $booking): Booking;

    /**
     * Validate that transition can occur
     */
    abstract protected function validate(Booking $booking): void;

    /**
     * Perform side effects after transition
     */
    protected function afterTransition(Booking $booking): void
    {
        // Override in child classes if needed
    }
}