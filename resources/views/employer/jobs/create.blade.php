@extends('layouts.app')

@section('title', 'Post a Job')

@push('styles')
<style>
/* Hide search section on employer jobs create page */
.search-wrap {
    display: none !important;
}

/* Modern Job Creation Form Styles */
.professional-form-container {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid #e8e8e8;
    overflow: hidden;
    position: relative;
}

.professional-form-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107, #dc3545);
}

.form-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #e8e8e8;
    padding: 2.5rem;
    position: relative;
}

.form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23e9ecef" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.form-header-content {
    position: relative;
    z-index: 1;
}

.form-header h2 {
    color: #2c3e50;
    font-weight: 700;
    margin: 0;
    font-size: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-header h2::before {
    content: '🚀';
    font-size: 1.5rem;
}

.form-header p {
    color: #6c757d;
    margin: 0.75rem 0 0 0;
    font-size: 1rem;
    font-weight: 500;
}

/* Progress Indicator */
.form-progress {
    background: #f8f9fa;
    padding: 1rem 2.5rem;
    border-bottom: 1px solid #e8e8e8;
    display: none; /* Hide progress tabs */
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 600px;
    margin: 0 auto;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.progress-step::after {
    content: '';
    position: absolute;
    top: 15px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: #e9ecef;
    z-index: 1;
}

.progress-step:last-child::after {
    display: none;
}

.progress-step.active::after {
    background: #007bff;
}

.progress-step.completed::after {
    background: #28a745;
}

.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.progress-step.active .step-circle {
    background: #007bff;
    color: white;
    transform: scale(1.1);
}

.progress-step.completed .step-circle {
    background: #28a745;
    color: white;
}

.step-label {
    margin-top: 0.5rem;
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
    text-align: center;
}

.progress-step.active .step-label {
    color: #007bff;
    font-weight: 600;
}

.progress-step.completed .step-label {
    color: #28a745;
    font-weight: 600;
}

.form-body {
    padding: 2.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.form-section {
    margin-bottom: 3rem;
    padding: 2rem;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #f0f0f0;
    position: relative;
    transition: all 0.3s ease;
}

.form-section:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.form-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #007bff, #28a745);
    border-radius: 0 2px 2px 0;
}

.section-title {
    color: #2c3e50;
    font-weight: 700;
    font-size: 1.3rem;
    margin-bottom: 2rem;
    padding-left: 1rem;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title::before {
    content: '';
    width: 4px;
    height: 30px;
    background: linear-gradient(180deg, #007bff, #28a745);
    border-radius: 2px;
    position: absolute;
    left: -1rem;
}

.form-group {
    margin-bottom: 2rem;
    position: relative;
}

.form-label {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-label::before {
    content: '';
    width: 3px;
    height: 16px;
    background: linear-gradient(180deg, #007bff, #28a745);
    border-radius: 2px;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background-color: #ffffff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.3rem rgba(0, 123, 255, 0.1), 0 4px 12px rgba(0, 123, 255, 0.15);
    outline: none;
    transform: translateY(-1px);
}

.form-control:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.3rem rgba(220, 53, 69, 0.1);
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.invalid-feedback::before {
    content: '⚠️';
    font-size: 0.8rem;
}

/* Ad Type Selection */
.ad-type-container {
    background: #f8f9fa;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 1.5rem;
}

.ad-option {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 8px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.ad-option:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.ad-option.selected {
    border-color: #007bff;
    background-color: #f0f8ff;
}

.ad-option:last-child {
    margin-bottom: 0;
}

.form-check-input {
    margin-top: 0.25rem;
}

.form-check-label {
    cursor: pointer;
    margin-left: 0.5rem;
}

.ad-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.25rem;
}

.ad-description {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0;
}

.featured-options {
    margin-top: 1rem;
    padding-left: 1.5rem;
    border-left: 2px solid #e8e8e8;
}

.featured-option {
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.featured-option:hover {
    border-color: #007bff;
}

.featured-option:last-child {
    margin-bottom: 0;
}

/* Consent Section */
.consent-container {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1.5rem;
}

.consent-header {
    color: #856404;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.consent-content {
    color: #856404;
    font-size: 0.9rem;
    line-height: 1.5;
}

.consent-list {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.consent-list li {
    margin-bottom: 0.5rem;
}

/* Action Buttons */
.form-actions {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 1px solid #e8e8e8;
    padding: 2rem 2.5rem;
    position: relative;
}

.form-actions::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, #007bff, transparent);
}

.btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.btn-outline-primary {
    background: transparent;
    color: #007bff;
    border: 2px solid #007bff;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.1);
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
}

.btn-success {
    background-color: #28a745;
    color: #ffffff;
}

.btn-success:hover {
    background-color: #1e7e34;
    color: #ffffff;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background-color: #e0a800;
    color: #212529;
}

/* Modal Styles */
.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e8e8e8;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    color: #2c3e50;
    font-weight: 600;
}

.modal-body {
    padding: 2rem;
}

.modal-footer {
    background: #f8f9fa;
    border-top: 1px solid #e8e8e8;
    border-radius: 0 0 12px 12px;
}

/* Ad Type Selection Modal Styles */
.ad-option-card {
    background: #ffffff;
    border: 2px solid #e8e8e8;
    border-radius: 12px;
    padding: 0;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.ad-option-card:hover {
    border-color: #007bff;
    box-shadow: 0 4px 20px rgba(0, 123, 255, 0.1);
    transform: translateY(-2px);
}

.ad-option-card.selected {
    border-color: #007bff;
    background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%);
    box-shadow: 0 6px 25px rgba(0, 123, 255, 0.15);
}

.ad-card-header {
    background: #f8f9fa;
    padding: 1.5rem;
    text-align: center;
    border-radius: 10px 10px 0 0;
    position: relative;
}

.ad-card-header.featured {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}

.ad-icon {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 0.5rem;
}

.ad-card-header.featured .ad-icon {
    color: #ffc107;
}

.ad-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.ad-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

.ad-card-header.featured .ad-price {
    color: #856404;
}

.ad-card-body {
    padding: 1.5rem;
    flex-grow: 1;
}

.ad-duration {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 1rem;
    font-weight: 500;
}

.ad-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.ad-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ad-features li {
    padding: 0.25rem 0;
    color: #495057;
    font-size: 0.85rem;
}

.ad-features li i {
    color: #28a745;
    margin-right: 0.5rem;
    font-size: 0.8rem;
}

.featured-duration-options {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e8e8e8;
}

.duration-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.duration-title i {
    color: #007bff;
}

.duration-options-simple {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.duration-option-simple {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #ffffff;
    border: 1px solid #e8e8e8;
    border-radius: 6px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.duration-option-simple:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.duration-option-simple.selected {
    border-color: #007bff;
    background: #f0f8ff;
}

.duration-option-simple input[type="radio"] {
    margin: 0;
    width: 18px;
    height: 18px;
}

.duration-option-simple label {
    margin: 0;
    cursor: pointer;
    font-weight: 500;
    color: #2c3e50;
    flex-grow: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .duration-options-simple {
        gap: 0.5rem;
    }
    
    .duration-option-simple {
        padding: 0.5rem;
    }
}

.ad-card-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-radius: 0 0 10px 10px;
    border-top: 1px solid #e8e8e8;
}

.ad-card-footer .form-check {
    margin: 0;
}

.ad-card-footer .form-check-input {
    margin-top: 0.25rem;
}

.ad-card-footer .form-check-label {
    font-weight: 500;
    color: #2c3e50;
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-header {
        padding: 1.5rem;
    }
    
    .form-body {
        padding: 1.5rem;
    }
    
    .form-actions {
        padding: 1rem 1.5rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Breadcrumb Styling */
.breadcrumb-section {
    background: #f8f9fa;
    padding: 1rem 0;
    border-bottom: 1px solid #e8e8e8;
}

.page-title h1 {
    color: #2c3e50;
    font-weight: 600;
    margin: 0;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: #007bff;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #6c757d;
}
</style>
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Post Job</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Post Job</li>
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
                <div class="professional-form-container">
                    <div class="form-header">
                        <div class="form-header-content">
                            <h2>Post a New Job</h2>
                            <p>Fill out the form below to create your job posting. All fields marked with * are required.</p>
                        </div>
                    </div>
                    
                    <!-- Progress Indicator -->
                    <div class="form-progress">
                        <div class="progress-steps">
                            <div class="progress-step active" data-step="1">
                                <div class="step-circle">1</div>
                                <div class="step-label">Basic Info</div>
                            </div>
                            <div class="progress-step" data-step="2">
                                <div class="step-circle">2</div>
                                <div class="step-label">Job Details</div>
                            </div>
                            <div class="progress-step" data-step="3">
                                <div class="step-circle">3</div>
                                <div class="step-label">Location & Requirements</div>
                            </div>
                            <div class="progress-step" data-step="4">
                                <div class="step-circle">4</div>
                                <div class="step-label">Salary & Deadline</div>
                            </div>
                            <div class="progress-step" data-step="5">
                                <div class="step-circle">5</div>
                                <div class="step-label">Terms & Conditions</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
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

                        <form action="{{ route('employer.jobs.store') }}" method="POST" id="jobForm">
                                    @csrf
                            
                            <!-- Basic Information Section -->
                            <div class="form-section" data-section="1">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    Basic Information
                                </h3>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label">Job Title <sup class="text-danger">*</sup></label>
                                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="e.g., Software Engineer" required>
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Category <sup class="text-danger">*</sup></label>
                                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Employment Type <sup class="text-danger">*</sup></label>
                                                <select name="employment_type" class="form-control @error('employment_type') is-invalid @enderror" required>
                                                    <option value="">Select Type</option>
                                                    <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                                    <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                                    <option value="freelance" {{ old('employment_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                                    <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                                </select>
                                                @error('employment_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                                    </div>
                                                </div>

                                        
                            <!-- Hidden fields -->
                            <input type="hidden" name="priority" value="normal">
                            <input type="hidden" name="ad_type" id="hidden_ad_type" value="">
                            <input type="hidden" name="featured_duration" id="hidden_featured_duration" value="">
                            
                            <!-- Hidden submit button -->
                            <button type="submit" id="hidden_submit" style="display: none;"></button>

                            <!-- Job Details Section -->
                            <div class="form-section" data-section="2">
                                <h3 class="section-title">
                                    <i class="fas fa-briefcase text-success"></i>
                                    Job Details
                                </h3>
                                <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label">Job Description <sup class="text-danger">*</sup></label>
                                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe the job in detail..." required>{{ old('description') }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label">Requirements</label>
                                                <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="4" placeholder="List the job requirements...">{{ old('requirements') }}</textarea>
                                                @error('requirements')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                            <label class="form-label">Responsibilities</label>
                                                <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="4" placeholder="List the job responsibilities...">{{ old('responsibilities') }}</textarea>
                                                @error('responsibilities')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                            </div>
                                        </div>

                            <!-- Location & Requirements Section -->
                            <div class="form-section" data-section="3">
                                <h3 class="section-title">
                                    <i class="fas fa-map-marker-alt text-warning"></i>
                                    Location & Requirements
                                </h3>
                                <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Location (City) <sup class="text-danger">*</sup></label>
                                                <select name="location_city" id="location_city" class="form-control @error('location_city') is-invalid @enderror" required>
                                                    <option value="">Select City</option>
                                                </select>
                                                @error('location_city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Location (Country) <sup class="text-danger">*</sup></label>
                                                <select name="location_country" id="location_country" class="form-control @error('location_country') is-invalid @enderror" required>
                                                    <option value="">Select Country</option>
                                                    @php $oldCountry = old('location_country'); @endphp
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->name }}" {{ $oldCountry == $country->name ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('location_country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Experience Required</label>
                                                <input type="text" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years') }}" placeholder="e.g., 2-5 years">
                                                @error('experience_years')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Education Level</label>
                                                <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" value="{{ old('education_level') }}" placeholder="e.g., Bachelor's Degree">
                                                @error('education_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                            </div>
                                        </div>

                            <!-- Salary & Deadline Section -->
                            <div class="form-section" data-section="4">
                                <h3 class="section-title">
                                    <i class="fas fa-dollar-sign text-info"></i>
                                    Salary & Deadline
                                </h3>
                                <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label class="form-label">Minimum Salary</label>
                                                <input type="number" name="salary_min" class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min') }}" placeholder="e.g., 5000">
                                                @error('salary_min')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label class="form-label">Maximum Salary</label>
                                                <input type="number" name="salary_max" class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max') }}" placeholder="e.g., 8000">
                                                @error('salary_max')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label class="form-label">Salary Currency</label>
                                                <select name="salary_currency" class="form-control @error('salary_currency') is-invalid @enderror">
                                                    <option value="AED" {{ old('salary_currency', 'AED') == 'AED' ? 'selected' : '' }}>AED</option>
                                                    <option value="USD" {{ old('salary_currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                                    <option value="EUR" {{ old('salary_currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                    <option value="GBP" {{ old('salary_currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                </select>
                                                @error('salary_currency')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                            <label class="form-label">Application Deadline</label>
                                                <input type="text" id="application_deadline" name="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline') }}" placeholder="YYYY-MM-DD">
                                                @error('application_deadline')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                        </div>
                                    </div>
                                            </div>
                                        </div>

                            <!-- Consent Section -->
                            <div class="form-section" data-section="5">
                                <h3 class="section-title">
                                    <i class="fas fa-shield-alt text-danger"></i>
                                    Terms & Conditions
                                </h3>
                                <div class="consent-container">
                                    <div class="consent-header">
                                        <i class="fas fa-exclamation-triangle"></i> Employer Consent & Responsibility Statement
                                                    </div>
                                    <div class="consent-content">
                                                        <p class="mb-3">By posting a job on this website, I hereby confirm that:</p>
                                        <ul class="consent-list">
                                                            <li>All information provided in my job advertisement is true, accurate, and lawful.</li>
                                                            <li>I and my company are fully responsible for the content and activities related to my job postings.</li>
                                                            <li>FullTimez - M/s: CoreMedia & Business Management (FZC) and its representatives are not liable for any claim, dispute, or legal action arising from my postings, whether inside or outside the UAE.</li>
                                                            <li>I and my company authorize FullTimez- M/s: CoreMedia & Business Management (FZC) or its representatives to verify my company details or job postings by visit, call, or email if required.</li>
                                                        </ul>
                                                        <div class="form-check">
                                                            <input class="form-check-input @error('consent') is-invalid @enderror" type="checkbox" name="consent" id="consent" value="1" required>
                                                            <label class="form-check-label" for="consent">
                                                                <strong>I have read and agree to the above terms and conditions</strong>
                                                            </label>
                                                            @error('consent')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                        </form>
                                        </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Back to Jobs
                            </a>
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-outline-primary btn-lg" id="saveDraftBtn">
                                    <i class="fas fa-save"></i> Save Draft
                                </button>
                                <button type="button" class="btn btn-primary btn-lg" id="submitBtn" onclick="showAdTypeModal()">
                                    <i class="fas fa-rocket"></i> Continue to Ad Selection
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ad Type Selection Modal -->
<div class="modal fade" id="adTypeModal" tabindex="-1" aria-labelledby="adTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adTypeModalLabel">
                    <i class="fas fa-star text-primary"></i> Choose Advertisement Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h4 class="text-dark mb-2">Select Your Advertisement Package</h4>
                    <p class="text-muted">Choose how you'd like to promote your job posting</p>
                </div>
                
                <div class="row">
                    <!-- Recommended Ad Option -->
                    <div class="col-md-6 mb-3">
                        <div class="ad-option-card" onclick="selectAdType('recommended')" id="recommended-card">
                            <div class="ad-card-header">
                                <div class="ad-icon">
                                    <i class="fas fa-thumbs-up"></i>
                                </div>
                                <div class="ad-title">Recommended Ad</div>
                                <div class="ad-price">FREE</div>
                            </div>
                            <div class="ad-card-body">
                                <div class="ad-duration">7 Days</div>
                                <div class="ad-description">
                                    Your job will be published for 7 days at no cost. Perfect for testing the platform.
                                </div>
                                <ul class="ad-features">
                                    <li><i class="fas fa-check"></i> Standard job posting</li>
                                    <li><i class="fas fa-check"></i> 7 days visibility</li>
                                    <li><i class="fas fa-check"></i> Basic search results</li>
                                    <li><i class="fas fa-check"></i> No payment required</li>
                                </ul>
                            </div>
                            <div class="ad-card-footer">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modal_ad_type" id="modal_recommended" value="recommended">
                                    <label class="form-check-label" for="modal_recommended">
                                        Select Recommended Ad
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Ad Option -->
                    <div class="col-md-6 mb-3">
                        <div class="ad-option-card" onclick="selectAdType('featured')" id="featured-card">
                            <div class="ad-card-header featured">
                                <div class="ad-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="ad-title">Featured Ad</div>
                                <div class="ad-price">AED 49+</div>
                            </div>
                            <div class="ad-card-body">
                                <div class="ad-duration">7-30 Days</div>
                                <div class="ad-description">
                                    Get maximum visibility with featured placement. Choose your duration for optimal results.
                                </div>
                                <ul class="ad-features">
                                    <li><i class="fas fa-check"></i> Featured placement</li>
                                    <li><i class="fas fa-check"></i> Priority in search</li>
                                    <li><i class="fas fa-check"></i> Enhanced visibility</li>
                                    <li><i class="fas fa-check"></i> Multiple duration options</li>
                                </ul>
                                
                                <!-- Featured Duration Options -->
                                <div class="featured-duration-options" id="featured-duration-options" style="display: none;">
                                    <div class="duration-title">
                                        <i class="fas fa-clock"></i> Choose Duration:
                                    </div>
                                    <div class="duration-options-simple">
                                        <div class="duration-option-simple" onclick="selectFeaturedDuration('7')">
                                            <input type="radio" name="modal_featured_duration" id="modal_featured_7" value="7">
                                            <label for="modal_featured_7">7 Days - AED 49</label>
                                        </div>
                                        <div class="duration-option-simple" onclick="selectFeaturedDuration('15')">
                                            <input type="radio" name="modal_featured_duration" id="modal_featured_15" value="15">
                                            <label for="modal_featured_15">15 Days - AED 89</label>
                                        </div>
                                        <div class="duration-option-simple" onclick="selectFeaturedDuration('30')">
                                            <input type="radio" name="modal_featured_duration" id="modal_featured_30" value="30">
                                            <label for="modal_featured_30">30 Days - AED 149</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ad-card-footer">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="modal_ad_type" id="modal_featured" value="featured">
                                    <label class="form-check-label" for="modal_featured">
                                        Select Featured Ad
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmAdType" onclick="confirmAdSelection()" disabled>
                    <i class="fas fa-check"></i> Confirm Selection
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Job Submission Confirmation Modal -->
<div class="modal fade" id="jobSubmissionModal" tabindex="-1" aria-labelledby="jobSubmissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobSubmissionModalLabel">
                    <i class="fas fa-check-circle text-success"></i> Job Submission Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                </div>
                <h4 class="text-success mb-3" id="submission-title">Job Submitted Successfully!</h4>
                <p class="text-muted mb-4" id="submission-message">
                    Your job posting has been submitted and will be reviewed by our admin team.
                </p>
                <div class="alert alert-info" id="submission-details">
                    <i class="fas fa-info-circle"></i>
                    <strong>Next Steps:</strong><br>
                    <span id="next-steps-text">Your job will be published after admin approval.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-check"></i> Understood
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Professional Job Creation Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Progress tracking
    let currentStep = 1;
    const totalSteps = 5;
    
    // Update progress indicator
    function updateProgress() {
        document.querySelectorAll('.progress-step').forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                step.classList.add('completed');
                step.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
                step.querySelector('.step-circle').innerHTML = stepNumber;
            } else {
                step.querySelector('.step-circle').innerHTML = stepNumber;
            }
        });
    }
    
    // Scroll to section
    function scrollToSection(sectionNumber) {
        const section = document.querySelector(`[data-section="${sectionNumber}"]`);
        if (section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    
    // Add click handlers to progress steps
    document.querySelectorAll('.progress-step').forEach((step, index) => {
        step.addEventListener('click', function() {
            const stepNumber = index + 1;
            if (stepNumber <= currentStep) {
                currentStep = stepNumber;
                updateProgress();
                scrollToSection(stepNumber);
            }
        });
    });
    
    // Form validation and progress tracking
    function validateSection(sectionNumber) {
        const section = document.querySelector(`[data-section="${sectionNumber}"]`);
        if (!section) return true;
        
        const requiredFields = section.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }
    
    // Auto-advance progress based on form completion
    function checkFormProgress() {
        let completedSections = 0;
        
        for (let i = 1; i <= totalSteps; i++) {
            if (validateSection(i)) {
                completedSections = i;
            } else {
                break;
            }
        }
        
        if (completedSections > currentStep) {
            currentStep = completedSections;
            updateProgress();
        }
    }
    
    // Add event listeners to form fields
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('blur', checkFormProgress);
        field.addEventListener('change', checkFormProgress);
    });
    
    // Initialize progress
    updateProgress();
    // Dynamic city loading based on country selection
    const countrySelect = document.getElementById('location_country');
    const citySelect = document.getElementById('location_city');

    function loadCities(countryName, selectedCity) {
        if (!citySelect) return;
        
        if (!countryName) {
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = false;
            return;
        }
        
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;
        
        fetch(`{{ url('/api/cities') }}/${encodeURIComponent(countryName)}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (data.success && data.cities && Array.isArray(data.cities)) {
                    data.cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.textContent = city.name;
                        if (selectedCity && selectedCity === city.name) {
                            option.selected = true;
                        }
                        citySelect.appendChild(option);
                    });
                }
                citySelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading cities:', error);
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = false;
            });
    }

    // Initialize on load with old values
    if (countrySelect && citySelect) {
        const initialCountry = countrySelect.value;
        const initialCity = '{{ old('location_city') }}';
        if (initialCountry) {
            loadCities(initialCountry, initialCity);
        }
        
        countrySelect.addEventListener('change', function(){
            loadCities(this.value, null);
        });
    }

    // Initialize Flatpickr for Application Deadline
    if (window.flatpickr) {
        flatpickr('#application_deadline', {
            dateFormat: 'Y-m-d',
            minDate: 'today',
            allowInput: true,
            altInput: true,
            altFormat: 'F j, Y'
        });
    }
    const form = document.querySelector('form');
    const adTypeModal = new bootstrap.Modal(document.getElementById('adTypeModal'));
    const jobSubmissionModal = new bootstrap.Modal(document.getElementById('jobSubmissionModal'));
    
    // Prevent default form submission
    function preventFormSubmission(e) {
        e.preventDefault();
        // Form submission is handled by the modal flow
    }
    
    form.addEventListener('submit', preventFormSubmission);
    
    // Function to show ad type selection modal
    window.showAdTypeModal = function() {
        // Validate form first
        if (!validateForm()) {
            return;
        }
        
        // Reset modal state
        resetModalState();
        
        // Show the modal
        adTypeModal.show();
    };
    
    // Function to select ad type in modal
    window.selectAdType = function(type) {
        // Update radio button
        const radio = document.querySelector(`input[name="modal_ad_type"][value="${type}"]`);
        if (radio) {
            radio.checked = true;
        }
        
        // Update visual state
        document.querySelectorAll('.ad-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        const selectedCard = document.getElementById(`${type}-card`);
        if (selectedCard) {
            selectedCard.classList.add('selected');
        }
        
        // Show/hide featured duration options
        const durationOptions = document.getElementById('featured-duration-options');
        if (type === 'featured') {
            durationOptions.style.display = 'block';
        } else {
            durationOptions.style.display = 'none';
            // Clear featured duration selection
            document.querySelectorAll('input[name="modal_featured_duration"]').forEach(input => {
                input.checked = false;
            });
        }
        
        // Enable confirm button
        document.getElementById('confirmAdType').disabled = false;
    };
    
    // Function to select featured duration
    window.selectFeaturedDuration = function(duration) {
        // Clear all selected states
        document.querySelectorAll('.duration-option-simple').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Set the selected option
        const selectedOption = document.querySelector(`input[name="modal_featured_duration"][value="${duration}"]`).closest('.duration-option-simple');
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }
        
        // Update radio button
        const radio = document.querySelector(`input[name="modal_featured_duration"][value="${duration}"]`);
        if (radio) {
            radio.checked = true;
        }
    };
    
    // Function to confirm ad selection and submit form
    window.confirmAdSelection = function() {
        const selectedAdType = document.querySelector('input[name="modal_ad_type"]:checked');
        const selectedDuration = document.querySelector('input[name="modal_featured_duration"]:checked');
        
        if (!selectedAdType) {
            alert('Please select an advertisement type.');
            return;
        }
        
        if (selectedAdType.value === 'featured' && !selectedDuration) {
            alert('Please select a duration for the featured ad.');
            return;
        }
        
        // Set hidden form values
        document.getElementById('hidden_ad_type').value = selectedAdType.value;
        if (selectedDuration) {
            document.getElementById('hidden_featured_duration').value = selectedDuration.value;
        }
        
        // Hide ad type modal
        adTypeModal.hide();
        
        // Remove the preventDefault listener temporarily
        form.removeEventListener('submit', preventFormSubmission);
        
        // Submit form using hidden submit button
        document.getElementById('hidden_submit').click();
    };
    
    // Function to show submission confirmation
    function showSubmissionConfirmation(adType, duration) {
        const titleElement = document.getElementById('submission-title');
        const messageElement = document.getElementById('submission-message');
        const nextStepsElement = document.getElementById('next-steps-text');
        
        if (adType === 'recommended') {
            titleElement.textContent = 'Job Submitted Successfully!';
            messageElement.textContent = 'Your job posting has been submitted and will be reviewed by our admin team.';
            nextStepsElement.textContent = 'Your job will be published after admin approval.';
        } else if (adType === 'featured') {
            titleElement.textContent = 'Featured Ad Submitted Successfully!';
            messageElement.textContent = 'Thank you for posting your featured ad! The admin will review it and share the payment link via email shortly.';
            nextStepsElement.innerHTML = `
                1. Our admin team will review your job posting<br>
                2. You will receive a payment link via email<br>
                3. Once payment is confirmed, your job will go live
            `;
        }
        
        // Show confirmation modal
        jobSubmissionModal.show();
    }
    
    // Function to validate form
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        // Check consent checkbox
        const consentCheckbox = document.getElementById('consent');
        if (!consentCheckbox.checked) {
            consentCheckbox.classList.add('is-invalid');
            isValid = false;
        } else {
            consentCheckbox.classList.remove('is-invalid');
        }
        
        if (!isValid) {
            alert('Please fill in all required fields and accept the terms and conditions.');
        }
        
        return isValid;
    }
    
    // Function to reset modal state
    function resetModalState() {
        // Clear all selections
        document.querySelectorAll('input[name="modal_ad_type"]').forEach(input => {
            input.checked = false;
        });
        
        document.querySelectorAll('input[name="modal_featured_duration"]').forEach(input => {
            input.checked = false;
        });
        
        // Remove selected class from ad cards
        document.querySelectorAll('.ad-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Remove selected class from duration options
        document.querySelectorAll('.duration-option-simple').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Hide featured duration options
        document.getElementById('featured-duration-options').style.display = 'none';
        
        // Disable confirm button
        document.getElementById('confirmAdType').disabled = true;
    }
});
</script>
@endpush
@endsection

