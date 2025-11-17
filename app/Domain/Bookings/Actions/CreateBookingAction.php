<?php

namespace Domain\Bookings\Actions;

use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Properties\Actions\CheckPropertyAvailabilityAction;
use Domain\Properties\Models\Property;
use Illuminate\Support\Facades\Log;

class CreateBookingAction
{
    public function __construct(
        private CheckPropertyAvailabilityAction $checkAvailabilityAction
    ) {}

    public function execute(
        Property $property,
        Carbon $checkIn,
        Carbon $checkOut
    ): Booking {
        // Check availability
        $isAvailable = $this->checkAvailabilityAction->execute(
            $property,
            $checkIn,
            $checkOut
        );

        if (!$isAvailable) {
            throw new \DomainException(
                'Property is not available for the selected dates'
            );
        }

        // Create the booking
        $booking = Booking::create([
            'property_id' => $property->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => 'pending',
        ]);

        Log::info('Booking created', [
            'booking_id' => $booking->id,
            'property_id' => $property->id,
            'check_in' => $checkIn->toDateString(),
            'check_out' => $checkOut->toDateString(),
        ]);

        return $booking;
    }
}