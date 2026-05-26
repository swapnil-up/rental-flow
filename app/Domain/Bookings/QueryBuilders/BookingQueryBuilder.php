<?php

namespace Domain\Bookings\QueryBuilders;

use Carbon\Carbon;
use Domain\Bookings\States\BookingStatus;
use Illuminate\Database\Eloquent\Builder;

class BookingQueryBuilder extends Builder
{
    /**
     * Get only confirmed bookings
     */
    public function whereConfirmed(): self
    {
        return $this->where('status', BookingStatus::Confirmed);
    }

    public function whereActive(): self
    {
        return $this->where('status', BookingStatus::Active);
    }

    public function whereCancellable(): self
    {
        return $this->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->where('check_in', '>', now());
    }

    /**
     * Get upcoming bookings (check-in within X days)
     */
    public function whereUpcoming(int $days = 30): self
    {
        return $this->whereBetween('check_in', [
            now(),
            now()->addDays($days),
        ]);
    }

    /**
     * Get bookings for a specific property
     */
    public function forProperty(int $propertyId): self
    {
        return $this->where('property_id', $propertyId);
    }

    /**
     * Get bookings that overlap with given dates
     */
    public function whereOverlaps(Carbon $checkIn, Carbon $checkOut): self
    {
        return $this->where(function ($query) use ($checkIn, $checkOut) {
            $query->whereBetween('check_in', [$checkIn, $checkOut])
                ->orWhereBetween('check_out', [$checkIn, $checkOut])
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkIn)
                      ->where('check_out', '>=', $checkOut);
                });
        });
    }
}