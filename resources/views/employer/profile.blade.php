@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Employer Profile')

@section('content')
<style>
/* Professional Employer Profile Styles */
.profile-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px 0;
}

.profile-header {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    padding: 30px;
}

.profile-info {
    display: flex;
    align-items: center;
    gap: 25px;
    flex-wrap: wrap;
}

.profile-avatar-section {
    position: relative;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.default-avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-details h1 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.profile-details p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0 0 10px 0;
}

.profile-badge {
    display: inline-block;
    background: #3498db;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.profile-form-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
    margin-bottom: 30px;
}

.form-section {
    margin-bottom: 40px;
}

.section-title {
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ecf0f1;
}

.section-title i {
    color: #3498db;
    font-size: 18px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
    display: block;
}

.form-control {
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #ffffff;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    outline: none;
}

.form-control.is-invalid {
    border-color: #e74c3c;
}

.form-control.is-invalid:focus {
    border-color: #e74c3c;
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.invalid-feedback {
    color: #e74c3c;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.text-muted {
    color: #7f8c8d !important;
    font-size: 12px;
}

.btn-primary {
    background: #3498db;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.alert {
    border-radius: 8px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 25px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.file-upload-info {
    background: #f8f9fa;
    border: 1px solid #ecf0f1;
    border-radius: 6px;
    padding: 10px 15px;
    margin-top: 8px;
    font-size: 12px;
}

.file-upload-info a {
    color: #3498db;
    text-decoration: none;
}

.file-upload-info a:hover {
    text-decoration: underline;
}

.character-count {
    text-align: right;
    font-size: 12px;
    color: #7f8c8d;
    margin-top: 5px;
}

.current-image {
    margin-top: 10px;
}

.current-image img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 8px;
    border: 2px solid #ecf0f1;
}

/* Document Verification Styles */
.document-verification-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
}

.document-status-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
    height: 100%;
}

.document-status-card:hover {
    border-color: #3498db;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.1);
}

.document-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge-warning {
    background-color: #f39c12;
    color: #fff;
}

.badge-success {
    background-color: #27ae60;
    color: #fff;
}

