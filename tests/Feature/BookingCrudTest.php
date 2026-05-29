<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(\App\Models\User::factory()->admin()->create());
    }

    public function test_it_can_list_bookings(): void
    {
        Booking::factory()->count(3)->create();

        $response = $this->get('/admin/bookings');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Bookings/Index'));
    }

    public function test_it_can_show_create_form(): void
    {
        Property::factory()->create();

        $response = $this->get('/admin/bookings/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Bookings/Create'));
    }

    public function test_it_can_create_a_booking(): void
    {
        $property = Property::factory()->create(['status' => PropertyStatus::Available]);

        $response = $this->post('/admin/bookings', [
            'property_id' => $property->id,
            'check_in' => Carbon::today()->addDays(1)->toDateString(),
            'check_out' => Carbon::today()->addDays(5)->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'property_id' => $property->id,
            'status' => BookingStatus::Pending->value,
        ]);
    }

    public function test_it_validates_booking_dates(): void
    {
        $property = Property::factory()->create();

        $response = $this->post('/admin/bookings', [
            'property_id' => $property->id,
            'check_in' => Carbon::today()->subDays(5)->toDateString(),
            'check_out' => Carbon::today()->subDays(7)->toDateString(),
        ]);

        $response->assertSessionHasErrors(['check_in', 'check_out']);
    }

    public function test_it_can_show_a_booking(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->get("/admin/bookings/{$booking->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Bookings/Show'));
    }

    public function test_it_can_delete_a_booking(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->delete("/admin/bookings/{$booking->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_it_can_confirm_a_booking(): void
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Pending,
            'check_in' => Carbon::today()->addDays(5),
        ]);

        $response = $this->post("/admin/bookings/{$booking->id}/confirm");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => BookingStatus::Confirmed->value,
        ]);
    }

    public function test_it_can_cancel_a_booking(): void
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Pending,
        ]);

        $response = $this->post("/admin/bookings/{$booking->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => BookingStatus::Cancelled->value,
        ]);
    }

    public function test_it_can_filter_bookings_by_status(): void
    {
        Booking::factory()->create(['status' => BookingStatus::Pending]);
        Booking::factory()->create(['status' => BookingStatus::Confirmed]);
        Booking::factory()->create(['status' => BookingStatus::Cancelled]);

        $response = $this->get('/admin/bookings?status=' . BookingStatus::Pending->value);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Bookings/Index')
            ->has('bookings.data', 1)
        );
    }
}
