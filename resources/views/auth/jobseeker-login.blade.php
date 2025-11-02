@extends('layouts.app')

@section('title', 'Jobseeker Login')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Jobseekers Login</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jobseekers Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms jobseekerWrp">
    <div class="container">
        <div class="login-container">
            <h2>Jobseeker Login</h2>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('jobseeker.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <div class="form-actions">
                    <button type="submit">Login</button>
                </div>
            </form>
            <div class="extra-links">
                <p><a href="{{ route('jobseeker.forgot-password') }}">Forgot Password?</a></p>
                <p>Don't have an account? <a href="{{ route('jobseeker.register') }}">Register here</a></p>
                <p class="mt-2">Are you an employer? <a href="{{ route('employer.login') }}">Login as Employer</a></p>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hide search section on jobseeker login page */
.search-wrap {
    display: none !important;
}
</style>
@endpush
@endsection

