<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        
        // Check if user data actually changed
        $userDataChanged = false;
        $originalUserData = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
        
        $newUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
        
        if ($originalUserData !== $newUserData) {
            $userDataChanged = true;
        }

        $user->update($newUserData);

        $profileDataChanged = false;
        
        if ($user->isSeeker()) {
            $profileData = [
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
                'current_position' => $request->current_position,
                'experience_years' => $request->experience_years,
                'expected_salary' => $request->expected_salary,
                'bio' => $request->bio,
            ];

            // Check if profile data actually changed
            if ($user->seekerProfile) {
                $originalProfileData = [
                    'full_name' => $user->seekerProfile->full_name,
                    'date_of_birth' => $user->seekerProfile->date_of_birth,
                    'gender' => $user->seekerProfile->gender,
                    'nationality' => $user->seekerProfile->nationality,
                    'city' => $user->seekerProfile->city,
                    'state' => $user->seekerProfile->state,
                    'country' => $user->seekerProfile->country,
                    'postal_code' => $user->seekerProfile->postal_code,
                    'current_position' => $user->seekerProfile->current_position,
                    'experience_years' => $user->seekerProfile->experience_years,
                    'expected_salary' => $user->seekerProfile->expected_salary,
                    'bio' => $user->seekerProfile->bio,
                ];
                
                if ($originalProfileData !== $profileData) {
                    $profileDataChanged = true;
                }
            } else {
                // Creating new profile - this is a change
                $profileDataChanged = true;
            }

            if ($request->hasFile('profile_picture')) {
                if ($user->seekerProfile && $user->seekerProfile->profile_picture) {
                    Storage::disk('public')->delete($user->seekerProfile->profile_picture);
                }
                $profileData['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
                $profileDataChanged = true; // File upload is always a change
            }

            if ($request->hasFile('cv_file')) {
                if ($user->seekerProfile && $user->seekerProfile->cv_file) {
                    Storage::disk('public')->delete($user->seekerProfile->cv_file);
                }
                $profileData['cv_file'] = $request->file('cv_file')->store('cvs', 'public');
                $profileDataChanged = true; // File upload is always a change
            }

            if ($user->seekerProfile) {
                $user->seekerProfile()->update($profileData);
            } else {
                $user->seekerProfile()->create($profileData);
            }
        }

        if ($user->isEmployer()) {
            $profileData = [
                'company_name' => $request->company_name,
                'mobile_no' => $request->mobile_no,
                'email_id' => $request->email_id,
                'landline_no' => $request->landline_no,
                'company_website' => $request->company_website,
                'industry' => $request->industry,
                'company_size' => $request->company_size,
                'founded_year' => $request->founded_year,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
                'company_description' => $request->company_description,
            ];

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                if ($user->employerProfile && $user->employerProfile->profile_picture) {
                    Storage::disk('public')->delete($user->employerProfile->profile_picture);
                }
                $profileData['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
                $profileDataChanged = true; // File upload is always a change
            }

            if ($request->hasFile('company_logo')) {
                if ($user->employerProfile && $user->employerProfile->company_logo) {
                    Storage::disk('public')->delete($user->employerProfile->company_logo);
                }
                $profileData['company_logo'] = $request->file('company_logo')->store('logos', 'public');
                $profileDataChanged = true; // File upload is always a change
            }

            // Check if profile data actually changed
            if ($user->employerProfile) {
                $originalProfileData = [
                    'company_name' => $user->employerProfile->company_name,
                    'mobile_no' => $user->employerProfile->mobile_no,
                    'email_id' => $user->employerProfile->email_id,
                    'landline_no' => $user->employerProfile->landline_no,
                    'company_website' => $user->employerProfile->company_website,
                    'industry' => $user->employerProfile->industry,
                    'company_size' => $user->employerProfile->company_size,
                    'founded_year' => $user->employerProfile->founded_year,
                    'city' => $user->employerProfile->city,
                    'state' => $user->employerProfile->state,
                    'country' => $user->employerProfile->country,
                    'company_description' => $user->employerProfile->company_description,
                ];
                
                if ($originalProfileData !== $profileData) {
                    $profileDataChanged = true;
                }
            } else {
                // Creating new profile - this is a change
                $profileDataChanged = true;
            }

            if ($user->employerProfile) {
                $user->employerProfile()->update($profileData);
            } else {
                $user->employerProfile()->create($profileData);
            }
        }

        // Send notification only if actual changes were made
        if ($userDataChanged || $profileDataChanged) {
            $user->notify(new UserActionNotification(
                'profile_updated',
                'updated your profile',
                'Your profile information has been updated successfully.',
                route('profile'),
                'View Profile'
            ));
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
