@extends('layouts.admin')

@section('title', 'Jobs Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jobs Management</li>
                    </ol>
                </div>
                <h4 class="page-title">Jobs Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle text-primary">
                                <i class="fas fa-briefcase font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['total'] }}</h5>
                            <p class="text-muted mb-0">Total Jobs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle text-success">
                                <i class="fas fa-check font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['published'] }}</h5>
                            <p class="text-muted mb-0">Published</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle text-warning">
                                <i class="fas fa-clock font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['pending'] }}</h5>
                            <p class="text-muted mb-0">Pending Approval</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle text-danger">
                                <i class="fas fa-times font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $stats['closed'] }}</h5>
                            <p class="text-muted mb-0">Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">All Jobs</h4>
                        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Job
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.jobs.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search jobs by title, location..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="company">
                        <option value="">All Companies</option>
                        @foreach($jobs->pluck('employer.employerProfile.company_name')->unique()->filter() as $company)
                            <option value="{{ $company }}" {{ request('company') == $company ? 'selected' : '' }}>
                                {{ $company }}
                            </option>
                        @endforeach
                    </select>
            </div>
                <div class="col-md-3">
                    <select class="form-control" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="featured_pending" {{ request('status') == 'featured_pending' ? 'selected' : '' }}>Featured Ad Pending</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
        </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                                </div>
                            </form>
                                </div>
                            </div>
                            
                    <!-- Jobs Grid -->
                    <div class="row g-3">
                        @forelse($jobs as $job)
                            <div class="col-md-6 col-lg-4">
                                <div class="card job-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-2">
                                                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-decoration-none">
                                                        {{ $job->title }}
                                                    </a>
                                                </h5>
                                                @if($job->employer && $job->employer->employerProfile)
                                                    <p class="text-muted mb-2 small">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $job->employer->employerProfile->company_name }}
                                                    </p>
                                @endif
                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="jobActions{{ $job->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="jobActions{{ $job->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.jobs.show', $job) }}">
                                                            <i class="fas fa-eye text-primary"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.jobs.edit', $job) }}">
                                                            <i class="fas fa-edit text-info"></i> Edit Job
                                                        </a>
                                                    </li>
                            @if($job->status === 'pending' || $job->status === 'featured_pending')
                                                        <li>
                                <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this job?');">
                                    @csrf
                                                                <button type="submit" class="dropdown-item text-success">
                                                                    <i class="fas fa-check"></i> Approve Job
                                    </button>
                                </form>
                                                        </li>
                                                        <li>
                                <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this job?');">
                                    @csrf
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fas fa-times"></i> Reject Job
                                    </button>
                                </form>
                                                        </li>
                            @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                @csrf
                                @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete Job
                                </button>
                            </form>
                                                    </li>
                                                </ul>
                        </div>
                    </div>
                    
                                        <div class="mb-3">
                                            @if($job->status == 'published')
                                                <span class="badge bg-success">Published</span>
                                            @elseif($job->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending Approval</span>
                                            @elseif($job->status == 'featured_pending')
                                                <span class="badge bg-info">Featured Ad Pending</span>
                                            @elseif($job->status == 'draft')
                                                <span class="badge bg-secondary">Draft</span>
                                            @else
                                                <span class="badge bg-danger">Closed</span>
                                            @endif
                                            @if($job->category)
                                                <span class="badge bg-primary">{{ $job->category->name }}</span>
                                            @endif
                        </div>
                        
                                        <div class="job-details mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                <small class="text-muted">
                                                    {{ $job->location_city }}, {{ $job->location_country }}
                                                </small>
                                            </div>
                        @if($job->salary_min || $job->salary_max)
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-dollar-sign text-muted me-2"></i>
                                                    <small class="text-muted">
                                @if($job->salary_min && $job->salary_max)
                                    ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                @elseif($job->salary_min)
                                    From ${{ number_format($job->salary_min) }}
                                @else
                                    Up to ${{ number_format($job->salary_max) }}
                                @endif
                                                    </small>
                        </div>
                        @endif
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-users text-muted me-2"></i>
                                                <small class="text-muted">
                                                    {{ $job->applications()->count() }} Application(s)
                                                </small>
                                            </div>
                                        </div>

                                        <div class="border-top pt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                Posted: {{ $job->created_at->format('M j, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                    </div>
                </div>
            </div>
            @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">No jobs found</h5>
                                    <p class="text-muted">No jobs match your current filters.</p>
                                    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Create First Job
                </a>
                                </div>
            </div>
            @endforelse
        </div>
        
                    <!-- Pagination -->
        <div class="row mt-4">
                        <div class="col-md-6">
                <div class="pagination-info">
                    <p class="text-muted mb-0">
                        Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} jobs
                    </p>
                </div>
            </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <form method="GET" action="{{ route('admin.jobs.index') }}" class="me-3">
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
                                <div class="pagination-controls">
                                    {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e0e0e0;
}

.job-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.job-card .card-title a {
    color: #333;
    transition: color 0.2s;
}

.job-card .card-title a:hover {
    color: #007bff;
}

.job-details {
    font-size: 0.875rem;
}

.avatar-sm {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-sm i {
    font-size: 1.25rem;
}

.font-20 {
    font-size: 1.25rem;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

@media (max-width: 768px) {
    .job-card {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection
