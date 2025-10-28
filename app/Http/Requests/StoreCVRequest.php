<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCVRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSeeker();
    }

    public function rules(): array
    {
        return [
            'current_position' => 'required|string|max:255',
            'expected_salary' => 'nullable|string|max:100',
            'experience_years' => 'required|string|max:50',
            'nationality' => 'required|string|max:100',
            'first_language' => 'required|string|max:50',
            'second_language' => 'nullable|string|max:50',
            'bio' => 'required|string|min:100|max:2000',
            'skills' => 'required|string',
            
            'projects' => 'nullable|array',
            'projects.*.name' => 'nullable|string|max:255',
            'projects.*.type' => 'nullable|string|max:100',
            'projects.*.link' => 'nullable|url|max:255',
            'projects.*.description' => 'nullable|string|max:1000',
            
            'experience' => 'nullable|array',
            'experience.*.company' => 'nullable|string|max:255',
            'experience.*.title' => 'nullable|string|max:255',
            'experience.*.start_date' => 'nullable|date',
            'experience.*.end_date' => 'nullable|date',
            'experience.*.description' => 'nullable|string|max:1000',
            
            'education' => 'nullable|array',
            'education.*.institution' => 'nullable|string|max:255',
            'education.*.degree' => 'nullable|string|max:255',
            'education.*.field' => 'nullable|string|max:255',
            'education.*.year' => 'nullable|integer|min:1950|max:' . (date('Y') + 10),
            
            'certificates' => 'nullable|array',
            'certificates.*.name' => 'nullable|string|max:255',
            'certificates.*.organization' => 'nullable|string|max:255',
            'certificates.*.date' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'current_position.required' => 'Current role is required',
            'experience_years.required' => 'Years of experience is required',
            'nationality.required' => 'Nationality is required',
            'first_language.required' => 'First language is required',
            'bio.required' => 'Bio is required',
            'bio.min' => 'Bio must be at least 100 characters',
            'bio.max' => 'Bio must not exceed 2000 characters',
            'skills.required' => 'Please add at least one skill',
            'projects.*.link.url' => 'Please enter a valid URL',
            'experience.*.end_date.after_or_equal' => 'End date must be after start date',
        ];
    }
}
