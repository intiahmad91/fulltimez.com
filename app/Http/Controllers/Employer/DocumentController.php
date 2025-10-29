<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\EmployerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    private function employerProfileIsComplete($user): bool
    {
        $profile = $user->employerProfile;
        if (!$profile) {
            return false;
        }

        $required = [
            'company_name',
            'city',
            'country',
            'mobile_no',
            'email_id',
        ];

        foreach ($required as $field) {
            if (empty($profile->{$field})) {
                return false;
            }
        }

        return true;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$this->employerProfileIsComplete($user)) {
            return redirect()->route('profile')
                ->with('error', 'Please complete your company profile first before uploading documents.');
        }
        $documents = $user->employerDocuments()->orderBy('created_at', 'desc')->get();
        
        return view('employer.documents.index', compact('documents'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$this->employerProfileIsComplete($user)) {
            return redirect()->route('profile')
                ->with('error', 'Please complete your company profile first before uploading documents.');
        }
        // Hide sections only for documents that are already submitted and not rejected
        $existingDocuments = $user->employerDocuments()
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('document_type')
            ->toArray();
        
        // Get rejected documents that can be resubmitted
        $rejectedDocuments = $user->employerDocuments()
            ->where('status', 'rejected')
            ->pluck('document_type')
            ->toArray();
        
        return view('employer.documents.create', compact('existingDocuments', 'rejectedDocuments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$this->employerProfileIsComplete($user)) {
            return redirect()->route('profile')
                ->with('error', 'Please complete your company profile first before uploading documents.');
        }
        $request->validate([
            'trade_license_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'trade_license_number' => 'nullable|string|max:255',
            'landline_number' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_website' => 'nullable|url|max:255',
            'contact_person_name' => 'nullable|string|min:3|max:100',
            'contact_person_mobile' => 'nullable|string|min:10|max:20',
            'contact_person_position' => 'nullable|string|min:2|max:100',
            'contact_person_email' => 'nullable|email|max:255',
        ]);
        $submittedDocuments = [];

        // Process Trade License
        if ($request->filled('trade_license_number') || $request->hasFile('trade_license_file')) {
            $existingDocument = $user->employerDocuments()
                ->where('document_type', 'trade_license')
                ->first();

            if (!$existingDocument) {
                $documentData = [
                    'employer_id' => $user->id,
                    'document_type' => 'trade_license',
                    'status' => 'pending',
                    'document_number' => $request->trade_license_number,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ];

                // Handle file upload
                if ($request->hasFile('trade_license_file')) {
                    $file = $request->file('trade_license_file');
                    $filename = 'trade_license_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('documents/trade_licenses', $filename, 'public');
                    $documentData['document_path'] = $path;
                }

                EmployerDocument::create($documentData);
                $submittedDocuments[] = 'Trade License';
            } elseif ($existingDocument->status === 'rejected') {
                // Allow resubmission by updating existing rejected record
                $updateData = [
                    'document_number' => $request->trade_license_number ?? $existingDocument->document_number,
                    'status' => 'pending',
                    'admin_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ];

                if ($request->hasFile('trade_license_file')) {
                    // delete old file if exists
                    if ($existingDocument->document_path && Storage::disk('public')->exists($existingDocument->document_path)) {
                        Storage::disk('public')->delete($existingDocument->document_path);
                    }
                    $file = $request->file('trade_license_file');
                    $filename = 'trade_license_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('documents/trade_licenses', $filename, 'public');
                    $updateData['document_path'] = $path;
                }

                $existingDocument->update($updateData);
                $submittedDocuments[] = 'Trade License (Resubmitted)';
            }
        }

        // Process Office Landline
        if ($request->filled('landline_number')) {
            $existingDocument = $user->employerDocuments()
                ->where('document_type', 'office_landline')
                ->first();

            if (!$existingDocument) {
                EmployerDocument::create([
                    'employer_id' => $user->id,
                    'document_type' => 'office_landline',
                    'status' => 'pending',
                    'landline_number' => $request->landline_number,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Office Landline';
            } elseif ($existingDocument->status === 'rejected') {
                $existingDocument->update([
                    'landline_number' => $request->landline_number,
                    'status' => 'pending',
                    'admin_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Office Landline (Resubmitted)';
            }
        }

        // Process Company Email
        if ($request->filled('company_email')) {
            $existingDocument = $user->employerDocuments()
                ->where('document_type', 'company_email')
                ->first();

            if (!$existingDocument) {
                EmployerDocument::create([
                    'employer_id' => $user->id,
                    'document_type' => 'company_email',
                    'status' => 'pending',
                    'company_email' => $request->company_email,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Company Email';
            } elseif ($existingDocument->status === 'rejected') {
                $existingDocument->update([
                    'company_email' => $request->company_email,
                    'status' => 'pending',
                    'admin_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Company Email (Resubmitted)';
            }
        }

        // Check if any company information is provided
        $hasCompanyInfo = $request->filled('company_website') || $request->filled('contact_person_name') || 
                         $request->filled('contact_person_mobile') || $request->filled('contact_person_position') || 
                         $request->filled('contact_person_email');

        // If no documents were submitted but company information is provided, create a company info document
        if (empty($submittedDocuments) && $hasCompanyInfo) {
            $existingCompanyInfo = $user->employerDocuments()
                ->where('document_type', 'company_info')
                ->first();

            if (!$existingCompanyInfo) {
                EmployerDocument::create([
                    'employer_id' => $user->id,
                    'document_type' => 'company_info',
                    'status' => 'pending',
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Company Information';
            } elseif ($existingCompanyInfo->status === 'rejected') {
                $existingCompanyInfo->update([
                    'status' => 'pending',
                    'admin_notes' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'company_website' => $request->company_website,
                    'contact_person_name' => $request->contact_person_name,
                    'contact_person_mobile' => $request->contact_person_mobile,
                    'contact_person_position' => $request->contact_person_position,
                    'contact_person_email' => $request->contact_person_email,
                ]);
                $submittedDocuments[] = 'Company Information (Resubmitted)';
            } else {
                // Company info already exists and is not rejected, so we should still add it to submitted documents
                $submittedDocuments[] = 'Company Information (Updated)';
            }
        }

        if (empty($submittedDocuments)) {
            return redirect()->back()
                ->withErrors(['error' => 'No documents were submitted. Please fill in at least one document field or complete company information.'])
                ->withInput();
        }

        $message = count($submittedDocuments) > 1 
            ? 'Documents submitted successfully for review: ' . implode(', ', $submittedDocuments)
            : 'Document submitted successfully for review: ' . $submittedDocuments[0];

        return redirect()->route('employer.documents.index')
            ->with('success', $message);
    }

    public function show(EmployerDocument $document)
    {
        // Ensure the document belongs to the authenticated user
        if ($document->employer_id !== Auth::id()) {
            abort(403);
        }

        return view('employer.documents.show', compact('document'));
    }

    public function destroy(EmployerDocument $document)
    {
        // Ensure the document belongs to the authenticated user
        if ($document->employer_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if document is pending
        if ($document->status !== 'pending') {
            return redirect()->back()
                ->withErrors(['error' => 'Only pending documents can be deleted.']);
        }

        // Delete file if exists
        if ($document->document_path && Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }

        $document->delete();

        return redirect()->route('employer.documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}