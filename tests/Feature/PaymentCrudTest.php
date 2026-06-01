<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Tenant $tenant;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->tenant = Tenant::factory()->create();
        $this->booking = Booking::factory()->create(['tenant_id' => $this->tenant->id]);
    }

    public function test_admin_can_list_payments(): void
    {
        $this->actingAs($this->admin);
        Payment::factory()->count(3)->create();

        $response = $this->get('/admin/payments');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Payments/Index'));
    }

    public function test_admin_can_show_payment(): void
    {
        $this->actingAs($this->admin);
        $payment = Payment::factory()->create();

        $response = $this->get("/admin/payments/{$payment->id}");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Payments/Show'));
    }

    public function test_admin_can_mark_payment_as_paid(): void
    {
        $this->actingAs($this->admin);
        $payment = Payment::factory()->create(['status' => PaymentStatus::Pending]);

        $response = $this->post("/admin/payments/{$payment->id}/paid");

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::Paid->value,
        ]);
    }

    public function test_admin_can_refund_paid_payment(): void
    {
        $this->actingAs($this->admin);
        $payment = Payment::factory()->create([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        $response = $this->post("/admin/payments/{$payment->id}/refund");

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::Refunded->value,
        ]);
    }

    public function test_cannot_refund_unpaid_payment(): void
    {
        $this->actingAs($this->admin);
        $payment = Payment::factory()->create(['status' => PaymentStatus::Pending]);

        $response = $this->post("/admin/payments/{$payment->id}/refund");

        $response->assertSessionHas('error');
    }

    public function test_tenant_can_view_own_payments(): void
    {
        $user = User::factory()->create(['role' => 'tenant']);
        $this->tenant->user()->associate($user)->save();
        $this->actingAs($user);

        Payment::factory()->create(['tenant_id' => $this->tenant->id]);
        Payment::factory()->count(2)->create();

        $response = $this->get('/payments');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Tenant/Payments/Index')
            ->has('payments', 1)
        );
    }
}
