<?php

namespace Domain\Properties\States;

class MaintenancePropertyStatus extends PropertyStatus
{
    public static function value(): string
    {
        return 'maintenance';
    }

    public function color(): string
    {
        return 'orange';
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