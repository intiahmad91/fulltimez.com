<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployerDocument;
use App\Notifications\DocumentApproved;
use App\Notifications\DocumentRejected;
use App\Notifications\AllDocumentsApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployerDocument::with(['employer', 'reviewer']);

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

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get statistics for the dashboard
        $stats = [
            'total' => EmployerDocument::count(),
            'pending' => EmployerDocument::where('status', 'pending')->count(),
            'approved' => EmployerDocument::where('status', 'approved')->count(),
            'rejected' => EmployerDocument::where('status', 'rejected')->count(),
        ];

        return view('admin.documents.index', compact('documents', 'stats'));
    }

    public function show(EmployerDocument $document)
    {
        $document->load(['employer', 'reviewer']);
        return view('admin.documents.show', compact('document'));
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
            $this->checkAllDocumentsApproved($employerId);
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
        $this->checkAllDocumentsApproved($employerId);

        $employerName = $pendingDocuments->first()->employer->name;
        return redirect()->back()->with('success', "Successfully approved {$approvedCount} documents for {$employerName} and sent notifications.");
    }
}