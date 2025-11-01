@extends('layouts.app')

@section('title', $job->title)

@push('styles')
<style>
/* Modern Job Details Page Design */
.job-details-wrapper {
    background: #f9fafb;
    padding: 40px 0;
}

.job-header-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid #e5e7eb;
}

.job-header-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid #f3f4f6;
}

.job-title-section h1 {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 12px 0;
    line-height: 1.3;
    letter-spacing: -0.5px;
}

.job-title-section .job-category {
    display: inline-block;
    background: #2773e8;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.company-info-card {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #f9fafb;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
}

.company-avatar {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: #2773e8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    flex-shrink: 0;
}

.company-details h4 {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 4px 0;
}

.company-details p {
    font-size: 14px;
    color: #6b7280;
    margin: 0;
}

.job-content-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid #e5e7eb;
}

.content-section {
    margin-bottom: 32px;
}

.content-section:last-child {
    margin-bottom: 0;
}

.content-section h4 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.content-section p {
    font-size: 15px;
    line-height: 1.8;
    color: #374151;
    margin: 0;
}

.content-section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.content-section ul li {
    padding: 12px 0;
    padding-left: 28px;
    position: relative;
    font-size: 15px;
    line-height: 1.8;
    color: #374151;
    border-bottom: 1px solid #f3f4f6;
}

.content-section ul li:last-child {
    border-bottom: none;
}

.content-section ul li:before {
    content: "✓";
    position: absolute;
    left: 0;
    color: #2773e8;
    font-weight: 700;
    font-size: 16px;
}

.skills-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 16px;
}

.skill-tag {
    display: inline-block;
    background: #f3f4f6;
    color: #374151;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.skill-tag:hover {
    background: #2773e8;
    color: #ffffff;
    border-color: #2773e8;
    transform: translateY(-2px);
}

.job-sidebar-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #e5e7eb;
}

