@extends('layouts.admin')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Properties</h1>
        <a href="{{ route('admin.properties.create') }}" class="btn">Create Property</a>
    </div>

    @if($properties->isEmpty())
        <p>No properties yet. <a href="{{ route('admin.properties.create') }}">Create one!</a></p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Bedrooms</th>
                    <th>Monthly Rent</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($properties as $property)
                    <tr>
                        <td>{{ $property->name }}</td>
                        <td>{{ ucfirst($property->type) }}</td>
                        <td>{{ $property->city }}, {{ $property->state }}</td>
                        <td>{{ $property->bedrooms }}</td>
                        <td>${{ number_format($property->monthly_rent / 100, 2) }}</td>
                        <td>{{ ucfirst($property->status) }}</td>
                        <td>
                            <a href="{{ route('admin.properties.show', $property) }}">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $properties->links() }}
        </div>
    @endif
@endsection