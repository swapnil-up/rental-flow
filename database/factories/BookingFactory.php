<?php

namespace Database\Factories;

use Domain\Bookings\Models\Booking;
use Domain\Properties\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('now', '+1 month');
        $checkOut = fake()->dateTimeBetween($checkIn, '+2 months');

        return [
            'property_id' => Property::factory(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => 'pending',
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}