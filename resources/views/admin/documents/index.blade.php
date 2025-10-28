@extends('layouts.admin')

@section('title', 'Document Verification')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Document Verification</li>
                    </ol>
                </div>
                <h4 class="page-title">Document Verification</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-primary-subtle text-primary">
                                <i class="fas fa-file-alt font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->total() }}</h5>
                            <p class="text-muted mb-0">Total Documents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-warning-subtle text-warning">
                                <i class="fas fa-clock font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->where('status', 'pending')->count() }}</h5>
                            <p class="text-muted mb-0">Pending Review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-success-subtle text-success">
                                <i class="fas fa-check font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->where('status', 'approved')->count() }}</h5>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-danger-subtle text-danger">
                                <i class="fas fa-times font-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-1">{{ $documents->where('status', 'rejected')->count() }}</h5>
                            <p class="text-muted mb-0">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Document Verification Management</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="document_type" class="form-control">
                                        <option value="">All Types</option>
                                        <option value="trade_license" {{ request('document_type') == 'trade_license' ? 'selected' : '' }}>Trade License</option>
                                        <option value="office_landline" {{ request('document_type') == 'office_landline' ? 'selected' : '' }}>Office Landline</option>
                                        <option value="company_email" {{ request('document_type') == 'company_email' ? 'selected' : '' }}>Company Email</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control" placeholder="Search by employer name or email..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Documents Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employer</th>
                                    <th>Document Type</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Reviewed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td>{{ $document->id }}</td>
                                        <td>
                                            <div>
                                                <strong>{{ $document->employer->name }}</strong><br>
                                                <small class="text-muted">{{ $document->employer->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $document->document_type_name }}</span>
                                        </td>
                                        <td>
                                            @if($document->document_type === 'trade_license')
                                                @if($document->document_number)
                                                    <small><strong>License #:</strong> {{ $document->document_number }}</small><br>
                                                @endif
                                            @elseif($document->document_type === 'office_landline')
                                                @if($document->landline_number)
                                                    <small><strong>Landline:</strong> {{ $document->landline_number }}</small>
                                                @endif
                                            @elseif($document->document_type === 'company_email')
                                                @if($document->company_email)
                                                    <small><strong>Email:</strong> {{ $document->company_email }}</small>
                                                @endif
                                            @elseif($document->document_type === 'company_info')
                                                <small><strong>Company Information</strong></small>
                                            @endif
                                            
                                            <!-- Company Information View Button -->
                                            @if($document->company_website || $document->contact_person_name || $document->contact_person_mobile || $document->contact_person_position || $document->contact_person_email)
                                                <br><a href="{{ route('admin.documents.show', $document) }}" class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $document->status_badge_class }}">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $document->created_at->format('M j, Y') }}</small><br>
                                            <small class="text-muted">{{ $document->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($document->reviewed_at)
                                                <small>{{ $document->reviewed_at->format('M j, Y') }}</small><br>
                                                <small class="text-muted">{{ $document->reviewed_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionsDropdown{{ $document->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-cog"></i> Actions
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $document->id }}">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.documents.show', $document) }}">
                                                            <i class="fas fa-eye text-primary"></i> View Details
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.documents.approve', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this document? This will notify the employer.');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check"></i> Approve Document
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger" onclick="showRejectForm({{ $document->id }})">
                                                            <i class="fas fa-times"></i> Reject Document
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No documents found</h5>
                                            <p class="text-muted">No documents match your current filters.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            {{ $documents->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Document Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this document.</p>
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="admin_notes" id="reject_reason" class="form-control" rows="3" placeholder="Explain why this document is being rejected..." required></textarea>
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

<script>
function showRejectForm(documentId) {
    // Set the form action URL
    document.getElementById('rejectForm').action = '/admin/documents/' + documentId + '/reject';
    
    // Clear the textarea
    document.getElementById('reject_reason').value = '';
    
    // Show the modal
    var modal = new bootstrap.Modal(document.getElementById('rejectModal'));
    modal.show();
}
</script>

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

.avatar-sm {
    width: 3rem;
    height: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-success-subtle {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-danger-subtle {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group form {
    display: inline-block;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        margin-right: 1px;
    }
}
</style>
@endsection
