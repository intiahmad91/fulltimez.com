@extends('layouts.admin')

@section('title', 'Package Details')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Package Details</h4>
                    <p class="text-muted mb-0">View package information and usage statistics</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit Package
                    </a>
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Packages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Package Details -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Package Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Package Name:</strong><br>
                                <span class="fs-5">{{ $package->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Price:</strong><br>
                                <span class="fs-5 text-primary">{{ $package->formatted_price }}</span>
                            </div>
                        </div>
                    </div>

                    @if($package->description)
                        <div class="mb-3">
                            <strong>Description:</strong><br>
                            <p class="text-muted">{{ $package->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Duration:</strong><br>
                                <span class="badge bg-info">{{ $package->duration_text }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Sort Order:</strong><br>
                                <span class="badge bg-light text-dark">{{ $package->sort_order }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Status:</strong><br>
                                @if($package->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Featured:</strong><br>
                                @if($package->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @else
                                    <span class="badge bg-light text-dark">Regular</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Created:</strong><br>
                                <small class="text-muted">{{ $package->created_at->format('M j, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Last Updated:</strong><br>
                                <small class="text-muted">{{ $package->updated_at->format('M j, Y \a\t g:i A') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-cogs"></i> Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Package
                        </a>
                        
                        <form action="{{ route('admin.packages.toggle-status', $package) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $package->is_active ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="fas fa-{{ $package->is_active ? 'pause' : 'play' }}"></i> 
                                {{ $package->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.packages.toggle-featured', $package) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $package->is_featured ? 'btn-outline-warning' : 'btn-warning' }} w-100">
                                <i class="fas fa-star"></i> 
                                {{ $package->is_featured ? 'Remove from Featured' : 'Make Featured' }}
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this package? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Delete Package
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
