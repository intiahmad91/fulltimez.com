<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\PaymentVerification;
use App\Models\Package;
use App\Models\Role;
use App\Models\User;
use App\Notifications\PaymentVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by routes
    }

    public function index()
    {
        $employer = auth()->user();
        $payments = PaymentVerification::where('employer_id', $employer->id)
            ->with(['job', 'verifiedBy'])
            ->latest()
            ->paginate(10);

        return view('employer.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $jobId = $request->get('job_id');
        $packageId = $request->get('package_id');
        
        $job = null;
        if ($jobId) {
            $job = JobPosting::where('employer_id', auth()->id())->findOrFail($jobId);
        }

        $packages = Package::where('is_active', true)->orderBy('sort_order')->get();
        $selectedPackage = null;
        if ($packageId) {
            $selectedPackage = Package::find($packageId);
        }

        return view('employer.payments.create', compact('job', 'packages', 'selectedPackage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'nullable|exists:job_postings,id',
            'package_id' => 'required|exists:packages,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'required|string|max:255',
            'payment_notes' => 'nullable|string|max:1000',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if there's pending job data from job posting
        $pendingJobData = session('pending_job_data');
        $job = null;
        
        if ($pendingJobData) {
            // Create the job with pending status
            $pendingJobData['employer_id'] = auth()->id();
            $pendingJobData['slug'] = \Illuminate\Support\Str::slug($pendingJobData['title']) . '-' . time();
            $pendingJobData['status'] = 'pending';
            $pendingJobData['published_at'] = null;
            
            $job = JobPosting::create($pendingJobData);
            $validated['job_id'] = $job->id;
            
            // Clear the session data
            session()->forget('pending_job_data');
        } else if ($validated['job_id']) {
            // Verify job belongs to employer
            $job = JobPosting::where('employer_id', auth()->id())->findOrFail($validated['job_id']);
        }

        // Handle file upload
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_' . auth()->id() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $directory = public_path('payment-screenshots');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            $file->move($directory, $filename);
            $validated['payment_screenshot'] = 'payment-screenshots/' . $filename;
        }

        $validated['employer_id'] = auth()->id();
        
        // Set package_type based on selected package
        $package = Package::find($validated['package_id']);
        $validated['package_type'] = $package ? strtolower(str_replace(' ', '_', $package->name)) : 'premium';

        // Create payment verification
        $payment = PaymentVerification::create($validated);

        // Notify all admin users
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $employer = auth()->user();
            $companyName = $employer->employerProfile->company_name ?? 'Company';
            $jobTitle = $job->title ?? null;

            $package = Package::find($payment->package_id);
            $packageName = $package ? $package->name : 'Unknown Package';
            
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($payment, $employer, $companyName, $jobTitle, $packageName) {
                $admin->notify(new PaymentVerificationRequest(
                    $payment->id,
                    $employer->name,
                    $companyName,
                    $packageName,
                    $payment->amount,
                    $payment->currency,
                    $jobTitle
                ));
            });
        }

        return redirect()->route('employer.payments.index')
            ->with('success', 'Payment verification request submitted successfully! Admin will review and verify your payment.');
    }

    public function show(PaymentVerification $payment)
    {
        // Ensure the payment belongs to the authenticated employer
        if ($payment->employer_id !== auth()->id()) {
            abort(403, 'Unauthorized access to payment verification.');
        }

        $payment->load(['job', 'verifiedBy']);

        return view('employer.payments.show', compact('payment'));
    }

    public function stripe(Request $request)
    {
        $amount = $request->get('amount');
        $duration = $request->get('duration');
        $jobTitle = $request->get('job_title');
        
        if (!$amount || !$duration) {
            return redirect()->route('employer.jobs.create')
                ->with('error', 'Invalid payment parameters.');
        }

        // Check if Stripe keys are configured
        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret || $stripeSecret === 'sk_test_your_stripe_secret_key_here') {
            // For testing purposes, use a mock payment intent
            return view('employer.payments.stripe', [
                'paymentIntent' => (object)[
                    'id' => 'pi_test_' . time(),
                    'client_secret' => 'pi_test_' . time() . '_secret_test',
                    'amount' => $amount * 100,
                    'currency' => 'aed'
                ],
                'amount' => $amount,
                'duration' => $duration,
                'jobTitle' => $jobTitle
            ]);
        }

        // Set Stripe API key
        Stripe::setApiKey($stripeSecret);

        try {
            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'aed',
                'metadata' => [
                    'employer_id' => auth()->id(),
                    'duration' => $duration,
                    'job_title' => $jobTitle,
                ],
            ]);

            return view('employer.payments.stripe', compact('paymentIntent', 'amount', 'duration', 'jobTitle'));
        } catch (\Exception $e) {
            \Log::error('Stripe payment initialization failed: ' . $e->getMessage());
            return redirect()->route('employer.jobs.create')
                ->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request)
    {
        $paymentIntentId = $request->get('payment_intent');
        
        if (!$paymentIntentId) {
            return redirect()->route('employer.jobs.create')
                ->with('error', 'Invalid payment confirmation.');
        }

        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Verify payment intent
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                // Get pending job data
                $pendingJobData = session('pending_job_data');
                
                if ($pendingJobData) {
                    // Create the job with featured status
                    $pendingJobData['employer_id'] = auth()->id();
                    $pendingJobData['slug'] = \Illuminate\Support\Str::slug($pendingJobData['title']) . '-' . time();
                    $pendingJobData['status'] = 'pending'; // Will be approved by admin
                    $pendingJobData['published_at'] = null;
                    $pendingJobData['priority'] = 'premium';
                    $pendingJobData['featured_duration'] = $paymentIntent->metadata['duration'];
                    
                    $job = JobPosting::create($pendingJobData);
                    
                    // Create payment record
                    PaymentVerification::create([
                        'employer_id' => auth()->id(),
                        'job_id' => $job->id,
                        'package_id' => null,
                        'amount' => $paymentIntent->amount / 100,
                        'currency' => strtoupper($paymentIntent->currency),
                        'payment_method' => 'stripe',
                        'transaction_id' => $paymentIntent->id,
                        'status' => 'verified',
                        'verified_at' => now(),
                        'verified_by' => null, // Auto-verified for Stripe
                        'payment_notes' => 'Stripe payment completed successfully',
                    ]);
                    
                    // Clear session data
                    session()->forget('pending_job_data');
                    
                    return redirect()->route('employer.jobs.index')
                        ->with('success', 'Payment successful! Your featured job has been submitted for admin approval.');
                }
            }
            
            return redirect()->route('employer.jobs.create')
                ->with('error', 'Payment verification failed.');
                
        } catch (\Exception $e) {
            return redirect()->route('employer.jobs.create')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function stripeCancel()
    {
        return redirect()->route('employer.jobs.create')
            ->with('info', 'Payment was cancelled. You can try again.');
    }
}