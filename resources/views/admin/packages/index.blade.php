@extends('layouts.admin')

@section('title', 'Package Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Package Management</h4>
                    <p class="text-muted mb-0">Manage job posting packages for employers</p>
                </div>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Package
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-primary mb-1">{{ $packages->total() }}</h3>
                    <p class="text-muted mb-0">Total Packages</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-success mb-1">{{ $packages->where('is_active', true)->count() }}</h3>
                    <p class="text-muted mb-0">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-warning mb-1">{{ $packages->where('is_featured', true)->count() }}</h3>
                    <p class="text-muted mb-0">Featured</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-info mb-1">${{ number_format($packages->sum('price'), 2) }}</h3>
                    <p class="text-muted mb-0">Total Value</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Packages Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Package</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($packages as $package)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $package->name }}</strong>
                                                @if($package->description)
                                                    <br><small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $package->formatted_price }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $package->duration_text }}</small>
                                        </td>
                                        <td>
                                            @if($package->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($package->is_featured)
                                                <span class="badge bg-warning">Featured</span>
                                            @else
                                                <span class="badge bg-light text-dark">Regular</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a href="{{ route('admin.packages.show', $package) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No packages found</h5>
                                            <p class="text-muted">Create your first package to get started.</p>
                                            <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create Package
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($packages->hasPages())
                    <div class="pagination-wrapper mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination-info">
                                <span class="text-muted">
                                    Showing {{ $packages->firstItem() }} to {{ $packages->lastItem() }} of {{ $packages->total() }} packages
                                </span>
                            </div>
                            <div class="pagination-controls">
                                {{ $packages->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
