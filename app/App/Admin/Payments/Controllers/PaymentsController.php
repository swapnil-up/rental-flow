<?php

namespace App\Admin\Payments\Controllers;

use App\Http\Controllers\Controller;
use Domain\Payments\Models\Payment;
use Domain\Payments\States\PaymentStatus;
use Domain\Tenants\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PaymentsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Payment::with('booking.property', 'tenant');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($tenantId = $request->get('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $payments = $query->latest()->paginate(15)
            ->through(fn (Payment $p) => [
                'id' => $p->id,
                'booking_id' => $p->booking_id,
                'tenant_name' => $p->tenant?->name,
                'property_name' => $p->booking->property->name,
                'amount' => $p->amount,
                'type' => $p->type,
                'status' => $p->status->value,
                'status_label' => $p->status->label(),
                'status_color' => $p->status->color(),
                'due_date' => $p->due_date->format('M d, Y'),
                'paid_at' => $p->paid_at?->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'filters' => $request->only(['status', 'type', 'tenant_id']),
            'statuses' => PaymentStatus::cases(),
            'tenants' => Tenant::select('id', 'name')->get(),
        ]);
    }

    public function show(Payment $payment): Response
    {
        $payment->load('booking.property', 'tenant');

        return Inertia::render('Admin/Payments/Show', [
            'payment' => [
                'id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'tenant_name' => $payment->tenant?->name,
                'tenant_id' => $payment->tenant_id,
                'property_name' => $payment->booking->property->name,
                'property_id' => $payment->booking->property_id,
                'amount' => $payment->amount,
                'type' => $payment->type,
                'status' => $payment->status->value,
                'status_label' => $payment->status->label(),
                'status_color' => $payment->status->color(),
                'due_date' => $payment->due_date->format('M d, Y'),
                'paid_at' => $payment->paid_at?->format('M d, Y'),
                'notes' => $payment->notes,
            ],
        ]);
    }

    public function markPaid(Payment $payment): RedirectResponse
    {
        if ($payment->status === PaymentStatus::Paid || $payment->status === PaymentStatus::Refunded) {
            return back()->with('error', 'Payment is already ' . $payment->status->label());
        }

        $payment->update([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Payment marked as paid.');
    }

    public function markRefunded(Payment $payment): RedirectResponse
    {
        if ($payment->status !== PaymentStatus::Paid) {
            return back()->with('error', 'Only paid payments can be refunded.');
        }

        $payment->update([
            'status' => PaymentStatus::Refunded,
        ]);

        return back()->with('success', 'Payment refunded.');
    }
}
