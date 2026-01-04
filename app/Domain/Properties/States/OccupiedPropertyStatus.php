<?php

namespace Domain\Properties\States;

class OccupiedPropertyStatus extends PropertyStatus
{
    public static function value(): string
    {
        return 'occupied';
    }

    public function color(): string
    {
        return 'blue';
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