<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobCategory;
use App\Models\Package;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::where('employer_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Check if user is employer
        if (!$user->isEmployer()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Employer privileges required.');
        }
        
        $profile = $user->employerProfile;
        if (!$profile) {
            return redirect()->route('dashboard')->with('error', 'Employer profile not found. Please complete your registration.');
        }
        
        // Require admin approval before posting jobs
        if ($profile->verification_status !== 'verified') {
            return redirect()->route('employer.documents.index')
                ->with('error', 'Your employer profile is pending admin approval. Please complete document verification and wait for admin approval before posting jobs.');
        }

        // Check if all required documents are approved
        $requiredTypes = ['trade_license', 'office_landline', 'company_email'];
        $approvedDocuments = $user->employerDocuments()
            ->whereIn('document_type', $requiredTypes)
            ->where('status', 'approved')
            ->get();

        $approvedTypes = $approvedDocuments->pluck('document_type')->toArray();
        $allDocumentsApproved = count(array_intersect($requiredTypes, $approvedTypes)) === count($requiredTypes);

        if (!$allDocumentsApproved) {
            $missingDocuments = array_diff($requiredTypes, $approvedTypes);
            $missingNames = array_map(function($type) {
                return match($type) {
                    'trade_license' => 'Trade License',
                    'office_landline' => 'Office Landline',
                    'company_email' => 'Company Email',
                    default => ucfirst(str_replace('_', ' ', $type))
                };
            }, $missingDocuments);

            return redirect()->route('employer.documents.index')
                ->with('error', 'You cannot post jobs until all required documents are approved. Missing: ' . implode(', ', $missingNames) . '. Please complete document verification first.');
        }

        $categories = JobCategory::where('is_active', true)->get();
        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();
        return view('employer.jobs.create', compact('categories', 'packages'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $profile = $user->employerProfile;
        if (!$profile) {
            return redirect()->route('dashboard')->with('error', 'Employer profile not found. Please complete your registration.');
        }
        
        // Require admin approval before posting jobs
        if ($profile->verification_status !== 'verified') {
            return redirect()->route('employer.documents.index')
                ->with('error', 'Your employer profile is pending admin approval. Please complete document verification and wait for admin approval before posting jobs.');
        }

        // Check if all required documents are approved
        $requiredTypes = ['trade_license', 'office_landline', 'company_email'];
        $approvedDocuments = $user->employerDocuments()
            ->whereIn('document_type', $requiredTypes)
            ->where('status', 'approved')
            ->get();

        $approvedTypes = $approvedDocuments->pluck('document_type')->toArray();
        $allDocumentsApproved = count(array_intersect($requiredTypes, $approvedTypes)) === count($requiredTypes);

        if (!$allDocumentsApproved) {
            $missingDocuments = array_diff($requiredTypes, $approvedTypes);
            $missingNames = array_map(function($type) {
                return match($type) {
                    'trade_license' => 'Trade License',
                    'office_landline' => 'Office Landline',
                    'company_email' => 'Company Email',
                    default => ucfirst(str_replace('_', ' ', $type))
                };
            }, $missingDocuments);

            return redirect()->route('employer.documents.index')
                ->with('error', 'You cannot post jobs until all required documents are approved. Missing: ' . implode(', ', $missingNames) . '. Please complete document verification first.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:job_categories,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'experience_years' => 'nullable|string',
            'education_level' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|in:AED,USD,EUR,GBP',
            'location_city' => 'required|string',
            'location_country' => 'required|string',
            'application_deadline' => 'nullable|date|after:today',
            'ad_type' => 'required|in:recommended,featured',
            'featured_duration' => 'nullable|in:7,15,30',
            'consent' => 'required|accepted',
        ]);

        // Additional validation for featured ads
        if ($validated['ad_type'] === 'featured' && empty($validated['featured_duration'])) {
            return redirect()->back()
                ->withErrors(['featured_duration' => 'Featured duration is required for featured ads.'])
                ->withInput();
        }

        // Enforce rule: Only one active Recommended (FREE) ad per employer at a time
        if (($validated['ad_type'] ?? null) === 'recommended') {
            $hasActiveRecommended = JobPosting::where('employer_id', auth()->id())
                ->where('ad_type', 'recommended')
                ->where(function($q) {
                    $q->where('status', 'pending')
                      ->orWhere(function($q2) {
                          $q2->where('status', 'published')
                             ->where(function($q3) {
                                 $q3->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                             });
                      });
                })
                ->exists();

            if ($hasActiveRecommended) {
                return redirect()->back()
                    ->withErrors(['ad_type' => 'You already have an active Recommended ad. You can post a new one after the current ad expires.'])
                    ->withInput();
            }
        }

        // Handle different ad types
        if ($validated['ad_type'] === 'featured') {
            // Featured ad - use package-based durations; employer selects a package, not a custom date
            $prices = [
                '7' => 49,
                '15' => 89,
                '30' => 149
            ];
            
            $duration = $validated['featured_duration'];
            $amount = $prices[$duration] ?? 49;
            
            // Create job with featured status for admin review
            $validated['employer_id'] = auth()->id();
            $validated['slug'] = Str::slug($validated['title']) . '-' . time();
            $validated['status'] = 'featured_pending'; // Special status for featured ads
            $validated['published_at'] = null;
            $validated['priority'] = 'featured';
            $validated['featured_duration'] = $duration;
            $validated['featured_amount'] = $amount;
            
            $job = JobPosting::create($validated);
            
            // Create payment verification for featured ad
            $paymentVerification = \App\Models\PaymentVerification::create([
                'employer_id' => auth()->id(),
                'job_id' => $job->id,
                'package_id' => null, // No package for featured ads
                'package_type' => 'featured_ad',
                'amount' => $amount,
                'currency' => 'AED',
                'payment_method' => 'pending',
                'transaction_id' => 'featured_ad_' . $job->id,
                'status' => 'pending',
                'payment_notes' => 'Featured ad request - ' . $duration . ' days duration'
            ]);
            
            // Notify all admins about new featured ad request
            $adminRole = Role::where('slug', 'admin')->first();
            if ($adminRole) {
                $admins = User::where('role_id', $adminRole->id)->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\FeaturedAdRequest(
                        $job->id,
                        $job->title,
                        $amount,
                        $duration
                    ));
                }
            }
            
            return redirect()->route('employer.jobs.index')
                ->with('success', 'Your featured ad has been submitted for review. You will receive a payment link via email shortly.');
        }

        // For recommended jobs (free), set to pending for admin approval
        $validated['employer_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . time();
        $validated['status'] = 'pending';
        $validated['published_at'] = null; // Will be set when admin approves
        $validated['priority'] = 'normal'; // Recommended section

        $job = JobPosting::create($validated);

        // Create payment verification for recommended ad (free)
        $paymentVerification = \App\Models\PaymentVerification::create([
            'employer_id' => auth()->id(),
            'job_id' => $job->id,
            'package_id' => null, // No package for recommended ads
            'package_type' => 'recommended_ad',
            'amount' => 0, // Free
            'currency' => 'AED',
            'payment_method' => 'free',
            'transaction_id' => 'recommended_ad_' . $job->id,
            'status' => 'pending',
            'payment_notes' => 'Recommended ad (FREE 7 days) - No payment required'
        ]);

        // Notify all admins about new job posting
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($job) {
                $admin->notify(new \App\Notifications\NewJobPendingApproval($job->id, $job->title, $job->employer->name));
            });
        }

        $successMessage = 'Your job has been sent for admin\'s approval.';
        if ($profile->verification_status === 'pending') {
            $successMessage = 'Your job has been submitted. It will be reviewed by admin along with your employer profile verification.';
        }

        return redirect()->route('employer.jobs.index')
            ->with('success', $successMessage);
    }

    public function edit(JobPosting $job)
    {
        if ($job->employer_id !== auth()->id()) {
            abort(403);
        }

        $categories = JobCategory::where('is_active', true)->get();
        return view('employer.jobs.edit', compact('job', 'categories'));
    }

    public function update(Request $request, JobPosting $job)
    {
        if ($job->employer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:job_categories,id',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'experience_years' => 'nullable|string',
            'education_level' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'location_city' => 'required|string',
            'location_country' => 'required|string',
            'application_deadline' => 'nullable|date',
        ]);

        $job->update($validated);

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(JobPosting $job)
    {
        if ($job->employer_id !== auth()->id()) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('employer.jobs.index')
            ->with('success', 'Job deleted successfully!');
    }
}

