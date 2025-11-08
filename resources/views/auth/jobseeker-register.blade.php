@extends('layouts.app')

@section('title', 'Jobseeker Registration')

@push('styles')
<style>
/* Hide search section on jobseeker register page */
.search-wrap {
    display: none !important;
}

.form-group {
    position: relative;
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.3rem;
    display: block;
    font-size: 14px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 10px 40px 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
}

.form-group input.is-invalid,
.form-group select.is-invalid {
    border-color: #dc3545;
    padding-right: 40px;
}

.form-group input.is-valid,
.form-group select.is-valid {
    border-color: #28a745;
    padding-right: 40px;
}

.validation-icon {
    position: absolute;
    right: 12px;
    top: 36px;
    font-size: 16px;
}

.validation-icon.valid {
    color: #28a745;
}

.validation-icon.invalid {
    color: #dc3545;
}

.password-strength-meter {
    height: 4px;
    background: #e0e0e0;
    border-radius: 4px;
    margin-top: 6px;
    overflow: hidden;
}

.password-strength-bar {
    height: 100%;
    transition: width 0.3s ease, background-color 0.3s ease;
    width: 0;
}

.password-strength-bar.weak {
    width: 33%;
    background-color: #dc3545;
}

.password-strength-bar.medium {
    width: 66%;
    background-color: #ffc107;
}

.password-strength-bar.strong {
    width: 100%;
    background-color: #28a745;
}

.password-requirements {
    font-size: 11px;
    margin-top: 6px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
    display: none;
}

.password-requirements.show {
    display: block;
}

.password-requirements ul {
    margin: 0;
    padding-left: 15px;
    list-style: none;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.password-requirements li {
    margin: 0;
    color: #666;
    font-size: 11px;
    flex: 0 0 calc(50% - 4px);
}

.password-requirements li.met {
    color: #28a745;
}

.password-requirements li.met:before {
    content: "✓ ";
    font-weight: bold;
}

.password-requirements li:not(.met):before {
    content: "✗ ";
    font-weight: bold;
    color: #dc3545;
}

.form-hint {
    font-size: 11px;
    color: #666;
    margin-top: 3px;
    display: block;
}

.text-danger.small {
    font-size: 12px;
}

.text-success.small {
    font-size: 12px;
}

.file-preview {
    margin-top: 8px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
    display: none;
}

.file-preview img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 4px;
}

.file-preview .file-info {
    margin-top: 6px;
    font-size: 12px;
}

.btn-primary {
    padding: 12px 24px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.alert {
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 1rem;
}

.form-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 20px 0 15px 0;
    padding-bottom: 8px;
    border-bottom: 2px solid #4CAF50;
}

.form-section-title:first-of-type {
    margin-top: 0;
}

.job_forms h2 {
    margin-bottom: 0.5rem;
}

.job_forms .text-center.text-muted {
    margin-bottom: 1.5rem;
    font-size: 14px;
}

.form-container {
    max-width: 900px;
    margin: 0 auto;
}

