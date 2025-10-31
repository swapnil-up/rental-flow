<?php

namespace App\Admin\Properties\Controllers;

use App\Admin\Properties\Requests\PropertyRequest;
use App\Http\Controllers\Controller;
use Domain\Properties\DataTransferObjects\PropertyData;
use Domain\Properties\Models\Property;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Domain\Properties\Actions\CreatePropertyAction;

class PropertiesController extends Controller
{
    public function index(): Response
    {
        $properties = Property::query()
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Properties/Index', [
            'properties' => $properties,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Properties/Create');
    }

    public function store(PropertyRequest $request, CreatePropertyAction $createPropertyAction): RedirectResponse
    {
        $propertyData = PropertyData::fromRequest($request->validated());
        
        $property = $createPropertyAction->execute($propertyData);

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('success', 'Property created successfully!');
    }

    public function show(Property $property): Response
    {
        return Inertia::render('Admin/Properties/Show', [
            'property' => $property,
        ]);
    }
}