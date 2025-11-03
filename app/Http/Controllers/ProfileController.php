<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Notifications\UserActionNotification;
use Illuminate\Http\Request;

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
        
        // Combine phone country code and phone number
        $phoneNumber = '';
        if ($request->filled('phone_country_code') && $request->filled('phone')) {
            $countryCode = explode(' ', $request->phone_country_code)[1]; // Get "+971" from "🇦🇪 +971"
            $phoneNumber = $countryCode . ' ' . $request->phone;
        } elseif ($request->filled('phone')) {
            $phoneNumber = $request->phone; // Fallback to just phone if no country code
        }
        
        $newUserData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phoneNumber,
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
                    $oldPath = public_path($user->seekerProfile->profile_picture);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('profile_picture');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $directory = public_path('images/profiles');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                try {
                    $file->move($directory, $filename);
                    $profileData['profile_picture'] = 'images/profiles/' . $filename;
                    $profileDataChanged = true; // File upload is always a change
                } catch (\Exception $e) {
                    \Log::error('Profile picture upload failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'filename' => $filename,
                    ]);
                    return redirect()->back()
                        ->withErrors(['profile_picture' => 'Failed to upload profile picture. Please try again.'])
                        ->withInput();
                }
            }

            if ($request->hasFile('cv_file')) {
                if ($user->seekerProfile && $user->seekerProfile->cv_file) {
                    $oldPath = public_path($user->seekerProfile->cv_file);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('cv_file');
                $filename = 'cv_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $directory = public_path('cvs');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                try {
                    $file->move($directory, $filename);
                    $profileData['cv_file'] = 'cvs/' . $filename;
                    $profileDataChanged = true; // File upload is always a change
                } catch (\Exception $e) {
                    \Log::error('CV file upload failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'filename' => $filename,
                    ]);
                    return redirect()->back()
                        ->withErrors(['cv_file' => 'Failed to upload CV file. Please try again.'])
                        ->withInput();
                }
            }

            // If profile was changed, CV uploaded, or picture uploaded, set approval_status to pending
            if ($profileDataChanged || $request->hasFile('cv_file') || $request->hasFile('profile_picture')) {
                $profileData['approval_status'] = 'pending';
            }

            if ($user->seekerProfile) {
                $user->seekerProfile()->update($profileData);
            } else {
                // New profile - default to pending
                $profileData['approval_status'] = 'pending';
                $user->seekerProfile()->create($profileData);
            }
        }

        if ($user->isEmployer()) {
            // Combine mobile country code and mobile number
            $mobileNumber = '';
            if ($request->filled('mobile_country_code') && $request->filled('mobile_no')) {
                $countryCode = explode(' ', $request->mobile_country_code)[1]; // Get "+971" from "🇦🇪 +971"
                $mobileNumber = $countryCode . ' ' . $request->mobile_no;
            } elseif ($request->filled('mobile_no')) {
                $mobileNumber = $request->mobile_no; // Fallback to just mobile_no if no country code
            }
            
            // Combine landline country code and landline number
            $landlineNumber = '';
            if ($request->filled('landline_country_code') && $request->filled('landline_no')) {
                $countryCode = explode(' ', $request->landline_country_code)[1]; // Get "+971" from "🇦🇪 +971"
                $landlineNumber = $countryCode . ' ' . $request->landline_no;
            } elseif ($request->filled('landline_no')) {
                $landlineNumber = $request->landline_no; // Fallback to just landline_no if no country code
            }
            
            $profileData = [
                'company_name' => $request->company_name,
                'mobile_no' => $mobileNumber,
                'email_id' => $request->email_id,
                'landline_no' => $landlineNumber,
                'company_website' => $request->company_website,
                'industry' => $request->industry,
                'company_size' => $request->company_size,
                'founded_year' => $request->founded_year,
                'city' => $request->city,
                'country' => $request->country,
                'company_description' => $request->company_description,
            ];

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                if ($user->employerProfile && $user->employerProfile->profile_picture) {
                    $oldPath = public_path($user->employerProfile->profile_picture);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('profile_picture');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $directory = public_path('images/profiles');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                try {
                    $file->move($directory, $filename);
                    $profileData['profile_picture'] = 'images/profiles/' . $filename;
                    $profileDataChanged = true; // File upload is always a change
                } catch (\Exception $e) {
                    \Log::error('Employer profile picture upload failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'filename' => $filename,
                    ]);
                    return redirect()->back()
                        ->withErrors(['profile_picture' => 'Failed to upload profile picture. Please try again.'])
                        ->withInput();
                }
            }

            if ($request->hasFile('company_logo')) {
                if ($user->employerProfile && $user->employerProfile->company_logo) {
                    $oldPath = public_path($user->employerProfile->company_logo);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('company_logo');
                $filename = 'logo_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $directory = public_path('logos');
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }
                try {
                    $file->move($directory, $filename);
                    $profileData['company_logo'] = 'logos/' . $filename;
                    $profileDataChanged = true; // File upload is always a change
                } catch (\Exception $e) {
                    \Log::error('Company logo upload failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'filename' => $filename,
                    ]);
                    return redirect()->back()
                        ->withErrors(['company_logo' => 'Failed to upload company logo. Please try again.'])
                        ->withInput();
                }
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
