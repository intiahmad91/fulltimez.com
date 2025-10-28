<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured jobs (jobs with featured_expires_at in the future)
        $featuredJobs = JobPosting::where('status', 'published')
            ->where('featured_expires_at', '>', now())
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with(['employer.employerProfile', 'category'])
            ->orderBy('featured_expires_at', 'desc')
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->get();

        // Get recommended jobs (non-featured published jobs that are still active)
        $recommendedJobs = JobPosting::where('status', 'published')
            ->where(function($q) {
                $q->whereNull('featured_expires_at')
                  ->orWhere('featured_expires_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->with(['employer.employerProfile', 'category'])
            ->orderBy('published_at', 'desc')
            ->take(6)
            ->get();

        // If no recommended jobs found, show any published jobs as fallback
        if ($recommendedJobs->isEmpty()) {
            $recommendedJobs = JobPosting::where('status', 'published')
                ->where(function($q) {
                    $q->whereNull('featured_expires_at')
                      ->orWhere('featured_expires_at', '<=', now());
                })
                ->with(['employer.employerProfile', 'category'])
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        }

        $jobSeekers = User::whereHas('role', function($q) {
                $q->where('slug', 'seeker');
            })
            ->with('seekerProfile')
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get();

        // Get featured job seeker for the profile section
        $featuredJobSeeker = User::whereHas('role', function($q) {
                $q->where('slug', 'seeker');
            })
            ->with('seekerProfile')
            ->where('status', 'active')
            ->whereHas('seekerProfile', function($q) {
                $q->whereNotNull('full_name')
                  ->whereNotNull('current_position');
            })
            ->latest()
            ->first();

        return view('home', compact('featuredJobs', 'recommendedJobs', 'jobSeekers', 'featuredJobSeeker'));
    }
}

