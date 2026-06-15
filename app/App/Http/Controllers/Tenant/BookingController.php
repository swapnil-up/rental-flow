<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function index(): Response
    {
        $tenant = auth()->user()->tenant;

        $bookings = $tenant?->bookings()->with('property')->latest()->get()
            ->map(fn ($booking) => [
                'id' => $booking->id,
                'property_name' => $booking->property->name,
                'property_address' => $booking->property->address,
                'check_in' => $booking->check_in->format('M d, Y'),
                'check_out' => $booking->check_out->format('M d, Y'),
                'total_amount' => $booking->total_amount,
                'status' => $booking->status->value,
                'status_label' => $booking->status->label(),
                'status_color' => $booking->status->color(),
                'can_cancel' => $booking->status === BookingStatus::Pending,
            ]) ?? [];

        return Inertia::render('Tenant/Bookings/Index', [
            'bookings' => $bookings,
        ]);
    }

    public function cancel(int $id): RedirectResponse
    {
        $tenant = auth()->user()->tenant;
        $booking = Booking::where('tenant_id', $tenant->id)->findOrFail($id);

        if ($booking->status !== BookingStatus::Pending) {
            return back()->with('error', 'Only pending bookings can be cancelled.');
        }

        $booking->update(['status' => BookingStatus::Cancelled]);

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
