<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('home')->with('error', 'Invalid verification link.');
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('home')->with('error', 'Invalid verification link.');
        }

        // Auto-login the user if not already logged in
        if (!Auth::check()) {
            Auth::login($user);
        }

        if ($user->hasVerifiedEmail()) {
            // Check if user is employer and redirect to documents page
            if ($user->isEmployer()) {
                return redirect()->route('employer.documents.create')->with('info', 'Email already verified. Please upload your documents.');
            }
            return redirect()->route('dashboard')->with('info', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            // Check if user is employer and redirect to documents page
            if ($user->isEmployer()) {
                return redirect()->route('employer.documents.create')->with('success', 'Email verified successfully! Please upload your documents to complete your registration.');
            }
            return redirect()->route('dashboard')->with('success', 'Email verified successfully! You can now access all features.');
        }

        return redirect()->route('home')->with('error', 'Verification failed.');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    }
}
