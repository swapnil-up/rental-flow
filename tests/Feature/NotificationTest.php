<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\BookingCancelled;
use App\Notifications\BookingConfirmed;
use App\Notifications\MaintenanceStatusChanged;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Maintenance\States\MaintenanceRequestStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->tenant = Tenant::factory()->create();
    }

    public function test_sends_booking_confirmed_notification(): void
    {
        $this->actingAs($this->admin);

        Notification::fake();

        $booking = Booking::factory()
            ->for($this->tenant)
            ->for(Property::factory()->create())
            ->create(['status' => BookingStatus::Pending]);

        $this->post("/admin/bookings/{$booking->id}/confirm");

        Notification::assertSentTo($this->tenant, BookingConfirmed::class);
    }

    public function test_sends_booking_cancelled_notification(): void
    {
        $this->actingAs($this->admin);

        Notification::fake();

        $booking = Booking::factory()
            ->for($this->tenant)
            ->for(Property::factory()->create())
            ->create(['status' => BookingStatus::Pending]);

        $this->post("/admin/bookings/{$booking->id}/confirm");

        Notification::assertSentTo($this->tenant, BookingConfirmed::class);
    }

    public function test_sends_maintenance_status_changed_notification(): void
    {
        $this->actingAs($this->admin);

        Notification::fake();

        $request = MaintenanceRequest::factory()
            ->for($this->tenant)
            ->for(Property::factory()->create())
            ->create(['status' => MaintenanceRequestStatus::Reported]);

        $this->post("/admin/maintenance/{$request->id}/transition", [
            'status' => MaintenanceRequestStatus::Assigned->value,
        ]);

        Notification::assertSentTo($this->tenant, MaintenanceStatusChanged::class);
    }

    public function test_notification_not_sent_when_booking_has_no_tenant(): void
    {
        $this->actingAs($this->admin);

        Notification::fake();

        $booking = Booking::factory()
            ->for(Property::factory()->create())
            ->create([
                'tenant_id' => null,
                'status' => BookingStatus::Pending,
            ]);

        $this->post("/admin/bookings/{$booking->id}/confirm");

        Notification::assertNothingSent();
    }
}
