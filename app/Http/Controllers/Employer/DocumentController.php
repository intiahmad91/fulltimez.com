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
            'country',
            'mobile_no',
            'email_id',
        ];

        foreach ($required as $field) {
            if (empty($profile->{$field})) {
                return false;
            }
        }

        // require either city or state (one must be present, not necessarily both)
        $hasCity = !empty($profile->city);
        $hasState = !empty($profile->state);
        if (!($hasCity || $hasState)) {
            return false;
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
        
        // Parse contact person mobile if editing existing document
        $contactMobileData = ['country_code' => '🇦🇪 +971', 'number' => ''];
        // This will be handled per document if editing
        
        return view('employer.documents.create', compact('existingDocuments', 'rejectedDocuments', 'contactMobileData'));
    }
    
    /**
     * Parse phone number to extract country code and number
     */
    private function parsePhoneNumber($phoneNumber)
    {
        if (empty($phoneNumber)) {
            return ['country_code' => '🇦🇪 +971', 'number' => ''];
        }
        
        // Common country codes mapping
        $countryCodeMap = [
            '+971' => '🇦🇪 +971',
            '+966' => '🇸🇦 +966',
            '+974' => '🇶🇦 +974',
            '+965' => '🇰🇼 +965',
            '+973' => '🇧🇭 +973',
            '+968' => '🇴🇲 +968',
            '+1' => '🇺🇸 +1',
            '+44' => '🇬🇧 +44',
            '+91' => '🇮🇳 +91',
            '+92' => '🇵🇰 +92',
            '+20' => '🇪🇬 +20',
        ];
        
        // Extract country code if it starts with +
        if (preg_match('/^(\+\d{1,4})\s*(.+)$/', $phoneNumber, $matches)) {
            $code = $matches[1];
            $number = $matches[2];
            $countryCode = $countryCodeMap[$code] ?? '🇦🇪 +971'; // Default to UAE
            return ['country_code' => $countryCode, 'number' => $number];
        }
        
        // If no country code found, assume UAE and treat entire as number
        return ['country_code' => '🇦🇪 +971', 'number' => $phoneNumber];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$this->employerProfileIsComplete($user)) {
            return redirect()->route('profile')
                ->with('error', 'Please complete your company profile first before uploading documents.');
        }

		// Normalize company website: add https:// if missing scheme
		if ($request->filled('company_website')) {
			$website = trim($request->input('company_website'));
			
			// If it doesn't start with http:// or https://, add https://
			if (!preg_match('/^https?:\/\//i', $website)) {
				// Add https:// before the website (handles both www.abc.com and abc.com)
				$website = 'https://' . ltrim($website);
			}
			
			$request->merge(['company_website' => $website]);
		}

		// Combine contact person mobile country code and mobile number
		if ($request->filled('contact_person_mobile_country_code') && $request->filled('contact_person_mobile')) {
			$countryCode = explode(' ', $request->contact_person_mobile_country_code)[1]; // Get "+971" from "🇦🇪 +971"
			$fullMobileNumber = $countryCode . ' ' . $request->contact_person_mobile;
			$request->merge(['contact_person_mobile' => $fullMobileNumber]);
		}

		$request->validate([
            'trade_license_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'trade_license_number' => 'nullable|string|max:255',
            'landline_number' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'company_website' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        // After normalization, check if it's a valid URL
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            $fail('Please enter a valid website URL. If you entered without https://, please add https:// at the beginning (e.g., https://www.example.com).');
                        }
                    }
                },
            ],
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
                    $directory = public_path('documents/trade_licenses');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    // Move file and verify it was saved
                    try {
                        $file->move($directory, $filename);
                        $documentData['document_path'] = 'documents/trade_licenses/' . $filename;
                    } catch (\Exception $e) {
                        \Log::error('Trade license file upload failed', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'filename' => $filename,
                        ]);
                        return redirect()->back()
                            ->withErrors(['trade_license_file' => 'Failed to upload file. Please try again.'])
                            ->withInput();
                    }
                }

                try {
                    EmployerDocument::create($documentData);
                    $submittedDocuments[] = 'Trade License';
                } catch (\Exception $e) {
                    \Log::error('Trade license document creation failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                    ]);
                    
                    // Delete uploaded file if document creation failed
                    if (isset($documentData['document_path'])) {
                        $fullPath = public_path($documentData['document_path']);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                    
                    return redirect()->back()
                        ->withErrors(['error' => 'Failed to save trade license. Please try again.'])
                        ->withInput();
                }
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
                    if ($existingDocument->document_path) {
                        $oldPath = public_path($existingDocument->document_path);
                        // Try multiple path formats
                        $oldPaths = [
                            $oldPath,
                            public_path('/' . ltrim($existingDocument->document_path, '/')),
                            storage_path('app/public/' . ltrim($existingDocument->document_path, '/')),
                        ];
                        foreach ($oldPaths as $path) {
                            if (file_exists($path)) {
                                unlink($path);
                                break;
                            }
                        }
                    }
                    $file = $request->file('trade_license_file');
                    $filename = 'trade_license_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $directory = public_path('documents/trade_licenses');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    try {
                        $file->move($directory, $filename);
                        $updateData['document_path'] = 'documents/trade_licenses/' . $filename;
                    } catch (\Exception $e) {
                        \Log::error('Trade license file upload failed (resubmission)', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'filename' => $filename,
                        ]);
                        return redirect()->back()
                            ->withErrors(['trade_license_file' => 'Failed to upload file. Please try again.'])
                            ->withInput();
                    }
                }

                try {
                    $existingDocument->update($updateData);
                    $submittedDocuments[] = 'Trade License (Resubmitted)';
                } catch (\Exception $e) {
                    \Log::error('Trade license document update failed', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'document_id' => $existingDocument->id,
                    ]);
                    
                    return redirect()->back()
                        ->withErrors(['error' => 'Failed to update trade license. Please try again.'])
                        ->withInput();
                }
            } elseif ($existingDocument->status === 'pending') {
                // Allow updating pending documents (admin hasn't reviewed yet)
                $updateData = [];
                
                if ($request->filled('trade_license_number')) {
                    $updateData['document_number'] = $request->trade_license_number;
                }
                
                if ($request->hasFile('trade_license_file')) {
                    // delete old file if exists
                    if ($existingDocument->document_path) {
                        $oldPath = public_path($existingDocument->document_path);
                        $oldPaths = [
                            $oldPath,
                            public_path('/' . ltrim($existingDocument->document_path, '/')),
                            storage_path('app/public/' . ltrim($existingDocument->document_path, '/')),
                        ];
                        foreach ($oldPaths as $path) {
                            if (file_exists($path)) {
                                unlink($path);
                                break;
                            }
                        }
                    }
                    $file = $request->file('trade_license_file');
                    $filename = 'trade_license_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $directory = public_path('documents/trade_licenses');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }
                    
                    try {
                        $file->move($directory, $filename);
                        $updateData['document_path'] = 'documents/trade_licenses/' . $filename;
                    } catch (\Exception $e) {
                        \Log::error('Trade license file upload failed (pending update)', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'filename' => $filename,
                        ]);
                        return redirect()->back()
                            ->withErrors(['trade_license_file' => 'Failed to upload file. Please try again.'])
                            ->withInput();
                    }
                }
                
                // Update other fields if provided
                if ($request->filled('company_website')) {
                    $updateData['company_website'] = $request->company_website;
                }
                if ($request->filled('contact_person_name')) {
                    $updateData['contact_person_name'] = $request->contact_person_name;
                }
                if ($request->filled('contact_person_mobile')) {
                    $updateData['contact_person_mobile'] = $request->contact_person_mobile;
                }
                if ($request->filled('contact_person_position')) {
                    $updateData['contact_person_position'] = $request->contact_person_position;
                }
                if ($request->filled('contact_person_email')) {
                    $updateData['contact_person_email'] = $request->contact_person_email;
                }
                
                if (!empty($updateData)) {
                    try {
                        $existingDocument->update($updateData);
                        $submittedDocuments[] = 'Trade License (Updated)';
                    } catch (\Exception $e) {
                        \Log::error('Trade license document update failed (pending)', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id,
                            'document_id' => $existingDocument->id,
                        ]);
                        return redirect()->back()
                            ->withErrors(['error' => 'Failed to update trade license. Please try again.'])
                            ->withInput();
                    }
                }
            } else {
                // Document is approved, cannot update
                return redirect()->back()
                    ->withErrors(['error' => 'Your trade license has already been approved and cannot be updated.'])
                    ->withInput();
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
        // Ensure the document belongs to the authenticated user OR current user is admin
        $user = Auth::user();
        $isOwner = $document->employer_id === ($user?->id);
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        if (!($isOwner || $isAdmin)) {
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
        if ($document->document_path) {
            $filePath = public_path($document->document_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $document->delete();

        return redirect()->route('employer.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function viewFile(EmployerDocument $document)
    {
        // Allow owner or admin to view
        $user = Auth::user();
        $isOwner = $document->employer_id === ($user?->id);
        $isAdmin = $user && method_exists($user, 'isAdmin') && $user->isAdmin();
        if (!($isOwner || $isAdmin)) {
            abort(403);
        }

        if (!$document->document_path) {
            return redirect()->back()->withErrors(['error' => 'No file attached for this document.']);
        }

        // Handle different path formats (with or without leading slash)
        $documentPath = ltrim($document->document_path, '/');
        
        // Try multiple path possibilities
        $possiblePaths = [
            public_path($documentPath), // documents/trade_licenses/file.pdf
            public_path('/' . $documentPath), // /documents/trade_licenses/file.pdf
            storage_path('app/public/' . $documentPath), // In case files are in storage
            base_path('public/' . $documentPath), // Alternative base path
        ];

        $fullPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $fullPath = $path;
                break;
            }
        }

        if (!$fullPath || !file_exists($fullPath)) {
            return redirect()->back()->withErrors(['error' => 'File not found. Please re-upload the document.']);
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function streamSigned(Request $request, EmployerDocument $document)
    {
        if (!$document->document_path) {
            abort(404, 'No file attached for this document.');
        }

        // Validate HMAC token to avoid relying on web server/middleware signature
        $token = $request->query('t');
        $expected = hash_hmac('sha256', $document->document_path, config('app.key'));
        if (!$token || !hash_equals($expected, $token)) {
            \Log::error('Invalid HMAC token for document stream', [
                'document_id' => $document->id,
                'provided_token' => $token,
                'expected_token' => $expected,
                'document_path' => $document->document_path,
            ]);
            abort(403, 'Invalid or missing token.');
        }

        // Handle different path formats (with or without leading slash)
        $documentPath = ltrim($document->document_path, '/');
        
        // Try multiple path possibilities
        $possiblePaths = [
            public_path($documentPath), // documents/trade_licenses/file.pdf
            public_path('/' . $documentPath), // /documents/trade_licenses/file.pdf
            storage_path('app/public/' . $documentPath), // In case files are in storage
            base_path('public/' . $documentPath), // Alternative base path
        ];

        $fullPath = null;
        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $fullPath = $path;
                break;
            }
        }

        if (!$fullPath || !file_exists($fullPath)) {
            \Log::error('Document file not found in streamSigned', [
                'document_id' => $document->id,
                'document_path' => $document->document_path,
                'attempted_paths' => $possiblePaths,
            ]);
            abort(404, 'File not found. Please re-upload the document.');
        }

        // Get the original filename from the path or generate one
        $filename = basename($documentPath);
        if (empty($filename) || $filename === $documentPath) {
            // Generate filename based on document type
            $extension = pathinfo($documentPath, PATHINFO_EXTENSION);
            $filename = 'document_' . $document->id . '.' . ($extension ?: 'pdf');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        
        // Force download instead of inline viewing
        return response()->download($fullPath, $filename, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }
}