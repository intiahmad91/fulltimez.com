@extends('layouts.app')

@section('title', 'Submit Document')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Submit Document</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employer.documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Submit Document</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="dashboard-section">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')

            <div class="col-lg-9">
                <div class="dashboard-content">
                    <div class="dashboard-panel">
                        <div class="panel-header">
                            <div>
                                <h4>Submit New Document</h4>
                                <p class="text-muted" style="font-size: 14px; margin: 5px 0 0 0;">Upload company documents for verification</p>
                            </div>
                            <a href="{{ route('employer.documents.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Documents
                            </a>
                        </div>
                        <div class="panel-body">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('employer.documents.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- Trade License Section -->
                                <div class="document-section mb-5">
                                    <h5 class="section-title">
                                        <i class="fas fa-file-alt"></i> Trade License
                                        @if(in_array('trade_license', $rejectedDocuments))
                                            <span class="badge bg-danger ms-2">Rejected - Resubmit</span>
                                        @elseif(in_array('trade_license', $existingDocuments))
                                            <span class="badge bg-success ms-2">Already Submitted</span>
                                        @endif
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="trade_license_file" class="form-label">Trade License File</label>
                                                <input type="file" name="trade_license_file" id="trade_license_file" class="form-control @error('trade_license_file') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                                <small class="form-text text-muted">Accepted formats: PDF, JPG, JPEG, PNG (Max: 2MB)</small>
                                                @error('trade_license_file')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="trade_license_number" class="form-label">License Number</label>
                                                <input type="text" name="trade_license_number" id="trade_license_number" class="form-control @error('trade_license_number') is-invalid @enderror" value="{{ old('trade_license_number') }}" placeholder="Enter trade license number">
                                                @error('trade_license_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Office Landline Section -->
                                <div class="document-section mb-5">
                                    <h5 class="section-title">
                                        <i class="fas fa-phone"></i> Office Landline
                                        @if(in_array('office_landline', $rejectedDocuments))
                                            <span class="badge bg-danger ms-2">Rejected - Resubmit</span>
                                        @elseif(in_array('office_landline', $existingDocuments))
                                            <span class="badge bg-success ms-2">Already Submitted</span>
                                        @endif
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label for="landline_number" class="form-label">Office Landline Number</label>
                                                <input type="text" name="landline_number" id="landline_number" class="form-control @error('landline_number') is-invalid @enderror" value="{{ old('landline_number') }}" placeholder="Enter office landline number">
                                                <small class="form-text text-muted">Include country code if applicable (e.g., +971 4 123 4567)</small>
                                                @error('landline_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Email Section -->
                                <div class="document-section mb-5">
                                    <h5 class="section-title">
                                        <i class="fas fa-envelope"></i> Company Email
                                        @if(in_array('company_email', $rejectedDocuments))
                                            <span class="badge bg-danger ms-2">Rejected - Resubmit</span>
                                        @elseif(in_array('company_email', $existingDocuments))
                                            <span class="badge bg-success ms-2">Already Submitted</span>
                                        @endif
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label for="company_email" class="form-label">Company Email Address</label>
                                                <input type="email" name="company_email" id="company_email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email') }}" placeholder="Enter official company email">
                                                <small class="form-text text-muted">This should be your official company email address</small>
                                                @error('company_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Company Information Section -->
                                <div class="document-section mb-5">
                                    <h5 class="section-title">
                                        <i class="fas fa-building"></i> Company Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="company_website" class="form-label">Company Website <span class="text-danger">*</span></label>
                                                <input type="text" name="company_website" id="company_website" class="form-control @error('company_website') is-invalid @enderror" value="{{ old('company_website') }}" placeholder="www.company.com or https://www.company.com">
                                                <small class="form-text text-muted">Enter your website URL (e.g., www.company.com or https://www.company.com). https:// will be added automatically if not provided.</small>
                                                @error('company_website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="contact_person_name" class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                                                <input type="text" name="contact_person_name" id="contact_person_name" class="form-control @error('contact_person_name') is-invalid @enderror" value="{{ old('contact_person_name') }}" placeholder="Enter contact person full name">
                                                @error('contact_person_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="contact_person_mobile" class="form-label">Contact Person Mobile <span class="text-danger">*</span></label>
                                                <div class="phone-input-group" style="display: flex; gap: 10px; align-items: center;">
                                                    <div class="country-code-selector" style="flex: 0 0 200px;">
                                                        <select name="contact_person_mobile_country_code" id="contact_person_mobile_country_code" class="form-control @error('contact_person_mobile_country_code') is-invalid @enderror" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-family: 'Segoe UI Emoji', 'Apple Color Emoji', 'Noto Color Emoji', sans-serif;">
                                                            <option value="🇦🇪 +971" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '🇦🇪 +971') == '🇦🇪 +971' ? 'selected' : '' }}>🇦🇪 +971 (UAE)</option>
                                                            <option value="🇸🇦 +966" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇸🇦 +966' ? 'selected' : '' }}>🇸🇦 +966 (Saudi Arabia)</option>
                                                            <option value="🇶🇦 +974" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇶🇦 +974' ? 'selected' : '' }}>🇶🇦 +974 (Qatar)</option>
                                                            <option value="🇰🇼 +965" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇰🇼 +965' ? 'selected' : '' }}>🇰🇼 +965 (Kuwait)</option>
                                                            <option value="🇧🇭 +973" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇧🇭 +973' ? 'selected' : '' }}>🇧🇭 +973 (Bahrain)</option>
                                                            <option value="🇴🇲 +968" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇴🇲 +968' ? 'selected' : '' }}>🇴🇲 +968 (Oman)</option>
                                                            <option value="🇺🇸 +1" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇺🇸 +1' ? 'selected' : '' }}>🇺🇸 +1 (USA)</option>
                                                            <option value="🇬🇧 +44" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇬🇧 +44' ? 'selected' : '' }}>🇬🇧 +44 (UK)</option>
                                                            <option value="🇮🇳 +91" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇮🇳 +91' ? 'selected' : '' }}>🇮🇳 +91 (India)</option>
                                                            <option value="🇵🇰 +92" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇵🇰 +92' ? 'selected' : '' }}>🇵🇰 +92 (Pakistan)</option>
                                                            <option value="🇪🇬 +20" {{ old('contact_person_mobile_country_code', $contactMobileData['country_code'] ?? '') == '🇪🇬 +20' ? 'selected' : '' }}>🇪🇬 +20 (Egypt)</option>
                                                        </select>
                                                    </div>
                                                    <div class="phone-number-input" style="flex: 1;">
                                                        <input type="tel" name="contact_person_mobile" id="contact_person_mobile" class="form-control @error('contact_person_mobile') is-invalid @enderror" value="{{ old('contact_person_mobile', $contactMobileData['number'] ?? '') }}" placeholder="50 123 4567" required minlength="7" maxlength="15">
                                                    </div>
                                                </div>
                                                @error('contact_person_mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @error('contact_person_mobile_country_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-4">
                                                <label for="contact_person_position" class="form-label">Position <span class="text-danger">*</span></label>
                                                <input type="text" name="contact_person_position" id="contact_person_position" class="form-control @error('contact_person_position') is-invalid @enderror" value="{{ old('contact_person_position') }}" placeholder="e.g., HR Manager, CEO, Director">
                                                @error('contact_person_position')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-4">
                                                <label for="contact_person_email" class="form-label">Contact Person Email <span class="text-danger">*</span></label>
                                                <input type="email" name="contact_person_email" id="contact_person_email" class="form-control @error('contact_person_email') is-invalid @enderror" value="{{ old('contact_person_email') }}" placeholder="contact@company.com">
                                                <small class="form-text text-muted">This should be the contact person's official email address</small>
                                                @error('contact_person_email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                                                <ul class="mb-0">
                                                    <li>All documents will be reviewed by our admin team</li>
                                                    <li>You will be notified via email once the review is complete</li>
                                                    <li>Only pending documents can be deleted or modified</li>
                                                    <li>Ensure all information provided is accurate and up-to-date</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-top: 40px; padding-top: 25px; border-top: 1px solid #e9ecef;">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Submit Document
                                    </button>
                                    <a href="{{ route('employer.documents.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File upload validation for trade license
    const tradeLicenseFile = document.getElementById('trade_license_file');
    if (tradeLicenseFile) {
        tradeLicenseFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                
                if (file.size > maxSize) {
                    alert('File size must not exceed 2MB');
                    this.value = '';
                    return false;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Please upload PDF, JPG, or PNG file only');
                    this.value = '';
                    return false;
                }
            }
        });
    }
});
</script>

<style>
/* Professional Design - Clean and Minimal */
.document-section {
    background: #ffffff;
    padding: 30px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    margin-bottom: 30px;
}

.section-title {
    color: #2c3e50;
    font-weight: 500;
    font-size: 16px;
    margin-bottom: 25px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
}

.section-title i {
    margin-right: 10px;
    color: #555;
    font-size: 18px;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px 15px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    border-color: #2c3e50;
    box-shadow: 0 0 0 2px rgba(44, 62, 80, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    font-size: 12px;
    color: #dc3545;
}

.form-text {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

.alert-info {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-left: 4px solid #6c757d;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 0;
}

.alert-info h6 {
    color: #2c3e50;
    font-weight: 500;
    margin-bottom: 12px;
}

.alert-info ul {
    margin: 0;
    padding-left: 20px;
    color: #555;
    font-size: 14px;
    line-height: 1.8;
}

.alert-info li {
    margin-bottom: 6px;
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.panel-header h4 {
    color: #2c3e50;
    font-weight: 500;
    font-size: 20px;
    margin: 0;
    display: flex;
    align-items: center;
}

.panel-header h4 i {
    margin-right: 10px;
    color: #555;
}

.btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 4px;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #2c3e50;
    color: #ffffff;
}

.btn-primary:hover {
    background: #1a252f;
}

.btn-secondary {
    background: #6c757d;
    color: #ffffff;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-outline-secondary {
    background: transparent;
    color: #6c757d;
    border: 1px solid #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: #ffffff;
}

.badge {
    font-size: 12px;
    font-weight: 500;
    padding: 5px 12px;
    border-radius: 4px;
}

.badge.bg-success {
    background: #28a745;
}

.badge.bg-danger {
    background: #dc3545;
}

.form-group {
    margin-bottom: 0;
}

.mb-4 {
    margin-bottom: 20px !important;
}

.mb-5 {
    margin-bottom: 30px !important;
}

.text-muted {
    color: #6c757d !important;
}

.text-danger {
    color: #dc3545 !important;
}

.panel-body {
    padding: 0;
}

.dashboard-panel {
    background: #ffffff;
    border-radius: 4px;
    padding: 30px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

@media (max-width: 768px) {
    .panel-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .panel-header h4 {
        margin-bottom: 15px;
    }
    
    .document-section {
        padding: 20px;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 10px;
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

.phone-input-group input[type="tel"] {
    position: relative;
    top: 10px;
}

.phone-input-group input[type="tel"]:focus,
.country-code-selector select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}
</style>
@endsection
