@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>My Applications</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Applications</li>
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
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center border-primary">
                            <div class="card-body">
                                <i class="fas fa-paper-plane fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $applications->total() }}</h4>
                                <small class="text-muted">Total Applied</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h4 class="mb-0">{{ $applications->where('status', 'pending')->count() }}</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-info">
                            <div class="card-body">
                                <i class="fas fa-star fa-2x text-info mb-2"></i>
                                <h4 class="mb-0">{{ $applications->whereIn('status', ['reviewed', 'shortlisted'])->count() }}</h4>
                                <small class="text-muted">Under Review</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center border-success">
                            <div class="card-body">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $applications->where('status', 'accepted')->count() }}</h4>
                                <small class="text-muted">Accepted</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-list"></i> Application History
                        </h3>
                        <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Browse More Jobs
                        </a>
                    </div>
                    <div class="card-body">
                        @if($applications->count() > 0)
                            @foreach($applications as $application)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            @if($application->job->employer && $application->job->employer->employerProfile && $application->job->employer->employerProfile->company_logo)
                                                <img src="{{ asset($application->job->employer->employerProfile->company_logo) }}" 
                                                     alt="company" 
                                                     class="img-fluid rounded"
                                                     style="max-width: 80px; max-height: 80px; object-fit: contain;">
                                            @else
                                                <div class="bg-light rounded p-3">
                                                    <i class="fas fa-building fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-2">
                                                <a href="{{ route('jobs.show', $application->job->slug) }}" class="text-decoration-none">
                                                    {{ $application->job->title }}
                                                </a>
                                            </h5>
                                            <p class="mb-1">
                                                <i class="fas fa-building text-muted"></i> 
                                                <strong>{{ optional($application->job->employer->employerProfile)->company_name ?? 'N/A' }}</strong>
                                            </p>
                                            <p class="mb-1">
                                                <i class="fas fa-map-marker-alt text-muted"></i> 
                                                {{ $application->job->location_city }}, {{ $application->job->location_country }}
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                <i class="fas fa-calendar"></i> 
                                                Applied {{ $application->created_at->diffForHumans() }}
                                                ({{ $application->created_at->format('M d, Y') }})
                                            </p>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            @if($application->status == 'pending')
                                                <span class="badge bg-warning text-dark p-2" style="font-size: 13px;">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @elseif($application->status == 'reviewed')
                                                <span class="badge bg-info p-2" style="font-size: 13px;">
                                                    <i class="fas fa-eye"></i> Reviewed
                                                </span>
                                            @elseif($application->status == 'shortlisted')
                                                <span class="badge bg-primary p-2" style="font-size: 13px;">
                                                    <i class="fas fa-star"></i> Shortlisted
                                                </span>
                                            @elseif($application->status == 'offered')
                                                <span class="badge bg-success p-2" style="font-size: 13px;">
                                                    <i class="fas fa-handshake"></i> Job Offered
                                                </span>
                                            @elseif($application->status == 'interviewed')
                                                <span class="badge bg-info p-2" style="font-size: 13px;">
                                                    <i class="fas fa-user-tie"></i> Interviewed
                                                </span>
                                            @elseif($application->status == 'withdrawn')
                                                <span class="badge bg-secondary p-2" style="font-size: 13px;">
                                                    <i class="fas fa-times"></i> Withdrawn
                                                </span>
                                            @elseif($application->status == 'rejected')
                                                <span class="badge bg-danger p-2" style="font-size: 13px;">
                                                    <i class="fas fa-times-circle"></i> Rejected
                                                </span>
                                            @endif
                                            
                                            @if($application->job->salary_min && $application->job->salary_max)
                                            <div class="mt-2 small">
                                                <strong class="text-success">
                                                    {{ $application->job->salary_currency ?? 'AED' }} {{ number_format((float)$application->job->salary_min) }}-{{ number_format((float)$application->job->salary_max) }}
                                                </strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <a href="{{ route('jobs.show', $application->job->slug) }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                <i class="fas fa-eye"></i> View Job
                                            </a>
                                            @if($application->cover_letter)
                                            <button type="button" class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#coverLetterModal{{ $application->id }}">
                                                <i class="fas fa-file-alt"></i> View Cover Letter
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Timeline -->
                                    <div class="mt-3 pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 11px;">
                                            <span class="{{ $application->status == 'pending' || $application->status == 'reviewed' || $application->status == 'shortlisted' || $application->status == 'accepted' ? 'text-success' : 'text-muted' }}">
                                                <i class="fas fa-check-circle"></i> Applied
                                            </span>
                                            <span class="flex-grow-1 border-bottom mx-2"></span>
                                            <span class="{{ $application->status == 'reviewed' || $application->status == 'shortlisted' || $application->status == 'accepted' ? 'text-success' : 'text-muted' }}">
                                                <i class="fas fa-eye"></i> Reviewed
                                            </span>
                                            <span class="flex-grow-1 border-bottom mx-2"></span>
                                            <span class="{{ $application->status == 'shortlisted' || $application->status == 'accepted' ? 'text-success' : 'text-muted' }}">
                                                <i class="fas fa-star"></i> Shortlisted
                                            </span>
                                            <span class="flex-grow-1 border-bottom mx-2"></span>
                                            <span class="{{ $application->status == 'accepted' ? 'text-success' : ($application->status == 'rejected' ? 'text-danger' : 'text-muted') }}">
                                                <i class="fas {{ $application->status == 'accepted' ? 'fa-check-circle' : ($application->status == 'rejected' ? 'fa-times-circle' : 'fa-question-circle') }}"></i> 
                                                {{ $application->status == 'accepted' ? 'Accepted' : ($application->status == 'rejected' ? 'Rejected' : 'Final') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cover Letter Modal -->
                            @if($application->cover_letter)
                            <div class="modal fade" id="coverLetterModal{{ $application->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-file-alt"></i> Cover Letter - {{ $application->job->title }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <strong>Applied to:</strong> {{ $application->job->title }} at {{ optional($application->job->employer->employerProfile)->company_name ?? 'Company' }}
                                            </div>
                                            <div class="p-3 bg-light rounded">
                                                <p style="white-space: pre-wrap;">{{ $application->cover_letter }}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach

                            <!-- Pagination -->
                            @if($applications->hasPages())
                            <div class="mt-4">
                                {{ $applications->links() }}
                            </div>
                            @endif
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-5x text-muted mb-4"></i>
                            <h4>No Applications Yet</h4>
                            <p class="text-muted mb-4">You haven't applied to any jobs yet. Start applying to find your dream job!</p>
                            <a href="{{ route('jobs.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-search"></i> Browse Jobs
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
