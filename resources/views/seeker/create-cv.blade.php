@extends('layouts.app')

@section('title', 'Create CV')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Create CV</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create CV</li>
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
                <div class="card p-5 mt-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 id="heading">Build Your Professional CV</h2>
                            <p>Fill all form fields to create your complete CV</p>
                        </div>
                        @if($profile && $profile->current_position)
                        <div>
                            <a href="{{ route('resume.preview') }}" class="btn btn-info me-2" target="_blank">
                                <i class="fas fa-eye"></i> Preview Resume
                            </a>
                            <a href="{{ route('resume.download') }}" class="btn btn-success">
                                <i class="fas fa-download"></i> Download PDF
                            </a>
                        </div>
                        @endif
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="msform" action="{{ route('seeker.cv.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <ul id="progressbar">
                            <li class="active clickable-step" id="info" data-step="0"><strong>Basic Info</strong></li>
                            <li class="clickable-step" id="bio" data-step="1"><strong>Bio</strong></li>
                            <li class="clickable-step" id="projects" data-step="2"><strong>Projects</strong></li>
                            <li class="clickable-step" id="experience" data-step="3"><strong>Experience</strong></li>
                            <li class="clickable-step" id="education" data-step="4"><strong>Education</strong></li>
                            <li class="clickable-step" id="certificates" data-step="5"><strong>Certificates</strong></li>
                            <li class="clickable-step" id="skills" data-step="6"><strong>Skills</strong></li>
                            <li class="clickable-step" id="submit" data-step="7"><strong>Submit</strong></li>
                        </ul>

                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 12.5%;"></div>
                        </div>
                        <br>

                        <!-- Step 1: Basic Info -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="fs-title">Basic Information:</h2>
                                    </div>
                                    <div class="col-5">
                                        <h2 class="steps">Step 1 - 8</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">Current Role <sup>*</sup></label>
                                        <input type="text" class="form-control" name="current_position" value="{{ old('current_position', auth()->user()->seekerProfile->current_position ?? '') }}" placeholder="e.g., Software Engineer" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">Expected Salary (AED)</label>
                                        <input type="text" class="form-control" name="expected_salary" value="{{ old('expected_salary', $profile->expected_salary ?? '') }}" placeholder="e.g., 5000-8000">
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">Years of Experience <sup>*</sup></label>
                                        <input type="text" class="form-control" name="experience_years" value="{{ old('experience_years', auth()->user()->seekerProfile->experience_years ?? '') }}" placeholder="e.g., 3-5 years" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">Nationality <sup>*</sup></label>
                                        <select class="form-control" name="nationality" required>
                                            <option value="">-- Select Nationality --</option>
                                            <option value="UAE" {{ old('nationality', auth()->user()->seekerProfile->nationality ?? '') == 'UAE' ? 'selected' : '' }}>UAE</option>
                                            <option value="India" {{ old('nationality', auth()->user()->seekerProfile->nationality ?? '') == 'India' ? 'selected' : '' }}>India</option>
                                            <option value="Pakistan" {{ old('nationality', auth()->user()->seekerProfile->nationality ?? '') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                            <option value="Egypt" {{ old('nationality', auth()->user()->seekerProfile->nationality ?? '') == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                            <option value="Other" {{ old('nationality', auth()->user()->seekerProfile->nationality ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">First Language <sup>*</sup></label>
                                        @php
                                            $languages = $profile && $profile->languages ? (is_array($profile->languages) ? $profile->languages : json_decode($profile->languages, true)) : [];
                                            $firstLang = isset($languages[0]) ? $languages[0] : '';
                                            $secondLang = isset($languages[1]) ? $languages[1] : '';
                                        @endphp
                                        <select class="form-control" name="first_language" required>
                                            <option value="">-- Select Language --</option>
                                            <option value="English" {{ old('first_language', $firstLang) == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Arabic" {{ old('first_language', $firstLang) == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                                            <option value="Hindi" {{ old('first_language', $firstLang) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                            <option value="Urdu" {{ old('first_language', $firstLang) == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                                            <option value="French" {{ old('first_language', $firstLang) == 'French' ? 'selected' : '' }}>French</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="fieldlabels">Second Language</label>
                                        <select class="form-control" name="second_language">
                                            <option value="">Select</option>
                                            <option value="English" {{ old('second_language', $secondLang) == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Arabic" {{ old('second_language', $secondLang) == 'Arabic' ? 'selected' : '' }}>Arabic</option>
                                            <option value="Hindi" {{ old('second_language', $secondLang) == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                            <option value="Urdu" {{ old('second_language', $secondLang) == 'Urdu' ? 'selected' : '' }}>Urdu</option>
                                            <option value="French" {{ old('second_language', $secondLang) == 'French' ? 'selected' : '' }}>French</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                        </fieldset>

                        <!-- Step 2: Bio -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Professional Bio:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 2 - 8</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="fieldlabels">Tell us about yourself <sup>*</sup></label>
                                        <textarea class="form-control" name="bio" rows="8" placeholder="Write a professional summary..." required>{{ old('bio', $profile->bio ?? '') }}</textarea>
                                        <small class="text-muted">Minimum 100 characters, Maximum 2000</small>
                                    </div>
                                </div>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 3: Projects (Multiple) -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Projects:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 3 - 8</h2>
                                    </div>
                                </div>
                                <div id="projects-container">
                                    @forelse($projects as $index => $project)
                                    <div class="project-item mb-4 p-3 border rounded position-relative">
                                        @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Project Name</label>
                                                <input type="text" class="form-control" name="projects[{{ $index }}][name]" value="{{ old('projects.'.$index.'.name', $project->project_name) }}" placeholder="Project Name">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Project Type</label>
                                                <input type="text" class="form-control" name="projects[{{ $index }}][type]" value="{{ old('projects.'.$index.'.type', $project->project_type) }}" placeholder="Web App, Mobile App, etc.">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Project Link</label>
                                                <input type="url" class="form-control" name="projects[{{ $index }}][link]" value="{{ old('projects.'.$index.'.link', $project->project_link) }}" placeholder="https://example.com">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Description</label>
                                                <textarea class="form-control" name="projects[{{ $index }}][description]" rows="2" placeholder="Brief description">{{ old('projects.'.$index.'.description', $project->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="project-item mb-4 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Project Name</label>
                                                <input type="text" class="form-control" name="projects[0][name]" placeholder="Project Name">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Project Type</label>
                                                <input type="text" class="form-control" name="projects[0][type]" placeholder="Web App, Mobile App, etc.">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Project Link</label>
                                                <input type="url" class="form-control" name="projects[0][link]" placeholder="https://example.com">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Description</label>
                                                <textarea class="form-control" name="projects[0][description]" rows="2" placeholder="Brief description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addProject()"><i class="fas fa-plus"></i> Add Another Project</button>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 4: Experience (Multiple) -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Work Experience:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 4 - 8</h2>
                                    </div>
                                </div>
                                <div id="experience-container">
                                    @forelse($experiences as $index => $exp)
                                    <div class="experience-item mb-4 p-3 border rounded position-relative">
                                        @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Company Name</label>
                                                <input type="text" class="form-control" name="experience[{{ $index }}][company]" value="{{ old('experience.'.$index.'.company', $exp->company_name) }}" placeholder="Company Name">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Job Title</label>
                                                <input type="text" class="form-control" name="experience[{{ $index }}][title]" value="{{ old('experience.'.$index.'.title', $exp->job_title) }}" placeholder="Your Position">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Start Date</label>
                                                <input type="date" class="form-control" name="experience[{{ $index }}][start_date]" value="{{ old('experience.'.$index.'.start_date', $exp->start_date ? $exp->start_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">End Date (Leave empty if current)</label>
                                                <input type="date" class="form-control" name="experience[{{ $index }}][end_date]" value="{{ old('experience.'.$index.'.end_date', $exp->end_date ? $exp->end_date->format('Y-m-d') : '') }}">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Description</label>
                                                <textarea class="form-control" name="experience[{{ $index }}][description]" rows="2" placeholder="Your responsibilities">{{ old('experience.'.$index.'.description', $exp->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="experience-item mb-4 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Company Name</label>
                                                <input type="text" class="form-control" name="experience[0][company]" placeholder="Company Name">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Job Title</label>
                                                <input type="text" class="form-control" name="experience[0][title]" placeholder="Your Position">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Start Date</label>
                                                <input type="date" class="form-control" name="experience[0][start_date]">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">End Date (Leave empty if current)</label>
                                                <input type="date" class="form-control" name="experience[0][end_date]">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Description</label>
                                                <textarea class="form-control" name="experience[0][description]" rows="2" placeholder="Your responsibilities"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addExperience()"><i class="fas fa-plus"></i> Add Another Experience</button>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 5: Education (Multiple) -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Education:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 5 - 8</h2>
                                    </div>
                                </div>
                                <div id="education-container">
                                    @forelse($educations as $index => $edu)
                                    <div class="education-item mb-4 p-3 border rounded position-relative">
                                        @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Institution Name</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][institution]" value="{{ old('education.'.$index.'.institution', $edu->institution_name) }}" placeholder="University/School">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Degree</label>
                                                <select class="form-control" name="education[{{ $index }}][degree]">
                                                    <option value="">Select</option>
                                                    <option value="High School" {{ old('education.'.$index.'.degree', $edu->degree) == 'High School' ? 'selected' : '' }}>High School</option>
                                                    <option value="Bachelor's" {{ old('education.'.$index.'.degree', $edu->degree) == "Bachelor's" ? 'selected' : '' }}>Bachelor's</option>
                                                    <option value="Master's" {{ old('education.'.$index.'.degree', $edu->degree) == "Master's" ? 'selected' : '' }}>Master's</option>
                                                    <option value="PhD" {{ old('education.'.$index.'.degree', $edu->degree) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Field of Study</label>
                                                <input type="text" class="form-control" name="education[{{ $index }}][field]" value="{{ old('education.'.$index.'.field', $edu->field_of_study) }}" placeholder="e.g., Computer Science">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Graduation Year</label>
                                                <input type="number" class="form-control" name="education[{{ $index }}][year]" value="{{ old('education.'.$index.'.year', $edu->end_date ? $edu->end_date->format('Y') : '') }}" placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}">
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="education-item mb-4 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Institution Name</label>
                                                <input type="text" class="form-control" name="education[0][institution]" placeholder="University/School">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Degree</label>
                                                <select class="form-control" name="education[0][degree]">
                                                    <option value="">Select</option>
                                                    <option value="High School">High School</option>
                                                    <option value="Bachelor's">Bachelor's</option>
                                                    <option value="Master's">Master's</option>
                                                    <option value="PhD">PhD</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Field of Study</label>
                                                <input type="text" class="form-control" name="education[0][field]" placeholder="e.g., Computer Science">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="fieldlabels">Graduation Year</label>
                                                <input type="number" class="form-control" name="education[0][year]" placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addEducation()"><i class="fas fa-plus"></i> Add Another Education</button>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 6: Certificates (Multiple) -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Certificates:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 6 - 8</h2>
                                    </div>
                                </div>
                                <div id="certificates-container">
                                    @forelse($certificates as $index => $cert)
                                    <div class="certificate-item mb-4 p-3 border rounded position-relative">
                                        @if($index > 0)
                                        <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Certificate Name</label>
                                                <input type="text" class="form-control" name="certificates[{{ $index }}][name]" value="{{ old('certificates.'.$index.'.name', $cert->certificate_name) }}" placeholder="Certificate Name">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Issuing Organization</label>
                                                <input type="text" class="form-control" name="certificates[{{ $index }}][organization]" value="{{ old('certificates.'.$index.'.organization', $cert->issuing_organization) }}" placeholder="Organization Name">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Issue Date</label>
                                                <input type="date" class="form-control" name="certificates[{{ $index }}][date]" value="{{ old('certificates.'.$index.'.date', $cert->issue_date ? $cert->issue_date->format('Y-m-d') : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="certificate-item mb-4 p-3 border rounded">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Certificate Name</label>
                                                <input type="text" class="form-control" name="certificates[0][name]" placeholder="Certificate Name">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Issuing Organization</label>
                                                <input type="text" class="form-control" name="certificates[0][organization]" placeholder="Organization Name">
                                            </div>
                                            <div class="col-lg-12">
                                                <label class="fieldlabels">Issue Date</label>
                                                <input type="date" class="form-control" name="certificates[0][date]">
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addCertificate()"><i class="fas fa-plus"></i> Add Another Certificate</button>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 7: Skills (Tags) -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Your Skills:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 7 - 8</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label class="fieldlabels">Add your skills <sup>*</sup></label>
                                        @php
                                            $existingSkills = '';
                                            if($profile && $profile->skills) {
                                                $skillsArray = is_array($profile->skills) ? $profile->skills : json_decode($profile->skills, true);
                                                $existingSkills = is_array($skillsArray) ? implode(',', $skillsArray) : '';
                                            }
                                        @endphp
                                        <input type="text" id="skills-input" class="form-control" name="skills" value="{{ old('skills', $existingSkills) }}" placeholder="Type skill and press Enter (e.g., PHP)">
                                        <small class="text-muted d-block mt-2">
                                            <strong>How to add skills:</strong><br>
                                            1. Type skill name (e.g., "PHP")<br>
                                            2. Press <kbd>Enter</kbd> or <kbd>,</kbd> (comma)<br>
                                            3. Purple tag will appear<br>
                                            4. Repeat to add more skills
                                        </small>
                                        <div id="skills-debug" class="alert alert-info mt-2" style="display:none;">
                                            <small>Debug: <span id="skills-count">0</span> skills added</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="button" name="next" class="next action-button" value="Next">
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>

                        <!-- Step 8: Submit -->
                        <fieldset>
                            <div class="form-card">
                                <div class="row">
                                    <div class="col-6">
                                        <h2 class="fs-title">Finish:</h2>
                                    </div>
                                    <div class="col-6">
                                        <h2 class="steps">Step 8 - 8</h2>
                                    </div>
                                </div>
                                <br><br>
                                <h2 class="purple-text text-center"><strong>READY TO SUBMIT!</strong></h2>
                                <br>
                                <div class="row justify-content-center">
                                    <div class="col-3">
                                        <img src="https://i.imgur.com/GwStPmg.png" class="fit-image">
                                    </div>
                                </div>
                                <br><br>
                                <div class="row justify-content-center">
                                    <div class="col-7 text-center">
                                        <h5 class="purple-text text-center">Click Submit to Create Your CV</h5>
                                        <button type="submit" class="btn btn-success btn-lg mt-3">
                                            <i class="fas fa-check"></i> Submit CV
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <input type="button" name="previous" class="previous action-button-previous" value="Previous">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<style>
.tagify {
    --tag-bg: #673AB7;
    --tag-hover: #5E35B1;
    --tag-text-color: #fff;
    min-height: 80px;
}
.remove-item-btn {
    position: absolute;
    top: 10px;
    right: 10px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<script>
let projectCount = {{ $projects->count() > 0 ? $projects->count() : 1 }};
let experienceCount = {{ $experiences->count() > 0 ? $experiences->count() : 1 }};
let educationCount = {{ $educations->count() > 0 ? $educations->count() : 1 }};
let certificateCount = {{ $certificates->count() > 0 ? $certificates->count() : 1 }};

function addProject() {
    const container = $('#projects-container');
    const html = `
        <div class="project-item mb-4 p-3 border rounded position-relative">
            <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-lg-6">
                    <label class="fieldlabels">Project Name</label>
                    <input type="text" class="form-control" name="projects[${projectCount}][name]" placeholder="Project Name">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Project Type</label>
                    <input type="text" class="form-control" name="projects[${projectCount}][type]" placeholder="Web App, Mobile App, etc.">
                </div>
                <div class="col-lg-12">
                    <label class="fieldlabels">Project Link</label>
                    <input type="url" class="form-control" name="projects[${projectCount}][link]" placeholder="https://example.com">
                </div>
                <div class="col-lg-12">
                    <label class="fieldlabels">Description</label>
                    <textarea class="form-control" name="projects[${projectCount}][description]" rows="2" placeholder="Brief description"></textarea>
                </div>
            </div>
        </div>
    `;
    container.append(html);
    projectCount++;
}

function addExperience() {
    const container = $('#experience-container');
    const html = `
        <div class="experience-item mb-4 p-3 border rounded position-relative">
            <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-lg-6">
                    <label class="fieldlabels">Company Name</label>
                    <input type="text" class="form-control" name="experience[${experienceCount}][company]" placeholder="Company Name">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Job Title</label>
                    <input type="text" class="form-control" name="experience[${experienceCount}][title]" placeholder="Your Position">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Start Date</label>
                    <input type="date" class="form-control" name="experience[${experienceCount}][start_date]">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">End Date</label>
                    <input type="date" class="form-control" name="experience[${experienceCount}][end_date]">
                </div>
                <div class="col-lg-12">
                    <label class="fieldlabels">Description</label>
                    <textarea class="form-control" name="experience[${experienceCount}][description]" rows="2" placeholder="Your responsibilities"></textarea>
                </div>
            </div>
        </div>
    `;
    container.append(html);
    experienceCount++;
}

function addEducation() {
    const container = $('#education-container');
    const html = `
        <div class="education-item mb-4 p-3 border rounded position-relative">
            <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-lg-6">
                    <label class="fieldlabels">Institution Name</label>
                    <input type="text" class="form-control" name="education[${educationCount}][institution]" placeholder="University/School">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Degree</label>
                    <select class="form-control" name="education[${educationCount}][degree]">
                        <option value="">Select</option>
                        <option value="High School">High School</option>
                        <option value="Bachelor's">Bachelor's</option>
                        <option value="Master's">Master's</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Field of Study</label>
                    <input type="text" class="form-control" name="education[${educationCount}][field]" placeholder="e.g., Computer Science">
                </div>
                <div class="col-lg-6">
                    <label class="fieldlabels">Graduation Year</label>
                    <input type="number" class="form-control" name="education[${educationCount}][year]" placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}">
                </div>
            </div>
        </div>
    `;
    container.append(html);
    educationCount++;
}

function addCertificate() {
    const container = $('#certificates-container');
    const html = `
        <div class="certificate-item mb-4 p-3 border rounded position-relative">
            <button type="button" class="btn btn-sm btn-danger remove-item-btn" onclick="$(this).parent().remove()">
                <i class="fas fa-times"></i>
            </button>
            <div class="row">
                <div class="col-lg-12">
                    <label class="fieldlabels">Certificate Name</label>
                    <input type="text" class="form-control" name="certificates[${certificateCount}][name]" placeholder="Certificate Name">
                </div>
                <div class="col-lg-12">
                    <label class="fieldlabels">Issuing Organization</label>
                    <input type="text" class="form-control" name="certificates[${certificateCount}][organization]" placeholder="Organization Name">
                </div>
                <div class="col-lg-12">
                    <label class="fieldlabels">Issue Date</label>
                    <input type="date" class="form-control" name="certificates[${certificateCount}][date]">
                </div>
            </div>
        </div>
    `;
    container.append(html);
    certificateCount++;
}

$(document).ready(function(){
    var current_fs, next_fs, previous_fs;
    var current = 1;
    var steps = 8;

    setProgressBar(current);

    // Initialize Tagify
    var input = document.getElementById('skills-input');
    
    // Make sure Tagify is loaded
    if (typeof Tagify === 'undefined') {
        console.error('Tagify library not loaded!');
        alert('Error loading skills input. Please refresh the page.');
    }
    
    var tagify = new Tagify(input, {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(','),
        delimiters: ",|;",
        maxTags: 30,
        dropdown: {
            enabled: 0
        },
        callbacks: {
            add: function() {
                // Update original input whenever tag is added
                const skills = tagify.value.map(item => item.value).join(',');
                input.value = skills;
                console.log('Input updated on add:', input.value);
            },
            remove: function() {
                // Update original input whenever tag is removed
                const skills = tagify.value.map(item => item.value).join(',');
                input.value = skills;
                console.log('Input updated on remove:', input.value);
            }
        }
    });
    
    // Load existing skills into Tagify (if any)
    const initialSkills = input.value;
    if (initialSkills && initialSkills.trim() !== '') {
        console.log('Loading existing skills:', initialSkills);
        // Tagify automatically parses comma-separated values
        tagify.addTags(initialSkills);
        console.log('Loaded skills count:', tagify.value.length);
        $('#skills-debug').show();
        $('#skills-count').text(tagify.value.length);
    }
    
    // Debug: Log when tag is added
    tagify.on('add', function(e) {
        console.log('✅ Tag added:', e.detail.data.value);
        console.log('Current tags:', tagify.value);
        console.log('Input value:', input.value);
        $('#skills-debug').show();
        $('#skills-count').text(tagify.value.length);
    });
    
    // Debug: Log when tag is removed
    tagify.on('remove', function(e) {
        console.log('❌ Tag removed:', e.detail.data.value);
        console.log('Remaining tags:', tagify.value);
        $('#skills-count').text(tagify.value.length);
        if (tagify.value.length === 0) {
            $('#skills-debug').hide();
        }
    });

    $(".next").click(function(){
        const currentFieldset = $(this).parent();
        const requiredFields = currentFieldset.find('[required]');
        let isValid = true;
        let missingFields = [];

        requiredFields.each(function() {
            const fieldValue = $(this).val();
            const fieldName = $(this).prev('label').text() || $(this).attr('name');
            
            // Check if value is empty or just whitespace
            if (!fieldValue || fieldValue.trim() === '' || fieldValue === 'Select') {
                isValid = false;
                $(this).addClass('is-invalid');
                missingFields.push(fieldName);
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Step 2: Bio validation (min 100 chars)
        if (current === 2) {
            const bioField = currentFieldset.find('[name="bio"]');
            const bioValue = bioField.val() || '';
            if (bioValue.length < 100) {
                isValid = false;
                alert('Bio must be at least 100 characters. Current: ' + bioValue.length + ' characters');
                bioField.addClass('is-invalid').focus();
                return false;
            }
        }

        // Step 7: Skills validation
        if (current === 7) {
            // Force update input value from Tagify
            if (tagify && tagify.value.length > 0) {
                const skillsString = tagify.value.map(item => item.value).join(',');
                $('#skills-input').val(skillsString);
            }
            
            const skillsValue = $('#skills-input').val();
            console.log('Step 7 validation:');
            console.log('- Tagify count:', tagify.value.length);
            console.log('- Input value:', skillsValue);
            
            if (tagify.value.length === 0) {
                isValid = false;
                alert('Please add at least one skill.\n\nHow to add:\n1. Type skill name\n2. Press Enter or Comma\n3. Tag will appear in purple\n\nCurrent tags: ' + tagify.value.length);
                $('#skills-input').focus();
                return false;
            }
        }

        if (!isValid) {
            if (missingFields.length > 0) {
                alert('Please fill the following required fields:\n- ' + missingFields.join('\n- '));
            } else {
                alert('Please fill all required fields marked with *');
            }
            return false;
        }

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        next_fs.show();
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                current_fs.css({'display': 'none', 'position': 'relative'});
                next_fs.css({'opacity': 1 - now});
            },
            duration: 500
        });
        setProgressBar(++current);
    });

    $(".previous").click(function(){
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        previous_fs.show();
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                current_fs.css({'display': 'none', 'position': 'relative'});
                previous_fs.css({'opacity': 1 - now});
            },
            duration: 500
        });
        setProgressBar(--current);
    });

    function setProgressBar(curStep){
        var percent = (100 / steps) * curStep;
        $(".progress-bar").css("width", percent + "%");
    }

    // Make progress bar tabs clickable
    $('.clickable-step').on('click', function() {
        const targetStep = $(this).data('step');
        const targetFieldset = $('fieldset').eq(targetStep);
        
        if (targetFieldset.length) {
            // Hide all fieldsets
            $('fieldset').hide();
            
            // Show target fieldset
            targetFieldset.show();
            
            // Update progress bar
            $("#progressbar li").removeClass("active");
            $("#progressbar li").each(function(index) {
                if (index <= targetStep) {
                    $(this).addClass("active");
                }
            });
            
            // Update current step
            current = targetStep + 1;
            setProgressBar(current);
            
            console.log('Jumped to step:', current);
        }
    });

    // Form submission handler
    $('#msform').on('submit', function(e) {
        console.log('=== FORM SUBMISSION ===');
        
        // CRITICAL: Force update input from Tagify BEFORE any validation
        if (tagify && tagify.value.length > 0) {
            const skillsString = tagify.value.map(item => item.value).join(',');
            input.value = skillsString;
            $('#skills-input').val(skillsString);
            console.log('✅ Force updated skills:', skillsString);
        }
        
        const skillsInputValue = $('#skills-input').val();
        const tagifyCount = tagify ? tagify.value.length : 0;
        
        console.log('Tagify count:', tagifyCount);
        console.log('Input value:', skillsInputValue);
        console.log('Tagify tags:', tagify ? tagify.value : 'null');
        
        // Validate using Tagify count (more reliable than input value)
        if (tagifyCount === 0) {
            e.preventDefault();
            console.error('❌ No skills found!');
            alert('ERROR: No skills detected!\n\nCurrent tags: ' + tagifyCount + '\n\nPlease:\n1. Go back to Step 7\n2. Add skills by pressing Enter\n3. Try again');
            return false;
        }

        // Check bio
        const bioValue = $('[name="bio"]').val();
        if (!bioValue || bioValue.length < 100) {
            e.preventDefault();
            alert('Bio must be at least 100 characters\nCurrent: ' + (bioValue ? bioValue.length : 0));
            return false;
        }

        console.log('✅ VALIDATION PASSED!');
        console.log('Submitting with skills:', skillsInputValue);
        
        // Show loading
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');
        
        return true;
    });
});
</script>
@endpush
@endsection
