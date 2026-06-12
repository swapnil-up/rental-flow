<?php

namespace Database\Factories;

use Domain\Expenses\Models\Expense;
use Domain\Expenses\States\ExpenseCategory;
use Domain\Properties\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'category' => fake()->randomElement(ExpenseCategory::cases()),
            'amount' => fake()->numberBetween(1000, 100000),
            'description' => fake()->sentence(),
            'date' => fake()->date(),
        ];
    }
}
