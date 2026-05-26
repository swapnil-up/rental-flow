<?php

namespace Domain\Bookings\States;

class CancelledBookingState extends BookingState
{
    public function color(): string
    {
        return 'red';
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
