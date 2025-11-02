@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Login</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="mb-3">Choose Your Login Type</h2>
            <p class="text-muted">Select your account type to continue</p>
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
                        <p class="text-muted mb-4">Access your job seeker account to manage your profile, applications, and find new opportunities.</p>
                        
                        <div class="features mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> View your applications</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Update your CV</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Save favorite jobs</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Manage your profile</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('jobseeker.login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login as Job Seeker
                            </a>
                            <p class="text-muted mt-2 mb-0" style="font-size: 14px;">
                                Don't have an account? 
                                <a href="{{ route('jobseeker.register') }}" class="text-primary">Register here</a>
                            </p>
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
                        <p class="text-muted mb-4">Access your employer account to post jobs, manage applications, and find the best talent.</p>
                        
                        <div class="features mb-4">
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Post and manage jobs</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Review applications</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Search candidates</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Manage company profile</li>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="{{ route('employer.login') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Login as Employer
                            </a>
                            <p class="text-muted mt-2 mb-0" style="font-size: 14px;">
                                Don't have an account? 
                                <a href="{{ route('employer.register') }}" class="text-success">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="text-center mt-5">
            <p class="text-muted">
                Need help? 
                <a href="{{ route('contact') }}" class="text-primary">Contact us</a> for assistance
            </p>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hide search section on login selection page */
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

