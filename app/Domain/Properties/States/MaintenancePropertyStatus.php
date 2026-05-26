<?php

namespace Domain\Properties\States;

class MaintenancePropertyStatus extends PropertyState
{
    public function color(): string
    {
        return 'yellow';
    }

    public function canAcceptBookings(): bool
    {
        return false;
    }

    public function requiresMaintenance(): bool
    {
        return true;
    }
}
