<?php

namespace Domain\Properties\Actions;

use Domain\Properties\DataTransferObjects\PropertyData;
use Domain\Properties\Models\Property;

class UpdatePropertyAction
{
    public function __construct(
        private CalculateTotalMonthlyCostAction $calculateTotalMonthlyCostAction
    ) {}

    public function execute(Property $property, PropertyData $propertyData): Property
    {
        $property->update($propertyData->toArray());

        $property->total_monthly_cost = $this->calculateTotalMonthlyCostAction
            ->execute($property);

        $property->save();

        return $property;
    }
}
