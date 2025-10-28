@extends('layouts.admin')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-lock"></i> Change Password</h5>
                <p class="mb-0 text-muted">Update your admin account password</p>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.change-password.post') }}" method="POST">
                    @csrf
                    
                    <div class="admin-form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="admin-form-control @error('current_password') is-invalid @enderror" 
                               required 
                               autofocus>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="password">New Password</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="admin-form-control @error('password') is-invalid @enderror" 
                               required>
                        <small class="text-muted">Password must be at least 8 characters long</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="admin-form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="admin-form-control" 
                               required>
                    </div>

                    <div class="admin-form-group">
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-secondary ms-2">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
