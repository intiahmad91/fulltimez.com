@extends('layouts.app')

@section('title', 'Resume Preview')

@section('content')
<section class="pagecontent" style="padding: 40px 0;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-end">
                <a href="{{ route('seeker.cv.create') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back to Edit
                </a>
                <a href="{{ route('resume.download') }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
        </div>
        
        <div class="card shadow-lg" style="max-width: 900px; margin: 0 auto;">
            <div class="card-body p-5" style="background: white;">
                <!-- Header -->
                <div class="text-center border-bottom pb-4 mb-4" style="border-color: #673AB7 !important; border-width: 3px !important;">
                    <h1 style="color: #673AB7; font-size: 36px; font-weight: bold;">{{ $profile->full_name ?? $user->name }}</h1>
                    <p class="mb-1" style="font-size: 18px; color: #666;">{{ $profile->current_position ?? 'Professional' }}</p>
                    <p style="color: #888; font-size: 14px;">
                        <i class="fas fa-envelope"></i> {{ $user->email }} | 
                        <i class="fas fa-phone"></i> {{ $user->phone }}
                        @if($profile->city || $profile->country)
                        | <i class="fas fa-map-marker-alt"></i> {{ $profile->city }}{{ $profile->city && $profile->country ? ', ' : '' }}{{ $profile->country }}
                        @endif
                    </p>
                </div>

                <!-- Professional Summary -->
                @if($profile && $profile->bio)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-user"></i> PROFESSIONAL SUMMARY
                    </h4>
                    <p style="text-align: justify; line-height: 1.8;">{{ $profile->bio }}</p>
                </div>
                @endif

                <!-- Personal Information -->
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-info-circle"></i> PERSONAL INFORMATION
                    </h4>
                    <div class="row">
                        @if($profile->nationality)
                        <div class="col-md-6 mb-2"><strong>Nationality:</strong> {{ $profile->nationality }}</div>
                        @endif
                        @if($profile->date_of_birth)
                        <div class="col-md-6 mb-2"><strong>Date of Birth:</strong> {{ $profile->date_of_birth->format('F d, Y') }}</div>
                        @endif
                        @if($profile->gender)
                        <div class="col-md-6 mb-2"><strong>Gender:</strong> {{ ucfirst($profile->gender) }}</div>
                        @endif
                        @if($profile->experience_years)
                        <div class="col-md-6 mb-2"><strong>Experience:</strong> {{ $profile->experience_years }}</div>
                        @endif
                        @if($profile->expected_salary)
                        <div class="col-md-6 mb-2"><strong>Expected Salary:</strong> {{ $profile->expected_salary }} AED</div>
                        @endif
                    </div>
                </div>

                <!-- Languages -->
                @if($profile && $profile->languages && count($profile->languages) > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-language"></i> LANGUAGES
                    </h4>
                    <div>
                        @foreach($profile->languages as $language)
                        <span class="badge bg-secondary me-2" style="font-size: 14px;">{{ $language }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Skills -->
                @if($profile && $profile->skills && count($profile->skills) > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-cogs"></i> SKILLS
                    </h4>
                    <div>
                        @foreach($profile->skills as $skill)
                        <span class="badge" style="background: #673AB7; font-size: 13px; margin: 3px; padding: 8px 12px;">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Work Experience -->
                @if($experiences->count() > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-briefcase"></i> WORK EXPERIENCE
                    </h4>
                    @foreach($experiences as $exp)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1" style="color: #333;">{{ $exp->job_title }}</h5>
                                <p class="mb-1" style="color: #666; font-style: italic;">{{ $exp->company_name }}</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-info">
                                    {{ $exp->start_date ? $exp->start_date->format('M Y') : 'N/A' }} - 
                                    {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                                </span>
                            </div>
                        </div>
                        @if($exp->description)
                        <p class="mt-2" style="text-align: justify;">{{ $exp->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Projects -->
                @if($projects->count() > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-project-diagram"></i> PROJECTS
                    </h4>
                    @foreach($projects as $project)
                    <div class="mb-3 pb-3 border-bottom">
                        <h5 class="mb-1" style="color: #333;">{{ $project->project_name }}</h5>
                        @if($project->project_type)
                        <p class="mb-1" style="color: #666; font-style: italic;">{{ $project->project_type }}</p>
                        @endif
                        @if($project->project_link)
                        <p class="mb-1"><a href="{{ $project->project_link }}" target="_blank" style="color: #673AB7;">{{ $project->project_link }}</a></p>
                        @endif
                        @if($project->description)
                        <p class="mt-2" style="text-align: justify;">{{ $project->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Education -->
                @if($educations->count() > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-graduation-cap"></i> EDUCATION
                    </h4>
                    @foreach($educations as $edu)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="mb-1" style="color: #333;">{{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</h5>
                                <p class="mb-0" style="color: #666; font-style: italic;">{{ $edu->institution_name }}</p>
                            </div>
                            <div>
                                <span class="badge bg-secondary">{{ $edu->end_date ? $edu->end_date->format('Y') : 'In Progress' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Certificates -->
                @if($certificates->count() > 0)
                <div class="mb-4">
                    <h4 style="color: #673AB7; border-bottom: 2px solid #673AB7; padding-bottom: 8px; margin-bottom: 15px;">
                        <i class="fas fa-certificate"></i> CERTIFICATIONS
                    </h4>
                    @foreach($certificates as $cert)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="mb-1" style="color: #333;">{{ $cert->certificate_name }}</h5>
                                <p class="mb-0" style="color: #666; font-style: italic;">{{ $cert->issuing_organization }}</p>
                            </div>
                            <div>
                                <span class="badge bg-warning">{{ $cert->issue_date ? $cert->issue_date->format('M Y') : '' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="text-center mt-5 pt-4" style="border-top: 1px solid #ddd; color: #999; font-size: 12px;">
                    Generated via FullTimez - {{ now()->format('F d, Y') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection



