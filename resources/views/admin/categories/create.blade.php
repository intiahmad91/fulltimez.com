@extends('layouts.admin')

@section('title', 'Create Category')
@section('page-title', 'Create Job Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-plus"></i> Create New Category</h5>
                <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="admin-form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="admin-form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="description">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  class="admin-form-control @error('description') is-invalid @enderror" 
                                  rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="icon">Icon Class (Font Awesome)</label>
                        <input type="text" 
                               id="icon" 
                               name="icon" 
                               class="admin-form-control @error('icon') is-invalid @enderror" 
                               value="{{ old('icon') }}" 
                               placeholder="e.g., fas fa-briefcase">
                        <small class="text-muted">Enter Font Awesome icon class (e.g., fas fa-briefcase)</small>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <div class="form-check">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   class="form-check-input" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Create Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-secondary ms-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
