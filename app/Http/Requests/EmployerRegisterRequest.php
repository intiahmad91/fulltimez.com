<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployerRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:100',
            'company_name' => 'required|string|min:3|max:255',
            'country_code' => 'required|string|max:20',
            'mobile_no' => 'required|string|min:7|max:15',
            'email_id' => 'required|email|max:255|unique:users,email',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100|required_without:state|prohibits:state',
            'state' => 'nullable|string|max:100|required_without:city|prohibits:city',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name must be at least 3 characters',
            'company_name.required' => 'Company name is required',
            'company_name.min' => 'Company name must be at least 3 characters',
            'country_code.required' => 'Country code is required',
            'country_code.max' => 'Country code cannot exceed 10 characters',
            'mobile_no.required' => 'Mobile number is required',
            'mobile_no.min' => 'Mobile number must be at least 7 digits',
            'email_id.required' => 'Email ID is required',
            'email_id.email' => 'Please enter a valid email address',
            'email_id.unique' => 'This email address is already registered. Please use a different email or try logging in.',
            'country.required' => 'Country is required',
            'city.required_without' => 'Provide city or state (one is required).',
            'city.prohibits' => 'Provide either city or state, not both.',
            'state.required_without' => 'Provide state or city (one is required).',
            'state.prohibits' => 'Provide either state or city, not both.',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number',
        ];
    }
}



