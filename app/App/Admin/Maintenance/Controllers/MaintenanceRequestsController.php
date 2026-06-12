<?php

namespace App\Admin\Maintenance\Controllers;

use App\Admin\Maintenance\Requests\TransitionMaintenanceRequest;
use App\Http\Controllers\Controller;
use App\Notifications\MaintenanceStatusChanged;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Maintenance\States\MaintenanceRequestStatus;
use Domain\Properties\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceRequestsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = MaintenanceRequest::with('property', 'tenant');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        if ($propertyId = $request->get('property_id')) {
            $query->where('property_id', $propertyId);
        }

        $requests = $query->latest()->paginate(15)
            ->through(fn (MaintenanceRequest $r) => [
                'id' => $r->id,
                'title' => $r->title,
                'property_name' => $r->property->name,
                'tenant_name' => $r->tenant?->name,
                'priority' => $r->priority,
                'status' => $r->status->value,
                'status_label' => $r->status->label(),
                'status_color' => $r->status->color(),
                'created_at' => $r->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Admin/Maintenance/Index', [
            'requests' => $requests,
            'filters' => $request->only(['status', 'priority', 'property_id']),
            'statuses' => MaintenanceRequestStatus::cases(),
            'properties' => Property::select('id', 'name')->get(),
        ]);
    }

    public function show(MaintenanceRequest $maintenanceRequest): Response
    {
        $maintenanceRequest->load('property', 'tenant');

        return Inertia::render('Admin/Maintenance/Show', [
            'request' => [
                'id' => $maintenanceRequest->id,
                'title' => $maintenanceRequest->title,
                'description' => $maintenanceRequest->description,
                'property_name' => $maintenanceRequest->property->name,
                'property_id' => $maintenanceRequest->property_id,
                'tenant_name' => $maintenanceRequest->tenant?->name,
                'tenant_id' => $maintenanceRequest->tenant_id,
                'priority' => $maintenanceRequest->priority,
                'status' => $maintenanceRequest->status->value,
                'status_label' => $maintenanceRequest->status->label(),
                'status_color' => $maintenanceRequest->status->color(),
                'available_transitions' => array_map(
                    fn (MaintenanceRequestStatus $s) => ['value' => $s->value, 'label' => $s->label()],
                    $maintenanceRequest->status->availableTransitions(),
                ),
                'created_at' => $maintenanceRequest->created_at->format('M d, Y'),
            ],
        ]);
    }

    public function transition(
        MaintenanceRequest $maintenanceRequest,
        TransitionMaintenanceRequest $request,
    ): RedirectResponse {
        $target = MaintenanceRequestStatus::from($request->validated()['status']);

        if (!$maintenanceRequest->status->canTransitionTo($target)) {
            return back()->with('error', "Cannot transition from {$maintenanceRequest->status->value} to {$target->value}");
        }

        $maintenanceRequest->update(['status' => $target]);
        $maintenanceRequest->load('property', 'tenant');

        if ($maintenanceRequest->tenant) {
            $maintenanceRequest->tenant->notify(new MaintenanceStatusChanged($maintenanceRequest));
        }

        return back()->with('success', "Request moved to {$target->label()}");
    }
}
