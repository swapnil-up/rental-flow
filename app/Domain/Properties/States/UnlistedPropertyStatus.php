<?php

namespace Domain\Properties\States;

class UnlistedPropertyStatus extends PropertyState
{
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
