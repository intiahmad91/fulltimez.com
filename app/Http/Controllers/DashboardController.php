<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard.index');
    }

    public function profile()
    {
        $user = auth()->user();
        
        // Show employer-specific profile view for employers
        if ($user->isEmployer()) {
            return view('employer.profile', compact('user'));
        }
        
        return view('dashboard.profile', compact('user'));
    }

    public function changePassword()
    {
        return view('dashboard.change-password');
    }
}

