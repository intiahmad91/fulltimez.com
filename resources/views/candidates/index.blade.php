@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<style>
.page-header {
    background: #f8f9fa;
    padding: 40px 0;
    border-bottom: 1px solid #e9ecef;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0;
}

.candidates-count {
    color: #6c757d;
    font-size: 1rem;
}

.filter-section {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.filter-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 15px;
}

.candidate-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    transition: border-color 0.2s ease;
}

.candidate-item:hover {
    border-color: #007bff;
}

.candidate-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.candidate-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.candidate-position {
    color: #007bff;
    font-weight: 500;
    margin-bottom: 10px;
}

.candidate-details {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.view-btn {
    background: #007bff;
    color: white;
    padding: 8px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 0.9rem;
    border: none;
    transition: background-color 0.2s ease;
}

.view-btn:hover {
    background: #0056b3;
    color: white;
    text-decoration: none;
}

.no-candidates {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.search-form {
    display: flex;
    gap: 15px;
    align-items: end;
}

.search-form .form-group {
    flex: 1;
    margin-bottom: 0;
}

.search-form .form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 8px 12px;
}

.search-form .btn {
    padding: 8px 20px;
    border-radius: 4px;
}

.simple-pagination {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.simple-pagination .pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
}

.simple-pagination .pagination li {
    display: inline-block;
}

.simple-pagination .pagination .page-link {
    display: block;
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    color: #007bff;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.simple-pagination .pagination .page-link:hover {
    background-color: #e9ecef;
    color: #0056b3;
    text-decoration: none;
}

.simple-pagination .pagination .active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.simple-pagination .pagination .disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
    cursor: not-allowed;
}


@media (max-width: 768px) {
    .search-form {
        flex-direction: column;
        gap: 10px;
    }
    
    .candidate-item {
        padding: 15px;
    }
    
    .candidate-avatar {
        width: 50px;
        height: 50px;
    }
    
}
</style>

<div class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="page-title">Candidates</h1>
                <p class="candidates-count">{{ $candidates->total() }} candidates found</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding: 30px 0;">
    <div class="filter-section">
        <h5 class="filter-title">Search Candidates</h5>
        <form action="{{ route('candidates.index') }}" method="GET" class="search-form">
            <div class="form-group">
                <label class="form-label">Name or Position</label>
                <input type="text" name="search" class="form-control" placeholder="Search by name or position" value="{{ request('search') }}">
            </div>
            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" placeholder="Enter city" value="{{ request('city') }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            @if(request('search') || request('city'))
            <div class="form-group">
                <a href="{{ route('candidates.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
            @endif
        </form>
    </div>

    <div class="row">
        @forelse($candidates as $candidate)
        <div class="col-lg-6 col-md-12">
            <div class="candidate-item">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <img src="{{ $candidate->seekerProfile && $candidate->seekerProfile->profile_picture ? asset($candidate->seekerProfile->profile_picture) : asset('images/avatar.jpg') }}" 
                             alt="Candidate" class="candidate-avatar">
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="candidate-name">{{ $candidate->seekerProfile->full_name ?? $candidate->name }}</h4>
                        <p class="candidate-position">{{ $candidate->seekerProfile->current_position ?? 'Job Seeker' }}</p>
                        
                        <div class="candidate-details">
                            <strong>Location:</strong> {{ $candidate->seekerProfile->city ?? 'N/A' }}, {{ $candidate->seekerProfile->country ?? 'UAE' }}
                        </div>
                        
                        <div class="candidate-details">
                            <strong>Experience:</strong> {{ $candidate->seekerProfile->experience_years ?? 'Not specified' }}
                        </div>
                        
                        @if($candidate->seekerProfile && $candidate->seekerProfile->expected_salary)
                        <div class="candidate-details">
                            <strong>Expected Salary:</strong> {{ $candidate->seekerProfile->expected_salary }}
                        </div>
                        @endif
                        
                        <div class="mt-3">
                            <a href="{{ route('candidates.show', $candidate->id) }}" class="view-btn">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="no-candidates">
                <h4>No candidates found</h4>
                <p>No candidates match your search criteria. Try adjusting your filters.</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($candidates->hasPages())
    <div class="simple-pagination">
        {{ $candidates->links() }}
    </div>
    @endif
</div>
@endsection
