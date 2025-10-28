<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with(['category', 'employer.employerProfile']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location_city', 'like', "%{$search}%")
                  ->orWhere('location_state', 'like', "%{$search}%")
                  ->orWhere('location_country', 'like', "%{$search}%");
            });
        }

        // Company filter
        if ($request->filled('company')) {
            $query->whereHas('employer.employerProfile', function($q) use ($request) {
                $q->where('company_name', 'like', "%{$request->company}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        
        $jobs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = JobCategory::where('is_active', true)->get();
        $employers = User::where('role_id', 2)->with('employerProfile')->get();
        
        return view('admin.jobs.create', compact('categories', 'employers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:job_categories,id',
            'employer_id' => 'required|exists:users,id',
            'location_city' => 'required|string|max:255',
            'location_state' => 'nullable|string|max:255',
            'location_country' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'employment_type' => 'required|in:full_time,part_time,contract,freelance',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'skills_required' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        $jobData = $request->all();
        
        // Handle skills_required as JSON
        if ($request->filled('skills_required')) {
            $skills = array_map('trim', explode(',', $request->skills_required));
            $jobData['skills_required'] = json_encode($skills);
        }

        $job = JobPosting::create($jobData);

        // If job is published immediately, notify employer and matching jobseekers
        if ($job->status === 'published') {
            if ($job->employer) {
                $expiresText = $job->expires_at ? $job->expires_at->format('F j, Y') : null;
                $job->employer->notify(new \App\Notifications\JobPublished(
                    $job->id,
                    $job->title,
                    $expiresText
                ));
            }
            $this->notifyMatchingJobseekers($job);
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job created successfully.');
    }

    public function show(JobPosting $job)
    {
        $job->load(['category', 'employer.employerProfile', 'applications.seeker.seekerProfile']);
        
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(JobPosting $job)
    {
        $categories = JobCategory::where('is_active', true)->get();
        $employers = User::where('role_id', 2)->with('employerProfile')->get();
        
        return view('admin.jobs.edit', compact('job', 'categories', 'employers'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:job_categories,id',
            'employer_id' => 'required|exists:users,id',
            'location_city' => 'required|string|max:255',
            'location_state' => 'nullable|string|max:255',
            'location_country' => 'required|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'employment_type' => 'required|in:full_time,part_time,contract,freelance',
            'experience_level' => 'required|in:entry,mid,senior,executive',
            'skills_required' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
        ]);

        $jobData = $request->all();
        
        // Handle skills_required as JSON
        if ($request->filled('skills_required')) {
            $skills = array_map('trim', explode(',', $request->skills_required));
            $jobData['skills_required'] = json_encode($skills);
        }

        $oldStatus = $job->status;
        $job->update($jobData);

        // If job status changed to published, notify employer and matching jobseekers
        if ($oldStatus !== 'published' && $job->status === 'published') {
            if ($job->employer) {
                $expiresText = $job->expires_at ? $job->expires_at->format('F j, Y') : null;
                $job->employer->notify(new \App\Notifications\JobPublished(
                    $job->id,
                    $job->title,
                    $expiresText
                ));
            }
            $this->notifyMatchingJobseekers($job);
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function approve(JobPosting $job)
    {
        // Calculate expiration date based on package
        $expiresAt = null;
        $featuredExpiresAt = now()->addDays(7); // All jobs start as featured for 7 days
        
        // Special handling for featured_pending status
        if ($job->status === 'featured_pending') {
            // Use the package duration selected; employer can't set date, only choose package
            $featuredDuration = $job->featured_duration ?? 7;
            $featuredExpiresAt = now()->addDays($featuredDuration);
            $expiresAt = now()->addDays($featuredDuration);
        } else {
            // For regular jobs, use priority-based calculation
            switch($job->priority) {
                case 'normal':
                    // FREE (Recommended) ads end in 7 days; employer cannot set this
                    $expiresAt = now()->addDays(7);
                    break;
                case 'premium':
                    $expiresAt = now()->addDays(60); // Premium gets longer duration
                    break;
                case 'premium_15':
                    $expiresAt = now()->addDays(75); // 15 days + 60 base
                    break;
                case 'premium_30':
                    $expiresAt = now()->addDays(90); // 30 days + 60 base
                    break;
            }
        }

        $job->update([
            'status' => 'published',
            'published_at' => now(),
            'expires_at' => $expiresAt,
            'featured_expires_at' => $featuredExpiresAt, // Set featured for 7 days
            'is_premium' => in_array($job->priority, ['premium', 'premium_15', 'premium_30']),
            'premium_expires_at' => in_array($job->priority, ['premium', 'premium_15', 'premium_30']) ? $expiresAt : null
        ]);

        // Notify employer that the job has been published
        if ($job->employer) {
            $expiresText = $expiresAt ? $expiresAt->format('F j, Y') : null;
            $job->employer->notify(new \App\Notifications\JobPublished(
                $job->id,
                $job->title,
                $expiresText
            ));
        }

        // Notify matching jobseekers about the new job
        $this->notifyMatchingJobseekers($job);

        $successMessage = 'Job approved and published successfully.';
        
        if ($job->status === 'featured_pending') {
            $featuredDuration = $job->featured_duration ?? 7;
            $successMessage = "Featured ad approved and published successfully. Job will be featured for {$featuredDuration} days, then move to recommended section.";
        } else {
            $successMessage = 'Job approved and published successfully. Job will be featured for 7 days, then move to recommended section.';
        }
        
        $successMessage .= ' Matching jobseekers have been notified.';
        
        return redirect()->back()->with('success', $successMessage);
    }

    private function notifyMatchingJobseekers(JobPosting $job)
    {
        // Get all jobseekers with profiles
        $jobseekers = \App\Models\User::whereHas('role', function($q) {
            $q->where('slug', 'seeker');
        })->with('seekerProfile')->get();

        $companyName = $job->employer->employerProfile->company_name ?? 'Company';
        $location = $job->location_city . ', ' . $job->location_country;
        
        // Prepare salary range
        $salaryRange = null;
        if ($job->salary_min || $job->salary_max) {
            if ($job->salary_min && $job->salary_max) {
                $salaryRange = '$' . number_format($job->salary_min) . ' - $' . number_format($job->salary_max);
            } elseif ($job->salary_min) {
                $salaryRange = 'From $' . number_format($job->salary_min);
            } else {
                $salaryRange = 'Up to $' . number_format($job->salary_max);
            }
        }

        foreach ($jobseekers as $jobseeker) {
            if (!$jobseeker->seekerProfile) {
                continue;
            }

            $matchingReasons = $this->getMatchingReasons($job, $jobseeker->seekerProfile);
            
            // Only notify if there are matching reasons
            if (!empty($matchingReasons)) {
                $jobseeker->notify(new \App\Notifications\NewJobMatchingProfile(
                    $job->id,
                    $job->title,
                    $companyName,
                    $location,
                    $salaryRange,
                    $job->employment_type,
                    $job->experience_level,
                    $matchingReasons
                ));
            }
        }
    }

    private function getMatchingReasons(JobPosting $job, $seekerProfile): array
    {
        $reasons = [];

        // Check skills match
        if ($job->skills_required && $seekerProfile->skills) {
            $jobSkills = is_array($job->skills_required) ? $job->skills_required : json_decode($job->skills_required, true);
            $seekerSkills = is_array($seekerProfile->skills) ? $seekerProfile->skills : json_decode($seekerProfile->skills, true);
            
            if ($jobSkills && $seekerSkills) {
                $matchingSkills = array_intersect(
                    array_map('strtolower', $jobSkills),
                    array_map('strtolower', $seekerSkills)
                );
                
                if (!empty($matchingSkills)) {
                    $reasons[] = 'Skills match: ' . implode(', ', array_slice($matchingSkills, 0, 3));
                }
            }
        }

        // Check experience level match
        if ($job->experience_level && $seekerProfile->experience_years) {
            $jobExp = strtolower($job->experience_level);
            $seekerExp = strtolower($seekerProfile->experience_years);
            
            // Simple matching logic - can be enhanced
            if (($jobExp === 'entry' && str_contains($seekerExp, '1-2')) ||
                ($jobExp === 'mid' && (str_contains($seekerExp, '3-5') || str_contains($seekerExp, '2-3'))) ||
                ($jobExp === 'senior' && (str_contains($seekerExp, '5-7') || str_contains($seekerExp, '6-8'))) ||
                ($jobExp === 'executive' && str_contains($seekerExp, '7-10'))) {
                $reasons[] = 'Experience level matches your profile';
            }
        }

        // Check location match
        if ($job->location_city && $seekerProfile->city) {
            if (strtolower($job->location_city) === strtolower($seekerProfile->city)) {
                $reasons[] = 'Same city location';
            }
        }

        // Check current position match with job title
        if ($job->title && $seekerProfile->current_position) {
            $jobTitleWords = explode(' ', strtolower($job->title));
            $positionWords = explode(' ', strtolower($seekerProfile->current_position));
            
            $commonWords = array_intersect($jobTitleWords, $positionWords);
            if (count($commonWords) >= 1) {
                $reasons[] = 'Job title matches your current position';
            }
        }

        return $reasons;
    }

    public function reject(JobPosting $job)
    {
        $job->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Job rejected successfully.');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job deleted successfully.');
    }
}