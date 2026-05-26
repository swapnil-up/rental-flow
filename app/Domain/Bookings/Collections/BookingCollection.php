<?php

namespace Domain\Bookings\Collections;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Illuminate\Database\Eloquent\Collection;

class BookingCollection extends Collection
{
    /**
     * Get only confirmed bookings
     */
    public function confirmed(): self
    {
        return $this->filter(fn (Booking $booking) =>
            $booking->status === BookingStatus::Confirmed
        );
    }

    /**
     * Get only cancellable bookings
     */
    public function cancellable(): self
    {
        return $this->filter(fn (Booking $booking) => 
            $booking->state->canBeCancelled()
        );
    }

    /**
     * Get upcoming bookings (check-in is in the future)
     */
    public function upcoming(): self
    {
        return $this->filter(fn (Booking $booking) => 
            $booking->check_in->isFuture()
        );
    }

    /**
     * Get past bookings (check-out is in the past)
     */
    public function past(): self
    {
        return $this->filter(fn (Booking $booking) => 
            $booking->check_out->isPast()
        );
    }

    /**
     * Calculate total nights across all bookings
     */
    public function totalNights(): int
    {
        return $this->sum(fn (Booking $booking) => 
            $booking->check_in->diffInDays($booking->check_out)
        );
    }

    /**
     * Group by property
     */
    public function groupedByProperty(): array
    {
        return $this->groupBy('property_id')->toArray();
    }
}