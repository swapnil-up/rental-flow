<?php

namespace Tests\Feature;

use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_list_properties(): void
    {
        Property::factory()->count(3)->create();

        $response = $this->get('/admin/properties');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Properties/Index'));
    }

    public function test_it_can_show_create_form(): void
    {
        $response = $this->get('/admin/properties/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Properties/Create'));
    }

    public function test_it_can_create_a_property(): void
    {
        $response = $this->post('/admin/properties', [
            'name' => 'Test Property',
            'type' => 'apartment',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'CA',
            'zip_code' => '90210',
            'bedrooms' => 2,
            'bathrooms' => 1.5,
            'square_feet' => 1000,
            'monthly_rent' => 1500.00,
            'utilities_cost' => 100.00,
            'management_fee' => 50.00,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'name' => 'Test Property',
            'monthly_rent' => 150000,
        ]);
    }

    public function test_it_can_show_a_property(): void
    {
        $property = Property::factory()->create();

        $response = $this->get("/admin/properties/{$property->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Properties/Show'));
    }

    public function test_it_can_show_edit_form(): void
    {
        $property = Property::factory()->create();

        $response = $this->get("/admin/properties/{$property->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Properties/Edit'));
    }

    public function test_it_can_update_a_property(): void
    {
        $property = Property::factory()->create();

        $response = $this->put("/admin/properties/{$property->id}", [
            'name' => 'Updated Name',
            'type' => 'house',
            'address' => $property->address,
            'city' => $property->city,
            'state' => $property->state,
            'zip_code' => $property->zip_code,
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'square_feet' => $property->square_feet,
            'monthly_rent' => 2000.00,
            'utilities_cost' => 0,
            'management_fee' => 0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'name' => 'Updated Name',
            'monthly_rent' => 200000,
        ]);
    }

    public function test_it_can_delete_a_property(): void
    {
        $property = Property::factory()->create();

        $response = $this->delete("/admin/properties/{$property->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('properties', ['id' => $property->id]);
    }

    public function test_it_can_filter_properties_by_city(): void
    {
        Property::factory()->create(['city' => 'Los Angeles']);
        Property::factory()->create(['city' => 'San Francisco']);

        $response = $this->get('/admin/properties?city=Los');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Properties/Index')
            ->has('properties.data', 1)
        );
    }

    public function test_it_can_filter_properties_by_status(): void
    {
        Property::factory()->create(['status' => PropertyStatus::Available]);
        Property::factory()->create(['status' => PropertyStatus::Maintenance]);

        $response = $this->get('/admin/properties?status=maintenance');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Properties/Index')
            ->has('properties.data', 1)
        );
    }
}
