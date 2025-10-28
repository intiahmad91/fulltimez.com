@extends('layouts.app')

@section('title', 'Document Verification')

@section('content')
<style>
/* Professional Document Verification Styles */
.documents-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px 0;
}

.documents-header {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    padding: 30px;
}

.header-content {
    display: flex;
    justify-content: between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-info h1 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.header-info p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-primary {
    background: #3498db;
    border: none;
    border-radius: 8px;
    padding: 12px 25px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #2980b9;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.btn-outline-primary {
    border: 2px solid #3498db;
    color: #3498db;
    background: transparent;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #3498db;
    color: white;
    transform: translateY(-1px);
}

.documents-content {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
}

.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

.document-card {
    background: #ffffff;
    border: 2px solid #ecf0f1;
    border-radius: 12px;
    padding: 25px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.document-card:hover {
    border-color: #3498db;
    box-shadow: 0 8px 25px rgba(52, 152, 219, 0.15);
    transform: translateY(-3px);
}

.document-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #3498db;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.document-card:hover::before {
    opacity: 1;
}

.document-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.document-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.document-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.document-icon.trade-license {
    background: #e74c3c;
}

.document-icon.office-landline {
    background: #f39c12;
}

.document-icon.company-email {
    background: #27ae60;
}

.document-title-text h3 {
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 5px 0;
}

.document-title-text p {
    color: #7f8c8d;
    font-size: 14px;
    margin: 0;
}

.document-status {
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-badge.pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-badge.rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.document-details {
    margin-bottom: 15px;
}

.details-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    margin-bottom: 8px;
}

.detail-group {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #f8f9fa;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
}

.detail-group i {
    color: #3498db;
    font-size: 12px;
}

.detail-group span {
    color: #2c3e50;
    font-weight: 500;
}

.rejection-reason {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8d7da;
    color: #721c24;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    margin-top: 8px;
}

.rejection-reason i {
    color: #e74c3c;
}

.document-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.action-btn.view {
    background: #3498db;
    color: white;
}

.action-btn.view:hover {
    background: #2980b9;
    color: white;
    transform: translateY(-1px);
}

.action-btn.delete {
    background: #e74c3c;
    color: white;
}

.action-btn.delete:hover {
    background: #c0392b;
    color: white;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.empty-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #ecf0f1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 40px;
    color: #bdc3c7;
}

.empty-state h3 {
    color: #2c3e50;
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 15px 0;
}

.empty-state p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0 0 25px 0;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.alert {
    border-radius: 8px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 25px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.progress-section {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
    margin-bottom: 30px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.progress-header h3 {
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.progress-bar-container {
    background: #ecf0f1;
    border-radius: 10px;
    height: 12px;
    overflow: hidden;
    margin-bottom: 15px;
}

.progress-bar {
    height: 100%;
    background: #3498db;
    border-radius: 10px;
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    color: #7f8c8d;
    font-size: 14px;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .documents-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .document-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .document-actions {
        justify-content: center;
    }
    
    .documents-header,
    .documents-content,
    .progress-section {
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .document-card {
        padding: 20px;
    }
    
    .action-btn {
        flex: 1;
        text-align: center;
    }
}
</style>

<section class="documents-container">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <!-- Documents Header -->
                <div class="documents-header">
                    <div class="header-content">
                        <div class="header-info">
                            <h1>Document Verification</h1>
                                </div>
                        <div class="header-actions">
                                    <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Submit New Document
                            </a>
                            <a href="{{ route('profile') }}" class="btn btn-outline-primary">
                                <i class="fas fa-user me-2"></i>View Profile
                                    </a>
                                </div>
                            </div>
                        </div>

                            @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                <!-- Progress Section -->
                @php
                    $totalRequired = 3;
                    $submittedCount = $documents->count();
                    $approvedCount = $documents->where('status', 'approved')->count();
                    $progressPercentage = ($submittedCount / $totalRequired) * 100;
                @endphp
                
                <div class="progress-section">
                    <div class="progress-header">
                        <h3>Verification Progress</h3>
                        <span class="status-badge {{ $approvedCount == $totalRequired ? 'approved' : 'pending' }}">
                            {{ $approvedCount }}/{{ $totalRequired }} Complete
                        </span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ $progressPercentage }}%"></div>
                    </div>
                    <div class="progress-text">
                        {{ $submittedCount }} of {{ $totalRequired }} required documents submitted
                    </div>
                </div>

                <!-- Documents Content -->
                <div class="documents-content">
                            @forelse($documents as $document)
                        <div class="documents-grid">
                                    <div class="document-card">
                                <div class="document-header">
                                                <div class="document-title">
                                        <div class="document-icon {{ str_replace('_', '-', $document->document_type) }}">
                                            @if($document->document_type === 'trade_license')
                                                <i class="fas fa-certificate"></i>
                                            @elseif($document->document_type === 'office_landline')
                                                <i class="fas fa-phone"></i>
                                            @elseif($document->document_type === 'company_email')
                                                <i class="fas fa-envelope"></i>
                                            @else
                                                <i class="fas fa-file-alt"></i>
                                            @endif
                                        </div>
                                        <div class="document-title-text">
                                            <h3>{{ $document->document_type_name }}</h3>
                                            <p>{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</p>
                                        </div>
                                                </div>
                                                <div class="document-status">
                                        <span class="status-badge {{ $document->status }}">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                                </div>
                                            </div>

                                <div class="document-details">
                                    <div class="details-row">
                                        <div class="detail-group">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span><strong>Submitted:</strong> {{ $document->created_at->format('M j, Y') }}</span>
                                        </div>
                                        
                                                                    @if($document->reviewed_at)
                                            <div class="detail-group">
                                                <i class="fas fa-check-circle"></i>
                                                <span><strong>Reviewed:</strong> {{ $document->reviewed_at->format('M j, Y') }}</span>
                                            </div>
                                                                    @endif
                                                            
                                                            @if($document->document_type === 'trade_license' && $document->document_number)
                                            <div class="detail-group">
                                                <i class="fas fa-hashtag"></i>
                                                <span><strong>License:</strong> {{ $document->document_number }}</span>
                                            </div>
                                                            @elseif($document->document_type === 'office_landline' && $document->landline_number)
                                            <div class="detail-group">
                                                <i class="fas fa-phone-alt"></i>
                                                <span><strong>Landline:</strong> {{ $document->landline_number }}</span>
                                            </div>
                                                            @elseif($document->document_type === 'company_email' && $document->company_email)
                                            <div class="detail-group">
                                                <i class="fas fa-at"></i>
                                                <span><strong>Email:</strong> {{ $document->company_email }}</span>
                                            </div>
                                                            @endif
                                    </div>
                                                            
                                    @if($document->status === 'rejected' && $document->rejection_reason)
                                        <div class="rejection-reason">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span><strong>Reason:</strong> {{ $document->rejection_reason }}</span>
                                                        </div>
                                    @endif
                                                    </div>

                                                        <div class="document-actions">
                                    <a href="{{ route('employer.documents.show', $document) }}" class="action-btn view">
                                        <i class="fas fa-eye me-1"></i>View Details
                                                            </a>
                                                            @if($document->status === 'pending')
                                                                <form action="{{ route('employer.documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                            <button type="submit" class="action-btn delete">
                                                <i class="fas fa-trash me-1"></i>Delete
                                                                    </button>
                                                                </form>
                                                            @endif
                                        </div>
                                    </div>
                                </div>
                        @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3>No Documents Submitted</h3>
                            <p>Submit your required documents for verification to complete your employer profile and start posting jobs.</p>
                            <a href="{{ route('employer.documents.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Submit First Document
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations to document cards
    const cards = document.querySelectorAll('.document-card');
    
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effects to action buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    
    actionBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
@endsection
