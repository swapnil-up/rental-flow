<?php

namespace Database\Factories;

use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true) . ' Property',
            'type' => fake()->randomElement(['apartment', 'house', 'condo', 'studio']),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip_code' => fake()->postcode(),
            'bedrooms' => fake()->numberBetween(0, 5),
            'bathrooms' => fake()->randomFloat(1, 1, 3),
            'square_feet' => fake()->numberBetween(500, 3000),
            'monthly_rent' => fake()->numberBetween(100000, 500000), // 1000-5000 in paisa
            'utilities_cost' => fake()->numberBetween(5000, 30000), // 50-300 in paisa
            'management_fee' => fake()->numberBetween(5000, 20000), // 50-200 in paisa
            'total_monthly_cost' => 0, // Will be calculated
            'status' => PropertyStatus::Available,
        ];
    }
}