@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Job Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-edit"></i> Edit Category</h5>
                <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="admin-form-group">
                        <label for="name">Category Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="admin-form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" 
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
                                  rows="4">{{ old('description', $category->description) }}</textarea>
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
                               value="{{ old('icon', $category->icon) }}" 
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
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update Category
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
