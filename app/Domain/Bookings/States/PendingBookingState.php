<?php

namespace Domain\Bookings\States;

class PendingBookingState extends BookingState
{
    public static function value(): string
    {
        return 'pending';
    }

    public function color(): string
    {
        return 'orange';
    }

    public function canBeCancelled(): bool
    {
        return true;
    }

    public function canBeConfirmed(): bool
    {
        return true;
    }

    public function canBeModified(): bool
    {
        return true;
    }
}