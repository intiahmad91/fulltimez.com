@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Edit Package</h4>
                    <p class="text-muted mb-0">Update package details</p>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Packages
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $package->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                    <div class="price-input-group">
                                        <div class="currency-selector">
                                            <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                                <option value="USD" {{ old('currency', $package->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>🇺🇸 USD</option>
                                                <option value="AED" {{ old('currency', $package->currency ?? 'USD') == 'AED' ? 'selected' : '' }}>🇦🇪 AED</option>
                                                <option value="SAR" {{ old('currency', $package->currency ?? 'USD') == 'SAR' ? 'selected' : '' }}>🇸🇦 SAR</option>
                                                <option value="QAR" {{ old('currency', $package->currency ?? 'USD') == 'QAR' ? 'selected' : '' }}>🇶🇦 QAR</option>
                                                <option value="KWD" {{ old('currency', $package->currency ?? 'USD') == 'KWD' ? 'selected' : '' }}>🇰🇼 KWD</option>
                                                <option value="BHD" {{ old('currency', $package->currency ?? 'USD') == 'BHD' ? 'selected' : '' }}>🇧🇭 BHD</option>
                                                <option value="OMR" {{ old('currency', $package->currency ?? 'USD') == 'OMR' ? 'selected' : '' }}>🇴🇲 OMR</option>
                                                <option value="EUR" {{ old('currency', $package->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR</option>
                                                <option value="GBP" {{ old('currency', $package->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP</option>
                                                <option value="INR" {{ old('currency', $package->currency ?? 'USD') == 'INR' ? 'selected' : '' }}>🇮🇳 INR</option>
                                                <option value="PKR" {{ old('currency', $package->currency ?? 'USD') == 'PKR' ? 'selected' : '' }}>🇵🇰 PKR</option>
                                                <option value="EGP" {{ old('currency', $package->currency ?? 'USD') == 'EGP' ? 'selected' : '' }}>🇪🇬 EGP</option>
                                            </select>
                                        </div>
                                        <div class="price-input">
                                            <span id="currency_symbol" class="currency-symbol">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $package->price) }}" required>
                                        </div>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required>
                                    @error('duration_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" min="0" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $package->sort_order) }}">
                                    <small class="form-text text-muted">Lower numbers appear first</small>
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                 <div class="form-check mb-3">
                                     <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                            {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
                                     <input type="hidden" name="is_active" value="0">
                                     <label class="form-check-label" for="is_active">
                                         Active Package
                                     </label>
                                     <small class="form-text text-muted d-block">Inactive packages won't be available for selection</small>
                                 </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                                           {{ old('is_featured', $package->is_featured) ? 'checked' : '' }}>
                                    <input type="hidden" name="is_featured" value="0">
                                    <label class="form-check-label" for="is_featured">
                                        Featured Package
                                    </label>
                                    <small class="form-text text-muted d-block">Featured packages are highlighted</small>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Package
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Package Info Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Package Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        <small class="text-muted">{{ $package->created_at->format('M j, Y \a\t g:i A') }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted">{{ $package->updated_at->format('M j, Y \a\t g:i A') }}</small>
                    </div>
                    <div class="mb-3">
                        <strong>Current Status:</strong><br>
                        @if($package->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                        @if($package->is_featured)
                            <span class="badge bg-warning ms-1">Featured</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
