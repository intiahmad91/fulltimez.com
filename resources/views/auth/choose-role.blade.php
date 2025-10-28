@extends('layouts.app')

@section('title', 'Login or Register')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Get Started</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Get Started</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Choose How You Want to Continue</h2>
            <p class="text-muted">Select your account type to login or register</p>
        </div>

        <div class="row justify-content-center">
            <!-- Job Seeker Option -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body text-center p-5">
                        <div class="role-icon mb-4">
                            <i class="fas fa-user-tie fa-5x text-primary"></i>
                        </div>
                        <h3 class="mb-3">Job Seeker</h3>
                        <p class="text-muted mb-4">Looking for your next career opportunity? Find and apply to jobs from top companies.</p>
                        
                        <div class="features mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Browse thousands of jobs</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Create professional CV</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Apply with one click</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Track your applications</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('jobseeker.login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login as Job Seeker
                            </a>
                            <a href="{{ route('jobseeker.register') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus"></i> Register as Job Seeker
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employer Option -->
            <div class="col-lg-5 col-md-6 mb-4">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body text-center p-5">
                        <div class="role-icon mb-4">
                            <i class="fas fa-building fa-5x text-success"></i>
                        </div>
                        <h3 class="mb-3">Employer</h3>
                        <p class="text-muted mb-4">Hiring talented professionals? Post jobs and find the perfect candidates for your company.</p>
                        
                        <div class="features mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Post unlimited jobs</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Access qualified candidates</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Manage applications</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Build company profile</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('employer.login') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login as Employer
                            </a>
                            <a href="{{ route('employer.register') }}" class="btn btn-outline-success btn-lg">
                                <i class="fas fa-building"></i> Register as Employer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Already have account -->
        <div class="text-center mt-5">
            <p class="text-muted">
                Not sure which one to choose? 
                <a href="{{ route('contact') }}" class="text-primary">Contact us</a> for help
            </p>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hide search section on get-started page */
.search-wrap {
    display: none !important;
}

.hover-card {
    transition: all 0.3s ease;
    cursor: default;
}

.hover-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
}

.role-icon {
    padding: 20px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 50%;
    display: inline-block;
}

.features {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.features ul {
    margin-bottom: 0;
}

.features li {
    font-size: 14px;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 16px;
}
</style>
@endpush
@endsection


