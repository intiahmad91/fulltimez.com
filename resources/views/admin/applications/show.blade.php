@extends('layouts.admin')

@section('title', 'Application Details')
@section('page-title', 'Application Details')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5><i class="fas fa-file-alt"></i> Application Details</h5>
            <div class="ms-auto">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Applications
                </a>
            </div>
        </div>
    </div>
    <div class="admin-card-body">
        <div class="row">
            <div class="col-lg-8">
                <!-- Application Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-user"></i> Candidate Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Name</label>
                                    <p class="mb-0">{{ $application->seeker->seekerProfile->full_name ?? $application->seeker->name }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="mb-0">{{ $application->seeker->email }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <p class="mb-0">{{ $application->seeker->seekerProfile->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Current Position</label>
                                    <p class="mb-0">{{ $application->seeker->seekerProfile->current_position ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Experience</label>
                                    <p class="mb-0">{{ $application->seeker->seekerProfile->experience ?? 'Not provided' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Location</label>
                                    <p class="mb-0">{{ $application->seeker->seekerProfile->location ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-briefcase"></i> Job Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Job Title</label>
                                    <p class="mb-0">{{ $application->job->title }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Company</label>
                                    <p class="mb-0">{{ optional($application->job->employer->employerProfile)->company_name ?? 'No Company' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Application Date</label>
                                    <p class="mb-0">{{ $application->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <span class="badge 
                                        @if($application->status == 'pending') bg-warning
                                        @elseif($application->status == 'reviewed') bg-info
                                        @elseif($application->status == 'shortlisted') bg-primary
                                        @elseif($application->status == 'accepted') bg-success
                                        @elseif($application->status == 'rejected') bg-danger
                                        @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cover Letter -->
                @if($application->cover_letter)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-file-text"></i> Cover Letter</h6>
                    </div>
                    <div class="card-body">
                        <div class="cover-letter-content">
                            {!! nl2br(e($application->cover_letter)) !!}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Resume -->
                @if($application->resume_file)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-file-pdf"></i> Resume</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <span class="me-3">Resume attached</span>
                            <a href="{{ asset($application->resume_file) }}" 
                               class="btn btn-outline-primary btn-sm" 
                               target="_blank">
                                <i class="fas fa-download"></i> Download Resume
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-cogs"></i> Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('jobs.show', $application->job->slug) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> View Job Posting
                            </a>
                            
                            @if($application->seeker->seekerProfile && $application->seeker->seekerProfile->cv_file)
                            <a href="{{ asset($application->seeker->seekerProfile->cv_file) }}" 
                               class="btn btn-outline-success" 
                               target="_blank">
                                <i class="fas fa-download"></i> Download CV
                            </a>
                            @endif
                            
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#statusModal">
                                <i class="fas fa-edit"></i> Update Status
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Application Stats -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Application Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h5 class="mb-1">{{ $application->created_at->format('d') }}</h5>
                                    <small class="text-muted">Day Applied</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h5 class="mb-1">{{ $application->created_at->format('M') }}</h5>
                                <small class="text-muted">Month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Application Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.applications.update-status', $application) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add any notes about this application..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
