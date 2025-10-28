@extends('layouts.app')

@section('title', 'Employer Login')

@push('styles')
<style>
/* Hide job search filter on employer login page */
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
                    <h1>Employer Login</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employer Login</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms jobseekerWrp">
    <div class="container">
        <div class="login-box login-container">
            <h2>Employer Login</h2>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('employer.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Company Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="extra-links">
                <p><a href="{{ route('employer.forgot-password') }}">Forgot Password?</a></p>
                <p>New to the platform? <a href="{{ route('employer.register') }}">Register your company</a></p>
                <p class="mt-2">Looking for a job? <a href="{{ route('jobseeker.login') }}">Login as Job Seeker</a></p>
            </div>
        </div>
    </div>
</section>
@endsection

