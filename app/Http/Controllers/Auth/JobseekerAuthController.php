<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobseekerRegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\SeekerProfile;
use App\Notifications\NewJobseekerRegistered;
use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class JobseekerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.jobseeker-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role->slug !== 'seeker') {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for jobseeker.']);
            }

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('verification.notice')
                    ->with('error', 'Please verify your email address before logging in. Check your inbox for verification link.');
            }

            // Reset failed login attempts on successful login
            \App\Models\FailedLoginAttempt::resetAttempts($credentials['email']);

            $request->session()->regenerate();
            
            // Send login notification
            $user->notify(new UserActionNotification(
                'login_successful',
                'logged in successfully',
                'You have logged in to your FullTimez account.',
                route('dashboard'),
                'Go to Dashboard'
            ));
            
            return redirect()->intended(route('dashboard'));
        }

        // Track failed login attempt
        $this->trackFailedLogin($credentials['email'], $request);

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegister()
    {
        return view('auth.jobseeker-register');
    }

    public function register(JobseekerRegisterRequest $request)
    {
        $validated = $request->validated();

        $role = Role::where('slug', 'seeker')->first();

        $user = User::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
            'status' => 'active',
        ]);

        $profileData = [
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'nationality' => $validated['nationality'] ?? null,
            'city' => $validated['city'] ?? null,
            'current_position' => $validated['current_position'] ?? null,
            'experience_years' => $validated['experience_years'] ?? null,
        ];

        if ($request->hasFile('profile_picture')) {
            $profileData['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        if ($request->hasFile('cv_file')) {
            $profileData['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
        }

        // Create profile with pending verification by default
        $profile = SeekerProfile::create($profileData);
        $profile->verification_status = 'pending';
        $profile->save();

        $user->sendEmailVerificationNotification();

        // Notify all admin users about the new jobseeker registration
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($user) {
                $admin->notify(new NewJobseekerRegistered($user->id, $user->name));
            });
        }

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function showForgotPassword()
    {
        return view('auth.jobseeker-forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->role->slug === 'seeker') {
            // Send password reset email
            $user->sendPasswordResetNotification();
            
            return back()->with('success', 'Password reset link has been sent to your email address.');
        }

        return back()->withErrors(['email' => 'No jobseeker account found with this email address.']);
    }

    /**
     * Track failed login attempts and send notification if needed
     */
    private function trackFailedLogin($email, $request)
    {
        $attempt = \App\Models\FailedLoginAttempt::recordAttempt(
            $email,
            $request->ip(),
            $request->userAgent()
        );

        // Check if we should send notification (3 or more attempts)
        if ($attempt->shouldSendNotification()) {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $user->notify(new \App\Notifications\FailedLoginAttempt(
                    $attempt->attempt_count,
                    $attempt->ip_address,
                    $attempt->user_agent
                ));
                $attempt->markNotificationSent();
            }
        }
    }
}
