<?php

namespace Tests\Feature;

use App\Models\User;
use Domain\Expenses\Models\Expense;
use Domain\Expenses\States\ExpenseCategory;
use Domain\Properties\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_can_list_expenses(): void
    {
        $this->actingAs($this->admin);
        Expense::factory()->count(3)->create();

        $response = $this->get('/admin/expenses');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Expenses/Index'));
    }

    public function test_can_show_create_form(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/expenses/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Expenses/Create'));
    }

    public function test_can_create_expense(): void
    {
        $this->actingAs($this->admin);
        $property = Property::factory()->create();

        $response = $this->post('/admin/expenses', [
            'property_id' => $property->id,
            'category' => ExpenseCategory::Repairs->value,
            'amount' => 250.00,
            'description' => 'Fixed the sink',
            'date' => '2026-06-01',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('expenses', [
            'property_id' => $property->id,
            'category' => 'repairs',
            'amount' => 25000,
            'description' => 'Fixed the sink',
        ]);
    }

    public function test_can_show_edit_form(): void
    {
        $this->actingAs($this->admin);
        $expense = Expense::factory()->create();

        $response = $this->get("/admin/expenses/{$expense->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Admin/Expenses/Edit'));
    }

    public function test_can_update_expense(): void
    {
        $this->actingAs($this->admin);
        $expense = Expense::factory()->create();

        $response = $this->put("/admin/expenses/{$expense->id}", [
            'property_id' => $expense->property_id,
            'category' => ExpenseCategory::Utilities->value,
            'amount' => 150.00,
            'description' => 'Updated description',
            'date' => '2026-06-15',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'category' => 'utilities',
            'amount' => 15000,
            'description' => 'Updated description',
        ]);
    }

    public function test_can_delete_expense(): void
    {
        $this->actingAs($this->admin);
        $expense = Expense::factory()->create();

        $response = $this->delete("/admin/expenses/{$expense->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }

    public function test_can_filter_expenses_by_category(): void
    {
        $this->actingAs($this->admin);
        Expense::factory()->create(['category' => ExpenseCategory::Repairs]);
        Expense::factory()->create(['category' => ExpenseCategory::Utilities]);

        $response = $this->get('/admin/expenses?category=utilities');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Expenses/Index')
            ->has('expenses.data', 1)
        );
    }

    public function test_guest_cannot_access_expenses(): void
    {
        $response = $this->get('/admin/expenses');
        $response->assertRedirect('/login');
    }
}
