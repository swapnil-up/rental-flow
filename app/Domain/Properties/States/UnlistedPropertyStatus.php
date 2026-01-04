<?php

namespace Domain\Properties\States;

class UnlistedPropertyStatus extends PropertyStatus
{
    public static function value(): string
    {
        return 'unlisted';
    }

    public function color(): string
    {
        return 'gray';
    }

    public function canAcceptBookings(): bool
    {
        return false;
    }

    public function requiresMaintenance(): bool
    {
        return false;
    }
}