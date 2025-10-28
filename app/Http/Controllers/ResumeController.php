<?php

namespace App\Http\Controllers;

use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    public function preview()
    {
        $user = auth()->user();
        
        if (!$user->isSeeker()) {
            return redirect()->route('dashboard')->with('error', 'Only job seekers can view resume.');
        }
        
        $profile = $user->seekerProfile;
        $projects = $user->projects()->get();
        $experiences = $user->experienceRecords()->orderBy('start_date', 'desc')->get();
        $educations = $user->educationRecords()->orderBy('end_date', 'desc')->get();
        $certificates = $user->certificates()->orderBy('issue_date', 'desc')->get();
        
        return view('seeker.resume-preview', compact('user', 'profile', 'projects', 'experiences', 'educations', 'certificates'));
    }

    public function download()
    {
        $user = auth()->user();
        
        if (!$user->isSeeker()) {
            abort(403, 'Unauthorized');
        }
        
        $profile = $user->seekerProfile;
        $projects = $user->projects()->get();
        $experiences = $user->experienceRecords()->orderBy('start_date', 'desc')->get();
        $educations = $user->educationRecords()->orderBy('end_date', 'desc')->get();
        $certificates = $user->certificates()->orderBy('issue_date', 'desc')->get();
        
        $pdf = Pdf::loadView('seeker.resume-pdf', compact('user', 'profile', 'projects', 'experiences', 'educations', 'certificates'));
        
        $filename = str_replace(' ', '_', $profile->full_name ?? $user->name) . '_Resume.pdf';
        
        // Send notification about resume download
        $user->notify(new UserActionNotification(
            'resume_downloaded',
            'downloaded your resume',
            'Your resume has been downloaded successfully. Keep it safe and use it for your job applications.',
            route('resume.preview'),
            'View Resume'
        ));
        
        return $pdf->download($filename);
    }
}



