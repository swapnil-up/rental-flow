<?php

namespace Domain\Bookings\States;

class CancelledBookingState extends BookingState
{
    public static function value(): string
    {
        return 'cancelled';
    }

    public function color(): string
    {
        return 'red';
    }

    public function canBeCancelled(): bool
    {
        return false; // Already cancelled
    }

    public function canBeConfirmed(): bool
    {
        return false;
    }

    public function canBeModified(): bool
    {
        return false;
    }
}