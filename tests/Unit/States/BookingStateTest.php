<?php

namespace Tests\Unit\States;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\PendingBookingState;
use Domain\Bookings\States\ConfirmedBookingState;
use Domain\Bookings\States\ActiveBookingState;
use Domain\Bookings\States\CompletedBookingState;
use Domain\Bookings\States\CancelledBookingState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingStateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_if_pending_booking_can_be_cancelled()
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $this->assertTrue($booking->state->canBeCancelled());
    }

    /** @test */
    public function test_if_pending_booking_can_be_confirmed()
    {
        $booking = Booking::factory()->create(['status' => 'pending']);

        $this->assertTrue($booking->state->canBeConfirmed());
    }

    /** @test */
    public function test_if_confirmed_booking_can_be_cancelled_before_checkin()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'check_in' => now()->addDays(5),
        ]);

        $this->assertTrue($booking->state->canBeCancelled());
    }

    /** @test */
    public function test_if_confirmed_booking_cannot_be_cancelled_after_checkin()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'check_in' => now()->subDays(1),
        ]);

        $this->assertFalse($booking->state->canBeCancelled());
    }

    /** @test */
    public function test_if_active_booking_cannot_be_cancelled()
    {
        $booking = Booking::factory()->create(['status' => 'active']);

        $this->assertFalse($booking->state->canBeCancelled());
    }

    /** @test */
    public function test_if_completed_booking_has_no_available_actions()
    {
        $booking = Booking::factory()->create(['status' => 'completed']);

        $this->assertEmpty($booking->state->availableActions());
    }

    /** @test */
    public function test_if_each_state_has_correct_color()
    {
        $colors = [
            'pending' => 'orange',
            'confirmed' => 'blue',
            'active' => 'green',
            'completed' => 'gray',
            'cancelled' => 'red',
        ];

        foreach ($colors as $status => $expectedColor) {
            $booking = Booking::factory()->create(['status' => $status]);
            $this->assertEquals($expectedColor, $booking->state->color());
        }
    }

    /** @test */
    public function test_if_confirmed_booking_can_be_modified_if_more_than_48_hours_away()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'check_in' => now()->addHours(72), // 3 days
        ]);

        $this->assertTrue($booking->state->canBeModified());
    }

    /** @test */
    public function test_if_confirmed_booking_cannot_be_modified_within_48_hours()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'check_in' => now()->addHours(24), // 1 day
        ]);

        $this->assertFalse($booking->state->canBeModified());
    }
}