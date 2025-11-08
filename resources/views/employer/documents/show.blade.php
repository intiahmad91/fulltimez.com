@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Document Details')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Document Details</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('employer.documents.index') }}">Documents</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="dashboard-section">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')

            <div class="col-lg-9">
                <div class="dashboard-content">
                    <div class="dashboard-panel">
                        <div class="panel-header">
                            <h4><i class="fas fa-file-alt"></i> Document Details</h4>
                            <a href="{{ route('employer.documents.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Documents
                            </a>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="document-details">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong>Document Type:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <span class="badge badge-primary">{{ $document->document_type_name }}</span>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong>Status:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <span class="badge {{ $document->status_badge_class }}">
                                                            {{ ucfirst($document->status) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-sm-4">
                                                        <strong>Submitted On:</strong>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        {{ $document->created_at->format('F j, Y \a\t g:i A') }}
                                                        <small class="text-muted">({{ $document->created_at->diffForHumans() }})</small>
                                                    </div>
                                                </div>

                                                @if($document->reviewed_at)
                                                    <div class="row mb-3">
                                                        <div class="col-sm-4">
                                                            <strong>Reviewed On:</strong>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            {{ $document->reviewed_at->format('F j, Y \a\t g:i A') }}
                                                            <small class="text-muted">({{ $document->reviewed_at->diffForHumans() }})</small>
                                                        </div>
                                                    </div>

                                                    @if($document->reviewer)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Reviewed By:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $document->reviewer->name }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif

                                                @if($document->document_type === 'trade_license')
                                                    @if($document->document_number)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>License Number:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $document->document_number }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($document->document_path)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Document File:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                @php 
                                                                    $documentUrl = asset($document->document_path);
                                                                @endphp
                                                                <div class="btn-group" role="group">
                                                                    <a href="{{ $documentUrl }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-eye"></i> View Document
                                                                    </a>
                                                                    <a href="{{ $documentUrl }}" download class="btn btn-sm btn-primary">
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
                                                            <div class="col-sm-4">
                                                                <strong>Landline Number:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $document->landline_number }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @elseif($document->document_type === 'company_email')
                                                    @if($document->company_email)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Company Email:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
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
                                                            <div class="col-sm-4">
                                                                <strong>Company Website:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <a href="{{ $document->company_website }}" target="_blank" class="text-primary">
                                                                    {{ $document->company_website }}
                                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($document->contact_person_name)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Contact Person:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $document->contact_person_name }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($document->contact_person_position)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Position:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                {{ $document->contact_person_position }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($document->contact_person_mobile)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Mobile Number:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <a href="tel:{{ $document->contact_person_mobile }}" class="text-primary">
                                                                    {{ $document->contact_person_mobile }}
                                                                    <i class="fas fa-phone ms-1"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($document->contact_person_email)
                                                        <div class="row mb-3">
                                                            <div class="col-sm-4">
                                                                <strong>Contact Email:</strong>
                                                            </div>
                                                            <div class="col-sm-8">
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
                                                        <div class="col-sm-4">
                                                            <strong>Admin Notes:</strong>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <div class="alert alert-info">
                                                                {{ $document->admin_notes }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="document-actions">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Actions</h6>
                                            </div>
                                            <div class="card-body">
                                                @if($document->status === 'pending')
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-clock"></i>
                                                        <strong>Under Review</strong><br>
                                                        <small>Your document is currently being reviewed by our admin team.</small>
                                                    </div>
                                                @elseif($document->status === 'approved')
                                                    <div class="alert alert-success">
                                                        <i class="fas fa-check-circle"></i>
                                                        <strong>Approved</strong><br>
                                                        <small>Your document has been approved!</small>
                                                    </div>
                                                @elseif($document->status === 'rejected')
                                                    <div class="alert alert-danger">
                                                        <i class="fas fa-times-circle"></i>
                                                        <strong>Rejected</strong><br>
                                                        <small>Please review the admin notes and resubmit.</small>
                                                    </div>
                                                @endif

                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('employer.documents.index') }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-list"></i> All Documents
                                                    </a>
                                                    
                                                    @if($document->status === 'pending')
                                                        <form action="{{ route('employer.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                                <i class="fas fa-trash"></i> Delete Document
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($document->status === 'rejected')
                                                        <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                                            <i class="fas fa-plus"></i> Resubmit Document
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.document-details .card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.document-actions .card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge-primary {
    background-color: #007bff;
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

@media (max-width: 768px) {
    .document-actions {
        margin-top: 20px;
    }
}
</style>
@endsection
