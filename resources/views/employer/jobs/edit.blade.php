@extends('layouts.app')

@section('title', 'Edit Job')

@push('styles')
<style>
/* Hide search section on employer jobs edit page */
.search-wrap {
    display: none !important;
}

/* Modern Job Edit Form Styles */
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
    content: '✏️';
    font-size: 1.5rem;
}

.form-header p {
    color: #6c757d;
    margin: 0.75rem 0 0 0;
    font-size: 1rem;
    font-weight: 500;
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

/* Responsive Design */
@media (max-width: 768px) {
    .form-header {
        padding: 2rem;
    }
    
    .form-body {
        padding: 1.5rem;
    }
    
    .form-section {
        padding: 1.5rem;
    }
    
    .form-actions {
        padding: 1.5rem;
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Edit Job</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employer.jobs.index') }}">My Jobs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Job</li>
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
                    <!-- Header Section -->
                    <div class="form-header">
                        <div class="form-header-content">
                            <h2>Edit Job Posting</h2>
                            <p>Update your job posting details below. All fields marked with * are required.</p>
                        </div>
                    </div>

                    <div class="form-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('employer.jobs.update', $job) }}" method="POST" id="jobEditForm">
                            @csrf
                            @method('PUT')
                            
                            <!-- Basic Information Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-info-circle text-primary"></i>
                                    Basic Information
                                </h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">Job Title <sup class="text-danger">*</sup></label>
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $job->title) }}" placeholder="e.g., Software Engineer" required>
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
                                                    <option value="{{ $category->id }}" {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
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
                                                <option value="full_time" {{ old('employment_type', $job->employment_type) == 'full_time' ? 'selected' : '' }}>Full-time</option>
                                                <option value="part_time" {{ old('employment_type', $job->employment_type) == 'part_time' ? 'selected' : '' }}>Part-time</option>
                                                <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                                <option value="freelance" {{ old('employment_type', $job->employment_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                                <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                            </select>
                                            @error('employment_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Job Details Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-briefcase text-success"></i>
                                    Job Details
                                </h3>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">Job Description <sup class="text-danger">*</sup></label>
                                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe the job in detail..." required>{{ old('description', $job->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">Requirements</label>
                                            <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="4" placeholder="List the job requirements...">{{ old('requirements', $job->requirements) }}</textarea>
                                            @error('requirements')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">Responsibilities</label>
                                            <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="4" placeholder="List the job responsibilities...">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                                            @error('responsibilities')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location & Requirements Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-map-marker-alt text-warning"></i>
                                    Location & Requirements
                                </h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Location (City) <sup class="text-danger">*</sup></label>
                                            <input type="text" name="location_city" class="form-control @error('location_city') is-invalid @enderror" value="{{ old('location_city', $job->location_city) }}" placeholder="e.g., Dubai" required>
                                            @error('location_city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Location (Country) <sup class="text-danger">*</sup></label>
                                            <input type="text" name="location_country" class="form-control @error('location_country') is-invalid @enderror" value="{{ old('location_country', $job->location_country ?? 'UAE') }}" placeholder="e.g., UAE" required>
                                            @error('location_country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Experience Required</label>
                                            <input type="text" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $job->experience_years) }}" placeholder="e.g., 2-5 years">
                                            @error('experience_years')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Education Level</label>
                                            <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" value="{{ old('education_level', $job->education_level) }}" placeholder="e.g., Bachelor's Degree">
                                            @error('education_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Salary & Status Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="fas fa-dollar-sign text-info"></i>
                                    Salary & Status
                                </h3>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Minimum Salary</label>
                                            <input type="number" name="salary_min" class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min', $job->salary_min) }}" placeholder="e.g., 5000">
                                            @error('salary_min')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Maximum Salary</label>
                                            <input type="number" name="salary_max" class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max', $job->salary_max) }}" placeholder="e.g., 8000">
                                            @error('salary_max')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label">Salary Currency</label>
                                            <select name="salary_currency" class="form-control @error('salary_currency') is-invalid @enderror">
                                                <option value="AED" {{ old('salary_currency', $job->salary_currency ?? 'AED') == 'AED' ? 'selected' : '' }}>AED</option>
                                                <option value="USD" {{ old('salary_currency', $job->salary_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ old('salary_currency', $job->salary_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                <option value="GBP" {{ old('salary_currency', $job->salary_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                                            </select>
                                            @error('salary_currency')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Application Deadline</label>
                                            <input type="date" name="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                                            @error('application_deadline')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                                <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                            @error('status')
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
                            <button type="submit" form="jobEditForm" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Job
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
