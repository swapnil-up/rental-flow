<?php

namespace App\Admin\Calendar\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    public function index(): Response
    {
        $month = (int) request('month', now()->month);
        $year = (int) request('year', now()->year);

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = $start->copy()->endOfMonth();

        $bookings = Booking::with('property')
            ->whereNotIn('status', [BookingStatus::Cancelled])
            ->where('check_in', '<=', $end)
            ->where('check_out', '>=', $start)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'property_id' => $b->property_id,
                'property_name' => $b->property->name,
                'check_in' => $b->check_in->format('Y-m-d'),
                'check_out' => $b->check_out->format('Y-m-d'),
                'status' => $b->status->value,
                'status_color' => $b->status->color(),
                'tenant_name' => $b->tenant?->name,
            ]);

        $prev = $start->copy()->subMonth();
        $next = $start->copy()->addMonth();

        return Inertia::render('Admin/Calendar/Index', [
            'month' => $month,
            'year' => $year,
            'monthName' => $start->format('F Y'),
            'startDayOfWeek' => $start->dayOfWeek,
            'daysInMonth' => $start->daysInMonth,
            'bookings' => $bookings,
            'prevMonth' => ['month' => $prev->month, 'year' => $prev->year],
            'nextMonth' => ['month' => $next->month, 'year' => $next->year],
        ]);
    }
}
