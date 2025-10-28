<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobseekerRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-zA-Z\s\-\.]+$/', // Only letters, spaces, hyphens, and dots
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'unique:users,email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,9}$/',
                'unique:users,phone',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ],
            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
                'after:' . now()->subYears(100)->format('Y-m-d'),
                'before:' . now()->subYears(16)->format('Y-m-d'), // Must be at least 16 years old
            ],
            'gender' => 'nullable|in:male,female,other',
            'nationality' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'city' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s\-]+$/',
            ],
            'current_position' => [
                'nullable',
                'string',
                'min:2',
                'max:255',
            ],
            'experience_years' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[0-9\-\+\s]+\s?(years?|months?)?$/i',
            ],
            'cv_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.min' => 'Full name must be at least 3 characters long.',
            'full_name.max' => 'Full name cannot exceed 255 characters.',
            'full_name.regex' => 'Full name can only contain letters, spaces, hyphens, and periods.',
            
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered. Please login or use a different email.',
            'email.max' => 'Email address cannot exceed 255 characters.',
            'email.regex' => 'Please enter a properly formatted email address.',
            
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Please enter a valid phone number format (e.g., +1234567890 or 1234567890).',
            
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.max' => 'Password cannot exceed 100 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&#).',
            
            'profile_picture.image' => 'Profile picture must be an image file.',
            'profile_picture.mimes' => 'Profile picture must be in JPG, JPEG, or PNG format.',
            'profile_picture.max' => 'Profile picture must not exceed 2MB in size.',
            'profile_picture.dimensions' => 'Profile picture must be at least 100x100 pixels and not exceed 5000x5000 pixels.',
            
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Please enter a valid date.',
            'date_of_birth.before' => 'You must be at least 16 years old to register.',
            'date_of_birth.after' => 'Please enter a valid date of birth.',
            
            'gender.in' => 'Please select a valid gender option.',
            
            'nationality.regex' => 'Nationality can only contain letters and spaces.',
            'nationality.max' => 'Nationality cannot exceed 100 characters.',
            
            'city.regex' => 'City name can only contain letters, spaces, and hyphens.',
            'city.max' => 'City name cannot exceed 100 characters.',
            
            'current_position.min' => 'Current position must be at least 2 characters.',
            'current_position.max' => 'Current position cannot exceed 255 characters.',
            
            'experience_years.regex' => 'Please enter experience in a valid format (e.g., "2-5 years" or "3 years").',
            'experience_years.max' => 'Experience field cannot exceed 50 characters.',
            
            'cv_file.file' => 'Please upload a valid file.',
            'cv_file.mimes' => 'CV must be in PDF, DOC, or DOCX format.',
            'cv_file.max' => 'CV file must not exceed 5MB in size.',
        ];
    }
}


