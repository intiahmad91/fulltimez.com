@extends('layouts.app')

@section('title', 'Candidate Details')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Candidate Detail</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Candidate Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<div class="listpgWraper">
    <div class="container">
        <div class="job-header">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <img src="{{ $candidate->seekerProfile && $candidate->seekerProfile->profile_picture ? Storage::url($candidate->seekerProfile->profile_picture) : asset('images/avatar.jpg') }}" alt="{{ $candidate->name }}" class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-7">
                    <h2>{{ $candidate->seekerProfile->full_name ?? $candidate->name }}</h2>
                    <p class="job-location">
                        <i class="fas fa-map-marker-alt"></i> {{ $candidate->seekerProfile->city ?? 'N/A' }}, {{ $candidate->seekerProfile->country ?? 'UAE' }}
                    </p>
                    <p><strong>Current Position:</strong> {{ $candidate->seekerProfile->current_position ?? 'Not specified' }}</p>
                    <p><strong>Experience:</strong> {{ $candidate->seekerProfile->experience_years ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 text-end">
                    @auth
                        @if(auth()->user()->isEmployer())
                            <a href="mailto:{{ $candidate->email }}" class="btn btn-primary">Contact Candidate</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>About</h4>
                    </div>
                    <div class="card-body">
                        <p>{{ $candidate->seekerProfile->bio ?? 'No bio available' }}</p>
                    </div>
                </div>

                @if($candidate->experienceRecords->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Work Experience</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($candidate->experienceRecords as $experience)
                            <li class="list-group-item">
                                <strong>{{ $experience->job_title }} at {{ $experience->company_name }}</strong>
                                <div class="experience-date">{{ $experience->start_date->format('M Y') }} - {{ $experience->is_current ? 'Present' : $experience->end_date->format('M Y') }}</div>
                                <p>{{ $experience->description }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if($candidate->educationRecords->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Education</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($candidate->educationRecords as $education)
                            <li class="list-group-item">
                                <strong>{{ $education->degree }} in {{ $education->field_of_study }}</strong>
                                <div>{{ $education->institution_name }}</div>
                                <div class="text-muted">{{ $education->start_date->format('Y') }} - {{ $education->is_current ? 'Present' : $education->end_date->format('Y') }}</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @if($candidate->certificates->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Certificates</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($candidate->certificates as $certificate)
                            <li class="list-group-item">
                                <strong>{{ $certificate->certificate_name }}</strong>
                                <div>{{ $certificate->issuing_organization }}</div>
                                <div class="text-muted">Issued: {{ $certificate->issue_date->format('M Y') }}</div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Contact Information</h4>
                    </div>
                    <div class="card-body">
                        <p><i class="fas fa-envelope"></i> {{ $candidate->email }}</p>
                        <p><i class="fas fa-phone"></i> {{ $candidate->phone ?? 'Not provided' }}</p>
                        @if($candidate->seekerProfile)
                            <p><i class="fas fa-globe"></i> {{ $candidate->seekerProfile->nationality ?? 'N/A' }}</p>
                            <p><i class="fas fa-money-bill"></i> Expected: {{ $candidate->seekerProfile->expected_salary ?? 'Negotiable' }}</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

