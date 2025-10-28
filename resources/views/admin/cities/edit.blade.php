@extends('layouts.admin')

@section('title', 'Edit City')
@section('page-title', 'Edit City')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-edit"></i> Edit City: {{ $city->name }}</h5>
        <a href="{{ route('admin.cities.index') }}" class="admin-btn admin-btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Cities
        </a>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.cities.update', $city) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">City Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $city->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-control @error('country_id') is-invalid @enderror" 
                                id="country_id" name="country_id" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" 
                                        {{ old('country_id', $city->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }} ({{ $country->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $city->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>
                <small class="form-text text-muted">Inactive cities won't appear in dropdowns</small>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.cities.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Update City
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
