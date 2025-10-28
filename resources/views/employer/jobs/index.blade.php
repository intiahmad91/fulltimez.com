@extends('layouts.app')

@section('title', 'My Jobs')

@push('styles')
<style>
/* Hide search section on employer jobs page */
.search-wrap {
    display: none !important;
}

/* Professional Jobs Listing Styles */
.jobs-listing-container {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid #e8e8e8;
    overflow: hidden;
    position: relative;
}

.jobs-listing-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107, #dc3545);
}

.jobs-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e8e8e8;
    padding: 2.5rem;
    position: relative;
}

.jobs-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23e9ecef" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.jobs-header-content {
    position: relative;
    z-index: 1;
}

.jobs-header h2 {
    color: #2c3e50;
    font-weight: 700;
    margin: 0;
    font-size: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.jobs-header h2::before {
    content: '💼';
    font-size: 1.5rem;
}

.jobs-header p {
    color: #6c757d;
    margin: 0.75rem 0 0 0;
    font-size: 1rem;
    font-weight: 500;
}

/* Statistics Cards */
.jobs-stats {
    background: #f8f9fa;
    padding: 1.5rem 2.5rem;
    border-bottom: 1px solid #e8e8e8;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.total { background: linear-gradient(135deg, #007bff, #0056b3); }
.stat-icon.published { background: linear-gradient(135deg, #28a745, #1e7e34); }
.stat-icon.pending { background: linear-gradient(135deg, #ffc107, #e0a800); }
.stat-icon.applications { background: linear-gradient(135deg, #17a2b8, #138496); }

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Job Cards */
.jobs-content {
    padding: 2.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.job-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.job-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #007bff, #28a745);
    border-radius: 0 2px 2px 0;
}

.job-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
}

.job-header {
    display: flex;
    justify-content: between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.job-title-section {
    flex: 1;
}

.job-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    text-decoration: none;
    transition: color 0.3s ease;
}

.job-title:hover {
    color: #007bff;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.job-meta-item i {
    color: #007bff;
    font-size: 0.8rem;
}

.job-status-section {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.75rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge.published {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-badge.pending {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge.draft {
    background: linear-gradient(135deg, #e2e3e5, #d6d8db);
    color: #495057;
    border: 1px solid #d6d8db;
}

.job-stats {
    display: flex;
    gap: 0.75rem;
}

.job-stat {
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.job-stat.applications {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    color: #1976d2;
}

.job-stat.views {
    background: linear-gradient(135deg, #f3e5f5, #e1bee7);
    color: #7b1fa2;
}

.job-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
}

.action-btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.action-btn.edit {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
}

.action-btn.edit:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.action-btn.delete {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.action-btn.delete:hover {
    background: linear-gradient(135deg, #c82333, #bd2130);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.action-btn.view {
    background: linear-gradient(135deg, #28a745, #1e7e34);
    color: white;
}

.action-btn.view:hover {
    background: linear-gradient(135deg, #1e7e34, #155724);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.empty-description {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.empty-action {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.empty-action:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .jobs-header {
        padding: 2rem;
    }
    
    .jobs-content {
        padding: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .job-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .job-status-section {
        align-items: flex-start;
        width: 100%;
    }
    
    .job-actions {
        flex-direction: column;
    }
    
    .action-btn {
        justify-content: center;
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
                <div class="jobs-listing-container">
                    <!-- Header Section -->
                    <div class="jobs-header">
                        <div class="jobs-header-content">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2>My Job Postings</h2>
                                    <p>Manage and track all your job postings in one place</p>
                                </div>
                                <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle"></i> Post New Job
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Section -->
                    <div class="jobs-stats">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon total">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="stat-number">{{ $jobs->total() }}</div>
                                <div class="stat-label">Total Jobs</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon published">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-number">{{ $jobs->where('status', 'published')->count() }}</div>
                                <div class="stat-label">Published</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon pending">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number">{{ $jobs->where('status', 'pending')->count() }}</div>
                                <div class="stat-label">Pending</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon applications">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-number">{{ $jobs->sum('applications_count') }}</div>
                                <div class="stat-label">Total Applications</div>
                            </div>
                        </div>
                    </div>

                    <!-- Jobs Content -->
                    <div class="jobs-content">
                        @forelse($jobs as $job)
                        <div class="job-card">
                            <div class="job-header">
                                <div class="job-title-section">
                                    <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="job-title">
                                        {{ $job->title }}
                                    </a>
                                    <div class="job-meta">
                                        <div class="job-meta-item">
                                            <i class="fas fa-folder"></i>
                                            <span>{{ $job->category->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="job-meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $job->location_city }}, {{ $job->location_country }}</span>
                                        </div>
                                        <div class="job-meta-item">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                        </div>
                                        @if($job->salary_min && $job->salary_max)
                                        <div class="job-meta-item">
                                            <i class="fas fa-dollar-sign"></i>
                                            <span class="text-success fw-bold">
                                                {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }}-{{ number_format((float)$job->salary_max) }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="job-status-section">
                                    <div class="status-badge {{ $job->status }}">
                                        <i class="fas fa-{{ $job->status == 'published' ? 'check-circle' : ($job->status == 'pending' ? 'clock' : 'edit') }}"></i>
                                        {{ ucfirst($job->status) }}
                                    </div>
                                    <div class="job-stats">
                                        <div class="job-stat applications">
                                            <i class="fas fa-users"></i>
                                            <span>{{ $job->applications_count }} Applications</span>
                                        </div>
                                        <div class="job-stat views">
                                            <i class="fas fa-eye"></i>
                                            <span>{{ $job->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="job-actions">
                                <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="action-btn view">
                                    <i class="fas fa-external-link-alt"></i> View Job
                                </a>
                                <a href="{{ route('employer.jobs.edit', $job) }}" class="action-btn edit">
                                    <i class="fas fa-edit"></i> Edit Job
                                </a>
                                <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="d-inline-block confirm-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete">
                                        <i class="fas fa-trash-alt"></i> Delete Job
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="empty-state">
                            <div class="empty-icon">💼</div>
                            <h3 class="empty-title">No Jobs Posted Yet</h3>
                            <p class="empty-description">
                                Start attracting top talent by posting your first job. Create compelling job descriptions to reach qualified candidates.
                            </p>
                            <a href="{{ route('employer.jobs.create') }}" class="empty-action">
                                <i class="fas fa-plus-circle"></i> Post Your First Job
                            </a>
                        </div>
                        @endforelse

                        @if($jobs->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $jobs->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Delete confirmation
    document.querySelectorAll('.confirm-delete').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this job? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection

