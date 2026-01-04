<?php

namespace Database\Seeders;

use Domain\Bookings\Models\Booking;
use Domain\Properties\Models\Property;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();

        if ($properties->isEmpty()) {
            $this->command->warn('No properties found. Create some first!');
            return;
        }

        foreach ($properties->take(3) as $property) {
            // Pending booking
            Booking::factory()->create([
                'property_id' => $property->id,
                'status' => 'pending',
                'check_in' => now()->addDays(30),
                'check_out' => now()->addDays(35),
            ]);

            // Confirmed booking
            Booking::factory()->create([
                'property_id' => $property->id,
                'status' => 'confirmed',
                'check_in' => now()->addDays(10),
                'check_out' => now()->addDays(15),
            ]);

            // Active booking
            Booking::factory()->create([
                'property_id' => $property->id,
                'status' => 'active',
                'check_in' => now()->subDays(2),
                'check_out' => now()->addDays(3),
            ]);

            // Completed booking
            Booking::factory()->create([
                'property_id' => $property->id,
                'status' => 'completed',
                'check_in' => now()->subDays(30),
                'check_out' => now()->subDays(25),
            ]);

            // Cancelled booking
            Booking::factory()->create([
                'property_id' => $property->id,
                'status' => 'cancelled',
                'check_in' => now()->addDays(60),
                'check_out' => now()->addDays(65),
            ]);
        }

        $this->command->info('Bookings seeded successfully!');
    }
}