<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Domain\Payments\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function index(): Response
    {
        $tenant = auth()->user()->tenant;

        $payments = Payment::with('booking.property')
            ->where('tenant_id', $tenant?->id)
            ->latest()
            ->get()
            ->map(fn (Payment $p) => [
                'id' => $p->id,
                'property_name' => $p->booking->property->name,
                'amount' => $p->amount,
                'type' => $p->type,
                'status' => $p->status->value,
                'status_label' => $p->status->label(),
                'status_color' => $p->status->color(),
                'due_date' => $p->due_date->format('M d, Y'),
                'paid_at' => $p->paid_at?->format('M d, Y'),
            ]);

        return Inertia::render('Tenant/Payments/Index', [
            'payments' => $payments,
        ]);
    }
}
