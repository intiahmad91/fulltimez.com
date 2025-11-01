<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = auth()->user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        if ($user->isSeeker()) {
            $rules = array_merge($rules, [
                'full_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date|before:today',
                'gender' => 'nullable|in:male,female,other',
                'nationality' => 'nullable|string|max:100',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'postal_code' => 'nullable|string|max:20',
                'current_position' => 'nullable|string|max:255',
                'experience_years' => 'nullable|string|max:50',
                'expected_salary' => 'nullable|string|max:100',
                'bio' => 'nullable|string|max:1000',
                'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ]);
        }

        if ($user->isEmployer()) {
            $rules = array_merge($rules, [
                'company_name' => 'required|string|max:255',
                'mobile_no' => 'required|string|max:20',
                'email_id' => 'required|email|max:255',
                'landline_no' => 'required|string|max:20',
                'company_website' => 'nullable|url|max:255',
                'industry' => 'required|string|max:100',
                'company_size' => 'nullable|string|max:50',
                'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
                'company_description' => 'nullable|string|max:2000',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.required' => 'Phone number is required',
            'profile_picture.image' => 'Profile picture must be an image',
            'profile_picture.max' => 'Profile picture must not exceed 2MB',
            'cv_file.mimes' => 'CV must be PDF, DOC, or DOCX format',
            'cv_file.max' => 'CV file must not exceed 5MB',
            'company_logo.max' => 'Company logo must not exceed 2MB',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'founded_year.max' => 'Founded year cannot be in the future',
        ];
    }
}

