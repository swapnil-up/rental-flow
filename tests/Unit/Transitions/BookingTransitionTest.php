<?php

namespace Tests\Unit\Transitions;

use Domain\Bookings\Models\Booking;
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
            'status' => 'pending',
            'check_in' => now()->addDays(5),
        ]);

        $transition = new ConfirmBookingTransition();
        $result = $transition->execute($booking);

        $this->assertEquals('confirmed', $result->status);
    }

    /** @test */
    public function test_it_cannot_confirm_already_confirmed_booking()
    {
        $booking = Booking::factory()->create(['status' => 'confirmed']);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot confirm booking in confirmed state');

        $transition = new ConfirmBookingTransition();
        $transition->execute($booking);
    }

    /** @test */
    public function test_it_cannot_confirm_booking_with_past_checkin_date()
    {
        $booking = Booking::factory()->create([
            'status' => 'pending',
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
        $booking = Booking::factory()->create(['status' => 'pending']);

        $transition = new CancelBookingTransition();
        $result = $transition->execute($booking);

        $this->assertEquals('cancelled', $result->status);
    }

    /** @test */
    public function test_it_can_cancel_confirmed_booking_before_checkin()
    {
        $booking = Booking::factory()->create([
            'status' => 'confirmed',
            'check_in' => now()->addDays(5),
        ]);

        $transition = new CancelBookingTransition();
        $result = $transition->execute($booking);

        $this->assertEquals('cancelled', $result->status);
    }

    /** @test */
    public function test_it_cannot_cancel_active_booking()
    {
        $booking = Booking::factory()->create(['status' => 'active']);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot cancel booking in active state');

        $transition = new CancelBookingTransition();
        $transition->execute($booking);
    }

    /** @test */
    public function test_it_cannot_cancel_completed_booking()
    {
        $booking = Booking::factory()->create(['status' => 'completed']);

        $this->expectException(\DomainException::class);

        $transition = new CancelBookingTransition();
        $transition->execute($booking);
    }
}