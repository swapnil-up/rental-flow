<?php

namespace Tests\Unit\Actions;

use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Properties\Actions\CheckPropertyAvailabilityAction;
use Domain\Properties\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckPropertyAvailabilityActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_true_when_property_is_available_with_no_bookings()
    {
        $property = Property::factory()->create([
            'status' => 'available',
        ]);

        $action = new CheckPropertyAvailabilityAction();

        $isAvailable = $action->execute(
            $property,
            Carbon::parse('2025-01-01'),
            Carbon::parse('2025-01-05')
        );

        $this->assertTrue($isAvailable);
    }

    public function test_it_returns_false_when_dates_overlap_with_existing_booking()
    {
        $property = Property::factory()->create([
            'status' => 'available',
        ]);

        // Existing booking from Jan 1-5
        Booking::factory()->create([
            'property_id' => $property->id,
            'check_in' => '2025-01-01',
            'check_out' => '2025-01-05',
            'status' => 'confirmed',
        ]);

        $action = new CheckPropertyAvailabilityAction();

        // Try to book Jan 3-7 (overlaps)
        $isAvailable = $action->execute(
            $property,
            Carbon::parse('2025-01-03'),
            Carbon::parse('2025-01-07')
        );

        $this->assertFalse($isAvailable);
    }

    public function test_it_returns_true_when_dates_do_not_overlap()
    {
        $property = Property::factory()->create([
            'status' => 'available',
        ]);

        // Existing booking from Jan 1-5
        Booking::factory()->create([
            'property_id' => $property->id,
            'check_in' => '2025-01-01',
            'check_out' => '2025-01-05',
            'status' => 'confirmed',
        ]);

        $action = new CheckPropertyAvailabilityAction();

        // Try to book Jan 6-10 (doesn't overlap)
        $isAvailable = $action->execute(
            $property,
            Carbon::parse('2025-01-06'),
            Carbon::parse('2025-01-10')
        );

        $this->assertTrue($isAvailable);
    }

    public function test_it_returns_false_when_property_status_is_not_available()
    {
        $property = Property::factory()->create([
            'status' => 'maintenance',
        ]);

        $action = new CheckPropertyAvailabilityAction();

        $isAvailable = $action->execute(
            $property,
            Carbon::parse('2025-01-01'),
            Carbon::parse('2025-01-05')
        );

        $this->assertFalse($isAvailable);
    }
}