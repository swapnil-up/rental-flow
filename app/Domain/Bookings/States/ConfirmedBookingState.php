<?php

namespace Domain\Bookings\States;

class ConfirmedBookingState extends BookingState
{
    public function color(): string
    {
        return 'blue';
    }

    public function canBeCancelled(): bool
    {
        return true;
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
