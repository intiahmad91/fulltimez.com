@extends('layouts.app')

@section('title', 'My Jobs')

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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">My Job Postings</h3>
                        <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Post New Job
                        </a>
                    </div>
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="jobs-grid">
                            @forelse($jobs as $job)
                            <div class="job-card-item card mb-3">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-7">
                                            <div class="d-flex align-items-start">
                                                <div class="job-icon me-2">
                                                    <i class="fas fa-briefcase text-primary" style="font-size: 1.2rem;"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="text-dark text-decoration-none">
                                                            {{ $job->title }}
                                                        </a>
                                                    </h6>
                                                    <div class="job-meta small text-muted">
                                                        <span class="me-2">
                                                            <i class="fas fa-folder"></i> {{ $job->category->name ?? 'N/A' }}
                                                        </span>
                                                        <span class="me-2">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $job->location_city }}
                                                        </span>
                                                        @if($job->salary_min && $job->salary_max)
                                                        <span class="text-success fw-bold">
                                                            <i class="fas fa-dollar-sign"></i> 
                                                            {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }}-{{ number_format((float)$job->salary_max) }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="d-flex align-items-center justify-content-md-end flex-wrap gap-2 mt-2 mt-md-0">
                                                <span class="badge bg-{{ $job->status == 'published' ? 'success' : 'warning' }} text-white small">
                                                    <i class="fas fa-{{ $job->status == 'published' ? 'check-circle' : 'clock' }}"></i>
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                                <span class="badge bg-info text-white small">
                                                    {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                </span>
                                                <span class="badge bg-primary text-white small">
                                                    <i class="fas fa-users"></i> {{ $job->applications_count }}
                                                </span>
                                                <span class="badge bg-light text-dark border small">
                                                    <i class="fas fa-clock"></i> {{ $job->created_at->diffForHumans() }}
                                                </span>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('employer.jobs.edit', $job) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="d-inline-block confirm-delete">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">No jobs posted yet</h5>
                                    <p class="text-muted mb-3">Start attracting top talent by posting your first job.</p>
                                    <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>Post Your First Job
                                    </a>
                                </div>
                            </div>
                            @endforelse
                        </div>

                        <style>
                            .job-card-item {
                                border: 1px solid #e3e6f0;
                                transition: all 0.3s ease;
                            }
                            .job-card-item:hover {
                                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                                border-color: #007bff;
                            }
                            .job-card-item h6 {
                                font-size: 0.95rem;
                                font-weight: 600;
                            }
                            .job-card-item h6 a:hover {
                                color: #007bff !important;
                            }
                            .job-meta {
                                font-size: 0.8rem;
                                line-height: 1.4;
                            }
                            .job-meta i {
                                font-size: 0.75rem;
                            }
                            .badge.small {
                                font-size: 0.7rem;
                                padding: 0.25rem 0.5rem;
                            }
                            .btn-group-sm .btn {
                                padding: 0.25rem 0.5rem;
                                font-size: 0.75rem;
                            }
                        </style>

                        @if($jobs->hasPages())
                            <div class="mt-4">
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

