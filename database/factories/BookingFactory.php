<?php

namespace Database\Factories;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => Tenant::factory(),
            'check_in' => now()->addDays(rand(1, 30)),
            'check_out' => now()->addDays(rand(31, 60)),
            'status' => BookingStatus::Pending,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Confirmed,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Cancelled,
        ]);
    }
}