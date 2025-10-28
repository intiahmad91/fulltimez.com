@extends('layouts.app')

@section('title', 'Employer Registration')

@push('styles')
<style>
/* Hide job search filter on employer pages */
.search-wrap {
    display: none !important;
}
</style>
@endpush

@push('head')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Employer Registration</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employer Registration</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="job_forms">
    <div class="container">
        <div class="form-container">
            <h2>Employer Registration</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
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
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            <form id="employerRegisterForm" class="register-form" action="{{ route('employer.register.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="name">Name <sup class="text-danger">*</sup></label>
                <input type="text" id="name" name="name" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" required minlength="3" maxlength="100" placeholder="e.g., John Doe" />
                @error('name')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror

                <label for="company">Company Name <sup class="text-danger">*</sup></label>
                <input type="text" id="company" name="company_name" class="@error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required minlength="3" maxlength="255" placeholder="e.g., GreenTech Solutions" />
                @error('company_name')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror

                <label for="mobile_no">Mobile No <sup class="text-danger">*</sup></label>
                <div class="phone-input-group">
                    <div class="country-code-selector">
                        <select id="country_code" name="country_code" class="@error('country_code') is-invalid @enderror" required>
                            <option value="🇦🇪 +971" {{ old('country_code', '🇦🇪 +971') == '🇦🇪 +971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                            <option value="🇸🇦 +966" {{ old('country_code') == '🇸🇦 +966' ? 'selected' : '' }}>🇸🇦 +966 (Saudi Arabia)</option>
                            <option value="🇶🇦 +974" {{ old('country_code') == '🇶🇦 +974' ? 'selected' : '' }}>🇶🇦 +974 (Qatar)</option>
                            <option value="🇰🇼 +965" {{ old('country_code') == '🇰🇼 +965' ? 'selected' : '' }}>🇰🇼 +965 (Kuwait)</option>
                            <option value="🇧🇭 +973" {{ old('country_code') == '🇧🇭 +973' ? 'selected' : '' }}>🇧🇭 +973 (Bahrain)</option>
                            <option value="🇴🇲 +968" {{ old('country_code') == '🇴🇲 +968' ? 'selected' : '' }}>🇴🇲 +968 (Oman)</option>
                            <option value="🇺🇸 +1" {{ old('country_code') == '🇺🇸 +1' ? 'selected' : '' }}>🇺🇸 +1 (USA)</option>
                            <option value="🇬🇧 +44" {{ old('country_code') == '🇬🇧 +44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                            <option value="🇮🇳 +91" {{ old('country_code') == '🇮🇳 +91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                            <option value="🇵🇰 +92" {{ old('country_code') == '🇵🇰 +92' ? 'selected' : '' }}>🇵🇰 +92 (Pakistan)</option>
                            <option value="🇪🇬 +20" {{ old('country_code') == '🇪🇬 +20' ? 'selected' : '' }}>🇪🇬 +20 (Egypt)</option>
                        </select>
                    </div>
                    <div class="phone-number-input">
                        <input type="tel" id="mobile_no" name="mobile_no" class="@error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no') }}" required minlength="7" maxlength="15" placeholder="50 123 4567" />
                    </div>
                </div>
                @error('country_code')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror
                @error('mobile_no')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror

                <label for="email_id">Email Id <sup class="text-danger">*</sup></label>
                <input type="email" id="email_id" name="email_id" class="@error('email_id') is-invalid @enderror" value="{{ old('email_id') }}" required placeholder="e.g., info@company.com" />
                @error('email_id')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror


                <label for="country">Country <sup class="text-danger">*</sup></label>
                <select id="country" name="country" class="@error('country') is-invalid @enderror" required>
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->name }}" {{ old('country') == $country->name ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror

                <label for="city">City <sup class="text-danger">*</sup></label>
                <select id="city" name="city" class="@error('city') is-invalid @enderror" required>
                    <option value="">Select City</option>
                </select>
                @error('city')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror


                <label for="password">Password <sup class="text-danger">*</sup></label>
                <input type="password" id="password" name="password" class="@error('password') is-invalid @enderror" required minlength="8" />
                @error('password')
                    <div class="text-danger small mt-1 mb-2">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mb-3">Must be at least 8 characters with uppercase, lowercase, and number</small>

                <label for="confirm-password">Confirm Password <sup class="text-danger">*</sup></label>
                <input type="password" id="confirm-password" name="password_confirmation" class="@error('password_confirmation') is-invalid @enderror" required minlength="8" />
                <div id="passwordMatchError" class="text-danger small mt-1 mb-2" style="display: none;">Passwords do not match</div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-building"></i> Register as Employer
                </button>
            </form>
            
            <div class="extra-links">
                <p>Already have an account? <a href="{{ route('employer.login') }}">Login here</a></p>
                <p class="mt-2">Looking for a job? <a href="{{ route('jobseeker.register') }}">Register as Job Seeker</a></p>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Form Container Responsive */
.form-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
    font-size: 28px;
}

/* Form Elements */
.register-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 16px;
}

.register-form input,
.register-form select,
.register-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    margin-bottom: 15px;
    transition: border-color 0.3s ease;
}

.register-form input:focus,
.register-form select:focus,
.register-form textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* Phone Input Group */
.phone-input-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.country-code-selector {
    flex: 0 0 200px;
}

