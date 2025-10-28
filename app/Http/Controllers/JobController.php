<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // Base query for published jobs
        $baseQuery = JobPosting::where('status', 'published')
            ->with(['employer.employerProfile', 'category']);

        // Handle header search - title
        if ($request->filled('title')) {
            $baseQuery->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%')
                  ->orWhere('description', 'like', '%' . $request->title . '%');
            });
        }

        // Handle sidebar search
        if ($request->filled('search')) {
            $baseQuery->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $baseQuery->where('category_id', $request->category);
        }

        // Handle location from header or sidebar
        if ($request->filled('location')) {
            $baseQuery->where(function($q) use ($request) {
                $q->where('location_city', 'like', '%' . $request->location . '%')
                  ->orWhere('location_state', 'like', '%' . $request->location . '%')
                  ->orWhere('location_country', 'like', '%' . $request->location . '%');
            });
        }

        // Handle country from header
        if ($request->filled('country')) {
            $baseQuery->where('location_country', 'like', '%' . $request->country . '%');
        }

        if ($request->filled('education')) {
            $baseQuery->where('education_level', 'like', '%' . $request->education . '%');
        }

        if ($request->filled('salary')) {
            $salaryRange = $request->salary;
            if ($salaryRange === '15000+') {
                $baseQuery->where('salary_min', '>=', 15000);
            } else {
                [$min, $max] = explode('-', $salaryRange);
                $baseQuery->whereBetween('salary_min', [(int)$min, (int)$max]);
            }
        }

        // Clone query for featured and recommended splits
        $featuredJobs = (clone $baseQuery)
            ->featured()
            ->orderBy('featured_expires_at', 'desc')
            ->get();

        $recommendedJobs = (clone $baseQuery)
            ->notFeatured()
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        $categories = JobCategory::where('is_active', true)->get();

        return view('jobs.index', [
            'featuredJobs' => $featuredJobs,
            'recommendedJobs' => $recommendedJobs,
            'categories' => $categories,
        ]);
    }

    public function show($slug)
    {
        $job = JobPosting::where('slug', $slug)
            ->where('status', 'published')
            ->with(['employer.employerProfile', 'category'])
            ->firstOrFail();

        $job->increment('views_count');

        $relatedJobs = JobPosting::where('status', 'published')
            ->where('category_id', $job->category_id)
            ->where('id', '!=', $job->id)
            ->take(3)
            ->get();

        return view('jobs.show', compact('job', 'relatedJobs'));
    }

    public function search(Request $request)
    {
        $query = JobPosting::where('status', 'published')
            ->with(['employer.employerProfile', 'category']);

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('location')) {
            $query->where('location_city', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('specialist')) {
            $query->whereJsonContains('skills_required', $request->specialist);
        }

        $jobs = $query->orderByRaw('featured_expires_at IS NOT NULL AND featured_expires_at > NOW() DESC, created_at DESC')->paginate(12);
        $categories = JobCategory::where('is_active', true)->get();

        return view('jobs.index', compact('jobs', 'categories'));
    }

    public function getSuggestions(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = JobPosting::where('status', 'published')
            ->where('title', 'like', '%' . $query . '%')
            ->select('title')
            ->distinct()
            ->limit(10)
            ->get()
            ->pluck('title');

        return response()->json($suggestions);
    }
}
