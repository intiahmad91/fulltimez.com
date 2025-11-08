@extends('layouts.admin')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Document Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Document Verification</a></li>
                        <li class="breadcrumb-item active">Document Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Document Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Document Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Document Type:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge badge-info">{{ $document->document_type_name }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge {{ $document->status_badge_class }}">
                                {{ ucfirst($document->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Employer:</strong>
                        </div>
                        <div class="col-sm-9">
                            <strong>{{ $document->employer->name }}</strong><br>
                            <small class="text-muted">{{ $document->employer->email }}</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong>Submitted On:</strong>
                        </div>
                        <div class="col-sm-9">
                            {{ $document->created_at->format('F j, Y \a\t g:i A') }}
                            <small class="text-muted">({{ $document->created_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    @if($document->reviewed_at)
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Reviewed On:</strong>
                            </div>
                            <div class="col-sm-9">
                                {{ $document->reviewed_at->format('F j, Y \a\t g:i A') }}
                                <small class="text-muted">({{ $document->reviewed_at->diffForHumans() }})</small>
                            </div>
                        </div>

                        @if($document->reviewer)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Reviewed By:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->reviewer->name }}
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($document->document_type === 'trade_license')
                        @if($document->document_number)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>License Number:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->document_number }}
                                </div>
                            </div>
                        @endif

                        @if($document->document_path)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Document File:</strong>
                                </div>
                                <div class="col-sm-9">
                                    @php 
                                        $documentUrl = asset($document->document_path);
                                    @endphp
                                    <div class="btn-group" role="group">
                                        <a href="{{ $documentUrl }}" target="_blank" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i> View Document
                                        </a>
                                        <a href="{{ $documentUrl }}" download class="btn btn-primary">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                    <small class="d-block text-muted mt-2">
                                        <strong>File Path:</strong> {{ $document->document_path }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    @elseif($document->document_type === 'office_landline')
                        @if($document->landline_number)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Landline Number:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->landline_number }}
                                </div>
                            </div>
                        @endif
                    @elseif($document->document_type === 'company_email')
                        @if($document->company_email)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Company Email:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <a href="mailto:{{ $document->company_email }}">{{ $document->company_email }}</a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Company Information Section -->
                    @if($document->company_website || $document->contact_person_name || $document->contact_person_mobile || $document->contact_person_position || $document->contact_person_email)
                        <hr class="my-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building"></i> Company Information
                        </h6>
                        
                        @if($document->company_website)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Company Website:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <a href="{{ $document->company_website }}" target="_blank" class="text-primary">
                                        {{ $document->company_website }}
                                        <i class="fas fa-external-link-alt ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($document->contact_person_name)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Contact Person:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->contact_person_name }}
                                </div>
                            </div>
                        @endif

                        @if($document->contact_person_position)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Position:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $document->contact_person_position }}
                                </div>
                            </div>
                        @endif

                        @if($document->contact_person_mobile)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Mobile Number:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <a href="tel:{{ $document->contact_person_mobile }}" class="text-primary">
                                        {{ $document->contact_person_mobile }}
                                        <i class="fas fa-phone ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($document->contact_person_email)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Contact Email:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <a href="mailto:{{ $document->contact_person_email }}" class="text-primary">
                                        {{ $document->contact_person_email }}
                                        <i class="fas fa-envelope ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($document->admin_notes)
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Admin Notes:</strong>
                            </div>
                            <div class="col-sm-9">
                                <div class="alert alert-info">
                                    {{ $document->admin_notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @if($document->status === 'pending')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i>
                            <strong>Pending Review</strong><br>
                            <small>This document is waiting for your review.</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="fas fa-check"></i> Approve Document
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times"></i> Reject Document
                            </button>
                        </div>
                    @elseif($document->status === 'approved')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>Approved</strong><br>
                            <small>This document has been approved.</small>
                        </div>
                    @elseif($document->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            <strong>Rejected</strong><br>
                            <small>This document has been rejected.</small>
                        </div>
                    @endif

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i> Back to Documents
                        </a>
                        <a href="{{ route('admin.users.show', $document->employer) }}" class="btn btn-outline-info">
                            <i class="fas fa-user"></i> View Employer Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- All documents for this employer -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Documents for: {{ $document->employer->name }}</h5>
                    <span class="text-muted small">Employer Email: {{ $document->employer->email }}</span>
                </div>
                <div class="card-body">
                    @php
                        $allDocs = \App\Models\EmployerDocument::where('employer_id', $document->employer_id)
                            ->orderBy('created_at','desc')
                            ->get();
                    @endphp

                    @if($allDocs->isEmpty())
                        <div class="alert alert-light mb-0">No other documents found for this employer.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:18%">Type</th>
                                        <th style="width:42%">Details</th>
                                        <th style="width:15%">Status</th>
                                        <th style="width:15%">Submitted</th>
                                        <th style="width:10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($allDocs as $doc)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $doc->document_type_name }}</span>
                                        </td>
                                        <td>
                                            @if($doc->document_type === 'trade_license')
                                                @if($doc->document_number)
                                                    <div><strong>License #:</strong> {{ $doc->document_number }}</div>
                                                @endif
                                                @if($doc->document_path)
                                                    <a href="{{ asset($doc->document_path) }}" target="_blank" class="btn btn-xs btn-outline-primary mt-1">
                                                        <i class="fas fa-eye"></i> View File
                                                    </a>
                                                @endif
                                            @elseif($doc->document_type === 'office_landline')
                                                <div><strong>Landline:</strong> {{ $doc->landline_number ?? '—' }}</div>
                                            @elseif($doc->document_type === 'company_email')
                                                <div><strong>Email:</strong> {{ $doc->company_email ?? '—' }}</div>
                                            @elseif($doc->document_type === 'company_info')
                                                <div><strong>Company Information</strong></div>
                                                @if($doc->company_website)
                                                    <div>Website: <a href="{{ $doc->company_website }}" target="_blank">{{ $doc->company_website }}</a></div>
                                                @endif
                                                @if($doc->contact_person_name)
                                                    <div>Contact: {{ $doc->contact_person_name }}</div>
                                                @endif
                                                @if($doc->contact_person_position)
                                                    <div>Position: {{ $doc->contact_person_position }}</div>
                                                @endif
                                                @if($doc->contact_person_mobile)
                                                    <div>Mobile: {{ $doc->contact_person_mobile }}</div>
                                                @endif
                                                @if($doc->contact_person_email)
                                                    <div>Email: {{ $doc->contact_person_email }}</div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $doc->status === 'approved' ? 'bg-success' : ($doc->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($doc->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $doc->created_at->format('M j, Y') }}</small><br>
                                            <small class="text-muted">{{ $doc->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="docActions{{ $doc->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="docActions{{ $doc->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.documents.show', $doc) }}">
                                                            <i class="fas fa-eye text-primary"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.documents.approve', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this document? This will notify the employer.');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check"></i> Approve Document
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" onclick="setRejectDocument({{ $doc->id }})">
                                                            <i class="fas fa-times"></i> Reject Document
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.documents.approve', $document) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this document?</p>
                    <div class="mb-3">
                        <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" placeholder="Add any notes for the employer..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this document.</p>
                    <div class="mb-3">
                        <label for="reject_notes" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="admin_notes" id="reject_notes" class="form-control" rows="3" placeholder="Explain why this document is being rejected..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.badge-info {
    background-color: #17a2b8;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}
</style>

<script>
function setRejectDocument(id){
    // point the reject modal to the selected document
    const form = document.querySelector('#rejectModal form');
    form.action = '/admin/documents/' + id + '/reject';
    const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>
@endsection
