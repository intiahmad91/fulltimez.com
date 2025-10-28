<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Notifications\ApplicationStatusUpdated;
use App\Notifications\JobOfferProposal;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::whereHas('job', function($query) {
            $query->where('employer_id', auth()->id());
        })
        ->with(['job', 'seeker.seekerProfile'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('employer.applications', compact('applications'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        if ($application->job->employer_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,interviewed,offered,rejected,withdrawn'
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;

        $application->update([
            'status' => $newStatus
        ]);

        // Send email notification to jobseeker about status update
        $jobseeker = $application->seeker;
        $job = $application->job;
        $companyName = $job->employer->employerProfile->company_name ?? 'Company';

        if ($jobseeker && $oldStatus !== $newStatus) {
            // Send regular status update notification
            $jobseeker->notify(new ApplicationStatusUpdated(
                $application->id,
                $job->title,
                $companyName,
                $oldStatus,
                $newStatus,
                $request->employer_notes ?? null
            ));

            // If status is "offered", send special job offer proposal
            if ($newStatus === 'offered') {
                $employer = $job->employer;
                $employerProfile = $employer->employerProfile;
                
                $jobseeker->notify(new JobOfferProposal(
                    $application->id,
                    $job->title,
                    $companyName,
                    $employer->name,
                    $request->employer_position ?? 'HR Manager',
                    $employer->email,
                    $employer->phone ?? $employerProfile->contact_phone ?? 'N/A',
                    $request->department ?? null,
                    $request->start_date ?? null,
                    $request->salary ?? null,
                    $request->work_type ?? $job->employment_type ?? null,
                    $request->benefits ?? null,
                    $request->confirmation_deadline ?? null,
                    $request->employer_notes ?? null
                ));
            }
        }

        return redirect()->back()->with('success', 'Application status updated successfully! The jobseeker has been notified via email.');
    }
}



