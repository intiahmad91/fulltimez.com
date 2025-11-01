<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerDocument;
use App\Models\User;
use App\Notifications\DocumentApproved;
use App\Notifications\DocumentRejected;
use App\Notifications\AllDocumentsApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployerDocument::with(['employer.employerProfile', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by document type
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        // Search by employer name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $documents = $query->orderBy('id', 'desc')->get();

        // Group documents by employer
        $groupedDocuments = $documents->groupBy('employer_id')->map(function ($docs) {
            return [
                'employer' => $docs->first()->employer,
                'documents' => $docs->sortByDesc('id'),
            ];
        })->sortByDesc(function ($group) {
            return $group['employer']->id;
        });

        // Paginate the grouped documents
        $perPage = 10; // Companies per page
        $currentPage = request()->get('page', 1);
        $items = $groupedDocuments->slice(($currentPage - 1) * $perPage, $perPage);
        $paginatedGroups = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $groupedDocuments->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get statistics for the dashboard
        $stats = [
            'total' => EmployerDocument::count(),
            'pending' => EmployerDocument::where('status', 'pending')->count(),
            'approved' => EmployerDocument::where('status', 'approved')->count(),
            'rejected' => EmployerDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.documents.index', compact('paginatedGroups', 'stats'));
    }

    public function show(EmployerDocument $document)
    {
        $document->load(['employer', 'reviewer']);
        return view('admin.documents.show', compact('document'));
    }

    public function viewFile(EmployerDocument $document)
    {
        // Admins can view any document file
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
            // Log the error for debugging
            \Log::error('Document file not found', [
                'document_id' => $document->id,
                'document_path' => $document->document_path,
                'attempted_paths' => $possiblePaths,
            ]);
            
            return redirect()->back()->withErrors(['error' => 'File not found. The file may have been deleted or moved.']);
        }

        // Get the original filename from the path or generate one
        $filename = basename($documentPath);
        if (empty($filename) || $filename === $documentPath) {
            // Generate filename based on document type
            $extension = pathinfo($documentPath, PATHINFO_EXTENSION);
            $filename = 'document_' . $document->id . '.' . ($extension ?: 'pdf');
        }

        // Force download instead of inline viewing
        return response()->download($fullPath, $filename, [
            'Content-Type' => mime_content_type($fullPath) ?: 'application/octet-stream',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
        ]);
    }

    public function approve(Request $request, EmployerDocument $document)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $document->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes ?? 'Document approved by admin.',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notify employer
        $document->employer->notify(new DocumentApproved($document));

        // Check if all required documents are now approved
        $this->checkAllDocumentsApproved($document->employer);

        return redirect()->back()
            ->with('success', 'Document approved successfully. Employer has been notified.');
    }

    public function reject(Request $request, EmployerDocument $document)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $document->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Notify employer
        $document->employer->notify(new DocumentRejected($document));

        return redirect()->back()
            ->with('success', 'Document rejected. Employer has been notified.');
    }

    public function statistics()
    {
        $stats = [
            'total' => EmployerDocument::count(),
            'pending' => EmployerDocument::where('status', 'pending')->count(),
            'approved' => EmployerDocument::where('status', 'approved')->count(),
            'rejected' => EmployerDocument::where('status', 'rejected')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Check if all required documents are approved for an employer
     */
    private function checkAllDocumentsApproved($employer)
    {
        $requiredTypes = ['trade_license', 'office_landline', 'company_email'];
        $approvedDocuments = $employer->employerDocuments()
            ->whereIn('document_type', $requiredTypes)
            ->where('status', 'approved')
            ->get();

        // Check if all required document types are approved
        $approvedTypes = $approvedDocuments->pluck('document_type')->toArray();
        $allApproved = count(array_intersect($requiredTypes, $approvedTypes)) === count($requiredTypes);

        if ($allApproved) {
            // Auto-approve employer profile
            if ($employer->employerProfile && $employer->employerProfile->verification_status !== 'verified') {
                $employer->employerProfile->update(['verification_status' => 'verified']);
            }
            
            // Send congratulations notification
            $employer->notify(new AllDocumentsApproved($approvedDocuments->toArray()));
        }
    }

    public function bulkApprove(Request $request)
    {
        $pendingDocuments = EmployerDocument::where('status', 'pending')->get();
        
        if ($pendingDocuments->isEmpty()) {
            return redirect()->back()->with('error', 'No pending documents found to approve.');
        }

        $approvedCount = 0;
        $employersNotified = [];

        foreach ($pendingDocuments as $document) {
            // Approve the document
            $document->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => 'Bulk approved by admin'
            ]);

            // Notify the employer
            $document->employer->notify(new DocumentApproved($document));
            $approvedCount++;

            // Track employers for all documents approved notification
            $employerId = $document->employer_id;
            if (!in_array($employerId, $employersNotified)) {
                $employersNotified[] = $employerId;
            }
        }

        // Check for employers who now have all documents approved
        foreach ($employersNotified as $employerId) {
            $employer = User::find($employerId);
            if ($employer) {
                $this->checkAllDocumentsApproved($employer);
            }
        }

        return redirect()->back()->with('success', "Successfully approved {$approvedCount} documents and notified all employers.");
    }

    public function bulkApproveByEmployer(Request $request)
    {
        $request->validate([
            'employer_id' => 'required|exists:users,id'
        ]);

        $employerId = $request->employer_id;
        $pendingDocuments = EmployerDocument::where('employer_id', $employerId)
            ->where('status', 'pending')
            ->get();

        if ($pendingDocuments->isEmpty()) {
            return redirect()->back()->with('error', 'No pending documents found for this employer.');
        }

        $approvedCount = 0;

        foreach ($pendingDocuments as $document) {
            // Approve the document
            $document->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'admin_notes' => 'Bulk approved by admin for employer'
            ]);

            // Notify the employer
            $document->employer->notify(new DocumentApproved($document));
            $approvedCount++;
        }

        // Check if all documents are now approved for this employer
        $employer = User::find($employerId);
        if ($employer) {
            $this->checkAllDocumentsApproved($employer);
        }

        $employerName = $pendingDocuments->first()->employer->name;
        return redirect()->back()->with('success', "Successfully approved {$approvedCount} documents for {$employerName} and sent notifications.");
    }
}