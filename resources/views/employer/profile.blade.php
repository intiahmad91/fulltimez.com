@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Employer Profile')

@section('content')
<section class="pagecontent dashboard_wrap">
    <div class="container">
        <div class="row contactWrp">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Update Company Profile</h3>
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
                                <form id="employerProfileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Contact Information Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3"><i class="fas fa-user me-2"></i>Contact Information</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Contact Person Name <sup class="text-danger">*</sup></label>
                                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="Full Name" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Profile Picture</label>
                                                    <input type="file" name="profile_picture" id="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                                                    @error('profile_picture')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    @if($user->employerProfile && $user->employerProfile->profile_picture)
                                                        <div class="mt-2" id="current_profile_preview">
                                                            <img src="{{ asset($user->employerProfile->profile_picture) }}" alt="Current Profile Picture" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                            <small class="text-muted d-block">Current profile picture</small>
                                                        </div>
                                                    @endif
                                                    <div id="profile_picture_preview" class="file-preview" style="display: none; margin-top: 8px; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                                                        <img id="profile_picture_img" src="" alt="Profile Picture Preview" style="max-width: 100px; max-height: 100px; border-radius: 4px; display: block;">
                                                        <div id="profile_picture_info" class="file-info" style="margin-top: 6px; font-size: 12px;"></div>
                                                    </div>
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
                                                    <div class="phone-input-group" style="display: flex; gap: 10px; align-items: center;">
                                                        <div class="country-code-selector" style="flex: 0 0 200px;">
                                                            <select name="phone_country_code" id="phone_country_code" class="form-control @error('phone_country_code') is-invalid @enderror" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;">
                                                                <option value="🇦🇪 +971" {{ old('phone_country_code', $phoneData['country_code'] ?? '🇦🇪 +971') == '🇦🇪 +971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                                                <option value="🇸🇦 +966" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇸🇦 +966' ? 'selected' : '' }}>🇸🇦 +966 (Saudi Arabia)</option>
                                                                <option value="🇶🇦 +974" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇶🇦 +974' ? 'selected' : '' }}>🇶🇦 +974 (Qatar)</option>
                                                                <option value="🇰🇼 +965" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇰🇼 +965' ? 'selected' : '' }}>🇰🇼 +965 (Kuwait)</option>
                                                                <option value="🇧🇭 +973" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇧🇭 +973' ? 'selected' : '' }}>🇧🇭 +973 (Bahrain)</option>
                                                                <option value="🇴🇲 +968" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇴🇲 +968' ? 'selected' : '' }}>🇴🇲 +968 (Oman)</option>
                                                                <option value="🇺🇸 +1" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇺🇸 +1' ? 'selected' : '' }}>🇺🇸 +1 (USA)</option>
                                                                <option value="🇬🇧 +44" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇬🇧 +44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                                                <option value="🇮🇳 +91" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇮🇳 +91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                                                                <option value="🇵🇰 +92" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇵🇰 +92' ? 'selected' : '' }}>🇵🇰 +92 (Pakistan)</option>
                                                                <option value="🇪🇬 +20" {{ old('phone_country_code', $phoneData['country_code'] ?? '') == '🇪🇬 +20' ? 'selected' : '' }}>🇪🇬 +20 (Egypt)</option>
                                                            </select>
                                                        </div>
                                                        <div class="phone-number-input" style="flex: 1;">
                                                            <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $phoneData['number'] ?? $user->phone ?? '') }}" placeholder="50 123 4567" required minlength="7" maxlength="15">
                                                        </div>
                                                    </div>
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    @error('phone_country_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Company Contact Details Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3"><i class="fas fa-phone me-2"></i>Company Contact Details</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Mobile No <sup class="text-danger">*</sup></label>
                                                    <div class="phone-input-group" style="display: flex; gap: 10px; align-items: center;">
                                                        <div class="country-code-selector" style="flex: 0 0 200px;">
                                                            <select name="mobile_country_code" id="mobile_country_code" class="form-control @error('mobile_country_code') is-invalid @enderror" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;">
                                                                <option value="🇦🇪 +971" {{ old('mobile_country_code', $mobileData['country_code'] ?? '🇦🇪 +971') == '🇦🇪 +971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                                                <option value="🇸🇦 +966" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇸🇦 +966' ? 'selected' : '' }}>🇸🇦 +966 (Saudi Arabia)</option>
                                                                <option value="🇶🇦 +974" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇶🇦 +974' ? 'selected' : '' }}>🇶🇦 +974 (Qatar)</option>
                                                                <option value="🇰🇼 +965" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇰🇼 +965' ? 'selected' : '' }}>🇰🇼 +965 (Kuwait)</option>
                                                                <option value="🇧🇭 +973" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇧🇭 +973' ? 'selected' : '' }}>🇧🇭 +973 (Bahrain)</option>
                                                                <option value="🇴🇲 +968" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇴🇲 +968' ? 'selected' : '' }}>🇴🇲 +968 (Oman)</option>
                                                                <option value="🇺🇸 +1" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇺🇸 +1' ? 'selected' : '' }}>🇺🇸 +1 (USA)</option>
                                                                <option value="🇬🇧 +44" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇬🇧 +44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                                                <option value="🇮🇳 +91" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇮🇳 +91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                                                                <option value="🇵🇰 +92" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇵🇰 +92' ? 'selected' : '' }}>🇵🇰 +92 (Pakistan)</option>
                                                                <option value="🇪🇬 +20" {{ old('mobile_country_code', $mobileData['country_code'] ?? '') == '🇪🇬 +20' ? 'selected' : '' }}>🇪🇬 +20 (Egypt)</option>
                                                            </select>
                                                        </div>
                                                        <div class="phone-number-input" style="flex: 1;">
                                                            <input type="tel" name="mobile_no" id="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" value="{{ old('mobile_no', $mobileData['number'] ?? '') }}" placeholder="50 123 4567" required minlength="7" maxlength="15">
                                                        </div>
                                                    </div>
                                                    @error('mobile_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    @error('mobile_country_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Email Id <sup class="text-danger">*</sup></label>
                                                    <input type="email" name="email_id" class="form-control @error('email_id') is-invalid @enderror" value="{{ old('email_id', $user->employerProfile->email_id ?? '') }}" placeholder="e.g., info@company.com" required>
                                                    @error('email_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Landline No <sup class="text-danger">*</sup></label>
                                                    <div class="phone-input-group" style="display: flex; gap: 10px; align-items: center;">
                                                        <div class="country-code-selector" style="flex: 0 0 200px;">
                                                            <select name="landline_country_code" id="landline_country_code" class="form-control @error('landline_country_code') is-invalid @enderror" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;">
                                                                <option value="🇦🇪 +971" {{ old('landline_country_code', $landlineData['country_code'] ?? '🇦🇪 +971') == '🇦🇪 +971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                                                <option value="🇸🇦 +966" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇸🇦 +966' ? 'selected' : '' }}>🇸🇦 +966 (Saudi Arabia)</option>
                                                                <option value="🇶🇦 +974" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇶🇦 +974' ? 'selected' : '' }}>🇶🇦 +974 (Qatar)</option>
                                                                <option value="🇰🇼 +965" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇰🇼 +965' ? 'selected' : '' }}>🇰🇼 +965 (Kuwait)</option>
                                                                <option value="🇧🇭 +973" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇧🇭 +973' ? 'selected' : '' }}>🇧🇭 +973 (Bahrain)</option>
                                                                <option value="🇴🇲 +968" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇴🇲 +968' ? 'selected' : '' }}>🇴🇲 +968 (Oman)</option>
                                                                <option value="🇺🇸 +1" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇺🇸 +1' ? 'selected' : '' }}>🇺🇸 +1 (USA)</option>
                                                                <option value="🇬🇧 +44" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇬🇧 +44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                                                <option value="🇮🇳 +91" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇮🇳 +91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                                                                <option value="🇵🇰 +92" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇵🇰 +92' ? 'selected' : '' }}>🇵🇰 +92 (Pakistan)</option>
                                                                <option value="🇪🇬 +20" {{ old('landline_country_code', $landlineData['country_code'] ?? '') == '🇪🇬 +20' ? 'selected' : '' }}>🇪🇬 +20 (Egypt)</option>
                                                            </select>
                                                        </div>
                                                        <div class="phone-number-input" style="flex: 1;">
                                                            <input type="tel" name="landline_no" id="landline_no" class="form-control @error('landline_no') is-invalid @enderror" value="{{ old('landline_no', $landlineData['number'] ?? '') }}" placeholder="4 123 4567" required minlength="7" maxlength="15">
                                                        </div>
                                                    </div>
                                                    @error('landline_no')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    @error('landline_country_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Company Information Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3"><i class="fas fa-building me-2"></i>Company Information</h5>
                                        <div class="row">
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
                                                    <label>Industry <sup class="text-danger">*</sup></label>
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
                                                    <textarea name="company_description" class="form-control @error('company_description') is-invalid @enderror" rows="5" placeholder="Tell us about your company" maxlength="2000">{{ old('company_description', $user->employerProfile->company_description ?? '') }}</textarea>
                                                    <small class="text-muted">{{ strlen($user->employerProfile->company_description ?? '') }}/2000 characters</small>
                                                    @error('company_description')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Information Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3"><i class="fas fa-map-marker-alt me-2"></i>Location Information</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Country <sup class="text-danger">*</sup></label>
                                                    <select id="country" name="country" class="form-control @error('country') is-invalid @enderror" required>
                                                        <option value="">Select Country</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->name }}" {{ old('country', $user->employerProfile->country ?? '') == $country->name ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('country')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>City <sup class="text-danger">*</sup></label>
                                                    <select id="city" name="city" class="form-control @error('city') is-invalid @enderror" required>
                                                        <option value="">Select City</option>
                                                    </select>
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Company Branding Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3"><i class="fas fa-image me-2"></i>Company Branding</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Company Logo (Max 2MB)</label>
                                                    <input type="file" name="company_logo" id="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                                    @if($user->employerProfile && $user->employerProfile->company_logo)
                                                        <div class="mt-2" id="current_logo_preview">
                                                            <img src="{{ asset($user->employerProfile->company_logo) }}" alt="Current Company Logo" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                                            <small class="text-muted d-block">Current logo</small>
                                                        </div>
                                                    @endif
                                                    <div id="company_logo_preview" class="file-preview" style="display: none; margin-top: 8px; padding: 8px; background: #f8f9fa; border-radius: 4px;">
                                                        <img id="company_logo_img" src="" alt="Logo Preview" style="max-width: 100px; max-height: 100px; border-radius: 4px; display: block;">
                                                        <div id="company_logo_info" class="file-info" style="margin-top: 6px; font-size: 12px;"></div>
                                                    </div>
                                                    @error('company_logo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save"></i> Update Profile
                                            </button>
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
    const form = document.getElementById('employerProfileForm');
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');
    const currentCity = '{{ old("city", $user->employerProfile->city ?? "") }}';
    const currentCountry = '{{ old("country", $user->employerProfile->country ?? "") }}';
    
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
                            
                            // Select current city if it matches
                            if (currentCity === city.name) {
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
        } else {
            citySelect.disabled = false;
        }
    });
    
    // If a country is already selected, load its cities
    if (currentCountry && countrySelect.value === currentCountry) {
        countrySelect.dispatchEvent(new Event('change'));
    } else if (countrySelect.value) {
        countrySelect.dispatchEvent(new Event('change'));
    }
    
    // Profile picture preview
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('profile_picture_preview');
            const img = document.getElementById('profile_picture_img');
            const info = document.getElementById('profile_picture_info');
            const currentProfilePreview = document.getElementById('current_profile_preview');
            
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                
                if (!validTypes.includes(file.type)) {
                    alert('Please select a JPG, JPEG, or PNG image');
                    this.value = '';
                    if (preview) preview.style.display = 'none';
                    return;
                }
                
                if (fileSize > 2) {
                    alert('Profile picture must not exceed 2MB');
                    this.value = '';
                    if (preview) preview.style.display = 'none';
                    return;
                }
                
                // Hide current profile preview if exists
                if (currentProfilePreview) {
                    currentProfilePreview.style.display = 'none';
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (img) img.src = e.target.result;
                    if (info) info.innerHTML = `<strong>${file.name}</strong><br>Size: ${fileSize.toFixed(2)} MB`;
                    if (preview) preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                if (preview) preview.style.display = 'none';
                if (currentProfilePreview) currentProfilePreview.style.display = 'block';
            }
        });
    }
    
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
    
    const logoInput = document.getElementById('company_logo');
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            const preview = document.getElementById('company_logo_preview');
            const img = document.getElementById('company_logo_img');
            const info = document.getElementById('company_logo_info');
            const currentLogoPreview = document.getElementById('current_logo_preview');
            
            if (file) {
                const fileSize = file.size / 1024 / 1024; // in MB
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload JPG or PNG image only');
                    this.value = '';
                    if (preview) preview.style.display = 'none';
                    return false;
                }
                
                if (fileSize > 2) {
                    alert('Logo file size must not exceed 2MB');
                    this.value = '';
                    if (preview) preview.style.display = 'none';
                    return false;
                }
                
                // Hide current logo preview if exists
                if (currentLogoPreview) {
                    currentLogoPreview.style.display = 'none';
                }
                
                // Show new preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (img) img.src = e.target.result;
                    if (info) info.innerHTML = `<strong>${file.name}</strong><br>Size: ${fileSize.toFixed(2)} MB`;
                    if (preview) preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                if (preview) preview.style.display = 'none';
                if (currentLogoPreview) currentLogoPreview.style.display = 'block';
            }
        });
    }
});
</script>

<!-- Document Verification Section -->
<section class="dashboard-section">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <div class="dashboard-content">
                    <div class="dashboard-panel">
                        <div class="panel-header">
                            <h4><i class="fas fa-file-alt"></i> Document Verification</h4>
                            <a href="{{ route('employer.documents.index') }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> View All Documents
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Required Documents</h6>
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
                                        <i class="fas fa-plus"></i> Submit Missing Documents
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.document-status-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.document-status-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.document-icon {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

@media (max-width: 768px) {
    .document-status-card .card-body {
        padding: 1rem;
    }
    
    .phone-input-group {
        flex-direction: column;
        gap: 10px;
    }
    
    .country-code-selector {
        flex: none !important;
        width: 100%;
    }
    
    .phone-number-input {
        flex: none !important;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .phone-input-group {
        gap: 8px;
    }
    
    .country-code-selector select,
    .phone-number-input input {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

.phone-input-group input[type="tel"]:focus,
.country-code-selector select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.phone-input-group input[type="tel"] {
    position: relative;
    top: 10px;
}
</style>
@endpush
@endsection


