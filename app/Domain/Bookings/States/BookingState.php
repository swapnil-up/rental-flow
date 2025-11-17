<?php

namespace Domain\Bookings\States;

use Domain\Bookings\Models\Booking;

abstract class BookingState
{
    public function __construct(
        protected Booking $booking
    ) {}

    /**
     * Get the state name/value for database storage
     */
    abstract public static function value(): string;

    /**
     * Get display color for UI badges
     */
    abstract public function color(): string;

    /**
     * Can this booking be cancelled?
     */
    abstract public function canBeCancelled(): bool;

    /**
     * Can this booking be confirmed?
     */
    abstract public function canBeConfirmed(): bool;

    /**
     * Can this booking be modified?
     */
    abstract public function canBeModified(): bool;

    /**
     * Get available actions for this state
     */
    public function availableActions(): array
    {
        $actions = [];

        if ($this->canBeCancelled()) {
            $actions[] = 'cancel';
        }

        if ($this->canBeConfirmed()) {
            $actions[] = 'confirm';
        }

        if ($this->canBeModified()) {
            $actions[] = 'modify';
        }

        return $actions;
    }
}