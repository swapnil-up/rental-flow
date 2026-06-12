<?php

namespace App\Admin\Reports\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Domain\Expenses\Models\Expense;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Properties\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfitLossController extends Controller
{
    public function index(Request $request): Response
    {
        $period = $request->get('period', 'month');
        $month = (int) $request->get('month', now()->month);
        $year = (int) $request->get('year', now()->year);

        [$start, $end] = match ($period) {
            'quarter' => $this->quarterRange($month, $year),
            'year' => [Carbon::create($year, 1, 1), Carbon::create($year, 12, 31)->endOfDay()],
            default => [Carbon::create($year, $month, 1), Carbon::create($year, $month, 1)->endOfMonth()],
        };

        $properties = Property::all();

        $incomeByProperty = Payment::query()
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('payments.status', PaymentStatus::Paid)
            ->whereBetween('payments.paid_at', [$start, $end])
            ->groupBy('bookings.property_id')
            ->selectRaw('bookings.property_id, SUM(payments.amount) as total')
            ->pluck('total', 'property_id');

        $expensesByProperty = Expense::query()
            ->whereBetween('date', [$start, $end])
            ->groupBy('property_id')
            ->selectRaw('property_id, SUM(amount) as total')
            ->pluck('total', 'property_id');

        $rows = $properties->map(fn (Property $p) => [
            'property_id' => $p->id,
            'property_name' => $p->name,
            'income' => (int) ($incomeByProperty[$p->id] ?? 0),
            'expenses' => (int) ($expensesByProperty[$p->id] ?? 0),
            'net' => (int) ($incomeByProperty[$p->id] ?? 0) - (int) ($expensesByProperty[$p->id] ?? 0),
        ]);

        $totals = [
            'income' => $rows->sum('income'),
            'expenses' => $rows->sum('expenses'),
            'net' => $rows->sum('net'),
        ];

        $prevMonth = $start->copy()->subMonth();
        $nextMonth = $start->copy()->addMonth();

        return Inertia::render('Admin/Reports/ProfitLoss', [
            'rows' => $rows,
            'totals' => $totals,
            'period' => $period,
            'month' => $month,
            'year' => $year,
            'periodLabel' => $start->format('F Y'),
            'prev' => ['period' => $period, 'month' => $prevMonth->month, 'year' => $prevMonth->year],
            'next' => ['period' => $period, 'month' => $nextMonth->month, 'year' => $nextMonth->year],
        ]);
    }

    private function quarterRange(int $month, int $year): array
    {
        $startMonth = (int) (floor(($month - 1) / 3) * 3) + 1;
        $start = Carbon::create($year, $startMonth, 1);
        $end = $start->copy()->addMonths(3)->subDay()->endOfDay();
        return [$start, $end];
    }
}
