@extends('layouts.admin')

@section('title', 'Countries')
@section('page-title', 'Countries')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-globe"></i> Countries</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.countries.create') }}" class="admin-btn admin-btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Country
            </a>
            <span class="admin-badge badge-primary">{{ $countries->total() }} Total</span>
        </div>
    </div>
    <div class="admin-card-body">
        <!-- Countries List -->
        <div class="countries-list" id="countriesList">
            @forelse($countries as $country)
            <div class="country-card" data-country-id="{{ $country->id }}">
                <div class="country-card-content">
                    <div class="country-header">
                        <div class="country-main-info">
                            <div class="country-title-section">
                                <div class="country-title-row">
                                    <h4 class="country-title">
                                        <i class="fas fa-globe"></i>
                                        {{ $country->name }}
                                    </h4>
                                    <div class="country-meta-badges">
                                        <span class="meta-badge code-badge">
                                            <i class="fas fa-code"></i>
                                            {{ $country->code }}
                                        </span>
                                        <span class="meta-badge cities-badge">
                                            <i class="fas fa-city"></i>
                                            {{ $country->cities_count }} Cities
                                        </span>
                                    </div>
                                </div>
                                <div class="country-created">
                                    <i class="fas fa-calendar"></i>
                                    Created {{ $country->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            
                            <div class="country-status-section">
                                @if($country->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="country-actions">
                            <a href="{{ route('admin.countries.edit', $country) }}" class="action-btn edit-btn" title="Edit Country">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this country? This will also delete all its cities.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" title="Delete Country">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="no-countries">
                <div class="no-countries-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h4>No countries found</h4>
                <p>There are no countries available.</p>
                <a href="{{ route('admin.countries.create') }}" class="create-btn">
                    <i class="fas fa-plus"></i> Create First Country
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($countries->hasPages())
        <div class="pagination-wrapper mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <span class="text-muted">
                        Showing {{ $countries->firstItem() }} to {{ $countries->lastItem() }} of {{ $countries->total() }} countries
                    </span>
                </div>
                <div class="pagination-controls">
                    {{ $countries->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
