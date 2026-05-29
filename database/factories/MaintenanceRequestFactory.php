<?php

namespace Database\Factories;

use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Maintenance\States\MaintenanceRequestStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceRequestFactory extends Factory
{
    protected $model = MaintenanceRequest::class;

    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => Tenant::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'emergency']),
            'status' => MaintenanceRequestStatus::Reported,
        ];
    }
}
