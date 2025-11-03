<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCVRequest;
use App\Models\Project;
use App\Models\ExperienceRecord;
use App\Models\EducationRecord;
use App\Models\Certificate;
use App\Notifications\UserActionNotification;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        
        // Load existing CV data
        $profile = $user->seekerProfile;
        $projects = $user->projects()->get();
        $experiences = $user->experienceRecords()->get();
        $educations = $user->educationRecords()->get();
        $certificates = $user->certificates()->get();
        
        return view('seeker.create-cv', compact('profile', 'projects', 'experiences', 'educations', 'certificates'));
    }

    public function store(StoreCVRequest $request)
    {
        $user = auth()->user();

        if ($user->seekerProfile) {
            // Update basic profile data
            $profileData = [
                'current_position' => $request->current_position,
                'expected_salary' => $request->expected_salary,
                'experience_years' => $request->experience_years,
                'nationality' => $request->nationality,
                'bio' => $request->bio,
            ];

            // Handle languages - Always save (even if empty)
            $languages = [];
            if ($request->filled('first_language')) {
                $languages[] = $request->first_language;
            }
            if ($request->filled('second_language')) {
                $languages[] = $request->second_language;
            }
            // Save languages array (empty array if no languages selected)
            $profileData['languages'] = !empty($languages) ? json_encode($languages) : json_encode([]);

            // Handle skills - convert comma-separated string to JSON array
            if ($request->skills) {
                $skillsArray = array_map('trim', explode(',', $request->skills));
                $skillsArray = array_filter($skillsArray);
                $profileData['skills'] = json_encode(array_values($skillsArray));
            }

            // Set approval_status to pending when CV is updated
            $profileData['approval_status'] = 'pending';
            
            $user->seekerProfile()->update($profileData);
        }

        // Handle multiple projects (Update/Insert)
        if ($request->has('projects')) {
            // Delete old projects for this user (clean slate)
            Project::where('seeker_id', $user->id)->delete();
            
            foreach ($request->projects as $projectData) {
                if (!empty($projectData['name'])) {
                    Project::create([
                        'seeker_id' => $user->id,
                        'project_name' => $projectData['name'],
                        'project_type' => $projectData['type'] ?? null,
                        'project_link' => $projectData['link'] ?? null,
                        'description' => $projectData['description'] ?? null,
                    ]);
                }
            }
        }

        // Handle multiple experience records
        if ($request->has('experience')) {
            // Delete old experience for this user
            ExperienceRecord::where('seeker_id', $user->id)->delete();
            
            foreach ($request->experience as $expData) {
                if (!empty($expData['company']) && !empty($expData['title'])) {
                    ExperienceRecord::create([
                        'seeker_id' => $user->id,
                        'company_name' => $expData['company'],
                        'job_title' => $expData['title'],
                        'start_date' => $expData['start_date'] ?? null,
                        'end_date' => $expData['end_date'] ?? null,
                        'is_current' => empty($expData['end_date']),
                        'description' => $expData['description'] ?? null,
                    ]);
                }
            }
        }

        // Handle multiple education records
        if ($request->has('education')) {
            // Delete old education for this user
            EducationRecord::where('seeker_id', $user->id)->delete();
            
            foreach ($request->education as $eduData) {
                if (!empty($eduData['institution']) && !empty($eduData['degree'])) {
                    $endDate = null;
                    if (!empty($eduData['year'])) {
                        $endDate = \Carbon\Carbon::create((int)$eduData['year'], 6, 1);
                    }
                    
                    EducationRecord::create([
                        'seeker_id' => $user->id,
                        'institution_name' => $eduData['institution'],
                        'degree' => $eduData['degree'],
                        'field_of_study' => $eduData['field'] ?? null,
                        'start_date' => $endDate ? $endDate->copy()->subYears(4) : null,
                        'end_date' => $endDate,
                    ]);
                }
            }
        }

        // Handle multiple certificates
        if ($request->has('certificates')) {
            // Delete old certificates for this user
            Certificate::where('seeker_id', $user->id)->delete();
            
            foreach ($request->certificates as $certData) {
                if (!empty($certData['name']) && !empty($certData['organization'])) {
                    Certificate::create([
                        'seeker_id' => $user->id,
                        'certificate_name' => $certData['name'],
                        'issuing_organization' => $certData['organization'],
                        'issue_date' => $certData['date'] ?? now(),
                        'does_not_expire' => true,
                    ]);
                }
            }
        }

        // Send notification about CV update
        $user->notify(new UserActionNotification(
            'cv_updated',
            'updated your CV',
            'Your CV has been updated successfully. All your information including projects, experience, education, and certificates have been saved.',
            route('seeker.cv.create'),
            'View CV'
        ));

        return redirect()->route('seeker.cv.create')->with('success', 'CV saved successfully! All your information has been updated.');
    }
}
