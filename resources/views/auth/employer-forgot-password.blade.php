@extends('layouts.app')

@section('title', 'Forgot Password')

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
                    <h1>Forgot Password</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms jobseekerWrp">
    <div class="container">
        <div class="login-container">
            <h2>Reset Your Password</h2>
            <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('employer.forgot-password.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address" />
                </div>
                <div class="form-actions">
                    <button type="submit">Send Reset Link</button>
                </div>
            </form>
            
            <div class="extra-links">
                <p>Remember your password? <a href="{{ route('employer.login') }}">Login here</a></p>
                <p class="mt-2">Don't have an account? <a href="{{ route('employer.register') }}">Register here</a></p>
            </div>
        </div>
    </div>
</section>
@endsection
