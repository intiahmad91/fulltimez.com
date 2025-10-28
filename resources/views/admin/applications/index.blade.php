@extends('layouts.admin')

@section('title', 'Manage Applications')
@section('page-title', 'Manage Applications')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-file-alt"></i> All Applications</h5>
        <span class="admin-badge badge-primary">{{ $applications->total() }} Total</span>
    </div>
    <div class="admin-card-body">

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-3">
                                    <div class="col-md-10">
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                            <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

        <!-- Applications List -->
        <div class="applications-list" id="applicationsList">
            @forelse($applications as $application)
            <div class="application-card" data-application-id="{{ $application->id }}">
                <div class="application-card-content">
                    <div class="application-header">
                        <div class="application-main-info">
                            <div class="application-title-section">
                                <div class="application-title-row">
                                    <h4 class="application-title">{{ $application->seeker->seekerProfile->full_name ?? $application->seeker->name }}</h4>
                                    <div class="application-meta-badges">
                                        <span class="meta-badge job-badge">
                                            <i class="fas fa-briefcase"></i>
                                            {{ $application->job->title }}
                                        </span>
                                        <span class="meta-badge date-badge">
                                            <i class="fas fa-calendar"></i>
                                            {{ $application->created_at->format('M d') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="application-company">
                                    <i class="fas fa-building"></i>
                                    {{ optional($application->job->employer->employerProfile)->company_name ?? 'No Company' }}
                                </div>
                            </div>
                            
                            <div class="application-status-section">
                                @if($application->status == 'pending')
                                    <span class="status-badge pending">Pending</span>
                                @elseif($application->status == 'reviewed')
                                    <span class="status-badge reviewed">Reviewed</span>
                                @elseif($application->status == 'shortlisted')
                                    <span class="status-badge shortlisted">Shortlisted</span>
                                @elseif($application->status == 'accepted')
                                    <span class="status-badge accepted">Accepted</span>
                                @else
                                    <span class="status-badge rejected">Rejected</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="application-actions">
                            <div class="d-flex gap-2">
                                <!-- View Button -->
                                <a href="{{ route('admin.applications.show', $application) }}" class="action-btn view-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Delete Button -->
                                <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Delete Application">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="application-details">
                        <div class="application-detail-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $application->seeker->email }}</span>
                        </div>
                        
                        @if($application->cover_letter)
                        <div class="application-detail-item">
                            <i class="fas fa-file-text"></i>
                            <span>Cover Letter Available</span>
                        </div>
                        @endif
                        
                        @if($application->resume_file)
                        <div class="application-detail-item">
                            <i class="fas fa-file-pdf"></i>
                            <span>Resume Available</span>
                        </div>
                        @endif
                    </div>
                    
                </div>
            </div>
            @empty
            <div class="no-applications">
                <div class="no-applications-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h4>No applications found</h4>
                <p>There are no applications matching your criteria.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="pagination-wrapper mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination-info">
                    <span class="text-muted">
                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }} of {{ $applications->total() }} applications
                    </span>
                </div>
                <div class="pagination-controls">
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


@endsection


