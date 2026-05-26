<?php

namespace Domain\Properties\States;

class AvailablePropertyStatus extends PropertyState
{
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