/* Show simplified basic form; hide advanced form */
#jobseekerRegisterForm { display: none !important; }
#jobseekerRegisterFormSimple .form-group { margin-bottom: 14px; }
#jobseekerRegisterFormSimple .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
#jobseekerRegisterFormSimple .btn-primary { width: 100%; }
</style>
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Jobseeker Registration</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jobseeker Registration</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms jobseekerWrp">
    <div class="container">
        <h2>Create Your Jobseeker Account</h2>
        <p class="text-center text-muted mb-4">Join thousands of job seekers and find your dream job today</p>
        
        <div class="form-container">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
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

            <!-- Simplified basic registration form -->
            <form id="jobseekerRegisterFormSimple" action="{{ route('jobseeker.register.post') }}" method="POST" novalidate>
                @csrf
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Full Name">
                </div>
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email Address">
                </div>
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="+971501234567">
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" required placeholder="Password">
                </div>
                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" required placeholder="Confirm Password">
                </div>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
            
            <div class="extra-links mt-4 text-center">
                <p>Already have an account? <a href="{{ route('jobseeker.login') }}"><strong>Login here</strong></a></p>
                <p class="mt-2">Want to hire? <a href="{{ route('employer.register') }}"><strong>Register as Employer</strong></a></p>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('jobseekerRegisterForm');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const passwordMatchError = document.getElementById('passwordMatchError');
    const passwordMatchSuccess = document.getElementById('passwordMatchSuccess');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.querySelector('.loading-spinner');
    
    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
    
    // Password strength indicator
    function checkPasswordStrength() {
        const value = password.value;
        const strengthBar = document.getElementById('passwordStrengthBar');
        
        const hasLength = value.length >= 8;
        const hasUpperCase = /[A-Z]/.test(value);
        const hasLowerCase = /[a-z]/.test(value);
        const hasNumber = /\d/.test(value);
        const hasSpecial = /[@$!%*?&#]/.test(value);
        
        // Update requirements
        document.getElementById('req-length').classList.toggle('met', hasLength);
        document.getElementById('req-uppercase').classList.toggle('met', hasUpperCase);
        document.getElementById('req-lowercase').classList.toggle('met', hasLowerCase);
        document.getElementById('req-number').classList.toggle('met', hasNumber);
        document.getElementById('req-special').classList.toggle('met', hasSpecial);
        
        // Calculate strength
        const criteriaMet = [hasLength, hasUpperCase, hasLowerCase, hasNumber, hasSpecial].filter(Boolean).length;
        
        strengthBar.className = 'password-strength-bar';
        if (criteriaMet <= 2) {
            strengthBar.classList.add('weak');
        } else if (criteriaMet <= 4) {
            strengthBar.classList.add('medium');
        } else {
            strengthBar.classList.add('strong');
        }
        
        // Validation
        if (value && criteriaMet < 5) {
            password.setCustomValidity('Password does not meet all requirements');
        } else {
            password.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', checkPasswordStrength);
    
    // Show/hide password requirements on focus/blur
    password.addEventListener('focus', function() {
        document.getElementById('passwordRequirements').classList.add('show');
    });
    
    password.addEventListener('blur', function() {
        // Keep visible if not all requirements met
        const value = this.value;
        const hasLength = value.length >= 8;
        const hasUpperCase = /[A-Z]/.test(value);
        const hasLowerCase = /[a-z]/.test(value);
        const hasNumber = /\d/.test(value);
        const hasSpecial = /[@$!%*?&#]/.test(value);
        const allMet = hasLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecial;
        
        if (allMet) {
            setTimeout(() => {
                document.getElementById('passwordRequirements').classList.remove('show');
            }, 1000);
        }
    });
    
    // Password match validation
    function checkPasswordMatch() {
        if (passwordConfirmation.value === '') {
            passwordMatchError.style.display = 'none';
            passwordMatchSuccess.style.display = 'none';
            document.getElementById('password_confirmation_icon').innerHTML = '';
            return;
        }
        
        if (password.value !== passwordConfirmation.value) {
            passwordMatchError.style.display = 'block';
            passwordMatchSuccess.style.display = 'none';
            passwordConfirmation.setCustomValidity('Passwords do not match');
            document.getElementById('password_confirmation_icon').innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            passwordConfirmation.classList.add('is-invalid');
            passwordConfirmation.classList.remove('is-valid');
        } else {
            passwordMatchError.style.display = 'none';
            passwordMatchSuccess.style.display = 'block';
            passwordConfirmation.setCustomValidity('');
            document.getElementById('password_confirmation_icon').innerHTML = '<i class="fas fa-check-circle valid"></i>';
            passwordConfirmation.classList.remove('is-invalid');
            passwordConfirmation.classList.add('is-valid');
        }
    }
    
    password.addEventListener('input', checkPasswordMatch);
    passwordConfirmation.addEventListener('input', checkPasswordMatch);
    
    // Real-time validation for other fields
    const fullNameInput = document.getElementById('full_name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    
    // Full name validation
    fullNameInput.addEventListener('input', function() {
        const value = this.value;
        const namePattern = /^[a-zA-Z\s\-\.]+$/;
        const icon = document.getElementById('full_name_icon');
        
        // Check for numbers or special characters
        const hasInvalidChars = /[^a-zA-Z\s\-\.]/.test(value);
        const hasMultipleSpaces = /\s{2,}/.test(value);
        const startsWithSpace = /^\s/.test(value);
        
        if (value.length === 0) {
            icon.innerHTML = '';
            this.classList.remove('is-invalid', 'is-valid');
            this.setCustomValidity('');
        } else if (hasInvalidChars) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Name can only contain letters, spaces, hyphens, and periods');
        } else if (startsWithSpace) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Name cannot start with a space');
        } else if (hasMultipleSpaces) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Name cannot have multiple consecutive spaces');
        } else if (value.length < 3) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Name must be at least 3 characters');
        } else if (namePattern.test(value)) {
            icon.innerHTML = '<i class="fas fa-check-circle valid"></i>';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.setCustomValidity('');
        }
    });
    
    // Email validation with detailed checks
    emailInput.addEventListener('input', function() {
        const value = this.value.trim();
        const icon = document.getElementById('email_icon');
        const emailError = document.getElementById('emailError');
        
        // Comprehensive email validation
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const hasAt = value.includes('@');
        const parts = value.split('@');
        const hasDomain = parts.length === 2 && parts[1].includes('.');
        const hasValidChars = /^[a-zA-Z0-9._%+-@]+$/.test(value);
        
        emailError.style.display = 'none';
        
        if (value.length === 0) {
            icon.innerHTML = '';
            this.classList.remove('is-invalid', 'is-valid');
            this.setCustomValidity('');
        } else if (!hasValidChars) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email contains invalid characters';
            emailError.style.display = 'block';
            this.setCustomValidity('Invalid characters in email');
        } else if (!hasAt) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email must contain @ symbol';
            emailError.style.display = 'block';
            this.setCustomValidity('Missing @ symbol');
        } else if (parts[0].length === 0) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email must have username before @';
            emailError.style.display = 'block';
            this.setCustomValidity('Missing username');
        } else if (!hasDomain) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email must have valid domain (e.g., example.com)';
            emailError.style.display = 'block';
            this.setCustomValidity('Invalid domain');
        } else if (!emailPattern.test(value)) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            emailError.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please enter a valid email format';
            emailError.style.display = 'block';
            this.setCustomValidity('Invalid email format');
        } else {
            icon.innerHTML = '<i class="fas fa-check-circle valid"></i>';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.setCustomValidity('');
        }
    });
    
    // Blur validation for email (check for common mistakes)
    emailInput.addEventListener('blur', function() {
        const value = this.value.trim();
        const emailError = document.getElementById('emailError');
        
        if (value && this.classList.contains('is-valid')) {
            // Check for common typos
            const commonDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'icloud.com'];
            const parts = value.split('@');
            if (parts.length === 2) {
                const domain = parts[1].toLowerCase();
                const suggestions = [];
                
                if (domain.includes('gmial') || domain.includes('gmai')) suggestions.push('gmail.com');
                if (domain.includes('yahooo') || domain.includes('yaho')) suggestions.push('yahoo.com');
                if (domain.includes('hotmial') || domain.includes('hotmal')) suggestions.push('hotmail.com');
                
                if (suggestions.length > 0) {
                    emailError.innerHTML = `<i class="fas fa-info-circle"></i> Did you mean <strong>${suggestions[0]}</strong>?`;
                    emailError.style.display = 'block';
                    emailError.className = 'text-warning small mt-1';
                }
            }
        }
    });
    
    // Phone validation
    phoneInput.addEventListener('input', function() {
        const value = this.value;
        const phonePattern = /^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/;
        const icon = document.getElementById('phone_icon');
        const hasLetters = /[a-zA-Z]/.test(value);
        const digitCount = (value.match(/\d/g) || []).length;
        
        if (value.length === 0) {
            icon.innerHTML = '';
            this.classList.remove('is-invalid', 'is-valid');
            this.setCustomValidity('');
        } else if (hasLetters) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Phone number cannot contain letters');
        } else if (digitCount < 10) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Phone number must have at least 10 digits');
        } else if (!phonePattern.test(value)) {
            icon.innerHTML = '<i class="fas fa-times-circle invalid"></i>';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
            this.setCustomValidity('Invalid phone number format');
        } else {
            icon.innerHTML = '<i class="fas fa-check-circle valid"></i>';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.setCustomValidity('');
        }
    });
    
    // File validation and preview
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('profile_picture_preview');
            const img = document.getElementById('profile_picture_img');
            const info = document.getElementById('profile_picture_info');
            
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                
                if (!validTypes.includes(file.type)) {
                    alert('Please select a JPG, JPEG, or PNG image');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                if (fileSize > 2) {
                    alert('Profile picture must not exceed 2MB');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    info.innerHTML = `<strong>${file.name}</strong><br>Size: ${fileSize.toFixed(2)} MB`;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
    }
    
    const cvFileInput = document.getElementById('cv_file');
    if (cvFileInput) {
        cvFileInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('cv_file_preview');
            const info = document.getElementById('cv_file_info');
            
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (!validTypes.includes(file.type)) {
                    alert('Please select a PDF, DOC, or DOCX file');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                if (fileSize > 5) {
                    alert('CV file must not exceed 5MB');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }
                
                // Show file info
                const fileIcon = file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-word';
                info.innerHTML = `
                    <i class="fas ${fileIcon} fa-3x text-primary"></i><br>
                    <strong>${file.name}</strong><br>
                    Size: ${fileSize.toFixed(2)} MB
                `;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        });
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
        
        if (!isValid) {
            alert('Please fill all required fields');
            return false;
        }
        
        if (password.value !== passwordConfirmation.value) {
            alert('Passwords do not match');
            passwordConfirmation.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        loadingSpinner.style.display = 'inline-block';
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registering...';
        
        // Submit form
        this.submit();
    });
    
    // Prevent multiple submissions
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
    });
});
</script>
@endpush
@endsection
