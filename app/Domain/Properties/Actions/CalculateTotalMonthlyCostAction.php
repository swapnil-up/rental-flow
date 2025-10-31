<?php

namespace Domain\Properties\Actions;

use Domain\Properties\Models\Property;

class CalculateTotalMonthlyCostAction
{
    public function execute(Property $property): int
    {
        // Business logic for calculating total monthly cost
        $total = $property->monthly_rent 
            + $property->utilities_cost 
            + $property->management_fee;

        // TODO: Add more complex logic here:
        // - Seasonal adjustments
        // - Discounts for long-term rentals
        // - Tax calculations

        return $total;
    }
}