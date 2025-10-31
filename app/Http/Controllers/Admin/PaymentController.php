<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\PaymentVerification;
use App\Notifications\PaymentVerificationStatus;
use App\Notifications\PackagePurchaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by routes
    }

    public function index(Request $request)
    {
        $query = PaymentVerification::with(['employer.employerProfile', 'job', 'verifiedBy']);

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        $stats = [
            'total' => PaymentVerification::count(),
            'pending' => PaymentVerification::where('status', 'pending')->count(),
            'verified' => PaymentVerification::where('status', 'verified')->count(),
            'rejected' => PaymentVerification::where('status', 'rejected')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function show(PaymentVerification $payment)
    {
        $payment->load(['employer.employerProfile', 'job', 'verifiedBy']);

        return view('admin.payments.show', compact('payment'));
    }

    public function verify(Request $request, PaymentVerification $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $payment->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        // If payment is verified, activate the premium package
        if ($validated['status'] === 'verified') {
            $this->activatePremiumPackage($payment);
        }

        // Notify the employer about the status update
        $payment->employer->notify(new PaymentVerificationStatus(
            $payment->id,
            $payment->status,
            $payment->package_type,
            $payment->admin_notes
        ));

        // If payment is verified and it's a package purchase, notify all admins
        if ($validated['status'] === 'verified' && $payment->package_id) {
            $adminRole = \App\Models\Role::where('slug', 'admin')->first();
            if ($adminRole) {
                $admins = \App\Models\User::where('role_id', $adminRole->id)->get();
                $package = \App\Models\Package::find($payment->package_id);
                
                foreach ($admins as $admin) {
                    $admin->notify(new PackagePurchaseNotification(
                        $payment->employer,
                        $package,
                        $payment->amount,
                        $payment
                    ));
                }
            }
        }

        $statusText = $validated['status'] === 'verified' ? 'verified' : 'rejected';
        
        return redirect()->back()
            ->with('success', "Payment has been {$statusText} successfully! The employer has been notified.");
    }

    public function sendEmail(Request $request, PaymentVerification $payment)
    {
        $validated = $request->validate([
            'email_to' => 'required|email',
            'email_subject' => 'required|string|max:255',
            'email_message' => 'required|string|max:5000',
        ]);

        try {
            // Send email using Laravel's mail system
            \Illuminate\Support\Facades\Mail::raw($validated['email_message'], function ($message) use ($validated) {
                $message->to($validated['email_to'])
                        ->subject($validated['email_subject'])
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return redirect()->back()
                ->with('success', 'Email sent successfully to ' . $validated['email_to']);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    private function activatePremiumPackage(PaymentVerification $payment)
    {
        // Get the package details
        $package = \App\Models\Package::find($payment->package_id);
        if (!$package) {
            return; // Package not found, skip activation
        }

        $featuredExpiryDate = now()->addDays($package->duration_days);

        // If payment is for a specific job, activate premium for that job
        if ($payment->job_id) {
            $job = $payment->job;
            $job->update([
                'is_premium' => true,
                'premium_expires_at' => $featuredExpiryDate,
                'featured_expires_at' => $featuredExpiryDate,
                'status' => 'published', // Activate the job
                'published_at' => now(), // Set published date
            ]);
            
            // Notify all admins about the new premium job posting
            $adminRole = \App\Models\Role::where('slug', 'admin')->first();
            if ($adminRole) {
                \App\Models\User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($job) {
                    $admin->notify(new \App\Notifications\NewJobPendingApproval(
                        $job->id, 
                        $job->title, 
                        $job->employer->name
                    ));
                });
            }
        } else {
            // If payment is for general premium package, activate for all employer's jobs
            $employer = $payment->employer;
            
            JobPosting::where('employer_id', $employer->id)
                ->whereIn('status', ['pending', 'published'])
                ->update([
                    'is_premium' => true,
                    'premium_expires_at' => $featuredExpiryDate,
                    'featured_expires_at' => $featuredExpiryDate,
                    'status' => 'published',
                    'published_at' => now(),
                ]);
        }
    }

    private function calculatePremiumExpiry(string $packageType): \Carbon\Carbon
    {
        return match($packageType) {
            'premium' => now()->addDays(7),
            'premium_15' => now()->addDays(15),
            'premium_30' => now()->addDays(30),
            default => now()->addDays(7),
        };
    }

    public function destroy(PaymentVerification $payment)
    {
        // Delete the payment screenshot file
        if ($payment->payment_screenshot) {
            $filePath = public_path($payment->payment_screenshot);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment verification deleted successfully.');
    }
}