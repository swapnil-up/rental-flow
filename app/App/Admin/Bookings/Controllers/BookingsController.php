<?php

namespace App\Admin\Bookings\Controllers;

use App\Admin\Bookings\Requests\BookingRequest;
use App\Http\Controllers\Controller;
use App\Notifications\BookingCancelled;
use App\Notifications\BookingConfirmed;
use Domain\Bookings\Actions\ConfirmBookingAction;
use Domain\Bookings\Actions\CancelBookingAction;
use Domain\Bookings\Actions\CreateBookingAction;
use Domain\Bookings\DataTransferObjects\BookingData;
use Domain\Bookings\Models\Booking;
use Domain\Bookings\States\BookingStatus;
use Domain\Properties\Models\Property;
use Domain\Tenants\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Booking::query()->with('property', 'tenant');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($propertyId = $request->get('property_id')) {
            $query->where('property_id', $propertyId);
        }

        if ($from = $request->get('from')) {
            $query->where('check_in', '>=', $from);
        }

        if ($to = $request->get('to')) {
            $query->where('check_out', '<=', $to);
        }

        $bookings = $query
            ->latest()
            ->paginate(15)
            ->through(fn (Booking $booking) => [
                'id' => $booking->id,
                'property_id' => $booking->property_id,
                'property_name' => $booking->property->name,
                'tenant_name' => $booking->tenant?->name,
                'check_in' => $booking->check_in->format('M d, Y'),
                'check_out' => $booking->check_out->format('M d, Y'),
                'status' => $booking->status->value,
                'status_color' => $booking->status->color(),
                'available_actions' => $booking->state->availableActions(),
            ]);

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'property_id', 'from', 'to']),
            'statuses' => BookingStatus::cases(),
            'properties' => Property::select('id', 'name')->get(),
            'tenants' => Tenant::select('id', 'name')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Bookings/Create', [
            'properties' => Property::select('id', 'name', 'city', 'state')->get(),
            'tenants' => Tenant::select('id', 'name')->get(),
        ]);
    }

    public function store(BookingRequest $request, CreateBookingAction $createBookingAction): RedirectResponse
    {
        $data = BookingData::fromRequest($request->validated());

        $property = Property::findOrFail($data->property_id);

        try {
            $booking = $createBookingAction->execute(
                $property,
                $data->check_in,
                $data->check_out
            );

            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('success', 'Booking created successfully!');
        } catch (\DomainException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Booking $booking): Response
    {
        $booking->load('property', 'tenant');

        return Inertia::render('Admin/Bookings/Show', [
            'booking' => [
                'id' => $booking->id,
                'property_name' => $booking->property->name,
                'property_id' => $booking->property_id,
                'tenant_name' => $booking->tenant?->name,
                'tenant_id' => $booking->tenant_id,
                'check_in' => $booking->check_in->format('M d, Y'),
                'check_out' => $booking->check_out->format('M d, Y'),
                'status' => $booking->status->value,
                'status_color' => $booking->status->color(),
                'available_actions' => $booking->state->availableActions(),
            ],
        ]);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully!');
    }

    public function confirm(
        Booking $booking,
        ConfirmBookingAction $action
    ): RedirectResponse {
        try {
            $action->execute($booking);
            $booking->load('property', 'tenant');

            if ($booking->tenant) {
                $booking->tenant->notify(new BookingConfirmed($booking));
            }

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
            $booking->load('property', 'tenant');

            if ($booking->tenant) {
                $booking->tenant->notify(new BookingCancelled($booking));
            }

            return back()->with('success', 'Booking cancelled successfully!');
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}