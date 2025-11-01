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
                            <h5 class="mb-1">{{ $stats['total'] }}</h5>
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
                            <h5 class="mb-1">{{ $stats['pending'] }}</h5>
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
                            <h5 class="mb-1">{{ $stats['approved'] }}</h5>
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
                            <h5 class="mb-1">{{ $stats['rejected'] }}</h5>
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

                    <!-- Documents Grid Grouped by Company -->
                    @forelse($paginatedGroups as $group)
                        <div class="company-group mb-4">
                            <!-- Company Header -->
                            <div class="card mb-3 company-header-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-lg me-3">
                                                <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    <i class="fas fa-building font-24"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $group['employer']->name }}</h5>
                                                <p class="text-muted mb-0">
                                                    <i class="fas fa-envelope me-1"></i>{{ $group['employer']->email }}
                                                    @if($group['employer']->employerProfile && $group['employer']->employerProfile->company_name)
                                                        <br><i class="fas fa-briefcase me-1"></i>{{ $group['employer']->employerProfile->company_name }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info">
                                                {{ $group['documents']->count() }} Document(s)
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Grid -->
                            <div class="row g-3">
                                @foreach($group['documents'] as $document)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card document-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="badge {{ $document->status_badge_class }}">
                                                        {{ ucfirst($document->status) }}
                                                    </span>
                                                    <span class="badge badge-info">{{ $document->document_type_name }}</span>
                                                </div>

                                                <h6 class="card-title mb-3">{{ $document->document_type_name }}</h6>

                                                @if($document->document_type === 'trade_license')
                                                    @if($document->document_number)
                                                        <p class="mb-2"><small><strong>License #:</strong> {{ $document->document_number }}</small></p>
                                                    @endif
                                                    @if($document->document_path)
                                                        <a href="{{ asset($document->document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2">
                                                            <i class="fas fa-eye"></i> View File
                                                        </a>
                                                    @endif
                                                @elseif($document->document_type === 'office_landline')
                                                    @if($document->landline_number)
                                                        <p class="mb-2"><small><strong>Landline:</strong> {{ $document->landline_number }}</small></p>
                                                    @endif
                                                @elseif($document->document_type === 'company_email')
                                                    @if($document->company_email)
                                                        <p class="mb-2"><small><strong>Email:</strong> {{ $document->company_email }}</small></p>
                                                    @endif
                                                @endif

                                                <div class="mt-3 pt-3 border-top">
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="fas fa-calendar me-1"></i>Submitted: {{ $document->created_at->format('M j, Y') }}
                                                    </small>
                                                    @if($document->reviewed_at)
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-check-circle me-1"></i>Reviewed: {{ $document->reviewed_at->format('M j, Y') }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="card-footer bg-transparent">
                                                <div class="btn-group w-100" role="group">
                                                    <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    @if($document->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-outline-success" onclick="approveDocument({{ $document->id }})">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="showRejectForm({{ $document->id }})">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No documents found</h5>
                            <p class="text-muted">No documents match your current filters.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            {{ $paginatedGroups->appends(request()->query())->links('pagination::bootstrap-4') }}
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

function approveDocument(documentId) {
    if (confirm('Approve this document? This will notify the employer.')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/documents/' + documentId + '/approve';
        
        var csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
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

.company-group {
    margin-bottom: 2rem;
}

.company-header-card {
    background: linear-gradient(135deg, #2773e8 0%, #764ba2 100%);
    color: white;
    border: none;
}

.company-header-card .card-body {
    color: white;
}

.company-header-card h5,
.company-header-card p {
    color: white !important;
}

.company-header-card .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.document-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e0e0e0;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.avatar-lg {
    width: 4rem;
    height: 4rem;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.font-24 {
    font-size: 1.5rem;
}

@media (max-width: 768px) {
    .document-card {
        margin-bottom: 1rem;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
    }
}
</style>
@endsection