.badge-danger {
    background-color: #e74c3c;
    color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-info {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-form-card,
    .document-verification-card {
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .btn-primary {
        width: 100%;
    }
}
</style>

<section class="profile-container">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-info">
                        <div class="profile-avatar-section">
                            @php
                                $profileImage = null;
                                
                                if(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo) {
                                    $profileImage = auth()->user()->employerProfile->company_logo;
                                } elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->profile_picture) {
                                    $profileImage = auth()->user()->employerProfile->profile_picture;
                                }
                            @endphp
                            
                            @if($profileImage)
                                <img src="{{ Storage::url($profileImage) }}" class="profile-avatar" alt="Company Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="default-avatar-large" style="display: none;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @else
                                <div class="default-avatar-large">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="profile-details">
                            <h1>{{ auth()->user()->name }}</h1>
                            <p>{{ auth()->user()->email }}</p>
                            <span class="profile-badge">{{ ucfirst(auth()->user()->role->slug) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="profile-form-card">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="employerProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-user"></i>
                                Contact Information
                            </h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Contact Person Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Enter contact person name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <input type="file" name="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                        <div class="text-muted mt-1">Max 2MB, JPG/PNG format</div>
                                        @if($user->employerProfile && $user->employerProfile->profile_picture)
                                            <div class="current-image">
                                                <img src="{{ Storage::url($user->employerProfile->profile_picture) }}" alt="Current Profile Picture">
                                                <div class="text-muted">Current profile picture</div>
                                            </div>
                                        @endif
                                        @error('profile_picture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Contact Details Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-phone"></i>
                                Company Contact Details
                            </h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Mobile Number <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no', $user->employerProfile->mobile_no ?? '') }}" placeholder="e.g., +971 50 123 4567" required>
                                        @error('mobile_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Company Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email_id" class="form-control @error('email_id') is-invalid @enderror" value="{{ old('email_id', $user->employerProfile->email_id ?? '') }}" placeholder="e.g., info@company.com" required>
                                        @error('email_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Landline Number <span class="text-danger">*</span></label>
                                        <input type="text" name="landline_no" class="form-control @error('landline_no') is-invalid @enderror" value="{{ old('landline_no', $user->employerProfile->landline_no ?? '') }}" placeholder="e.g., +971 4 123 4567" required>
                                        @error('landline_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-building"></i>
                                Company Information
                            </h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $user->employerProfile->company_name ?? '') }}" placeholder="Enter company name" required>
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
                                        <label>Industry <span class="text-danger">*</span></label>
                                        <select name="industry" class="form-control @error('industry') is-invalid @enderror" required>
                                            <option value="">Select Industry</option>
                                            <option value="Technology" {{ old('industry', $user->employerProfile->industry ?? '') == 'Technology' ? 'selected' : '' }}>Technology</option>
                                            <option value="Finance" {{ old('industry', $user->employerProfile->industry ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                            <option value="Healthcare" {{ old('industry', $user->employerProfile->industry ?? '') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                            <option value="Education" {{ old('industry', $user->employerProfile->industry ?? '') == 'Education' ? 'selected' : '' }}>Education</option>
                                            <option value="Retail" {{ old('industry', $user->employerProfile->industry ?? '') == 'Retail' ? 'selected' : '' }}>Retail</option>
                                            <option value="Manufacturing" {{ old('industry', $user->employerProfile->industry ?? '') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                            <option value="Construction" {{ old('industry', $user->employerProfile->industry ?? '') == 'Construction' ? 'selected' : '' }}>Construction</option>
                                            <option value="Hospitality" {{ old('industry', $user->employerProfile->industry ?? '') == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                                            <option value="Other" {{ old('industry', $user->employerProfile->industry ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('industry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Company Size</label>
                                        <select name="company_size" class="form-control @error('company_size') is-invalid @enderror">
                                            <option value="">Select Company Size</option>
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
                                        <label>Company Description</label>
                                        <textarea name="company_description" class="form-control @error('company_description') is-invalid @enderror" rows="4" placeholder="Describe your company, its mission, and what makes it unique" maxlength="2000">{{ old('company_description', $user->employerProfile->company_description ?? '') }}</textarea>
                                        <div class="character-count">{{ strlen($user->employerProfile->company_description ?? '') }}/2000 characters</div>
                                        @error('company_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location Information Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-map-marker-alt"></i>
                                Location Information
                            </h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select name="city" class="form-control @error('city') is-invalid @enderror" required>
                                            <option value="">Select City</option>
                                            <option value="Abu Dhabi" {{ old('city', $user->employerProfile->city ?? '') == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                            <option value="Dubai" {{ old('city', $user->employerProfile->city ?? '') == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                            <option value="Sharjah" {{ old('city', $user->employerProfile->city ?? '') == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                            <option value="Ajman" {{ old('city', $user->employerProfile->city ?? '') == 'Ajman' ? 'selected' : '' }}>Ajman</option>
                                            <option value="Ras Al Khaimah" {{ old('city', $user->employerProfile->city ?? '') == 'Ras Al Khaimah' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                            <option value="Fujairah" {{ old('city', $user->employerProfile->city ?? '') == 'Fujairah' ? 'selected' : '' }}>Fujairah</option>
                                            <option value="Umm Al Quwain" {{ old('city', $user->employerProfile->city ?? '') == 'Umm Al Quwain' ? 'selected' : '' }}>Umm Al Quwain</option>
                                            <option value="Other" {{ old('city', $user->employerProfile->city ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $user->employerProfile->state ?? '') }}" placeholder="Enter state">
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Country <span class="text-danger">*</span></label>
                                        <select name="country" class="form-control @error('country') is-invalid @enderror" required>
                                            <option value="">Select Country</option>
                                            <option value="United Arab Emirates" {{ old('country', $user->employerProfile->country ?? '') == 'United Arab Emirates' ? 'selected' : '' }}>United Arab Emirates</option>
                                            <option value="Saudi Arabia" {{ old('country', $user->employerProfile->country ?? '') == 'Saudi Arabia' ? 'selected' : '' }}>Saudi Arabia</option>
                                            <option value="Qatar" {{ old('country', $user->employerProfile->country ?? '') == 'Qatar' ? 'selected' : '' }}>Qatar</option>
                                            <option value="Kuwait" {{ old('country', $user->employerProfile->country ?? '') == 'Kuwait' ? 'selected' : '' }}>Kuwait</option>
                                            <option value="Bahrain" {{ old('country', $user->employerProfile->country ?? '') == 'Bahrain' ? 'selected' : '' }}>Bahrain</option>
                                            <option value="Oman" {{ old('country', $user->employerProfile->country ?? '') == 'Oman' ? 'selected' : '' }}>Oman</option>
                                            <option value="Other" {{ old('country', $user->employerProfile->country ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Branding Section -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-image"></i>
                                Company Branding
                            </h3>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Company Logo</label>
                                        <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                        <div class="text-muted mt-1">Max 2MB, JPG/PNG format</div>
                                        @if($user->employerProfile && $user->employerProfile->company_logo)
                                            <div class="file-upload-info">
                                                Current: <a href="{{ Storage::url($user->employerProfile->company_logo) }}" target="_blank">View Current Logo</a>
                                            </div>
                                        @endif
                                        @error('company_logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Update Company Profile
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Document Verification Section -->
                <div class="document-verification-card">
                    <h3 class="section-title">
                        <i class="fas fa-file-alt"></i>
                        Document Verification
                    </h3>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Required Documents</h6>
                        <p class="mb-0">Please submit the following documents for verification to complete your employer profile:</p>
                    </div>

                    @php
                        $user = auth()->user();
                        $documents = $user->employerDocuments;
                        $requiredTypes = ['trade_license', 'office_landline', 'company_email'];
                        $submittedTypes = $documents->pluck('document_type')->toArray();
                    @endphp

                    <div class="row">
                        @foreach($requiredTypes as $type)
                            @php
                                $document = $documents->where('document_type', $type)->first();
                                $typeName = match($type) {
                                    'trade_license' => 'Trade License',
                                    'office_landline' => 'Office Landline',
                                    'company_email' => 'Company Email',
                                    default => ucfirst(str_replace('_', ' ', $type))
                                };
                            @endphp
                            <div class="col-md-4 mb-3">
                                <div class="card document-status-card">
                                    <div class="card-body text-center">
                                        <div class="document-icon mb-3">
                                            @if($document)
                                                @if($document->status === 'approved')
                                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                                @elseif($document->status === 'rejected')
                                                    <i class="fas fa-times-circle fa-3x text-danger"></i>
                                                @else
                                                    <i class="fas fa-clock fa-3x text-warning"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-file-alt fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title">{{ $typeName }}</h6>
                                        @if($document)
                                            <span class="badge {{ $document->status_badge_class }} mb-2">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                            <p class="text-muted small">
                                                @if($document->status === 'pending')
                                                    Under Review
                                                @elseif($document->status === 'approved')
                                                    Verified
                                                @else
                                                    Needs Resubmission
                                                @endif
                                            </p>
                                            <a href="{{ route('employer.documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                                                View Details
                                            </a>
                                        @else
                                            <p class="text-muted small">Not Submitted</p>
                                            <a href="{{ route('employer.documents.create') }}?type={{ $type }}" class="btn btn-sm btn-primary">
                                                Submit Now
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(count($submittedTypes) < count($requiredTypes))
                        <div class="text-center mt-4">
                            <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Submit Missing Documents
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employerProfileForm');
    
    // Character count for textareas
    const textareas = form.querySelectorAll('textarea[maxlength]');
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = textarea.parentNode.querySelector('.character-count');
        
        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counter.textContent = `${currentLength}/${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                counter.style.color = '#e74c3c';
            } else {
                counter.style.color = '#7f8c8d';
            }
        });
    });
    
    // Form validation
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
        
        // Email validation
        const emailFields = form.querySelectorAll('[type="email"]');
        emailFields.forEach(emailField => {
            if (emailField && emailField.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('is-invalid');
                }
            }
        });
        
        // URL validation
        const urlField = form.querySelector('[name="company_website"]');
        if (urlField && urlField.value) {
            const urlRegex = /^https?:\/\/.+/;
            if (!urlRegex.test(urlField.value)) {
                isValid = false;
                alert('Company website must start with http:// or https://');
                urlField.focus();
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields correctly');
        }
    });
    
    // File validation
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = file.size / 1024 / 1024;
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                
                if (fileSize > 2) {
                    alert('File size must not exceed 2MB');
                    this.value = '';
                    return false;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload JPG or PNG image only');
                    this.value = '';
                    return false;
                }
            }
        });
    });
});
</script>
@endpush
@endsection


