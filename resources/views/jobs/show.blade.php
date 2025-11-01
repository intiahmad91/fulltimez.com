@extends('layouts.app')

@section('title', $job->title)

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Job Details</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Job Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job__main__sec">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
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
        
        <div class="row">
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="content-single">
                    <h3>{{ $job->title }}</h3>
                    
                    <h5>Job Description</h5>
                    <p>{!! nl2br(e($job->description)) !!}</p>

                    @if($job->requirements)
                    <h5>Requirements</h5>
                    <p>{!! nl2br(e($job->requirements)) !!}</p>
                    @endif

                    @if($job->responsibilities)
                    <h5>Responsibilities</h5>
                    <p>{!! nl2br(e($job->responsibilities)) !!}</p>
                    @endif

                    @if($job->benefits)
                    <h5>Benefits</h5>
                    <p>{!! nl2br(e($job->benefits)) !!}</p>
                    @endif

                    @php
                        $skills = is_array($job->skills_required) ? $job->skills_required : json_decode($job->skills_required, true);
                    @endphp
                    @if($skills && is_array($skills) && count($skills) > 0)
                    <h5>Required Skills</h5>
                    <ul>
                        @foreach($skills as $skill)
                            <li>{{ $skill }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 pl-40 pl-lg-15 mt-lg-30">
                <div class="sidebar-shadow">
                    <h5>{{ $job->category->name ?? 'Job Category' }}</h5>
                    <div class="text-description mt-15">
                        {{ $job->employer->employerProfile->company_name ?? 'Company' }}
                    </div>
                    <div class="text-start mt-20">
                        @auth
                            @if(auth()->user()->isSeeker())
                                @php
                                    $alreadyApplied = \App\Models\JobApplication::where('job_id', $job->id)
                                        ->where('seeker_id', auth()->id())
                                        ->exists();
                                @endphp
                                @if($alreadyApplied)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-check"></i> Already Applied
                                    </button>
                                @else
                                    <button type="button" class="btn btn-default mr-10" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="fas fa-paper-plane"></i> Apply Now
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('jobseeker.login') }}" class="btn btn-default mr-10">Login to Apply</a>
                        @endauth
                    </div>
                    
                    <div class="sidebar-list-job">
                        <ul>
                            <li>
                                <div class="sidebar-icon-item"><i class="fas fa-briefcase"></i></div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">Job Type</span>
                                    <strong class="small-heading">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</strong>
                                </div>
                            </li>
                            <li>
                                <div class="sidebar-icon-item"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">Location</span>
                                    <strong class="small-heading">{{ $job->location_city }}, {{ $job->location_country }}</strong>
                                </div>
                            </li>
                            @if(!empty($job->salary_min) && !empty($job->salary_max))
                            <li>
                                <div class="sidebar-icon-item"><i class="fas fa-dollar-sign"></i></div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">Salary</span>
                                    <strong class="small-heading">{{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }}</strong>
                                </div>
                            </li>
                            @endif
                            <li>
                                <div class="sidebar-icon-item"><i class="fas fa-clock"></i></div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">Date posted</span>
                                    <strong class="small-heading">{{ $job->created_at->diffForHumans() }}</strong>
                                </div>
                            </li>
                            @if($job->application_deadline)
                            <li>
                                <div class="sidebar-icon-item"><i class="fas fa-calendar"></i></div>
                                <div class="sidebar-text-info">
                                    <span class="text-description">Expiration date</span>
                                    <strong class="small-heading">{{ $job->application_deadline->format('M d, Y') }}</strong>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="sidebar-team-member pt-40">
                        <h6 class="small-heading">Contact Info</h6>
                        <div class="info-address">
                            @if($job->employer->employerProfile)
                                @if($job->employer->employerProfile->address)
                                <span><i class="fas fa-map-marker-alt"></i><span>{{ $job->employer->employerProfile->address }}</span></span>
                                @endif
                                @if($job->employer->employerProfile->contact_phone)
                                <span><i class="fas fa-headset"></i><span>{{ $job->employer->employerProfile->contact_phone }}</span></span>
                                @endif
                                @if($job->employer->employerProfile->contact_email)
                                <span><i class="fas fa-paper-plane"></i><span>{{ $job->employer->employerProfile->contact_email }}</span></span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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

