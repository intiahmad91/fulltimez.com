@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="pagecontent dashboard_wrap">
    <div class="container">
        <div class="row contactWrp">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Dashboard</h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="item-all-cat">
                            <div class="row category-type">
                                @if(auth()->user()->isSeeker())
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('profile') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-user"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Profile</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('seeker.cv.create') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-file-alt"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">My CV</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('applications.index') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-briefcase"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Applications</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('jobs.index') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-search"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Browse Jobs</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('change.password') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-lock"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Change Password</h5>
                                        </div>
                                    </div>
                                </div>
                                @elseif(auth()->user()->isEmployer())
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('employer.jobs.create') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-plus-circle"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Post Job</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('employer.jobs.index') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-briefcase"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">My Jobs</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('employer.applications') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-file-alt"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Applications</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <a href="{{ route('profile') }}"></a>
                                        <div class="iteam-all-icon1"><i class="fas fa-building"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Company Profile</h5>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="item-all-card text-dark text-center card">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();"></a>
                                        </form>
                                        <div class="iteam-all-icon1"><i class="fas fa-sign-out-alt"></i></div>
                                        <div class="item-all-text mt-3">
                                            <h5 class="mb-0 text-body">Logout</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

