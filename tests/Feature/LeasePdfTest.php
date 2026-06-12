<?php

namespace Tests\Feature;

use App\Models\User;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeasePdfTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_can_download_lease_pdf(): void
    {
        $this->actingAs($this->admin);

        $tenant = Tenant::factory()->create();
        $property = Property::factory()->create();
        $booking = Booking::factory()
            ->for($property)
            ->for($tenant)
            ->create(['status' => BookingStatus::Confirmed]);

        $response = $this->get("/admin/bookings/{$booking->id}/lease");

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', "attachment; filename=lease-{$booking->id}.pdf");
    }

    public function test_guest_cannot_download_lease(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->get("/admin/bookings/{$booking->id}/lease");
        $response->assertRedirect('/login');
    }
}
