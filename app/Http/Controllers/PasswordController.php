<?php

namespace App\Http\Controllers;

use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Send notification about password change
        $user->notify(new UserActionNotification(
            'password_changed',
            'changed your password',
            'Your password has been changed successfully. If you did not make this change, please contact support immediately.',
            route('change.password'),
            'Security Settings'
        ));

        return back()->with('success', 'Password changed successfully!');
    }
}

