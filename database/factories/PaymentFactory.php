<?php

namespace Database\Factories;

use Domain\Bookings\Models\Booking;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'tenant_id' => Tenant::factory(),
            'amount' => $this->faker->numberBetween(50000, 200000),
            'type' => 'rent',
            'status' => PaymentStatus::Pending,
            'due_date' => now()->addDays(30),
            'paid_at' => null,
        ];
    }
}
