<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployerRegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Models\EmployerProfile;
use App\Models\Country;
use App\Notifications\NewEmployerPendingApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.employer-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user->role->slug !== 'employer') {
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials for employer.']);
            }

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('verification.notice')
                    ->with('error', 'Please verify your email address before logging in. Check your inbox for verification link.');
            }

            // Admin users don't need approval
            if (!$user->isAdmin() && !$user->is_approved) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is pending admin approval. You will receive an email notification once your account is approved.']);
            }

            // Reset failed login attempts on successful login
            \App\Models\FailedLoginAttempt::resetAttempts($credentials['email']);

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Track failed login attempt
        $this->trackFailedLogin($credentials['email'], $request);

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function showRegister()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('auth.employer-register', compact('countries'));
    }

    public function register(EmployerRegisterRequest $request)
    {
        $validated = $request->validated();

        $role = Role::where('slug', 'employer')->first();

        // Extract country code from flag + code format (e.g., "🇦🇪 +971" -> "+971")
		$countryCode = explode(' ', $validated['country_code'])[1]; // Get "+971" from "🇦🇪 +971"
		$fullPhoneNumber = $countryCode . ' ' . $validated['mobile_no'];

		// Prevent duplicate registration by email or phone
		if (User::where('email', $validated['email_id'])->exists()) {
			return back()
				->withErrors(['email_id' => 'This email address is already registered. Please login or use a different email.'])
				->withInput();
		}

		if (
			User::where('phone', $fullPhoneNumber)->exists() ||
			EmployerProfile::where('mobile_no', $fullPhoneNumber)->exists()
		) {
			return back()
				->withErrors(['mobile_no' => 'This phone number is already registered. Please login or use a different number.'])
				->withInput();
		}

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email_id'],
            'phone' => $fullPhoneNumber,
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
            'status' => 'active',
        ]);

        EmployerProfile::create([
            'user_id' => $user->id,
            'company_name' => $validated['company_name'],
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'],
            'mobile_no' => $fullPhoneNumber,
            'email_id' => $validated['email_id'],
            'verification_status' => 'pending',
        ]);

        // Send email verification notification
        $user->sendEmailVerificationNotification();

        // Notify all admins about pending approval
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole) {
            User::where('role_id', $adminRole->id)->get()->each(function($admin) use ($user, $validated) {
                $admin->notify(new NewEmployerPendingApproval(
                    $user->id,
                    $validated['company_name'],
                    $validated['name'], // Use name as contact person
                    $validated['email_id'], // Use email_id as contact email
                    $validated['country'] ?? null,
                    $validated['city'] ?? null
                ));
            });
        }

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registration successful! Please check your email to verify your account before logging in.');
    }

    public function showForgotPassword()
    {
        return view('auth.employer-forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->role->slug === 'employer') {
            // Generate password reset token
            $token = \Str::random(64);
            \DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Send password reset email
            $user->notify(new \App\Notifications\PasswordResetNotification($token, 'employer'));

            return back()->with('success', 'Password reset link has been sent to your email address.');
        }

        return back()->withErrors(['email' => 'No employer account found with this email address.']);
    }

    public function showResetPassword($token)
    {
        return view('auth.employer-reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        // Check if token is not older than 1 hour
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Reset token has expired. Please request a new one.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete the reset token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('employer.login')
            ->with('success', 'Your password has been reset successfully. You can now login with your new password.');
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
