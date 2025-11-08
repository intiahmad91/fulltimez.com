@extends('layouts.app')

@section('title', 'My Jobs')

@push('styles')
<style>
/* Hide search section on employer jobs page */
.search-wrap {
    display: none !important;
}

.jobs-container {
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
}

.jobs-header {
    background: #ffffff;
    border-bottom: 1px solid #e0e0e0;
    padding: 2rem;
}

.jobs-header h2 {
    color: #333;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
}

.jobs-header p {
    color: #666;
    margin: 0;
    font-size: 0.95rem;
}

/* Statistics Cards */
.stats-section {
    background: #f8f9fa;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e0e0e0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
}

.stat-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.2s ease;
}

.stat-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #666;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Jobs Content */
.jobs-content {
    padding: 2rem;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.job-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1.5rem;
    transition: all 0.2s ease;
}

.job-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.job-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.75rem;
    text-decoration: none;
    display: block;
}

.job-title:hover {
    color: #007bff;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
    color: #666;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.job-meta-item i {
    color: #999;
    font-size: 0.75rem;
}

.job-status-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    font-size: 0.8125rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.job-status-badge.published {
    background: #d4edda;
    color: #155724;
}

.job-status-badge.pending {
    background: #fff3cd;
    color: #856404;
}

.job-status-badge.draft {
    background: #e2e3e5;
    color: #495057;
}

.job-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.job-stat {
    font-size: 0.8125rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.job-stat i {
    color: #999;
}

.job-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.action-btn {
    flex: 1;
    padding: 0.625rem 1rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    text-align: center;
    transition: all 0.2s ease;
    border: 1px solid #e0e0e0;
    background: #ffffff;
    color: #333;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn:hover {
    background: #f8f9fa;
    border-color: #007bff;
    color: #007bff;
}

.action-btn.primary {
    background: #007bff;
    border-color: #007bff;
    color: #ffffff;
}

.action-btn.primary:hover {
    background: #0056b3;
    border-color: #0056b3;
    color: #ffffff;
}

.action-btn.danger {
    background: #ffffff;
    border-color: #dc3545;
    color: #dc3545;
}

.action-btn.danger:hover {
    background: #dc3545;
    color: #ffffff;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 3rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.empty-description {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
}

.btn-create {
    background: #007bff;
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: 1px solid #007bff;
}

.btn-create:hover {
    background: #0056b3;
    border-color: #0056b3;
    color: #ffffff;
}

.btn-header {
    background: #007bff;
    color: #ffffff;
    padding: 0.625rem 1.25rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    border: 1px solid #007bff;
    font-size: 0.875rem;
}

.btn-header:hover {
    background: #0056b3;
    border-color: #0056b3;
    color: #ffffff;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e0e0e0;
}

/* Responsive */
@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .jobs-header {
        padding: 1.5rem;
    }
    
    .jobs-content {
        padding: 1.5rem;
    }
    
    .job-actions {
        flex-direction: column;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>My Jobs</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Jobs</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="pagecontent dashboard_wrap">
    <div class="container">
        <div class="row contactWrp">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <div class="jobs-container">
                    <!-- Header Section -->
                    <div class="jobs-header">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h2>My Job Postings</h2>
                                <p>Manage and track all your job postings</p>
                            </div>
                            <a href="{{ route('employer.jobs.create') }}" class="btn-header">
                                <i class="fas fa-plus"></i> Post New Job
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Section -->
                    <div class="stats-section">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-number">{{ $jobs->total() }}</div>
                                <div class="stat-label">Total Jobs</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $jobs->where('status', 'published')->count() }}</div>
                                <div class="stat-label">Published</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $jobs->where('status', 'pending')->count() }}</div>
                                <div class="stat-label">Pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $jobs->sum(function($job) { return $job->applications()->count(); }) }}</div>
                                <div class="stat-label">Total Applications</div>
                            </div>
                        </div>
                    </div>

                    <!-- Jobs Content -->
                    <div class="jobs-content">
                        @forelse($jobs as $job)
                            <div class="job-card">
                                <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="job-title">
                                    {{ $job->title }}
                                </a>
                                
                                <div class="job-meta">
                                    @if($job->category)
                                        <div class="job-meta-item">
                                            <i class="fas fa-folder"></i>
                                            <span>{{ $job->category->name }}</span>
                                        </div>
                                    @endif
                                    <div class="job-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $job->location_city }}, {{ $job->location_country }}</span>
                                    </div>
                                    <div class="job-meta-item">
                                        <i class="fas fa-briefcase"></i>
                                        <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                    </div>
                                    @if($job->salary_min || $job->salary_max)
                                        <div class="job-meta-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span>
                                                @if($job->salary_min && $job->salary_max)
                                                    {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }}
                                                @elseif($job->salary_min)
                                                    From {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }}
                                                @else
                                                    Up to {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_max) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="job-status-badge {{ $job->status }}">
                                    {{ ucfirst($job->status) }}
                                </div>

                                <div class="job-stats">
                                    <div class="job-stat">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $job->applications()->count() }} Applications</span>
                                    </div>
                                    <div class="job-stat">
                                        <i class="fas fa-clock"></i>
                                        <span>Posted {{ $job->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="job-actions">
                                    <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="action-btn">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('employer.jobs.edit', $job) }}" class="action-btn primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this job? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <h3 class="empty-title">No Jobs Posted Yet</h3>
                                <p class="empty-description">
                                    Start attracting top talent by posting your first job posting.
                                </p>
                                <a href="{{ route('employer.jobs.create') }}" class="btn-create">
                                    <i class="fas fa-plus"></i> Post Your First Job
                                </a>
                            </div>
                        @endforelse

                        @if($jobs->hasPages())
                            <div class="pagination-wrapper">
                                {{ $jobs->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
