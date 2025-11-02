<?php

namespace Domain\Properties\Actions;

use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Properties\Models\Property;

class CheckPropertyAvailabilityAction
{
    public function execute(
        Property $property,
        Carbon $checkIn,
        Carbon $checkOut
    ): bool {
        // Property must be available status
        if ($property->status !== 'available') {
            return false;
        }

        // Check for overlapping bookings
        $hasOverlap = Booking::query()
            ->where('property_id', $property->id)
            ->whereIn('status', ['confirmed', 'active'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                // Check in falls within existing booking
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    // Check out falls within existing booking
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    // Existing booking falls within new booking
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            })
            ->exists();

        return !$hasOverlap;
    }
}
