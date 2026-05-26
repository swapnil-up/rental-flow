<?php

namespace Domain\Properties\States;

use Domain\Properties\Models\Property;

enum PropertyStatus: string
{
    case Available = 'available';
    case Occupied = 'occupied';
    case Maintenance = 'maintenance';
    case Unlisted = 'unlisted';

    public function color(): string
    {
        return match ($this) {
            self::Available => 'green',
            self::Occupied => 'blue',
            self::Maintenance => 'yellow',
            self::Unlisted => 'gray',
        };
    }

    public function canAcceptBookings(): bool
    {
        return $this === self::Available;
    }

    public function requiresMaintenance(): bool
    {
        return $this === self::Maintenance;
    }

    public function resolveState(Property $property): PropertyState
    {
        return match ($this) {
            self::Available => new AvailablePropertyStatus($property),
            self::Occupied => new OccupiedPropertyStatus($property),
            self::Maintenance => new MaintenancePropertyStatus($property),
            self::Unlisted => new UnlistedPropertyStatus($property),
        };
    }
}
