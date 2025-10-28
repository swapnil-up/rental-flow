<?php

namespace App\Admin\Properties\Controllers;

use App\Admin\Properties\Requests\PropertyRequest;
use App\Http\Controllers\Controller;
use Domain\Properties\DataTransferObjects\PropertyData;
use Domain\Properties\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertiesController extends Controller
{
    public function index(): View
    {
        $properties = Property::query()
            ->latest()
            ->paginate(15);

        return view('admin.properties.index', [
            'properties' => $properties,
        ]);
    }

    public function create(): View
    {
        return view('admin.properties.create');
    }

    public function store(PropertyRequest $request): RedirectResponse
    {
        $propertyData = PropertyData::fromRequest($request->validated());
        
        $property = Property::create($propertyData->toArray());

        return redirect()
            ->route('admin.properties.show', $property)
            ->with('success', 'Property created successfully!');
    }

    public function show(Property $property): View
    {
        return view('admin.properties.show', [
            'property' => $property,
        ]);
    }
}