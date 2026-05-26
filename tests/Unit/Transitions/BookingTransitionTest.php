<?php

namespace Tests\Unit\Transitions;

use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Bookings\Transitions\ConfirmBookingTransition;
use Domain\Bookings\Transitions\CancelBookingTransition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTransitionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_confirm_pending_booking()
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Pending,
            'check_in' => now()->addDays(5),
        ]);

        $transition = new ConfirmBookingTransition();
        $result = $transition->execute($booking);

        $this->assertTrue($result->status === BookingStatus::Confirmed);
    }

    /** @test */
    public function test_it_cannot_confirm_already_confirmed_booking()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Confirmed]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot confirm booking in confirmed state');

        $transition = new ConfirmBookingTransition();
        $transition->execute($booking);
    }

    /** @test */
    public function test_it_cannot_confirm_booking_with_past_checkin_date()
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Pending,
            'check_in' => now()->subDays(1),
        ]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot confirm booking with past check-in date');

        $transition = new ConfirmBookingTransition();
        $transition->execute($booking);
    }

    /** @test */
    public function test_it_can_cancel_pending_booking()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Pending]);

        $transition = new CancelBookingTransition();
        $result = $transition->execute($booking);

        $this->assertTrue($result->status === BookingStatus::Cancelled);
    }

    /** @test */
    public function test_it_can_cancel_confirmed_booking_before_checkin()
    {
        $booking = Booking::factory()->create([
            'status' => BookingStatus::Confirmed,
            'check_in' => now()->addDays(5),
        ]);

        $transition = new CancelBookingTransition();
        $result = $transition->execute($booking);

        $this->assertTrue($result->status === BookingStatus::Cancelled);
    }

    /** @test */
    public function test_it_cannot_cancel_active_booking()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Active]);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot cancel booking in active state');

        $transition = new CancelBookingTransition();
        $transition->execute($booking);
    }

    /** @test */
    public function test_it_cannot_cancel_completed_booking()
    {
        $booking = Booking::factory()->create(['status' => BookingStatus::Completed]);

        $this->expectException(\DomainException::class);

        $transition = new CancelBookingTransition();
        $transition->execute($booking);
    }
}
