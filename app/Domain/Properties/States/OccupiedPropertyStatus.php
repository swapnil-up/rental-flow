<?php

namespace Domain\Properties\States;

class OccupiedPropertyStatus extends PropertyState
{
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
