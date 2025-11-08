@extends('layouts.app')

@section('title', 'Job Applications')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Job Applications</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Applications</li>
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
                        <h3 class="card-title mb-0">Applications Received</h3>
                        <span class="badge bg-primary">{{ $applications->total() }} Total</span>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Title</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>CV</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $application->seeker->seekerProfile && $application->seeker->seekerProfile->profile_picture ? asset($application->seeker->seekerProfile->profile_picture) : asset('images/avatar2.jpg') }}" 
                                                     alt="avatar" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                <div>
                                                    <strong>{{ $application->seeker->seekerProfile->full_name ?? $application->seeker->name }}</strong><br>
                                                    <small class="text-muted">{{ $application->seeker->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $application->job->title }}</strong><br>
                                            <small class="text-muted">{{ $application->job->location_city }}</small>
                                        </td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($application->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($application->status == 'reviewed')
                                                <span class="badge bg-info">Reviewed</span>
                                            @elseif($application->status == 'shortlisted')
                                                <span class="badge bg-primary">Shortlisted</span>
                                            @elseif($application->status == 'accepted')
                                                <span class="badge bg-success">Accepted</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($application->seeker->seekerProfile && $application->seeker->seekerProfile->cv_file)
                                                <a href="{{ asset($application->seeker->seekerProfile->cv_file) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   target="_blank">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            @else
                                                <span class="text-muted">No CV</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $application->id }}">
                                                <i class="fas fa-edit"></i> Update Status
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h4>No Applications Yet</h4>
                            <p class="text-muted">When candidates apply to your jobs, they will appear here.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Status Update Modals -->
@foreach($applications as $application)
<div class="modal fade" id="statusModal{{ $application->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $application->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel{{ $application->id }}">
                    Update Application Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employer.applications.update-status', $application) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Applicant: <strong>{{ $application->seeker->name }}</strong></label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job: <strong>{{ $application->job->title }}</strong></label>
                    </div>
                    <div class="mb-3">
                        <label for="status{{ $application->id }}" class="form-label">New Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status{{ $application->id }}" name="status" required>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interviewed" {{ $application->status == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                            <option value="offered" {{ $application->status == 'offered' ? 'selected' : '' }}>Job Offered</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="withdrawn" {{ $application->status == 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="employer_notes{{ $application->id }}" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="employer_notes{{ $application->id }}" name="employer_notes" rows="3" placeholder="Add any notes for the candidate...">{{ $application->employer_notes }}</textarea>
                        <small class="form-text text-muted">These notes will be included in the email notification to the candidate.</small>
                    </div>

                    <!-- Job Offer Details (shown when offering job) -->
                    <div id="jobOfferDetails{{ $application->id }}" style="display: none;">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-gift"></i> Job Offer Details</h6>
                            <p class="mb-0">Fill in the details below to send a professional job offer proposal to the candidate.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employer_position{{ $application->id }}" class="form-label">Your Position/Title</label>
                                    <input type="text" class="form-control" id="employer_position{{ $application->id }}" name="employer_position" placeholder="e.g., HR Manager, CEO">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department{{ $application->id }}" class="form-label">Department</label>
                                    <input type="text" class="form-control" id="department{{ $application->id }}" name="department" placeholder="e.g., Engineering, Marketing">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date{{ $application->id }}" class="form-label">Proposed Start Date</label>
                                    <input type="date" class="form-control" id="start_date{{ $application->id }}" name="start_date" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salary{{ $application->id }}" class="form-label">Salary</label>
                                    <input type="text" class="form-control" id="salary{{ $application->id }}" name="salary" placeholder="e.g., $5,000/month, $60,000/year">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="work_type{{ $application->id }}" class="form-label">Work Type</label>
                                    <select class="form-control" id="work_type{{ $application->id }}" name="work_type">
                                        <option value="">Select Work Type</option>
                                        <option value="Full-time">Full-time</option>
                                        <option value="Part-time">Part-time</option>
                                        <option value="Remote">Remote</option>
                                        <option value="On-site">On-site</option>
                                        <option value="Hybrid">Hybrid</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmation_deadline{{ $application->id }}" class="form-label">Confirmation Deadline</label>
                                    <input type="date" class="form-control" id="confirmation_deadline{{ $application->id }}" name="confirmation_deadline" min="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="benefits{{ $application->id }}" class="form-label">Benefits & Perks</label>
                            <textarea class="form-control" id="benefits{{ $application->id }}" name="benefits" rows="3" placeholder="e.g., Health insurance, 401k, flexible hours, annual bonus..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Update Status & Notify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle job offer details visibility
    @foreach($applications as $application)
    const statusSelect{{ $application->id }} = document.getElementById('status{{ $application->id }}');
    const jobOfferDetails{{ $application->id }} = document.getElementById('jobOfferDetails{{ $application->id }}');
    
    if (statusSelect{{ $application->id }} && jobOfferDetails{{ $application->id }}) {
        // Show/hide job offer details based on status selection
        function toggleJobOfferDetails{{ $application->id }}() {
            if (statusSelect{{ $application->id }}.value === 'offered') {
                jobOfferDetails{{ $application->id }}.style.display = 'block';
            } else {
                jobOfferDetails{{ $application->id }}.style.display = 'none';
            }
        }
        
        // Initial check
        toggleJobOfferDetails{{ $application->id }}();
        
        // Listen for changes
        statusSelect{{ $application->id }}.addEventListener('change', toggleJobOfferDetails{{ $application->id }});
    }
    @endforeach
});
</script>

@endsection


