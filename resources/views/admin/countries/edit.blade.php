@extends('layouts.admin')

@section('title', 'Edit Country')
@section('page-title', 'Edit Country')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-edit"></i> Edit Country: {{ $country->name }}</h5>
        <a href="{{ route('admin.countries.index') }}" class="admin-btn admin-btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Countries
        </a>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.countries.update', $country) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label">Country Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $country->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code" class="form-label">Country Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $country->code) }}" 
                               placeholder="e.g., UAE, PAK, USA" maxlength="3" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">3-letter country code (ISO format)</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $country->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active
                    </label>
                </div>
                <small class="form-text text-muted">Inactive countries won't appear in dropdowns</small>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.countries.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Update Country
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