.job-sidebar-card h5 {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 20px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.apply-button-wrapper {
    margin-bottom: 24px;
}

.apply-button-wrapper .btn-apply {
    width: 100%;
    padding: 14px 24px;
    background: #2773e8;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.apply-button-wrapper .btn-apply:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.apply-button-wrapper .btn-apply:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

.job-detail-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.job-detail-item:last-child {
    border-bottom: none;
}

.job-detail-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #2773e8;
    font-size: 18px;
    flex-shrink: 0;
}

.job-detail-content {
    flex: 1;
}

.job-detail-content span {
    display: block;
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.job-detail-content strong {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

.contact-info-section {
    margin-top: 8px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
    font-size: 14px;
    color: #374151;
}

.contact-item:last-child {
    border-bottom: none;
}

.contact-item i {
    color: #2773e8;
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .job-header-card {
        padding: 24px;
    }
    
    .job-header-top {
        flex-direction: column;
        gap: 20px;
    }
    
    .job-title-section h1 {
        font-size: 24px;
    }
    
    .company-info-card {
        flex-direction: column;
        text-align: center;
    }
    
    .job-content-card {
        padding: 24px;
    }
    
    .content-section h4 {
        font-size: 18px;
    }
    
    .job-sidebar-card {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="job-details-wrapper">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> 
                {{ session('error') }}
                @if($errors->any())
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <!-- Job Header -->
        <div class="job-header-card">
            <div class="job-header-top">
                <div class="job-title-section">
                    <span class="job-category">{{ $job->category->name ?? 'Job Category' }}</span>
                    <h1>{{ $job->title }}</h1>
                </div>
            </div>
            <div class="company-info-card">
                <div class="company-avatar">
                    {{ strtoupper(substr($job->employer->employerProfile->company_name ?? 'Company', 0, 1)) }}
                </div>
                <div class="company-details">
                    <h4>{{ $job->employer->employerProfile->company_name ?? 'Company' }}</h4>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $job->location_city }}, {{ $job->location_country }}</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="job-content-card">
                    <div class="content-section">
                        <h4><i class="fas fa-align-left"></i> Job Description</h4>
                        <p>{!! nl2br(e($job->description)) !!}</p>
                    </div>

                    @if($job->requirements)
                    <div class="content-section">
                        <h4><i class="fas fa-list-check"></i> Requirements</h4>
                        <p>{!! nl2br(e($job->requirements)) !!}</p>
                    </div>
                    @endif

                    @if($job->responsibilities)
                    <div class="content-section">
                        <h4><i class="fas fa-tasks"></i> Responsibilities</h4>
                        <p>{!! nl2br(e($job->responsibilities)) !!}</p>
                    </div>
                    @endif

                    @if($job->benefits)
                    <div class="content-section">
                        <h4><i class="fas fa-gift"></i> Benefits</h4>
                        <p>{!! nl2br(e($job->benefits)) !!}</p>
                    </div>
                    @endif

                    @php
                        $skills = is_array($job->skills_required) ? $job->skills_required : json_decode($job->skills_required, true);
                    @endphp
                    @if($skills && is_array($skills) && count($skills) > 0)
                    <div class="content-section">
                        <h4><i class="fas fa-code"></i> Required Skills</h4>
                        <div class="skills-tags">
                            @foreach($skills as $skill)
                                <span class="skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4 col-md-12 col-sm-12 col-12">
                <div class="job-sidebar-card">
                    <div class="apply-button-wrapper">
                        @auth
                            @if(auth()->user()->isSeeker())
                                @php
                                    $alreadyApplied = \App\Models\JobApplication::where('job_id', $job->id)
                                        ->where('seeker_id', auth()->id())
                                        ->exists();
                                @endphp
                                @if($alreadyApplied)
                                    <button class="btn-apply" disabled>
                                        <i class="fas fa-check-circle"></i> Already Applied
                                    </button>
                                @else
                                    <button type="button" class="btn-apply" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="fas fa-paper-plane"></i> Apply Now
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('jobseeker.login') }}" class="btn-apply">
                                    <i class="fas fa-sign-in-alt"></i> Login to Apply
                                </a>
                            @endif
                        @else
                            <a href="{{ route('jobseeker.login') }}" class="btn-apply">
                                <i class="fas fa-sign-in-alt"></i> Login to Apply
                            </a>
                        @endauth
                    </div>
                    
                    <h5>Job Details</h5>
                    <div class="job-details-list">
                        <div class="job-detail-item">
                            <div class="job-detail-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="job-detail-content">
                                <span>Job Type</span>
                                <strong>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</strong>
                            </div>
                        </div>
                        
                        <div class="job-detail-item">
                            <div class="job-detail-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="job-detail-content">
                                <span>Location</span>
                                <strong>{{ $job->location_city }}, {{ $job->location_country }}</strong>
                            </div>
                        </div>
                        
                        @if(!empty($job->salary_min) && !empty($job->salary_max))
                        <div class="job-detail-item">
                            <div class="job-detail-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="job-detail-content">
                                <span>Salary</span>
                                <strong>{{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }} / {{ ucfirst($job->salary_period ?? 'monthly') }}</strong>
                            </div>
                        </div>
                        @endif
                        
                        <div class="job-detail-item">
                            <div class="job-detail-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="job-detail-content">
                                <span>Date Posted</span>
                                <strong>{{ $job->created_at->diffForHumans() }}</strong>
                            </div>
                        </div>
                        
                        @if($job->application_deadline)
                        <div class="job-detail-item">
                            <div class="job-detail-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="job-detail-content">
                                <span>Expiration Date</span>
                                <strong>{{ $job->application_deadline->format('M d, Y') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($job->employer->employerProfile)
                <div class="job-sidebar-card">
                    <h5><i class="fas fa-address-card"></i> Contact Info</h5>
                    <div class="contact-info-section">
                        @if($job->employer->employerProfile->address)
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $job->employer->employerProfile->address }}</span>
                        </div>
                        @endif
                        @if($job->employer->employerProfile->contact_phone)
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $job->employer->employerProfile->contact_phone }}</span>
                        </div>
                        @endif
                        @if($job->employer->employerProfile->contact_email)
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $job->employer->employerProfile->contact_email }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Application Modal -->
@auth
    @if(auth()->user()->isSeeker())
    <div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applyModalLabel">
                        <i class="fas fa-briefcase"></i> Apply for {{ $job->title }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('jobs.apply', $job->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Applying to:</strong> {{ $job->title }} at {{ $job->employer->employerProfile->company_name ?? 'Company' }}
                        </div>

                        @if(auth()->user()->seekerProfile && auth()->user()->seekerProfile->cv_file)
                        <div class="mb-3">
                            <label class="form-label">Your CV</label>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                Your profile CV will be sent automatically
                                <a href="{{ asset(auth()->user()->seekerProfile->cv_file) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="mb-3">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                No CV in your profile. Please upload your CV in 
                                <a href="{{ route('profile') }}" target="_blank">My Profile</a> first.
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Cover Letter (Optional)</label>
                            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="6" placeholder="Write a brief cover letter explaining why you're a good fit for this position...">{{ old('cover_letter') }}</textarea>
                            <small class="text-muted">Tell the employer why you're interested in this position</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endauth
@endsection

