@extends('layouts.app')

@section('title', 'Edit Job')

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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Job Posting</h3>
                    </div>
                    <div class="card-body p-5">
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
                                <form action="{{ route('employer.jobs.update', $job) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Job Title <sup class="text-danger">*</sup></label>
                                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $job->title) }}" placeholder="e.g., Software Engineer" required>
                                                @error('title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Category <sup class="text-danger">*</sup></label>
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
                                                <label>Employment Type <sup class="text-danger">*</sup></label>
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

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Job Description <sup class="text-danger">*</sup></label>
                                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe the job in detail..." required>{{ old('description', $job->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Requirements</label>
                                                <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="4" placeholder="List the job requirements...">{{ old('requirements', $job->requirements) }}</textarea>
                                                @error('requirements')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Responsibilities</label>
                                                <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="4" placeholder="List the job responsibilities...">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                                                @error('responsibilities')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Location (City) <sup class="text-danger">*</sup></label>
                                                <input type="text" name="location_city" class="form-control @error('location_city') is-invalid @enderror" value="{{ old('location_city', $job->location_city) }}" placeholder="e.g., Dubai" required>
                                                @error('location_city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Location (Country) <sup class="text-danger">*</sup></label>
                                                <input type="text" name="location_country" class="form-control @error('location_country') is-invalid @enderror" value="{{ old('location_country', $job->location_country ?? 'UAE') }}" placeholder="e.g., UAE" required>
                                                @error('location_country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Experience Required</label>
                                                <input type="text" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" value="{{ old('experience_years', $job->experience_years) }}" placeholder="e.g., 2-5 years">
                                                @error('experience_years')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Education Level</label>
                                                <input type="text" name="education_level" class="form-control @error('education_level') is-invalid @enderror" value="{{ old('education_level', $job->education_level) }}" placeholder="e.g., Bachelor's Degree">
                                                @error('education_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Minimum Salary</label>
                                                <input type="number" name="salary_min" class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min', $job->salary_min) }}" placeholder="e.g., 5000">
                                                @error('salary_min')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Maximum Salary</label>
                                                <input type="number" name="salary_max" class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max', $job->salary_max) }}" placeholder="e.g., 8000">
                                                @error('salary_max')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Salary Currency</label>
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
                                                <label>Application Deadline</label>
                                                <input type="date" name="application_deadline" class="form-control @error('application_deadline') is-invalid @enderror" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}" min="{{ date('Y-m-d') }}">
                                                @error('application_deadline')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control @error('status') is-invalid @enderror">
                                                    <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('employer.jobs.index') }}" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-check"></i> Update Job
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
@endsection
