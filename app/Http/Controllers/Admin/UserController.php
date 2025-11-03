<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['role', 'seekerProfile', 'employerProfile']);

        if ($request->has('role') && $request->role != '') {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 24);
        $perPage = in_array($perPage, [12, 24, 48]) ? $perPage : 24;
        
        $users = $query->latest()->paginate($perPage);

        // Calculate statistics
        $stats = [
            'total' => User::count(),
            'seekers' => User::whereHas('role', function($q) { $q->where('slug', 'seeker'); })->count(),
            'employers' => User::whereHas('role', function($q) { $q->where('slug', 'employer'); })->count(),
            'admins' => User::whereHas('role', function($q) { $q->where('slug', 'admin'); })->count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'banned' => User::where('status', 'banned')->count(),
            'approved' => User::where('is_approved', true)->count(),
            'pending_approval' => User::where('is_approved', false)->whereHas('role', function($q) {
                $q->whereIn('slug', ['seeker', 'employer']);
            })->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['role', 'employerProfile', 'seekerProfile']);
        return view('admin.users.show', compact('user'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,banned'
        ]);

        $user->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function approveEmployer(User $user)
    {
        if (!$user->isEmployer() || !$user->employerProfile) {
            return redirect()->back()->with('error', 'Employer profile not found for this user.');
        }

        $user->employerProfile->update(['verification_status' => 'verified']);
        $user->update(['is_approved' => true]);

        // Send approval email notification
        try {
            $user->notify(new \App\Notifications\AccountApproved('employer'));
        } catch (\Exception $e) {
            // Log error but continue with approval
            \Log::error('Failed to send approval email to employer: ' . $e->getMessage());
        }

        // Remove related "new employer pending approval" notifications for all admins
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($user) {
                $admin->notifications()->where('data->employer_user_id', $user->id)->delete();
            });
        }

        return redirect()->back()->with('success', 'Employer account approved successfully. Approval email has been sent.');
    }

    public function approveSeeker(User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile) {
            return redirect()->back()->with('error', 'Seeker profile not found for this user.');
        }

        $user->seekerProfile->update([
            'verification_status' => 'verified',
            'approval_status' => 'approved' // Resume approval
        ]);
        $user->update(['is_approved' => true]); // Account approval

        // Send approval email notification
        try {
            $user->notify(new \App\Notifications\AccountApproved('seeker'));
        } catch (\Exception $e) {
            // Log error but continue with approval
            \Log::error('Failed to send approval email to seeker: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Jobseeker account and resume approved successfully. Approval email has been sent.');
    }

    /**
     * Approve a seeker resume (for Browse Resume page)
     */
    public function approveResume(User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile) {
            return redirect()->back()->with('error', 'Seeker profile not found for this user.');
        }

        $user->seekerProfile->update(['approval_status' => 'approved']);

        return redirect()->back()->with('success', 'Resume approved successfully. It will now appear on Browse Resume page.');
    }

    /**
     * Reject a seeker resume
     */
    public function rejectResume(User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile) {
            return redirect()->back()->with('error', 'Seeker profile not found for this user.');
        }

        $user->seekerProfile->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Resume rejected successfully.');
    }

    /**
     * Feature a seeker resume (for homepage)
     */
    public function featureResume(Request $request, User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile) {
            return redirect()->back()->with('error', 'Seeker profile not found for this user.');
        }

        $request->validate([
            'featured_duration' => 'required|integer|min:1|max:365', // days
        ]);

        // First approve if not already approved
        if ($user->seekerProfile->approval_status !== 'approved') {
            $user->seekerProfile->update(['approval_status' => 'approved']);
        }

        // Set featured - cast to int to ensure it's not a string
        $featuredExpiresAt = now()->addDays((int)$request->featured_duration);
        $user->seekerProfile->update([
            'is_featured' => true,
            'featured_expires_at' => $featuredExpiresAt,
        ]);

        return redirect()->back()->with('success', 'Resume featured successfully for ' . $request->featured_duration . ' days. It will appear on homepage.');
    }

    /**
     * Unfeature a seeker resume
     */
    public function unfeatureResume(User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile) {
            return redirect()->back()->with('error', 'Seeker profile not found for this user.');
        }

        $user->seekerProfile->update([
            'is_featured' => false,
            'featured_expires_at' => null,
        ]);

        return redirect()->back()->with('success', 'Resume unfeatured successfully.');
    }

    /**
     * Admin: Download jobseeker CV
     */
    public function downloadCv(User $user)
    {
        if (!$user->isSeeker() || !$user->seekerProfile || !$user->seekerProfile->cv_file) {
            return redirect()->back()->with('error', 'CV not found for this user.');
        }

        $path = $user->seekerProfile->cv_file;
        $fullPath = public_path($path);
        
        if (!file_exists($fullPath)) {
            return redirect()->back()->with('error', 'CV file is missing on the server.');
        }

        return response()->download($fullPath, 'CV-'.$user->name.'.pdf');
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete admin user!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    /**
     * Admin: Reset a user's password.
     */
    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'notify' => 'nullable|boolean',
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        // Optionally notify the user via email
        if ($request->boolean('notify')) {
            try {
                $user->notify(new \App\Notifications\UserActionNotification(
                    'password_reset_by_admin',
                    'reset your password',
                    'An administrator has reset your account password. If this was unexpected, contact support immediately.',
                    route('login'),
                    'Security Notice'
                ));
            } catch (\Throwable $e) {
                // Swallow notification errors, proceed with success
            }
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Password updated successfully for the user.');
    }

    public function sendFeaturedAdEmail(Request $request, \App\Models\JobPosting $job)
    {
        $validated = $request->validate([
            'email_to' => 'required|email',
            'email_subject' => 'required|string|max:255',
            'payment_link' => 'required|url',
            'email_message' => 'required|string|max:5000',
        ]);

        try {
            // Replace [PAYMENT_LINK] placeholder with actual payment link
            $message = str_replace('[PAYMENT_LINK]', $validated['payment_link'], $validated['email_message']);

            // Send email using Laravel's mail system
            \Illuminate\Support\Facades\Mail::raw($message, function ($mailMessage) use ($validated) {
                $mailMessage->to($validated['email_to'])
                        ->subject($validated['email_subject'])
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return redirect()->back()
                ->with('success', 'Payment link email sent successfully to ' . $validated['email_to']);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}



