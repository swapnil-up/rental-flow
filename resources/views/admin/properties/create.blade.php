@extends('layouts.admin')

@section('content')
    <h1>Create Property</h1>

    <form method="POST" action="{{ route('admin.properties.store') }}">
        @csrf

        <div class="form-group">
            <label>Property Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Type</label>
            <select name="type" required style="width: 100%; padding: 8px;">
                <option value="">Select type...</option>
                <option value="apartment" {{ old('type') === 'apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="house" {{ old('type') === 'house' ? 'selected' : '' }}>House</option>
                <option value="condo" {{ old('type') === 'condo' ? 'selected' : '' }}>Condo</option>
                <option value="studio" {{ old('type') === 'studio' ? 'selected' : '' }}>Studio</option>
            </select>
            @error('type')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="{{ old('address') }}" required>
            @error('address')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" value="{{ old('city') }}" required>
                @error('city')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" value="{{ old('state') }}" maxlength="2" required>
                @error('state')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Zip Code</label>
                <input type="text" name="zip_code" value="{{ old('zip_code') }}" required>
                @error('zip_code')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Bedrooms</label>
                <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" min="0" required>
                @error('bedrooms')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Bathrooms</label>
                <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" step="0.5" min="0" required>
                @error('bathrooms')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Square Feet</label>
                <input type="number" name="square_feet" value="{{ old('square_feet') }}" min="1" required>
                @error('square_feet')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label>Monthly Rent (Rs.)</label>
            <input type="number" name="monthly_rent" value="{{ old('monthly_rent') }}" step="0.01" min="0" required>
            @error('monthly_rent')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn">Create Property</button>
            <a href="{{ route('admin.properties.index') }}" style="margin-left: 10px;">Cancel</a>
        </div>
    </form>
@endsection