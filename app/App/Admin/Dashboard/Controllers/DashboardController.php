<?php

namespace App\Admin\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $totalProperties = Property::count();
        $availableProperties = Property::where('status', PropertyStatus::Available)->count();
        $activeBookings = Booking::where('status', BookingStatus::Active)->count();
        $pendingMaintenance = MaintenanceRequest::where('status', 'reported')->count();
        $monthlyRevenue = Payment::where('status', PaymentStatus::Paid)
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $occupancyRate = $totalProperties > 0
            ? round(($totalProperties - $availableProperties) / $totalProperties * 100)
            : 0;

        $upcomingCheckIns = Booking::with('property', 'tenant')
            ->whereIn('status', [BookingStatus::Confirmed, BookingStatus::Pending])
            ->whereBetween('check_in', [Carbon::today(), Carbon::today()->addDays(7)])
            ->latest('check_in')
            ->take(10)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'property_name' => $b->property->name,
                'tenant_name' => $b->tenant?->name,
                'check_in' => $b->check_in->format('M d, Y'),
                'status' => $b->status->value,
                'status_color' => $b->status->color(),
            ]);

        $upcomingCheckOuts = Booking::with('property', 'tenant')
            ->whereIn('status', [BookingStatus::Active, BookingStatus::Confirmed])
            ->whereBetween('check_out', [Carbon::today(), Carbon::today()->addDays(7)])
            ->latest('check_out')
            ->take(10)
            ->get()
            ->map(fn (Booking $b) => [
                'id' => $b->id,
                'property_name' => $b->property->name,
                'tenant_name' => $b->tenant?->name,
                'check_out' => $b->check_out->format('M d, Y'),
                'status' => $b->status->value,
                'status_color' => $b->status->color(),
            ]);

        $recentPayments = Payment::with('tenant')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Payment $p) => [
                'id' => $p->id,
                'tenant_name' => $p->tenant?->name,
                'amount' => $p->amount,
                'type' => $p->type,
                'status' => $p->status->value,
                'status_label' => $p->status->label(),
                'status_color' => $p->status->color(),
                'due_date' => $p->due_date->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Dashboard/Index', [
            'stats' => [
                'total_properties' => $totalProperties,
                'active_bookings' => $activeBookings,
                'pending_maintenance' => $pendingMaintenance,
                'monthly_revenue' => $monthlyRevenue,
                'occupancy_rate' => $occupancyRate,
            ],
            'upcomingCheckIns' => $upcomingCheckIns,
            'upcomingCheckOuts' => $upcomingCheckOuts,
            'recentPayments' => $recentPayments,
        ]);
    }
}
