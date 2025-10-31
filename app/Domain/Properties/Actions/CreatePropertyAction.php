<?php

namespace Domain\Properties\Actions;

use Domain\Properties\DataTransferObjects\PropertyData;
use Domain\Properties\Models\Property;
use Illuminate\Support\Facades\Log;

class CreatePropertyAction
{
    public function __construct(
        private CalculateTotalMonthlyCostAction $calculateTotalMonthlyCostAction
    ) {}

    public function execute(PropertyData $propertyData): Property
    {
        $property = Property::create($propertyData->toArray());

        $property->total_monthly_cost = $this->calculateTotalMonthlyCostAction
            ->execute($property);
        
        $property->save();

        Log::info('Property created', [
            'property_id' => $property->id,
            'name' => $property->name,
            'total_monthly_cost' => $property->total_monthly_cost,
        ]);

        return $property;
    }
}