@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Verify Email</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Verify Email</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-envelope fa-5x text-primary"></i>
                        </div>
                        
                        <h2 class="mb-4">Verify Your Email Address</h2>
                        
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <p class="mb-0">
                                <strong>Thank you for registering!</strong><br>
                                Before proceeding, please check your email for a verification link.
                            </p>
                        </div>

                        <p class="text-muted mb-4">
                            We've sent a verification email to: <strong>{{ auth()->user()->email }}</strong>
                        </p>

                        <p class="text-muted mb-4">
                            Please click the link in the email to verify your account and access all features.
                        </p>

                        <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Resend Verification Email
                            </button>
                        </form>

                        <hr class="my-4">

                        <p class="text-muted small">
                            <i class="fas fa-info-circle"></i> 
                            If you didn't receive the email, please check your spam folder or click the button above to resend.
                        </p>

                        <form method="POST" action="{{ route('logout') }}" class="d-inline mt-3">
                            @csrf
                            <button type="submit" class="btn btn-link text-muted">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hide search section on email verification page */
.search-wrap {
    display: none !important;
}
</style>
@endpush
@endsection


