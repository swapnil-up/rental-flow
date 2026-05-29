<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $tenant = auth()->user()->tenant;

        $bookings = $tenant?->bookings()->with('property')->latest()->get()
            ->map(fn ($booking) => [
                'id' => $booking->id,
                'property_name' => $booking->property->name,
                'check_in' => $booking->check_in->format('M d, Y'),
                'check_out' => $booking->check_out->format('M d, Y'),
                'status' => $booking->status->value,
                'status_color' => $booking->status->color(),
            ]) ?? [];

        return Inertia::render('Tenant/Dashboard', [
            'bookings' => $bookings,
        ]);
    }
}
