@extends('layouts.admin')

@section('title', 'Job Details')
@section('page-title', 'Job Details')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-briefcase"></i> {{ $job->title }}</h5>
        <div class="d-flex align-items-center gap-2">
            @if($job->status === 'pending')
                <form action="{{ route('admin.jobs.approve', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this job?');">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-success btn-sm">
                        <i class="fas fa-check"></i> Approve Job
                    </button>
                </form>
                <form action="{{ route('admin.jobs.reject', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this job?');">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-danger btn-sm">
                        <i class="fas fa-times"></i> Reject Job
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.jobs.edit', $job) }}" class="admin-btn admin-btn-secondary btn-sm">
                <i class="fas fa-edit"></i> Edit Job
            </a>
            <a href="{{ route('admin.jobs.index') }}" class="admin-btn admin-btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Jobs
            </a>
        </div>
    </div>
    <div class="admin-card-body">
        <div class="row">
            <div class="col-md-8">
                <!-- Job Details -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h6><i class="fas fa-info-circle"></i> Job Information</h6>
                    </div>
                    <div class="admin-card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Job Title:</strong> {{ $job->title }}</p>
                                <p><strong>Company:</strong> 
                                    @if($job->employer && $job->employer->employerProfile)
                                        {{ $job->employer->employerProfile->company_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                                <p><strong>Category:</strong> 
                                    @if($job->category)
                                        <span class="admin-badge badge-info">{{ $job->category->name }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                                <p><strong>Location:</strong> {{ $job->location }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Employment Type:</strong> 
                                    <span class="admin-badge badge-primary">{{ ucfirst($job->employment_type) }}</span>
                                </p>
                                <p><strong>Experience Level:</strong> 
                                    <span class="admin-badge badge-warning">{{ ucfirst($job->experience_level) }}</span>
                                </p>
                                <p><strong>Status:</strong> 
                                    @if($job->status == 'published')
                                        <span class="admin-badge badge-success">Published</span>
                                    @elseif($job->status == 'pending')
                                        <span class="admin-badge badge-warning">Pending</span>
                                    @elseif($job->status == 'rejected')
                                        <span class="admin-badge badge-danger">Rejected</span>
                                    @elseif($job->status == 'draft')
                                        <span class="admin-badge badge-secondary">Draft</span>
                                    @else
                                        <span class="admin-badge badge-secondary">Closed</span>
                                    @endif
                                </p>
                                <p><strong>Created:</strong> {{ $job->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($job->salary_min || $job->salary_max)
                        <div class="row mt-3">
                            <div class="col-12">
                                <p><strong>Salary Range:</strong>
                                    @if($job->salary_min && $job->salary_max)
                                        ${{ number_format($job->salary_min) }} - ${{ number_format($job->salary_max) }}
                                    @elseif($job->salary_min)
                                        From ${{ number_format($job->salary_min) }}
                                    @else
                                        Up to ${{ number_format($job->salary_max) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Job Description -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h6><i class="fas fa-file-alt"></i> Job Description</h6>
                    </div>
                    <div class="admin-card-body">
                        <div class="job-description">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Skills Required -->
                @if($job->skills_required)
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h6><i class="fas fa-tools"></i> Skills Required</h6>
                    </div>
                    <div class="admin-card-body">
                        @php
                            $skills = is_string($job->skills_required) ? json_decode($job->skills_required, true) : $job->skills_required;
                        @endphp
                        @if($skills && is_array($skills))
                            @foreach($skills as $skill)
                                <span class="admin-badge badge-secondary me-2 mb-2">{{ $skill }}</span>
                            @endforeach
                        @else
                            <p class="text-muted">{{ $job->skills_required }}</p>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <!-- Applications -->
                <div class="admin-card mb-4">
                    <div class="admin-card-header">
                        <h6><i class="fas fa-users"></i> Applications ({{ $job->applications->count() }})</h6>
                    </div>
                    <div class="admin-card-body">
                        @if($job->applications->count() > 0)
                            <div class="applications-list">
                                @foreach($job->applications->take(5) as $application)
                                <div class="application-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">
                                                @if($application->user && $application->user->seekerProfile)
                                                    {{ $application->user->seekerProfile->full_name }}
                                                @else
                                                    {{ $application->user->name ?? 'N/A' }}
                                                @endif
                                            </h6>
                                            <p class="text-muted mb-1">{{ $application->created_at->format('M d, Y') }}</p>
                                            @if($application->cover_letter)
                                                <p class="small">{{ Str::limit($application->cover_letter, 100) }}</p>
                                            @endif
                                        </div>
                                        <span class="admin-badge badge-info">{{ $application->status }}</span>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if($job->applications->count() > 5)
                                    <div class="text-center">
                                        <a href="{{ route('admin.applications.index', ['job_id' => $job->id]) }}" class="admin-btn admin-btn-primary btn-sm">
                                            View All Applications
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-muted text-center">No applications yet</p>
                        @endif
                    </div>
                </div>

                <!-- Job Statistics -->
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h6><i class="fas fa-chart-bar"></i> Statistics</h6>
                    </div>
                    <div class="admin-card-body">
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Applications:</span>
                                <strong>{{ $job->applications->count() }}</strong>
                            </div>
                        </div>
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Days Active:</span>
                                <strong>{{ $job->created_at->diffInDays(now()) }}</strong>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="d-flex justify-content-between">
                                <span>Last Updated:</span>
                                <strong>{{ $job->updated_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
