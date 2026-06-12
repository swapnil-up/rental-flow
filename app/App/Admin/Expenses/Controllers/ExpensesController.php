<?php

namespace App\Admin\Expenses\Controllers;

use App\Admin\Expenses\Requests\ExpenseRequest;
use App\Http\Controllers\Controller;
use Domain\Expenses\Models\Expense;
use Domain\Expenses\States\ExpenseCategory;
use Domain\Properties\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExpensesController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Expense::with('property');

        if ($propertyId = $request->get('property_id')) {
            $query->where('property_id', $propertyId);
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($from = $request->get('from')) {
            $query->where('date', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->where('date', '<=', $to);
        }

        $expenses = $query->latest('date')->paginate(15)
            ->through(fn (Expense $e) => [
                'id' => $e->id,
                'property_name' => $e->property->name,
                'category' => $e->category->value,
                'category_label' => $e->category->label(),
                'amount' => $e->amount,
                'description' => $e->description,
                'date' => $e->date->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Expenses/Index', [
            'expenses' => $expenses,
            'filters' => $request->only(['property_id', 'category', 'from', 'to']),
            'categories' => ExpenseCategory::cases(),
            'properties' => Property::select('id', 'name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Expenses/Create', [
            'categories' => ExpenseCategory::cases(),
            'properties' => Property::select('id', 'name')->get(),
        ]);
    }

    public function store(ExpenseRequest $request): RedirectResponse
    {
        Expense::create([
            'property_id' => $request->property_id,
            'category' => $request->category,
            'amount' => (int) ($request->amount * 100),
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense recorded successfully!');
    }

    public function edit(Expense $expense): Response
    {
        $expense->load('property');

        return Inertia::render('Admin/Expenses/Edit', [
            'expense' => [
                'id' => $expense->id,
                'property_id' => $expense->property_id,
                'category' => $expense->category->value,
                'amount' => number_format($expense->amount / 100, 2),
                'description' => $expense->description,
                'date' => $expense->date->format('Y-m-d'),
            ],
            'categories' => ExpenseCategory::cases(),
            'properties' => Property::select('id', 'name')->get(),
        ]);
    }

    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update([
            'property_id' => $request->property_id,
            'category' => $request->category,
            'amount' => (int) ($request->amount * 100),
            'description' => $request->description,
            'date' => $request->date,
        ]);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Expense deleted successfully!');
    }
}
