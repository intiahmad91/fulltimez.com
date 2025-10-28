@extends('layouts.admin')

@section('title', 'Create Package')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Create Package</h4>
                    <p class="text-muted mb-0">Add a new job posting package</p>
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

                    <form action="{{ route('admin.packages.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>🇺🇸 USD</option>
                                                <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>🇦🇪 AED</option>
                                                <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>🇸🇦 SAR</option>
                                                <option value="QAR" {{ old('currency') == 'QAR' ? 'selected' : '' }}>🇶🇦 QAR</option>
                                                <option value="KWD" {{ old('currency') == 'KWD' ? 'selected' : '' }}>🇰🇼 KWD</option>
                                                <option value="BHD" {{ old('currency') == 'BHD' ? 'selected' : '' }}>🇧🇭 BHD</option>
                                                <option value="OMR" {{ old('currency') == 'OMR' ? 'selected' : '' }}>🇴🇲 OMR</option>
                                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>🇪🇺 EUR</option>
                                                <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>🇬🇧 GBP</option>
                                                <option value="INR" {{ old('currency') == 'INR' ? 'selected' : '' }}>🇮🇳 INR</option>
                                                <option value="PKR" {{ old('currency') == 'PKR' ? 'selected' : '' }}>🇵🇰 PKR</option>
                                                <option value="EGP" {{ old('currency') == 'EGP' ? 'selected' : '' }}>🇪🇬 EGP</option>
                                            </select>
                                        </div>
                                        <div class="price-input">
                                            <span id="currency_symbol" class="currency-symbol">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" required>
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
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duration_days" class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                    <input type="number" min="1" class="form-control @error('duration_days') is-invalid @enderror" 
                                           id="duration_days" name="duration_days" value="{{ old('duration_days') }}" required>
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
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
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
                                            {{ old('is_active', true) ? 'checked' : '' }}>
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
                                           {{ old('is_featured') ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Create Package
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Package Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>Name:</strong> Choose a clear, descriptive name
                        </li>
                        <li class="mb-2">
                            <strong>Price:</strong> Set competitive pricing
                        </li>
                        <li class="mb-2">
                            <strong>Duration:</strong> How long the package is valid
                        </li>
                        <li class="mb-2">
                            <strong>Job Limit:</strong> Maximum job posts allowed
                        </li>
                        <li class="mb-2">
                            <strong>Featured:</strong> Highlight popular packages
                        </li>
                        <li class="mb-0">
                            <strong>Sort Order:</strong> Control display order
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.price-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.currency-selector {
    flex: 0 0 120px;
}

.currency-selector select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 14px;
    font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;
}

.price-input {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.price-input input[type="number"] {
    flex: 1;
    padding: 12px 12px 12px 30px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
}

.currency-symbol {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    z-index: 2;
    pointer-events: none;
    font-weight: bold;
    color: #666;
}

.price-input input[type="number"]:focus,
.currency-selector select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    .price-input-group {
        flex-direction: column;
        gap: 8px;
    }
    
    .currency-selector {
        flex: none;
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currencySelect = document.getElementById('currency');
    const currencySymbol = document.getElementById('currency_symbol');
    
    const currencySymbols = {
        'USD': '$',
        'AED': 'د.إ',
        'SAR': 'ر.س',
        'QAR': 'ر.ق',
        'KWD': 'د.ك',
        'BHD': 'د.ب',
        'OMR': 'ر.ع.',
        'EUR': '€',
        'GBP': '£',
        'INR': '₹',
        'PKR': '₨',
        'EGP': 'ج.م'
    };
    
    currencySelect.addEventListener('change', function() {
        const selectedCurrency = this.value;
        currencySymbol.textContent = currencySymbols[selectedCurrency] || '$';
    });
    
    // Initialize on page load
    const selectedCurrency = currencySelect.value;
    currencySymbol.textContent = currencySymbols[selectedCurrency] || '$';
});
</script>
@endpush

@endsection
