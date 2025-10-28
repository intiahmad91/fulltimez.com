<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NewJobApplication;
use App\Notifications\JobApplicationReceived;
use App\Notifications\JobApplicationSubmitted;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function store(Request $request, $jobId)
    {
        // Gate: jobseeker must be verified by admin before applying
        $seekerProfile = auth()->user()->seekerProfile;
        if (!$seekerProfile || $seekerProfile->verification_status !== 'verified') {
            return back()->withErrors(['error' => 'Your profile is pending admin approval. You can apply once your profile is verified.']);
        }

        $job = JobPosting::findOrFail($jobId);

        $existingApplication = JobApplication::where('job_id', $job->id)
            ->where('seeker_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['error' => 'You have already applied for this job.']);
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
        ]);

        $applicationData = [
            'job_id' => $job->id,
            'seeker_id' => auth()->id(),
            'cover_letter' => $validated['cover_letter'] ?? null,
            'status' => 'pending',
        ];

        $application = JobApplication::create($applicationData);

        $job->increment('applications_count');

        // Notify the jobseeker about their application submission
        $jobseeker = auth()->user();
        $companyName = $job->employer->employerProfile->company_name ?? 'Company';
        
        $jobseeker->notify(new JobApplicationSubmitted(
            $application->id,
            $job->title,
            $companyName,
            now()->format('F j, Y \a\t g:i A'),
            $validated['cover_letter'] ?? null
        ));

        // Notify the employer about the new job application
        $employer = $job->employer;
        
        if ($employer) {
            $employer->notify(new JobApplicationReceived(
                $application->id,
                $jobseeker->name,
                $job->title,
                $application->cover_letter
            ));
        }

        // Notify all admin users about the new job application
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $companyName = $job->employer->employerProfile->company_name ?? 'Company';
            
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($application, $jobseeker, $job, $companyName) {
                $admin->notify(new NewJobApplication(
                    $application->id,
                    $jobseeker->name,
                    $job->title,
                    $companyName
                ));
            });
        }

        return redirect()->route('jobs.show', $job->slug)
            ->with('success', 'Application submitted successfully!');
    }

    public function index()
    {
        $applications = JobApplication::where('seeker_id', auth()->id())
            ->with(['job.employer.employerProfile'])
            ->latest()
            ->paginate(10);

        return view('seeker.applications', compact('applications'));
    }
}

