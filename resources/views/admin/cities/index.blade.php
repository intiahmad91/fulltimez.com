@extends('layouts.admin')

@section('title', 'Cities')
@section('page-title', 'Cities')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-city"></i> Cities</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.cities.create') }}" class="admin-btn admin-btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add City
            </a>
            <span class="admin-badge badge-primary">{{ $cities->total() }} Total</span>
        </div>
    </div>
    <div class="admin-card-body">
        <!-- Cities List -->
        <div class="cities-list" id="citiesList">
            @forelse($cities as $city)
            <div class="city-card" data-city-id="{{ $city->id }}">
                <div class="city-card-content">
                    <div class="city-header">
                        <div class="city-main-info">
                            <div class="city-title-section">
                                <div class="city-title-row">
                                    <h4 class="city-title">
                                        <i class="fas fa-city"></i>
                                        {{ $city->name }}
                                    </h4>
                                    <div class="city-meta-badges">
                                        <span class="meta-badge country-badge">
                                            <i class="fas fa-globe"></i>
                                            {{ $city->country->name }}
                                        </span>
                                        <span class="meta-badge date-badge">
                                            <i class="fas fa-calendar"></i>
                                            {{ $city->created_at->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="city-status-section">
                                @if($city->is_active)
                                    <span class="status-badge active">Active</span>
                                @else
                                    <span class="status-badge inactive">Inactive</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="city-actions">
                            <a href="{{ route('admin.cities.edit', $city) }}" class="action-btn edit-btn" title="Edit City">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this city?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" title="Delete City">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="no-cities">
                <div class="no-cities-icon">
                    <i class="fas fa-city"></i>
                </div>
                <h4>No cities found</h4>
                <p>There are no cities available.</p>
                <a href="{{ route('admin.cities.create') }}" class="create-btn">
                    <i class="fas fa-plus"></i> Create First City
                </a>
            </div>
            @endforelse
        </div>

<!-- Pagination Info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="pagination-info">
                    <p class="text-muted mb-0">
                        Showing {{ $cities->firstItem() ?? 0 }} to {{ $cities->lastItem() ?? 0 }} of {{ $cities->total() }} cities
                    </p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="per-page-selector">
                    <form method="GET" action="{{ route('admin.cities.index') }}" class="d-inline">
                        @foreach(request()->query() as $key => $value)
                            @if($key !== 'per_page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pagination-controls">
                    {{ $cities->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
