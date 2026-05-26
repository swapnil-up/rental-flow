<?php

namespace Domain\Bookings\States;

use Domain\Bookings\Models\Booking;

abstract class BookingState
{
    public function __construct(
        protected Booking $booking
    ) {}

    abstract public function color(): string;

    abstract public function canBeCancelled(): bool;

    abstract public function canBeConfirmed(): bool;

    abstract public function canBeModified(): bool;

    public function availableActions(): array
    {
        return array_filter([
            $this->canBeCancelled() ? 'cancel' : null,
            $this->canBeConfirmed() ? 'confirm' : null,
            $this->canBeModified() ? 'modify' : null,
        ]);
    }
}
