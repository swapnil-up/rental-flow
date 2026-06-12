<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Expenses\Models\Expense;
use Domain\Expenses\States\ExpenseCategory;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfitLossTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_page_loads_with_no_data(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/reports/profit-loss');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Reports/ProfitLoss')
            ->has('rows')
            ->has('totals')
        );
    }

    public function test_shows_income_and_expenses(): void
    {
        $this->actingAs($this->admin);

        $property = Property::factory()->create();
        $tenant = Tenant::factory()->create();
        $booking = Booking::factory()->for($property)->for($tenant)->create();

        Payment::factory()->create([
            'booking_id' => $booking->id,
            'amount' => 100000,
            'status' => PaymentStatus::Paid,
            'paid_at' => Carbon::now(),
        ]);

        Expense::factory()->create([
            'property_id' => $property->id,
            'category' => ExpenseCategory::Repairs,
            'amount' => 25000,
            'date' => Carbon::now(),
        ]);

        $response = $this->get('/admin/reports/profit-loss');

        $response->assertInertia(fn ($page) => $page
            ->where('totals.income', 100000)
            ->where('totals.expenses', 25000)
            ->where('totals.net', 75000)
            ->has('rows', 1)
        );
    }

    public function test_filters_by_period(): void
    {
        $this->actingAs($this->admin);

        $property = Property::factory()->create();
        $tenant = Tenant::factory()->create();
        $booking = Booking::factory()->for($property)->for($tenant)->create();

        Payment::factory()->create([
            'booking_id' => $booking->id,
            'amount' => 50000,
            'status' => PaymentStatus::Paid,
            'paid_at' => Carbon::create(2025, 6, 15),
        ]);

        // Different month — should not appear
        Payment::factory()->create([
            'booking_id' => $booking->id,
            'amount' => 99999,
            'status' => PaymentStatus::Paid,
            'paid_at' => Carbon::create(2025, 7, 15),
        ]);

        $response = $this->get('/admin/reports/profit-loss?month=6&year=2025');

        $response->assertInertia(fn ($page) => $page
            ->where('totals.income', 50000)
        );
    }

    public function test_excludes_unpaid_payments(): void
    {
        $this->actingAs($this->admin);

        $property = Property::factory()->create();
        $tenant = Tenant::factory()->create();
        $booking = Booking::factory()->for($property)->for($tenant)->create();

        Payment::factory()->create([
            'booking_id' => $booking->id,
            'amount' => 100000,
            'status' => PaymentStatus::Pending,
            'paid_at' => null,
        ]);

        $response = $this->get('/admin/reports/profit-loss');

        $response->assertInertia(fn ($page) => $page
            ->where('totals.income', 0)
        );
    }
}
