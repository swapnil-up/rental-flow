<?php

namespace Domain\Bookings\States;

class ActiveBookingState extends BookingState
{
    public function color(): string
    {
        return 'green';
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
