@extends('layouts.admin')

@section('title', 'Edit Job')
@section('page-title', 'Edit Job')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-edit"></i> Edit Job: {{ $job->title }}</h5>
        <a href="{{ route('admin.jobs.index') }}" class="admin-btn admin-btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Jobs
        </a>
    </div>
    <div class="admin-card-body">
        <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $job->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employer_id" class="form-label">Employer <span class="text-danger">*</span></label>
                        <select class="form-control @error('employer_id') is-invalid @enderror" 
                                id="employer_id" name="employer_id" required>
                            <option value="">Select Employer</option>
                            @foreach($employers as $employer)
                                <option value="{{ $employer->id }}" 
                                        {{ old('employer_id', $job->employer_id) == $employer->id ? 'selected' : '' }}>
                                    {{ $employer->employerProfile->company_name ?? $employer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-control @error('category_id') is-invalid @enderror" 
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location_city" class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location_city') is-invalid @enderror" 
                               id="location_city" name="location_city" value="{{ old('location_city', $job->location_city) }}" required>
                        @error('location_city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location_state" class="form-label">State/Province</label>
                        <input type="text" class="form-control @error('location_state') is-invalid @enderror" 
                               id="location_state" name="location_state" value="{{ old('location_state', $job->location_state) }}">
                        @error('location_state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="location_country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('location_country') is-invalid @enderror" 
                               id="location_country" name="location_country" value="{{ old('location_country', $job->location_country) }}" required>
                        @error('location_country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                        <select class="form-control @error('employment_type') is-invalid @enderror" 
                                id="employment_type" name="employment_type" required>
                            <option value="">Select Type</option>
                            <option value="full_time" {{ old('employment_type', $job->employment_type) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part_time" {{ old('employment_type', $job->employment_type) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="freelance" {{ old('employment_type', $job->employment_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="experience_level" class="form-label">Experience Level <span class="text-danger">*</span></label>
                        <select class="form-control @error('experience_level') is-invalid @enderror" 
                                id="experience_level" name="experience_level" required>
                            <option value="">Select Level</option>
                            <option value="entry" {{ old('experience_level', $job->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                            <option value="mid" {{ old('experience_level', $job->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level</option>
                            <option value="senior" {{ old('experience_level', $job->experience_level) == 'senior' ? 'selected' : '' }}>Senior Level</option>
                            <option value="executive" {{ old('experience_level', $job->experience_level) == 'executive' ? 'selected' : '' }}>Executive Level</option>
                        </select>
                        @error('experience_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="salary_min" class="form-label">Minimum Salary</label>
                        <input type="number" class="form-control @error('salary_min') is-invalid @enderror" 
                               id="salary_min" name="salary_min" value="{{ old('salary_min', $job->salary_min) }}" min="0">
                        @error('salary_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="salary_max" class="form-label">Maximum Salary</label>
                        <input type="number" class="form-control @error('salary_max') is-invalid @enderror" 
                               id="salary_max" name="salary_max" value="{{ old('salary_max', $job->salary_max) }}" min="0">
                        @error('salary_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="skills_required" class="form-label">Skills Required</label>
                @php
                    $skills = '';
                    if ($job->skills_required) {
                        if (is_string($job->skills_required)) {
                            $decoded = json_decode($job->skills_required, true);
                            $skills = is_array($decoded) ? implode(', ', $decoded) : $job->skills_required;
                        } else {
                            $skills = is_array($job->skills_required) ? implode(', ', $job->skills_required) : $job->skills_required;
                        }
                    }
                @endphp
                <input type="text" class="form-control @error('skills_required') is-invalid @enderror" 
                       id="skills_required" name="skills_required" value="{{ old('skills_required', $skills) }}"
                       placeholder="e.g., PHP, Laravel, MySQL (comma separated)">
                @error('skills_required')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Separate multiple skills with commas</small>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="8" required>{{ old('description', $job->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-control @error('status') is-invalid @enderror" 
                        id="status" name="status" required>
                    <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.jobs.index') }}" class="admin-btn admin-btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="admin-btn admin-btn-primary">
                    <i class="fas fa-save"></i> Update Job
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
