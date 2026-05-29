<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Domain\Maintenance\Actions\CreateMaintenanceRequestAction;
use Domain\Maintenance\DataTransferObjects\MaintenanceRequestData;
use Domain\Maintenance\Models\MaintenanceRequest;
use Domain\Properties\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaintenanceController extends Controller
{
    public function index(): Response
    {
        $tenant = auth()->user()->tenant;

        $requests = MaintenanceRequest::with('property')
            ->where('tenant_id', $tenant?->id)
            ->latest()
            ->get()
            ->map(fn (MaintenanceRequest $r) => [
                'id' => $r->id,
                'title' => $r->title,
                'property_name' => $r->property->name,
                'priority' => $r->priority,
                'status_label' => $r->status->label(),
                'status_color' => $r->status->color(),
                'created_at' => $r->created_at->format('M d, Y'),
            ]);

        return Inertia::render('Tenant/Maintenance/Index', [
            'requests' => $requests,
        ]);
    }

    public function create(): Response
    {
        $tenant = auth()->user()->tenant;

        $properties = Property::query()
            ->whereHas('bookings', fn ($q) => $q->where('tenant_id', $tenant?->id))
            ->select('id', 'name', 'city', 'state')
            ->get();

        return Inertia::render('Tenant/Maintenance/Create', [
            'properties' => $properties,
        ]);
    }

    public function store(Request $request, CreateMaintenanceRequestAction $action): RedirectResponse
    {
        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high,emergency'],
        ]);

        $maintenanceData = MaintenanceRequestData::fromRequest($data);
        $tenant = auth()->user()->tenant;

        $action->execute($maintenanceData, $tenant?->id);

        return redirect('/maintenance')
            ->with('success', 'Maintenance request submitted.');
    }
}
