<?php

namespace Tests\Feature;

use App\Models\User;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Maintenance\States\MaintenanceRequestStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Property $property;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->property = Property::factory()->create();
    }

    public function test_admin_can_list_requests(): void
    {
        $this->actingAs($this->admin);
        MaintenanceRequest::factory()->count(3)->create();

        $response = $this->get('/admin/maintenance');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Maintenance/Index'));
    }

    public function test_admin_can_show_request(): void
    {
        $this->actingAs($this->admin);
        $request = MaintenanceRequest::factory()->create();

        $response = $this->get("/admin/maintenance/{$request->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Maintenance/Show'));
    }

    public function test_admin_can_transition_request(): void
    {
        $this->actingAs($this->admin);
        $request = MaintenanceRequest::factory()->create(['status' => MaintenanceRequestStatus::Reported]);

        $response = $this->post("/admin/maintenance/{$request->id}/transition", [
            'status' => MaintenanceRequestStatus::Assigned->value,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('maintenance_requests', [
            'id' => $request->id,
            'status' => MaintenanceRequestStatus::Assigned->value,
        ]);
    }

    public function test_admin_cannot_make_invalid_transition(): void
    {
        $this->actingAs($this->admin);
        $request = MaintenanceRequest::factory()->create(['status' => MaintenanceRequestStatus::Reported]);

        $response = $this->post("/admin/maintenance/{$request->id}/transition", [
            'status' => MaintenanceRequestStatus::InProgress->value,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('maintenance_requests', [
            'id' => $request->id,
            'status' => MaintenanceRequestStatus::Reported->value,
        ]);
    }

    public function test_tenant_can_submit_request(): void
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['role' => 'tenant']);
        $tenant->user()->associate($user)->save();

        $this->actingAs($user);

        $response = $this->post('/maintenance', [
            'property_id' => $this->property->id,
            'title' => 'Leaky faucet',
            'description' => 'Kitchen sink is leaking.',
            'priority' => 'medium',
        ]);

        $response->assertRedirect('/maintenance');
        $this->assertDatabaseHas('maintenance_requests', [
            'title' => 'Leaky faucet',
            'tenant_id' => $tenant->id,
        ]);
    }

    public function test_tenant_can_view_own_requests(): void
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['role' => 'tenant']);
        $tenant->user()->associate($user)->save();
        $this->actingAs($user);

        MaintenanceRequest::factory()->create(['tenant_id' => $tenant->id]);
        MaintenanceRequest::factory()->count(2)->create();

        $response = $this->get('/maintenance');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Tenant/Maintenance/Index')
            ->has('requests', 1)
        );
    }
}
