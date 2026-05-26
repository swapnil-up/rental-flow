<?php

namespace Domain\Bookings\States;

use Domain\Bookings\Models\Booking;

enum BookingStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Active = 'active';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'orange',
            self::Confirmed => 'blue',
            self::Active => 'green',
            self::Completed => 'gray',
            self::Cancelled => 'red',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::Pending, self::Confirmed], true);
    }

    public function canBeConfirmed(): bool
    {
        return $this === self::Pending;
    }

    public function canBeModified(): bool
    {
        return $this === self::Pending;
    }

    public function availableActions(): array
    {
        return array_filter([
            $this->canBeCancelled() ? 'cancel' : null,
            $this->canBeConfirmed() ? 'confirm' : null,
            $this->canBeModified() ? 'modify' : null,
        ]);
    }

    public function resolveState(Booking $booking): BookingState
    {
        return match ($this) {
            self::Pending => new PendingBookingState($booking),
            self::Confirmed => new ConfirmedBookingState($booking),
            self::Active => new ActiveBookingState($booking),
            self::Completed => new CompletedBookingState($booking),
            self::Cancelled => new CancelledBookingState($booking),
        };
    }
}
