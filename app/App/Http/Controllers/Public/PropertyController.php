<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Domain\Properties\Models\Property;
use Domain\Properties\States\PropertyStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PropertyController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Property::where('status', PropertyStatus::Available);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(12);

        return Inertia::render('Public/Properties/Index', [
            'properties' => $properties,
            'filters' => $request->only('search'),
        ]);
    }

    public function show(Property $property): Response
    {
        abort_if($property->status !== PropertyStatus::Available, 404);

        return Inertia::render('Public/Properties/Show', [
            'property' => [
                'id' => $property->id,
                'name' => $property->name,
                'type' => $property->type,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'zip_code' => $property->zip_code,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'square_feet' => $property->square_feet,
                'monthly_rent' => $property->monthly_rent,
                'description' => $property->description ?? $property->name,
            ],
        ]);
    }
}
