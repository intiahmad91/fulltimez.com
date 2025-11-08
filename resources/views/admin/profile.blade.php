@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-user"></i> Profile Information</h5>
            </div>
            <div class="admin-card-body text-center">
                <div class="admin-avatar-large mb-3">
                    @if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture)
                        <img src="{{ asset(auth()->user()->seekerProfile->profile_picture) }}" alt="Admin Avatar">
                    @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo)
                        <img src="{{ asset(auth()->user()->employerProfile->company_logo) }}" alt="Admin Avatar">
                    @else
                        <div class="default-avatar-large">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <h4>{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">Administrator</p>
                <div class="admin-badge badge-primary">Full Access</div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-edit"></i> Edit Profile</h5>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="name">Full Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="admin-form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="admin-form-group">
                                <label for="email">Email Address</label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="admin-form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', auth()->user()->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                        <a href="{{ route('admin.change-password') }}" class="admin-btn admin-btn-secondary ms-2">
                            <i class="fas fa-lock"></i> Change Password
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card mt-4">
            <div class="admin-card-header">
                <h5><i class="fas fa-info-circle"></i> Account Information</h5>
            </div>
            <div class="admin-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item">
                            <label>Account Type:</label>
                            <span class="admin-badge badge-primary">Administrator</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label>Account Status:</label>
                            <span class="admin-badge badge-success">Active</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label>Member Since:</label>
                            <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label>Last Updated:</label>
                            <span>{{ auth()->user()->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto;
    border: 4px solid #e9ecef;
}

.admin-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-avatar-large {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #ffffff;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f3f4;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.info-item span {
    color: #6c757d;
}
</style>
@endsection
