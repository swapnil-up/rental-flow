<?php

namespace Domain\Properties\States;

use Domain\Properties\Models\Property;

abstract class PropertyStatus
{
    public function __construct(
        protected Property $property
    ) {}

    abstract public static function value(): string;
    
    abstract public function color(): string;
    
    abstract public function canAcceptBookings(): bool;
    
    abstract public function requiresMaintenance(): bool;
}