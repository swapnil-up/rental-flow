<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $this->actingAs($this->admin);

        Property::factory()->count(5)->create();
        Booking::factory()->count(3)->create(['status' => BookingStatus::Active]);
        MaintenanceRequest::factory()->create();
        Payment::factory()->create(['status' => PaymentStatus::Paid, 'paid_at' => now()]);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard/Index')
            ->has('stats')
            ->has('upcomingCheckIns')
            ->has('upcomingCheckOuts')
            ->has('recentPayments')
        );
    }

    public function test_dashboard_shows_correct_stats(): void
    {
        $this->actingAs($this->admin);

        Property::factory()->count(3)->create(['status' => PropertyStatus::Occupied]);
        Property::factory()->create(['status' => PropertyStatus::Available]);

        $response = $this->get('/admin/dashboard');

        $response->assertInertia(fn ($page) => $page
            ->where('stats.total_properties', 4)
            ->where('stats.occupancy_rate', 75)
        );
    }

    public function test_admin_redirects_to_dashboard_after_login(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/');

        $response->assertRedirect('/admin/dashboard');
    }
}
