<?php

namespace App\Admin\Properties\Controllers;

use App\Admin\Properties\Requests\PropertyRequest;
use App\Http\Controllers\Controller;
use Domain\Properties\DataTransferObjects\PropertyData;
use Domain\Properties\Models\Property;
use Domain\Properties\Actions\CreatePropertyAction;
use Domain\Properties\Actions\UpdatePropertyAction;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PropertiesController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Property::query();

        if ($search = $request->get('search')) {
            $query->search($search);
        }

        if ($city = $request->get('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($bedrooms = $request->get('bedrooms')) {
            $query->where('bedrooms', $bedrooms);
        }

        if ($minPrice = $request->get('min_price')) {
            $query->where('monthly_rent', '>=', $minPrice * 100);
        }

        if ($maxPrice = $request->get('max_price')) {
            $query->where('monthly_rent', '<=', $maxPrice * 100);
        }

        $properties = $query->latest()->paginate(15);

        return Inertia::render('Admin/Properties/Index', [
            'properties' => $properties,
            'filters' => $request->only(['search', 'city', 'type', 'status', 'bedrooms', 'min_price', 'max_price']),
            'statuses' => PropertyStatus::cases(),
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

    public function edit(Property $property): Response
    {
        return Inertia::render('Admin/Properties/Edit', [
            'property' => $property,
        ]);
    }

    public function update(PropertyRequest $request, Property $property, UpdatePropertyAction $updatePropertyAction): RedirectResponse
    {
        $propertyData = PropertyData::fromRequest($request->validated());

        $updatePropertyAction->execute($property, $propertyData);

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property deleted successfully!');
    }
}