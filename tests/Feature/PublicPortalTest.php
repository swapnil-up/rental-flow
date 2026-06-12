<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\PropertyInquiry;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_public_properties_list(): void
    {
        Property::factory()->count(3)->create(['status' => PropertyStatus::Available]);

        $response = $this->get('/properties');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Public/Properties/Index'));
    }

    public function test_only_shows_available_properties(): void
    {
        Property::factory()->create(['name' => 'Available One', 'status' => PropertyStatus::Available]);
        Property::factory()->create(['name' => 'Occupied One', 'status' => PropertyStatus::Occupied]);

        $response = $this->get('/properties');

        $response->assertInertia(fn ($page) => $page
            ->component('Public/Properties/Index')
            ->has('properties.data', 1)
            ->where('properties.data.0.name', 'Available One')
        );
    }

    public function test_can_view_public_property_detail(): void
    {
        $property = Property::factory()->create(['status' => PropertyStatus::Available]);

        $response = $this->get("/properties/{$property->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Public/Properties/Show'));
    }

    public function test_unavailable_property_returns_404(): void
    {
        $property = Property::factory()->create(['status' => PropertyStatus::Occupied]);

        $response = $this->get("/properties/{$property->id}");
        $response->assertStatus(404);
    }

    public function test_can_submit_inquiry(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $property = Property::factory()->create(['status' => PropertyStatus::Available]);

        $response = $this->post("/properties/{$property->id}/inquiry", [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'I am interested in this property.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Notification::assertSentTo($admin, PropertyInquiry::class);
    }

    public function test_inquiry_validates_required_fields(): void
    {
        $property = Property::factory()->create(['status' => PropertyStatus::Available]);

        $response = $this->post("/properties/{$property->id}/inquiry", []);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }
}
