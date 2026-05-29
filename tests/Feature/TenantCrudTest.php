<?php

namespace Tests\Feature;

use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(\App\Models\User::factory()->admin()->create());
    }

    public function test_it_can_list_tenants(): void
    {
        Tenant::factory()->count(3)->create();

        $response = $this->get('/admin/tenants');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Tenants/Index'));
    }

    public function test_it_can_show_create_form(): void
    {
        $response = $this->get('/admin/tenants/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Tenants/Create'));
    }

    public function test_it_can_create_a_tenant(): void
    {
        $response = $this->post('/admin/tenants', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '555-0100',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tenants', ['email' => 'john@example.com']);
    }

    public function test_it_validates_unique_email(): void
    {
        Tenant::factory()->create(['email' => 'john@example.com']);

        $response = $this->post('/admin/tenants', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_it_can_show_a_tenant(): void
    {
        $tenant = Tenant::factory()->create();

        $response = $this->get("/admin/tenants/{$tenant->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Tenants/Show'));
    }

    public function test_it_can_show_edit_form(): void
    {
        $tenant = Tenant::factory()->create();

        $response = $this->get("/admin/tenants/{$tenant->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Tenants/Edit'));
    }

    public function test_it_can_update_a_tenant(): void
    {
        $tenant = Tenant::factory()->create();

        $response = $this->put("/admin/tenants/{$tenant->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone' => '555-0200',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'name' => 'Updated Name']);
    }

    public function test_it_can_delete_a_tenant(): void
    {
        $tenant = Tenant::factory()->create();

        $response = $this->delete("/admin/tenants/{$tenant->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('tenants', ['id' => $tenant->id]);
    }

    public function test_it_can_search_tenants(): void
    {
        Tenant::factory()->create(['name' => 'Alice Smith']);
        Tenant::factory()->create(['name' => 'Bob Jones']);

        $response = $this->get('/admin/tenants?search=Alice');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Tenants/Index')
            ->has('tenants.data', 1)
        );
    }
}
