@extends('layouts.admin')

@section('title', 'Job Categories')
@section('page-title', 'Job Categories')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-tags"></i> Job Categories</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Category
            </a>
            <span class="admin-badge badge-primary">{{ $categories->total() }} Total</span>
        </div>
    </div>
    <div class="admin-card-body">
        <!-- Categories List -->
        <div class="categories-list" id="categoriesList">
            @forelse($categories as $category)
            <div class="category-card" data-category-id="{{ $category->id }}">
                <div class="category-card-content">
                    <div class="category-header">
                        <div class="category-main-info">
                            <div class="category-title-section">
                                <div class="category-title-row">
                                    <h4 class="category-title">
                                        @if($category->icon)
                                            <i class="{{ $category->icon }}"></i>
                                        @endif
                                        {{ $category->name }}
                                    </h4>
                                    <div class="category-meta-badges">
                                        <span class="meta-badge jobs-badge">
                                            <i class="fas fa-briefcase"></i>
                                            {{ $category->jobPostings()->count() }} Jobs
                                        </span>
                                        <span class="meta-badge slug-badge">
                                            <i class="fas fa-link"></i>
                                            {{ $category->slug }}
                                        </span>
                                    </div>
                                </div>
                                @if($category->description)
                                <div class="category-description">
                                    {{ Str::limit($category->description, 100) }}
                                </div>
                                @endif
                            </div>
                            
                            <div class="category-status-section">
                                @if($category->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="category-actions">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="action-btn edit-btn" title="Edit Category">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" title="Delete Category">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="no-categories">
                <div class="no-categories-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <h4>No categories found</h4>
                <p>There are no job categories available.</p>
                <a href="{{ route('admin.categories.create') }}" class="create-btn">
                    <i class="fas fa-plus"></i> Create First Category
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="pagination-wrapper mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <span class="text-muted">
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                    </span>
                </div>
                <div class="pagination-controls">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