.country-code-selector select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 14px;
    font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;
}

.country-code-selector select option {
    font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;
    font-size: 16px;
    padding: 8px;
    background: white;
    color: #333;
}

/* Ensure emojis are displayed properly */
.country-code-selector select,
.country-code-selector select option,
.country-flag {
    -webkit-font-feature-settings: "liga" 1;
    font-feature-settings: "liga" 1;
    text-rendering: optimizeLegibility;
}

.phone-number-input {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}

.phone-number-input input[type="tel"] {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 15px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.submit-btn:hover {
    background: #0056b3;
}

.submit-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
}

/* Extra Links */
.extra-links {
    text-align: center;
    margin-top: 20px;
}

.extra-links p {
    margin-bottom: 10px;
    color: #666;
}

.extra-links a {
    color: #007bff;
    text-decoration: none;
}

.extra-links a:hover {
    text-decoration: underline;
}

/* Error Messages */
.text-danger {
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .form-container {
        padding: 20px 15px;
        margin: 0 15px;
    }
    
    .form-container h2 {
        font-size: 24px;
    }
    
    .phone-input-group {
        flex-direction: column;
        gap: 10px;
    }
    
    .country-code-selector {
        flex: none;
        width: 100%;
    }
    
    .phone-number-input {
        flex: none;
        width: 100%;
    }
    
    .submit-btn {
        font-size: 16px;
        padding: 12px;
    }
}

@media (max-width: 480px) {
    .form-container {
        padding: 15px 10px;
        margin: 0 10px;
    }
    
    .form-container h2 {
        font-size: 22px;
    }
    
    .register-form input,
    .register-form select,
    .register-form textarea {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

.phone-input-group input[type="tel"]:focus,
.country-code-selector select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

@media (max-width: 768px) {
    .phone-input-group {
        flex-direction: column;
        gap: 8px;
    }
    
    .country-code-selector {
        flex: none;
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employerRegisterForm');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('confirm-password');
    const passwordMatchError = document.getElementById('passwordMatchError');
    
    // Password match validation
    function checkPasswordMatch() {
        if (passwordConfirmation.value === '') {
            passwordMatchError.style.display = 'none';
            return;
        }
        
        if (password.value !== passwordConfirmation.value) {
            passwordMatchError.style.display = 'block';
            passwordConfirmation.setCustomValidity('Passwords do not match');
        } else {
            passwordMatchError.style.display = 'none';
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', checkPasswordMatch);
    passwordConfirmation.addEventListener('input', checkPasswordMatch);
    
    // Password strength validation
    password.addEventListener('input', function() {
        const value = this.value;
        const hasUpperCase = /[A-Z]/.test(value);
        const hasLowerCase = /[a-z]/.test(value);
        const hasNumber = /\d/.test(value);
        const isLongEnough = value.length >= 8;
        
        if (value && (!hasUpperCase || !hasLowerCase || !hasNumber || !isLongEnough)) {
            this.setCustomValidity('Password must contain uppercase, lowercase, number, and be 8+ characters');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.setCustomValidity('Please enter a valid email address');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // URL validation (inline, no alert loop)
    const websiteInput = document.getElementById('website');
    if (websiteInput) {
        const requiresHttp = /^(https?:\/\/).+/i;
        websiteInput.addEventListener('input', function() {
            if (this.value && !requiresHttp.test(this.value)) {
                this.setCustomValidity('Website URL must start with http:// or https://');
            } else {
                this.setCustomValidity('');
            }
        });
        websiteInput.addEventListener('blur', function() {
            if (this.value && !requiresHttp.test(this.value)) {
                this.reportValidity();
            }
        });
    }
    
    // Country code change handler
    const countryCodeSelect = document.getElementById('country_code');

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields');
            return false;
        }
        
        if (password.value !== passwordConfirmation.value) {
            e.preventDefault();
            alert('Passwords do not match');
            passwordConfirmation.focus();
            return false;
        }
    });
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');
    
    // Handle country selection change
    countrySelect.addEventListener('change', function() {
        const selectedCountry = this.value;
        
        // Clear city dropdown
        citySelect.innerHTML = '<option value="">Select City</option>';
        
        if (selectedCountry) {
            // Show loading state
            citySelect.innerHTML = '<option value="">Loading cities...</option>';
            citySelect.disabled = true;
            
            // Fetch cities for selected country
            fetch(`/api/cities/${encodeURIComponent(selectedCountry)}`)
                .then(response => response.json())
                .then(data => {
                    citySelect.innerHTML = '<option value="">Select City</option>';
                    
                    if (data.success && data.cities.length > 0) {
                        data.cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
                            option.textContent = city.name;
                            
                            // Check if this city was previously selected (for form validation errors)
                            if ('{{ old("city") }}' === city.name) {
                                option.selected = true;
                            }
                            
                            citySelect.appendChild(option);
                        });
                    } else {
                        citySelect.innerHTML = '<option value="">No cities available</option>';
                    }
                    
                    citySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error fetching cities:', error);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    citySelect.disabled = false;
                });
        }
    });
    
    // If a country is already selected (form validation error), load its cities
    if (countrySelect.value) {
        countrySelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush

@push('styles')
<style>
/* Hide search section on employer register page */
.search-wrap {
    display: none !important;
}
</style>
@endpush
@endsection
