@extends('layouts.app')

@section('title', 'Reset Password')

@push('styles')
<style>
/* Hide job search filter on employer pages */
.search-wrap {
    display: none !important;
}
</style>
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Reset Password</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reset Password</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms jobseekerWrp">
    <div class="container">
        <div class="login-container">
            <h2>Set New Password</h2>
            <p class="text-muted mb-4">Enter your email address and new password to reset your account.</p>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('employer.reset-password.post') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address" />
                </div>
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter new password" />
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm new password" />
                </div>
                
                <div class="form-actions">
                    <button type="submit">Reset Password</button>
                </div>
            </form>
            
            <div class="extra-links">
                <p>Remember your password? <a href="{{ route('employer.login') }}">Login here</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
