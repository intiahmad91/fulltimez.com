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

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            // Check if user is employer and show message
            if ($user->isEmployer()) {
                return redirect()->route('employer.login')
                    ->with('info', 'Email already verified. Your account is pending admin approval. You will receive an email notification once your account is approved.');
            }
            
            // For job seekers
            return redirect()->route('jobseeker.login')
                ->with('info', 'Email already verified. Your account is pending admin approval. You will receive an email notification once your account is approved.');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            // Check if user is employer and show message
            if ($user->isEmployer()) {
                return redirect()->route('employer.login')
                    ->with('success', 'Email verified successfully! Your account is pending admin approval. You will receive an email notification once your account is approved.');
            }
            
            // For job seekers
            return redirect()->route('jobseeker.login')
                ->with('success', 'Email verified successfully! Your account is pending admin approval. You will receive an email notification once your account is approved.');
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
