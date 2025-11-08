@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Change Password</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Change Password</li>
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
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-lock"></i> Change Your Password
                                </h3>
                            </div>
                            <div class="card-body p-5">
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <strong>Please fix the following errors:</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show">
                                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Password Requirements:</strong>
                                    <ul class="mb-0 mt-2 small">
                                        <li>At least 8 characters long</li>
                                        <li>Contains at least one uppercase letter (A-Z)</li>
                                        <li>Contains at least one lowercase letter (a-z)</li>
                                        <li>Contains at least one number (0-9)</li>
                                    </ul>
                                </div>

                                <form id="changePasswordForm" action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="mb-4">
                                        <label for="current_password" class="form-label">
                                            <i class="fas fa-key"></i> Current Password <sup class="text-danger">*</sup>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control form-control-lg @error('current_password') is-invalid @enderror" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   placeholder="Enter your current password"
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                                <i class="fas fa-eye" id="current_password_icon"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock"></i> New Password <sup class="text-danger">*</sup>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Enter new password"
                                                   required
                                                   minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password_icon"></i>
                                            </button>
                                        </div>
                                        <div id="password-strength" class="mt-2" style="display: none;">
                                            <small class="text-muted">Password Strength: </small>
                                            <div class="progress" style="height: 8px;">
                                                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                            </div>
                                            <small id="password-strength-text" class="text-muted"></small>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-check-double"></i> Confirm New Password <sup class="text-danger">*</sup>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" 
                                                   class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   placeholder="Re-enter new password"
                                                   required
                                                   minlength="8">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                                <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                            </button>
                                        </div>
                                        <div id="password-match" class="mt-2" style="display: none;">
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> Passwords match
                                            </small>
                                        </div>
                                        <div id="password-mismatch" class="mt-2" style="display: none;">
                                            <small class="text-danger">
                                                <i class="fas fa-times-circle"></i> Passwords do not match
                                            </small>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Update Password
                                        </button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Security Tips -->
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-shield-alt text-primary"></i> Security Tips
                                </h5>
                                <ul class="small text-muted mb-0">
                                    <li>Use a unique password that you don't use on other websites</li>
                                    <li>Never share your password with anyone</li>
                                    <li>Change your password regularly (every 3-6 months)</li>
                                    <li>Use a password manager to keep track of strong passwords</li>
                                    <li>Enable two-factor authentication when available</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const strengthContainer = document.getElementById('password-strength');
    
    if (password.length === 0) {
        strengthContainer.style.display = 'none';
        return;
    }
    
    strengthContainer.style.display = 'block';
    
    let strength = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 8) strength += 25;
    else feedback.push('8+ characters');
    
    // Uppercase check
    if (/[A-Z]/.test(password)) strength += 25;
    else feedback.push('uppercase letter');
    
    // Lowercase check
    if (/[a-z]/.test(password)) strength += 25;
    else feedback.push('lowercase letter');
    
    // Number check
    if (/[0-9]/.test(password)) strength += 25;
    else feedback.push('number');
    
    // Update progress bar
    strengthBar.style.width = strength + '%';
    
    // Update color and text
    if (strength <= 25) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Weak - Add: ' + feedback.join(', ');
        strengthText.className = 'text-danger small';
    } else if (strength <= 50) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Fair - Add: ' + feedback.join(', ');
        strengthText.className = 'text-warning small';
    } else if (strength <= 75) {
        strengthBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Good - Add: ' + feedback.join(', ');
        strengthText.className = 'text-info small';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Strong - All requirements met!';
        strengthText.className = 'text-success small';
    }
});

// Password match checker
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    const matchDiv = document.getElementById('password-match');
    const mismatchDiv = document.getElementById('password-mismatch');
    
    if (confirmation.length === 0) {
        matchDiv.style.display = 'none';
        mismatchDiv.style.display = 'none';
        return;
    }
    
    if (password === confirmation) {
        matchDiv.style.display = 'block';
        mismatchDiv.style.display = 'none';
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    } else {
        matchDiv.style.display = 'none';
        mismatchDiv.style.display = 'block';
        this.classList.remove('is-valid');
        this.classList.add('is-invalid');
    }
});

// Form validation
document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;
    
    if (password !== confirmation) {
        e.preventDefault();
        alert('New password and confirmation do not match!');
        return false;
    }
    
    // Check password strength
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasLength = password.length >= 8;
    
    if (!hasUpper || !hasLower || !hasNumber || !hasLength) {
        e.preventDefault();
        alert('Password must contain:\n- At least 8 characters\n- Uppercase letter\n- Lowercase letter\n- Number');
        return false;
    }
    
    // Disable submit button
    this.querySelector('button[type="submit"]').disabled = true;
    this.querySelector('button[type="submit"]').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
});
</script>
@endpush
@endsection
