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

        // Handle header search - title (combines title and description)
        if ($request->filled('title')) {
            $title = trim($request->title);
            $baseQuery->where(function($q) use ($title) {
                $q->where('title', 'like', '%' . $title . '%')
                  ->orWhere('description', 'like', '%' . $title . '%')
                  ->orWhere('requirements', 'like', '%' . $title . '%');
            });
        }

        // Handle sidebar search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('requirements', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $baseQuery->where('category_id', $request->category);
        }

        // Handle country from header - exact match first, then partial
        if ($request->filled('country')) {
            $country = trim($request->country);
            $baseQuery->where(function($q) use ($country) {
                $q->where('location_country', '=', $country)
                  ->orWhere('location_country', 'like', '%' . $country . '%');
            });
        }

        // Handle location from header - check city, state, and country
        if ($request->filled('location')) {
            $location = trim($request->location);
            $baseQuery->where(function($q) use ($location) {
                $q->where('location_city', '=', $location)
                  ->orWhere('location_city', 'like', '%' . $location . '%')
                  ->orWhere('location_state', '=', $location)
                  ->orWhere('location_state', 'like', '%' . $location . '%');
            });
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
        
        // Get countries and cities for search dropdowns
        $countries = \App\Models\Country::where('is_active', true)->orderBy('name')->get();
        $cities = \App\Models\City::where('is_active', true)
            ->when($request->filled('country'), function($q) use ($request) {
                $q->whereHas('country', function($cq) use ($request) {
                    $cq->where('name', 'like', '%' . $request->country . '%');
                });
            })
            ->orderBy('name')
            ->get();

        return view('jobs.index', [
            'featuredJobs' => $featuredJobs,
            'recommendedJobs' => $recommendedJobs,
            'categories' => $categories,
            'countries' => $countries,
            'cities' => $cities,
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
