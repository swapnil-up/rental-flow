<?php

namespace Tests\Unit\States;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingStateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_if_pending_booking_can_be_cancelled()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending]);

        $this->assertTrue($booking->status->canBeCancelled());
    }

    /** @test */
    public function test_if_pending_booking_can_be_confirmed()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending]);

        $this->assertTrue($booking->status->canBeConfirmed());
    }

    /** @test */
    public function test_if_confirmed_booking_can_be_cancelled_before_checkin()
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Confirmed,
            'check_in' => now()->addDays(5),
        ]);

        $this->assertTrue($booking->status->canBeCancelled());
    }

    /** @test */
    public function test_if_active_booking_cannot_be_cancelled()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Active]);

        $this->assertFalse($booking->status->canBeCancelled());
    }

    /** @test */
    public function test_if_completed_booking_has_no_available_actions()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Completed]);

        $this->assertEmpty($booking->status->availableActions());
    }

    /** @test */
    public function test_if_each_state_has_correct_color()
    {
        $colors = [
            BookingStatus::Pending->value => 'orange',
            BookingStatus::Confirmed->value => 'blue',
            BookingStatus::Active->value => 'green',
            BookingStatus::Completed->value => 'gray',
            BookingStatus::Cancelled->value => 'red',
        ];

        foreach ($colors as $status => $expectedColor) {
            $booking = Booking::factory()->create(['status' => $status]);
            $this->assertEquals($expectedColor, $booking->status->color());
        }
    }
}
