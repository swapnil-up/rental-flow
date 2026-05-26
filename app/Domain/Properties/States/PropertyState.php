<?php

namespace Domain\Properties\States;

use Domain\Properties\Models\Property;

abstract class PropertyState
{
    public function __construct(
        protected Property $property
    ) {}

    abstract public function color(): string;

    abstract public function canAcceptBookings(): bool;

    abstract public function requiresMaintenance(): bool;
}
