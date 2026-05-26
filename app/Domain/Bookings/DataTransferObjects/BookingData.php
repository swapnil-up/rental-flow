<?php

namespace Domain\Bookings\DataTransferObjects;

use Carbon\Carbon;

readonly class BookingData
{
    public function __construct(
        public int $property_id,
        public Carbon $check_in,
        public Carbon $check_out,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            property_id: (int) $data['property_id'],
            check_in: Carbon::parse($data['check_in']),
            check_out: Carbon::parse($data['check_out']),
        );
    }
}
