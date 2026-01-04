<?php

namespace Domain\Properties\States;

class AvailablePropertyStatus extends PropertyStatus
{
    public static function value(): string
    {
        return 'available';
    }

    public function color(): string
    {
        return 'green';
    }

    public function canAcceptBookings(): bool
    {
        return true;
    }

    public function requiresMaintenance(): bool
    {
        return false;
    }
}