<?php

namespace Domain\Bookings\States;

class CompletedBookingState extends BookingState
{
    public static function value(): string
    {
        return 'completed';
    }

    public function color(): string
    {
        return 'gray';
    }

    public function canBeCancelled(): bool
    {
        return false;
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