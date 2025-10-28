<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_seekers' => User::whereHas('role', function($q) {
                $q->where('slug', 'seeker');
            })->count(),
            'total_employers' => User::whereHas('role', function($q) {
                $q->where('slug', 'employer');
            })->count(),
            'total_jobs' => JobPosting::count(),
            'published_jobs' => JobPosting::where('status', 'published')->count(),
            'pending_jobs' => JobPosting::where('status', 'draft')->count(),
            'total_applications' => JobApplication::count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
        ];

        $recent_users = User::with('role')->latest()->take(5)->get();
        $recent_jobs = JobPosting::with('employer')->latest()->take(5)->get();
        $recent_applications = JobApplication::with(['job', 'seeker'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_jobs', 'recent_applications'));
    }
}



