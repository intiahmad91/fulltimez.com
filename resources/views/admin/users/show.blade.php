@extends('layouts.admin')

@section('title', 'Review User')
@section('page-title', 'Review User')

@section('content')
<div class="admin-card mt-0 mb-3">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="fas fa-key"></i> Reset User Password</h6>
        <a href="#" id="show_reset_form" class="admin-btn admin-btn-primary"><i class="fas fa-wrench"></i> Toggle Form</a>
    </div>
    <div class="admin-card-body">
        <form id="reset_password_form" action="{{ route('admin.users.reset-password', $user) }}" method="POST" style="display:none;">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="admin-form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" class="admin-form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="admin-form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="admin-form-control" required>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="admin-form-group">
                        <div class="admin-form-check">
                            <input type="checkbox" id="notify" name="notify" value="1">
                            <label for="notify" class="mb-0">Send email notification to user</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="admin-form-group mt-2">
                <button type="submit" class="admin-btn admin-btn-primary"><i class="fas fa-save"></i> Update Password</button>
            </div>
        </form>
    </div>
    </div>

<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-user"></i> {{ $user->name }}</h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="admin-card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><strong>Account</strong></div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> {{ ucfirst($user->role->slug) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                        <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            @if($user->isEmployer() && $user->employerProfile)
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><strong>Employer Profile</strong></div>
                    <div class="card-body">
                        <p><strong>Company:</strong> {{ $user->employerProfile->company_name }}</p>
                        <p><strong>Company Email:</strong> {{ $user->employerProfile->company_email }}</p>
                        <p><strong>Office Landline:</strong> {{ $user->employerProfile->office_landline }}</p>
                        <p><strong>Industry:</strong> {{ $user->employerProfile->industry ?? '-' }}</p>
                        <p><strong>Company Size:</strong> {{ $user->employerProfile->company_size ?? '-' }}</p>
                        <p><strong>City:</strong> {{ $user->employerProfile->city ?? '-' }}</p>
                        <p><strong>Contact Person:</strong> {{ $user->employerProfile->contact_person }} ({{ $user->employerProfile->contact_email }}, {{ $user->employerProfile->contact_phone }})</p>
                        <p><strong>Verification Status:</strong>
                            <span class="admin-badge {{ $user->employerProfile->verification_status === 'verified' ? 'badge-success' : ($user->employerProfile->verification_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($user->employerProfile->verification_status) }}
                            </span>
                        </p>
                        @if($user->employerProfile->trade_license)
                            <p><strong>Trade License:</strong></p>
                            <a href="{{ asset($user->employerProfile->trade_license) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file"></i> View Document
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        @if($user->isSeeker() && $user->seekerProfile)
        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><strong>Jobseeker Profile</strong></div>
                    <div class="card-body">
                        <p><strong>Full Name:</strong> {{ $user->seekerProfile->full_name }}</p>
                        <p><strong>City:</strong> {{ $user->seekerProfile->city ?? '-' }}</p>
                        <p><strong>Current Position:</strong> {{ $user->seekerProfile->current_position ?? '-' }}</p>
                        <p><strong>Verification Status:</strong>
                            <span class="admin-badge {{ $user->seekerProfile->verification_status === 'verified' ? 'badge-success' : ($user->seekerProfile->verification_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($user->seekerProfile->verification_status) }}
                            </span>
                        </p>
                        @php
                            $approvalStatus = $user->seekerProfile->approval_status ?? 'pending';
                            $isFeatured = method_exists($user->seekerProfile, 'isFeatured') ? $user->seekerProfile->isFeatured() : false;
                        @endphp
                        <p><strong>Resume Approval Status:</strong>
                            <span class="admin-badge {{ $approvalStatus === 'approved' ? 'badge-success' : ($approvalStatus === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                                {{ ucfirst($approvalStatus) }}
                            </span>
                        </p>
                        @if($isFeatured && $user->seekerProfile->featured_expires_at)
                        <p><strong>Featured:</strong>
                            <span class="admin-badge badge-warning">
                                <i class="fas fa-star"></i> Featured until {{ $user->seekerProfile->featured_expires_at->format('M d, Y') }}
                            </span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header"><strong>Resume / CV</strong></div>
                    <div class="card-body">
                        @if($user->seekerProfile->cv_file)
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>CV uploaded</span>
                                <a href="{{ route('admin.users.download-cv', $user) }}" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-download"></i> View / Download CV
                                </a>
                            </div>
                        @else
                            <span class="text-muted">No CV uploaded</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($user->isEmployer() && $user->employerProfile && $user->employerProfile->verification_status === 'pending')
        <div class="mt-4 d-flex gap-2">
            <form action="{{ route('admin.users.approve-employer', $user) }}" method="POST" onsubmit="return confirm('Approve this employer?');">
                @csrf
                <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Approve Employer</button>
            </form>
        </div>
        @endif

        @if($user->isSeeker() && $user->seekerProfile)
            @if($user->seekerProfile->verification_status === 'pending')
            <div class="mt-3 d-flex gap-2">
                <form action="{{ route('admin.users.approve-seeker', $user) }}" method="POST" onsubmit="return confirm('Approve this jobseeker account?');">
                    @csrf
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Approve Account</button>
                </form>
            </div>
            @endif
            
            <div class="mt-3">
                <h6><i class="fas fa-file-alt"></i> Resume Management</h6>
                <div class="d-flex gap-2 flex-wrap">
                    @if($approvalStatus !== 'approved')
                    <form action="{{ route('admin.users.approve-resume', $user) }}" method="POST" onsubmit="return confirm('Approve this resume for Browse Resume page?');">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-check"></i> Approve Resume
                        </button>
                    </form>
                    @endif
                    
                    @if($approvalStatus !== 'rejected')
                    <form action="{{ route('admin.users.reject-resume', $user) }}" method="POST" onsubmit="return confirm('Reject this resume?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-file-times"></i> Reject Resume
                        </button>
                    </form>
                    @endif
                    
                    @if(!$isFeatured)
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#featureResumeModal">
                        <i class="fas fa-star"></i> Feature Resume
                    </button>
                    @else
                    <form action="{{ route('admin.users.unfeature-resume', $user) }}" method="POST" onsubmit="return confirm('Unfeature this resume?');">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-star-half-alt"></i> Unfeature Resume
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
            <!-- Feature Resume Modal -->
            <div class="modal fade" id="featureResumeModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Feature Resume</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.users.feature-resume', $user) }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="featured_duration" class="form-label">Featured Duration (Days)</label>
                                    <input type="number" class="form-control" id="featured_duration" name="featured_duration" min="1" max="365" value="30" required>
                                    <small class="form-text text-muted">Resume will be featured on homepage for the specified number of days.</small>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> This resume will appear on the homepage featured candidates section.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-star"></i> Feature Resume
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


@push('styles')
<style>
.admin-card { border: 1px solid #e9ecef; border-radius: 8px; background: #fff; }
.admin-card-header { padding: 16px; border-bottom: 1px solid #e9ecef; }
.admin-card-body { padding: 16px; }
.admin-form-group { margin-bottom: 12px; }
.admin-form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
.admin-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 6px; border: none; cursor: pointer; }
.admin-btn-primary { background: #0d6efd; color: #fff; }
.admin-form-check { display: flex; align-items: center; gap: 8px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const toggle = document.getElementById('show_reset_form');
    const form = document.getElementById('reset_password_form');
    if (toggle && form) {
        toggle.addEventListener('click', function(e){
            e.preventDefault();
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        });
    }
});
</script>
@endpush

