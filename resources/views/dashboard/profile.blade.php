@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>My Profile</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Profile</li>
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
                        <h3 class="card-title">Update Profile</h3>
                    </div>
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="item-all-cat">
                            <div class="contact-form">
                                <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Name <sup class="text-danger">*</sup></label>
                                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Full Name" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Email Address <sup class="text-danger">*</sup></label>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="Email" required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Phone Number <sup class="text-danger">*</sup></label>
                                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Your Phone" required>
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        @if(auth()->user()->isSeeker())
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Full Name <sup class="text-danger">*</sup></label>
                                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $user->seekerProfile->full_name ?? '') }}" required>
                                                @error('full_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $user->seekerProfile && $user->seekerProfile->date_of_birth ? $user->seekerProfile->date_of_birth->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}">
                                                @error('date_of_birth')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control @error('gender') is-invalid @enderror">
                                                    <option value="">Select Gender</option>
                                                    <option value="male" {{ old('gender', $user->seekerProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                    <option value="female" {{ old('gender', $user->seekerProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                    <option value="other" {{ old('gender', $user->seekerProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Nationality</label>
                                                <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" value="{{ old('nationality', $user->seekerProfile->nationality ?? '') }}" placeholder="Nationality">
                                                @error('nationality')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->seekerProfile->city ?? '') }}" placeholder="City">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>State</label>
                                                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $user->seekerProfile->state ?? '') }}" placeholder="State">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $user->seekerProfile->country ?? '') }}" placeholder="Country">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Current Position</label>
                                                <input type="text" name="current_position" class="form-control @error('current_position') is-invalid @enderror" value="{{ old('current_position', $user->seekerProfile->current_position ?? '') }}" placeholder="Job Title">
                                                @error('current_position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Years of Experience</label>
                                                <input type="text" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $user->seekerProfile->experience_years ?? '') }}" placeholder="e.g., 2-5 years">
                                                @error('experience_years')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Expected Salary</label>
                                                <input type="text" name="expected_salary" class="form-control @error('expected_salary') is-invalid @enderror" value="{{ old('expected_salary', $user->seekerProfile->expected_salary ?? '') }}" placeholder="e.g., 10000-15000">
                                                @error('expected_salary')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Bio (Max 1000 characters)</label>
                                                <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="Tell us about yourself" maxlength="1000">{{ old('bio', $user->seekerProfile->bio ?? '') }}</textarea>
                                                <small class="text-muted">{{ strlen($user->seekerProfile->bio ?? '') }}/1000 characters</small>
                                                @error('bio')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Profile Picture (Max 2MB)</label>
                                                <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                                @if($user->seekerProfile && $user->seekerProfile->profile_picture)
                                                    <small class="text-muted d-block mt-1">Current: <a href="{{ Storage::url($user->seekerProfile->profile_picture) }}" target="_blank">View</a></small>
                                                @endif
                                                @error('profile_picture')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Upload CV (PDF, DOC, DOCX - Max 5MB)</label>
                                                <input type="file" name="cv_file" class="form-control @error('cv_file') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                                @if($user->seekerProfile && $user->seekerProfile->cv_file)
                                                    <small class="text-muted d-block mt-1">Current: <a href="{{ Storage::url($user->seekerProfile->cv_file) }}" target="_blank">Download CV</a></small>
                                                @endif
                                                @error('cv_file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        @if(auth()->user()->isEmployer())
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Company Name <sup class="text-danger">*</sup></label>
                                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $user->employerProfile->company_name ?? '') }}" placeholder="Company Name" required>
                                                @error('company_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Company Website</label>
                                                <input type="url" name="company_website" class="form-control @error('company_website') is-invalid @enderror" value="{{ old('company_website', $user->employerProfile->company_website ?? '') }}" placeholder="https://example.com">
                                                @error('company_website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Industry</label>
                                                <input type="text" name="industry" class="form-control @error('industry') is-invalid @enderror" value="{{ old('industry', $user->employerProfile->industry ?? '') }}" placeholder="Industry">
                                                @error('industry')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Company Size</label>
                                                <select name="company_size" class="form-control @error('company_size') is-invalid @enderror">
                                                    <option value="">Select Size</option>
                                                    <option value="1-10" {{ old('company_size', $user->employerProfile->company_size ?? '') == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                                    <option value="11-50" {{ old('company_size', $user->employerProfile->company_size ?? '') == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                                    <option value="51-200" {{ old('company_size', $user->employerProfile->company_size ?? '') == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                                    <option value="201-500" {{ old('company_size', $user->employerProfile->company_size ?? '') == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                                    <option value="500+" {{ old('company_size', $user->employerProfile->company_size ?? '') == '500+' ? 'selected' : '' }}>500+ employees</option>
                                                </select>
                                                @error('company_size')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Founded Year</label>
                                                <input type="number" name="founded_year" class="form-control @error('founded_year') is-invalid @enderror" value="{{ old('founded_year', $user->employerProfile->founded_year ?? '') }}" placeholder="e.g., 2010" min="1900" max="{{ date('Y') }}">
                                                @error('founded_year')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Company Description (Max 2000 characters)</label>
                                                <textarea name="company_description" class="form-control @error('company_description') is-invalid @enderror" rows="4" placeholder="Describe your company" maxlength="2000">{{ old('company_description', $user->employerProfile->company_description ?? '') }}</textarea>
                                                <small class="text-muted">{{ strlen($user->employerProfile->company_description ?? '') }}/2000 characters</small>
                                                @error('company_description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Company Logo (Max 2MB)</label>
                                                <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                                @if($user->employerProfile && $user->employerProfile->company_logo)
                                                    <small class="text-muted d-block mt-1">Current: <a href="{{ Storage::url($user->employerProfile->company_logo) }}" target="_blank">View Logo</a></small>
                                                @endif
                                                @error('company_logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-lg-12">
                                            <div class="text-end mt-4">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-save"></i> Update Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    
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
        
        const emailField = form.querySelector('[type="email"]');
        if (emailField && emailField.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailField.value)) {
                isValid = false;
                emailField.classList.add('is-invalid');
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields correctly');
        }
    });
});
</script>
@endpush
@endsection
