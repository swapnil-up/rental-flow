<?php

namespace Domain\Bookings\States;

use Carbon\Carbon;

class ConfirmedBookingState extends BookingState
{
    public static function value(): string
    {
        return 'confirmed';
    }

    public function color(): string
    {
        return 'blue';
    }

    public function canBeCancelled(): bool
    {
        // Can cancel if check-in is still in the future
        return $this->booking->check_in->isFuture();
    }

    public function canBeConfirmed(): bool
    {
        return false; // Already confirmed
    }

    public function canBeModified(): bool
    {
        // Can modify if check-in is more than 48 hours away
        return $this->booking->check_in->diffInHours(now()) > 48;
    }
}