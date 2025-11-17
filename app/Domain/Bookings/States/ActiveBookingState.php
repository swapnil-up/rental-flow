<?php

namespace Domain\Bookings\States;

class ActiveBookingState extends BookingState
{
    public static function value(): string
    {
        return 'active';
    }

    public function color(): string
    {
        return 'green';
    }

    public function canBeCancelled(): bool
    {
        return false; // Cannot cancel once checked in
    }

    public function canBeConfirmed(): bool
    {
        return false;
    }

    public function canBeModified(): bool
    {
        return false; // Cannot modify during stay
    }
}