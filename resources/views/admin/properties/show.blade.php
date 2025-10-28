@extends('layouts.admin')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>{{ $property->name }}</h1>
        <a href="{{ route('admin.properties.index') }}">Back to List</a>
    </div>

    <div style="background: #f7fafc; padding: 20px; border-radius: 8px;">
        <h3>Property Details</h3>
        
        <div style="display: grid; grid-template-columns: 200px 1fr; gap: 10px; margin-top: 15px;">
            <strong>Type:</strong>
            <span>{{ ucfirst($property->type) }}</span>

            <strong>Address:</strong>
            <span>{{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->zip_code }}</span>

            <strong>Bedrooms:</strong>
            <span>{{ $property->bedrooms }}</span>

            <strong>Bathrooms:</strong>
            <span>{{ $property->bathrooms }}</span>

            <strong>Square Feet:</strong>
            <span>{{ number_format($property->square_feet) }} sq ft</span>

            <strong>Monthly Rent:</strong>
            <span>${{ number_format($property->monthly_rent / 100, 2) }}</span>

            <strong>Status:</strong>
            <span>{{ ucfirst($property->status) }}</span>

            <strong>Created:</strong>
            <span>{{ $property->created_at->format('M d, Y') }}</span>
        </div>
    </div>
@endsection