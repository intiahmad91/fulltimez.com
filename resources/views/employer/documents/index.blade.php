@extends('layouts.app')

@section('title', 'Document Verification')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Document Verification</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Document Verification</li>
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
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4><i class="fas fa-file-alt"></i> Document Verification</h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Submit Document
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @forelse($documents as $document)
                                <div class="document-item mb-4">
                                    <div class="document-card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="document-title">
                                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                                    <strong>{{ $document->document_type_name }}</strong>
                                                </div>
                                                <div class="document-status">
                                                    @if($document->status === 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @elseif($document->status === 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($document->status === 'rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="document-details">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="document-info">
                                                            <p class="text-muted mb-2">
                                                                <small>
                                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                                    Submitted: {{ $document->created_at->format('M j, Y \a\t g:i A') }}
                                                                    @if($document->reviewed_at)
                                                                        | <i class="fas fa-check-circle me-1"></i>
                                                                        Reviewed: {{ $document->reviewed_at->format('M j, Y \a\t g:i A') }}
                                                                    @endif
                                                                </small>
                                                            </p>
                                                            
                                                            @if($document->document_type === 'trade_license' && $document->document_number)
                                                                <p class="mb-2"><strong>License Number:</strong> {{ $document->document_number }}</p>
                                                            @elseif($document->document_type === 'office_landline' && $document->landline_number)
                                                                <p class="mb-2"><strong>Landline:</strong> {{ $document->landline_number }}</p>
                                                            @elseif($document->document_type === 'company_email' && $document->company_email)
                                                                <p class="mb-2"><strong>Email:</strong> {{ $document->company_email }}</p>
                                                            @endif
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="document-actions">
                                                            <a href="{{ route('employer.documents.show', $document) }}" class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            @if($document->status === 'pending')
                                                                <form action="{{ route('employer.documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-outline-danger btn-sm mt-1">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No documents submitted yet</h5>
                                <p class="text-muted">Submit your required documents for verification to get started.</p>
                                <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Submit First Document
                                </a>
                            </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.document-card {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background: white;
}

.document-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.document-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem 1.25rem;
    border-radius: 8px 8px 0 0;
}

.document-title {
    font-size: 1.1rem;
    color: #495057;
    margin: 0;
}

.document-status .badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

.document-card .card-body {
    padding: 1.25rem;
}

.document-info p {
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.document-info strong {
    color: #495057;
}


.document-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.document-actions .btn {
    width: 100%;
    border-radius: 4px;
    font-size: 0.875rem;
}

@media (max-width: 768px) {
    .document-actions {
        margin-top: 1rem;
    }
    
    .document-actions .btn {
        width: auto;
        display: inline-block;
        margin-right: 0.5rem;
    }
}

@media (max-width: 576px) {
    .document-card .card-header {
        padding: 0.75rem 1rem;
    }
    
    .document-card .card-body {
        padding: 1rem;
    }
    
    .document-title {
        font-size: 1rem;
    }
}
</style>
@endsection
