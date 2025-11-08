<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $profile->full_name ?? $user->name }} - Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .resume-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #673AB7;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 28px;
            color: #673AB7;
            margin-bottom: 5px;
        }
        .header .contact-info {
            font-size: 11px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #673AB7;
            border-bottom: 2px solid #673AB7;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .info-value {
            flex: 1;
        }
        .bio-text {
            text-align: justify;
            margin-bottom: 10px;
        }
        .skills-container {
            margin-top: 10px;
        }
        .skill-tag {
            display: inline-block;
            background: #673AB7;
            color: white;
            padding: 5px 10px;
            margin: 3px;
            border-radius: 3px;
            font-size: 10px;
        }
        .entry-item {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .entry-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .entry-title {
            font-weight: bold;
            font-size: 13px;
        }
        .entry-subtitle {
            color: #666;
            font-style: italic;
        }
        .entry-date {
            color: #666;
            font-size: 11px;
        }
        .entry-description {
            margin-top: 5px;
            text-align: justify;
        }
        .languages {
            margin-top: 5px;
        }
        .language-item {
            display: inline-block;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="resume-container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $profile->full_name ?? $user->name }}</h1>
            <div class="contact-info">
                {{ $profile->current_position ?? 'Professional' }}<br>
                Email: {{ $user->email }} | Phone: {{ $user->phone }}<br>
                Location: {{ $profile->city ?? '' }}{{ $profile->city && $profile->country ? ', ' : '' }}{{ $profile->country ?? '' }}
            </div>
        </div>

        <!-- Professional Summary -->
        @if($profile && $profile->bio)
        <div class="section">
            <div class="section-title">Professional Summary</div>
            <div class="bio-text">{{ $profile->bio }}</div>
        </div>
        @endif

        <!-- Personal Information -->
        <div class="section">
            <div class="section-title">Personal Information</div>
            @if($profile->nationality)
            <div class="info-row">
                <span class="info-label">Nationality:</span>
                <span class="info-value">{{ $profile->nationality }}</span>
            </div>
            @endif
            @if($profile->date_of_birth)
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value">{{ $profile->date_of_birth->format('F d, Y') }}</span>
            </div>
            @endif
            @if($profile->gender)
            <div class="info-row">
                <span class="info-label">Gender:</span>
                <span class="info-value">{{ ucfirst($profile->gender) }}</span>
            </div>
            @endif
            @if($profile->experience_years)
            <div class="info-row">
                <span class="info-label">Experience:</span>
                <span class="info-value">{{ $profile->experience_years }}</span>
            </div>
            @endif
            @if($profile->expected_salary)
            <div class="info-row">
                <span class="info-label">Expected Salary:</span>
                <span class="info-value">{{ $profile->expected_salary }} AED</span>
            </div>
            @endif
        </div>

        <!-- Languages -->
        @if($profile && $profile->languages && count($profile->languages) > 0)
        <div class="section">
            <div class="section-title">Languages</div>
            <div class="languages">
                @foreach($profile->languages as $language)
                <span class="language-item">• {{ $language }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Skills -->
        @if($profile && $profile->skills && count($profile->skills) > 0)
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="skills-container">
                @foreach($profile->skills as $skill)
                <span class="skill-tag">{{ $skill }}</span>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Work Experience -->
        @if($experiences->count() > 0)
        <div class="section">
            <div class="section-title">Work Experience</div>
            @foreach($experiences as $exp)
            <div class="entry-item">
                <div class="entry-header">
                    <div>
                        <div class="entry-title">{{ $exp->job_title }}</div>
                        <div class="entry-subtitle">{{ $exp->company_name }}</div>
                    </div>
                    <div class="entry-date">
                        {{ $exp->start_date ? $exp->start_date->format('M Y') : 'N/A' }} - 
                        {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                    </div>
                </div>
                @if($exp->description)
                <div class="entry-description">{{ $exp->description }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Projects -->
        @if($projects->count() > 0)
        <div class="section">
            <div class="section-title">Projects</div>
            @foreach($projects as $project)
            <div class="entry-item">
                <div class="entry-title">{{ $project->project_name }}</div>
                @if($project->project_type)
                <div class="entry-subtitle">{{ $project->project_type }}</div>
                @endif
                @if($project->project_link)
                <div style="color: #673AB7; font-size: 10px;">{{ $project->project_link }}</div>
                @endif
                @if($project->description)
                <div class="entry-description">{{ $project->description }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <!-- Education -->
        @if($educations->count() > 0)
        <div class="section">
            <div class="section-title">Education</div>
            @foreach($educations as $edu)
            <div class="entry-item">
                <div class="entry-header">
                    <div>
                        <div class="entry-title">{{ $edu->degree }}{{ $edu->field_of_study ? ' in ' . $edu->field_of_study : '' }}</div>
                        <div class="entry-subtitle">{{ $edu->institution_name }}</div>
                    </div>
                    <div class="entry-date">
                        {{ $edu->end_date ? $edu->end_date->format('Y') : 'In Progress' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Certificates -->
        @if($certificates->count() > 0)
        <div class="section">
            <div class="section-title">Certifications</div>
            @foreach($certificates as $cert)
            <div class="entry-item">
                <div class="entry-header">
                    <div>
                        <div class="entry-title">{{ $cert->certificate_name }}</div>
                        <div class="entry-subtitle">{{ $cert->issuing_organization }}</div>
                    </div>
                    <div class="entry-date">
                        {{ $cert->issue_date ? $cert->issue_date->format('M Y') : '' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Footer -->
        <div style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #ccc; text-align: center; font-size: 10px; color: #999;">
            Generated via FullTimez - {{ now()->format('F d, Y') }}
        </div>
    </div>
</body>
</html>



