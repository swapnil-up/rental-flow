<?php

namespace App\Admin\Tenants\Controllers;

use App\Admin\Tenants\Requests\TenantRequest;
use App\Http\Controllers\Controller;
use Domain\Tenants\Actions\CreateTenantAction;
use Domain\Tenants\DataTransferObjects\TenantData;
use Domain\Tenants\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TenantsController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Tenant::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $tenants = $query->latest()->paginate(15);

        return Inertia::render('Admin/Tenants/Index', [
            'tenants' => $tenants,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Tenants/Create');
    }

    public function store(TenantRequest $request, CreateTenantAction $action): RedirectResponse
    {
        $data = TenantData::fromRequest($request->validated());
        $tenant = $action->execute($data);

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant created successfully!');
    }

    public function show(Tenant $tenant): Response
    {
        $tenant->load('bookings.property');

        return Inertia::render('Admin/Tenants/Show', [
            'tenant' => $tenant,
        ]);
    }

    public function edit(Tenant $tenant): Response
    {
        return Inertia::render('Admin/Tenants/Edit', [
            'tenant' => $tenant,
        ]);
    }

    public function update(TenantRequest $request, Tenant $tenant): RedirectResponse
    {
        $tenant->update($request->validated());

        return redirect()
            ->route('admin.tenants.show', $tenant)
            ->with('success', 'Tenant updated successfully!');
    }

    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant deleted successfully!');
    }
}
