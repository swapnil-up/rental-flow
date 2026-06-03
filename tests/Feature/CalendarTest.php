<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_calendar_page_loads(): void
    {
        $this->actingAs($this->admin);

        Booking::factory()
            ->for(Property::factory()->create(['name' => 'Test Property']))
            ->create([
                'check_in' => Carbon::now()->startOfMonth()->addDays(5),
                'check_out' => Carbon::now()->startOfMonth()->addDays(7),
                'status' => BookingStatus::Confirmed,
            ]);

        $response = $this->get('/admin/calendar');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Calendar/Index')
            ->has('bookings', 1)
            ->has('month')
            ->has('year')
            ->has('monthName')
            ->has('startDayOfWeek')
            ->has('daysInMonth')
            ->has('prevMonth')
            ->has('nextMonth')
        );
    }

    public function test_calendar_excludes_cancelled_bookings(): void
    {
        $this->actingAs($this->admin);

        Booking::factory()
            ->for(Property::factory()->create(['name' => 'Cancelled Booking']))
            ->create([
                'check_in' => Carbon::now()->startOfMonth()->addDays(3),
                'check_out' => Carbon::now()->startOfMonth()->addDays(5),
                'status' => BookingStatus::Cancelled,
            ]);

        $response = $this->get('/admin/calendar');

        $response->assertInertia(fn ($page) => $page
            ->where('bookings', [])
        );
    }

    public function test_calendar_navigates_months(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/calendar?month=12&year=2025');

        $response->assertInertia(fn ($page) => $page
            ->where('month', 12)
            ->where('year', 2025)
            ->where('monthName', 'December 2025')
            ->where('daysInMonth', 31)
        );
    }

    public function test_guest_cannot_access_calendar(): void
    {
        $response = $this->get('/admin/calendar');
        $response->assertRedirect('/login');
    }

    public function test_tenant_cannot_access_admin_calendar(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant']);
        $this->actingAs($tenant);

        $response = $this->get('/admin/calendar');
        $response->assertRedirect('/');
    }
}
