@extends('layouts.admin')

@section('title', 'Jobs Management')
@section('page-title', 'Jobs Management')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-briefcase"></i> Jobs Management</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.jobs.create') }}" class="admin-btn admin-btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Job
            </a>
            <span class="admin-badge badge-primary">{{ $jobs->total() }} Total</span>
        </div>
    </div>
    <div class="admin-card-body">
        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.jobs.index') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           placeholder="Search jobs..." value="{{ request('search') }}">
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
                <div class="col-md-1">
                    <button type="submit" class="admin-btn admin-btn-secondary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('admin.jobs.index') }}" class="admin-btn admin-btn-outline w-100">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </div>
        </form>

        <!-- Jobs List -->
        <div class="jobs-list" id="jobsList">
            @forelse($jobs as $job)
            <div class="job-card" data-job-id="{{ $job->id }}">
                <div class="job-card-content">
                    <div class="job-header">
                        <div class="job-main-info">
                            <div class="job-title-section">
                                <div class="job-title-row">
                                    <h4 class="job-title">{{ $job->title }}</h4>
                                    <div class="job-meta-badges">
                                        @if($job->category)
                                            <span class="meta-badge category-badge">
                                                <i class="fas fa-tag"></i>
                                                {{ $job->category->name }}
                                            </span>
                                        @endif
                                        <span class="meta-badge applications-badge">
                                            <i class="fas fa-users"></i>
                                            {{ $job->applications()->count() }}
                                        </span>
                                    </div>
                                </div>
                                <div class="job-company">
                                    @if($job->employer && $job->employer->employerProfile)
                                        <i class="fas fa-building"></i>
                                        {{ $job->employer->employerProfile->company_name }}
                                    @else
                                        <i class="fas fa-building"></i>
                                        <span class="text-muted">No Company</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="job-status-section">
                                @if($job->status == 'published')
                                    <span class="status-badge published">Published</span>
                                @elseif($job->status == 'pending')
                                    <span class="status-badge pending">Pending Approval</span>
                                @elseif($job->status == 'featured_pending')
                                    <span class="status-badge featured-pending">Featured Ad Pending</span>
                                @elseif($job->status == 'draft')
                                    <span class="status-badge draft">Draft</span>
                                @else
                                    <span class="status-badge closed">Closed</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="job-actions">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="action-btn view-btn" title="View Job">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($job->status === 'pending' || $job->status === 'featured_pending')
                                <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this job?');">
                                    @csrf
                                    <button type="submit" class="action-btn approve-btn" title="Approve Job" style="background: #28a745; color: white;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this job?');">
                                    @csrf
                                    <button type="submit" class="action-btn reject-btn" title="Reject Job" style="background: #dc3545; color: white;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="action-btn edit-btn" title="Edit Job">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn" title="Delete Job">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="job-details">
                        <div class="job-detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $job->location_city }}, {{ $job->location_country }}</span>
                        </div>
                        
                        @if($job->salary_min || $job->salary_max)
                        <div class="job-detail-item">
                            <i class="fas fa-dollar-sign"></i>
                            <span>
                                @if($job->salary_min && $job->salary_max)
                                    ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                @elseif($job->salary_min)
                                    From ${{ number_format($job->salary_min) }}
                                @else
                                    Up to ${{ number_format($job->salary_max) }}
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="no-jobs">
                <div class="no-jobs-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h4>No jobs found</h4>
                <p>There are no jobs matching your criteria.</p>
                <a href="{{ route('admin.jobs.create') }}" class="create-btn">
                    <i class="fas fa-plus"></i> Create First Job
                </a>
            </div>
            @endforelse
        </div>
        

        <!-- Pagination Info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="pagination-info">
                    <p class="text-muted mb-0">
                        Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} jobs
                    </p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="per-page-selector">
                    <form method="GET" action="{{ route('admin.jobs.index') }}" class="d-inline">
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
                    {{ $jobs->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Add loading animation to job cards
    const jobCards = document.querySelectorAll('.job-card');
    jobCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush