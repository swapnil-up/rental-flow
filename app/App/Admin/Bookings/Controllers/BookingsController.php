<?php

namespace App\Admin\Bookings\Controllers;

use App\Http\Controllers\Controller;
use Domain\Bookings\Actions\ConfirmBookingAction;
use Domain\Bookings\Actions\CancelBookingAction;
use Domain\Bookings\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BookingsController extends Controller
{
    public function index(): Response
    {
        $bookings = Booking::query()
            ->with('property')
            ->latest()
            ->paginate(15)
            ->through(fn (Booking $booking) => [
                'id' => $booking->id,
                'property_name' => $booking->property->name,
                'check_in' => $booking->check_in->format('M d, Y'),
                'check_out' => $booking->check_out->format('M d, Y'),
                'status' => $booking->status,
                'status_color' => $booking->state->color(),
                'available_actions' => $booking->state->availableActions(),
            ]);

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
        ]);
    }

    public function confirm(
        Booking $booking,
        ConfirmBookingAction $action
    ): RedirectResponse {
        try {
            $action->execute($booking);
            
            return back()->with('success', 'Booking confirmed successfully!');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function cancel(
        Booking $booking,
        CancelBookingAction $action
    ): RedirectResponse {
        try {
            $action->execute($booking);
            
            return back()->with('success', 'Booking cancelled successfully!');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}