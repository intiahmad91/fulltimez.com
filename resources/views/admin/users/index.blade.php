@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="container-fluid px-4">
    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['seekers'] }}</h3>
                    <p class="stat-label">Job Seekers</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['employers'] }}</h3>
                    <p class="stat-label">Employers</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['pending_approval'] }}</h3>
                    <p class="stat-label">Pending Approval</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="admin-card mb-4">
    <div class="admin-card-body">
                                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                                    <div class="col-md-4">
                    <label class="form-label">Search</label>
                                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                                    </div>
                <div class="col-md-2">
                    <label class="form-label">Role</label>
                                        <select name="role" class="form-control">
                                            <option value="">All Roles</option>
                                            <option value="seeker" {{ request('role') == 'seeker' ? 'selected' : '' }}>Seekers</option>
                                            <option value="employer" {{ request('role') == 'employer' ? 'selected' : '' }}>Employers</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                                        </select>
                                    </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                                        </select>
                                    </div>
                <div class="col-md-2">
                    <label class="form-label">Per Page</label>
                    <select name="per_page" class="form-control" onchange="this.form.submit()">
                        <option value="12" {{ request('per_page', 20) == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page', 20) == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page', 20) == 48 ? 'selected' : '' }}>48</option>
                    </select>
                                    </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                                    </div>
                                </form>
                            </div>
                        </div>

    <!-- Users Grid -->
    <div class="row g-4">
                                    @forelse($users as $user)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="user-card">
                <div class="user-card-header">
                    <div class="user-avatar-wrapper">
                        @if($user->isSeeker() && $user->seekerProfile && $user->seekerProfile->profile_picture)
                            <img src="{{ asset($user->seekerProfile->profile_picture) }}" alt="{{ $user->name }}" class="user-avatar">
                        @elseif($user->isEmployer() && $user->employerProfile && $user->employerProfile->company_logo)
                            <img src="{{ asset($user->employerProfile->company_logo) }}" alt="{{ $user->name }}" class="user-avatar">
                        @else
                            <div class="user-avatar-default">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="user-role-badge role-{{ $user->role->slug }}">
                        <i class="fas {{ $user->isAdmin() ? 'fa-shield-alt' : ($user->isEmployer() ? 'fa-building' : 'fa-user') }}"></i>
                        {{ ucfirst($user->role->slug) }}
                    </div>
                </div>
                
                <div class="user-card-body">
                    <h5 class="user-name">{{ $user->name }}</h5>
                    <p class="user-email">
                        <i class="fas fa-envelope"></i> {{ $user->email }}
                    </p>
                    
                                            @if($user->isSeeker() && $user->seekerProfile)
                        <div class="user-info">
                            <i class="fas fa-briefcase"></i>
                            <span>{{ $user->seekerProfile->current_position ?? 'Job Seeker' }}</span>
                        </div>
                        @if($user->seekerProfile->city)
                        <div class="user-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->seekerProfile->city }}</span>
                        </div>
                        @endif
                                            @elseif($user->isEmployer() && $user->employerProfile)
                        <div class="user-info">
                            <i class="fas fa-building"></i>
                            <span>{{ $user->employerProfile->company_name ?? 'Company' }}</span>
                        </div>
                        @if($user->employerProfile->city)
                        <div class="user-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->employerProfile->city }}</span>
                        </div>
                        @endif
                                            @endif
                    
                    <div class="user-status-badges mt-3">
                                            @if($user->status == 'active')
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i> Active
                            </span>
                                            @elseif($user->status == 'inactive')
                            <span class="status-badge status-inactive">
                                <i class="fas fa-pause-circle"></i> Inactive
                            </span>
                        @else
                            <span class="status-badge status-banned">
                                <i class="fas fa-ban"></i> Banned
                            </span>
                        @endif
                        
                        @if($user->is_approved)
                            <span class="status-badge status-approved">
                                <i class="fas fa-check"></i> Approved
                            </span>
                        @elseif(!$user->isAdmin())
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                        
                        @if($user->isSeeker() && $user->seekerProfile)
                            @if($user->seekerProfile->approval_status == 'approved')
                                <span class="status-badge status-approved">
                                    <i class="fas fa-file-check"></i> Resume Approved
                                </span>
                            @elseif($user->seekerProfile->approval_status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-file-clock"></i> Resume Pending
                                </span>
                            @elseif($user->seekerProfile->approval_status == 'rejected')
                                <span class="status-badge status-banned">
                                    <i class="fas fa-file-times"></i> Resume Rejected
                                </span>
                            @endif
                            
                            @if($user->seekerProfile->isFeatured() && $user->seekerProfile->featured_expires_at && $user->seekerProfile->featured_expires_at->isFuture())
                                <span class="status-badge" style="background: #fbbf24; color: #92400e;">
                                    <i class="fas fa-star"></i> Featured
                                </span>
                            @endif
                        @endif
                        
                        @if($user->hasVerifiedEmail())
                            <span class="status-badge status-verified">
                                <i class="fas fa-envelope-check"></i> Verified
                            </span>
                                            @else
                            <span class="status-badge status-unverified">
                                <i class="fas fa-envelope-open"></i> Unverified
                            </span>
                                            @endif
                    </div>
                    
                    <div class="user-meta mt-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i>
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </small>
                    </div>
                </div>
                
                                            @if(!$user->isAdmin())
                <div class="user-card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        
                        <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i>
                                                </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                                    @if($user->isEmployer() && $user->employerProfile && $user->employerProfile->verification_status !== 'verified')
                                                    <li>
                                    <form action="{{ route('admin.users.approve-employer', $user) }}" method="POST" onsubmit="return confirm('Approve this employer?');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle"></i> Approve Employer
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                                    @if($user->isSeeker() && $user->seekerProfile && $user->seekerProfile->verification_status !== 'verified')
                                                    <li>
                                    <form action="{{ route('admin.users.approve-seeker', $user) }}" method="POST" onsubmit="return confirm('Approve this jobseeker?');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle"></i> Approve Jobseeker
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                @if($user->status != 'active')
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                        @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-toggle-on"></i> Activate
                                                            </button>
                                                        </form>
                                                    </li>
                                @endif
                                
                                @if($user->status != 'inactive')
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                        @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="inactive">
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-toggle-off"></i> Deactivate
                                                            </button>
                                                        </form>
                                                    </li>
                                @endif
                                
                                @if($user->status != 'banned')
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                        @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="banned">
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-ban"></i> Ban
                                                            </button>
                                                        </form>
                                                    </li>
                                @endif
                                
                                                    <li><hr class="dropdown-divider"></li>
                                
                                                    <li>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                                        @csrf @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                    </div>
                </div>
                                            @endif
            </div>
        </div>
                                    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Users Found</h4>
                <p class="text-muted">Try adjusting your filters to find users.</p>
            </div>
        </div>
                                    @endforelse
                        </div>

    <!-- Pagination -->
        <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-0">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </p>
                </div>
                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Statistics Cards */
.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
}

.stat-primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-success .stat-icon {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-info .stat-icon {
    background: linear-gradient(135deg, #3494e6 0%, #ec6ead 100%);
}

.stat-warning .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
    color: #2d3748;
}

.stat-label {
    font-size: 14px;
    color: #718096;
    margin: 0;
    font-weight: 500;
}

/* User Cards */
.user-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.user-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.user-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 24px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar-wrapper {
    position: relative;
    z-index: 2;
}

.user-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.user-avatar-default {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 700;
    color: #667eea;
    border: 4px solid #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.user-role-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #fff;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    display: flex;
    align-items: center;
    gap: 4px;
}

.role-admin {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.3);
}

.role-employer {
    background: rgba(59, 130, 246, 0.2);
    border-color: rgba(59, 130, 246, 0.3);
}

.role-seeker {
    background: rgba(16, 185, 129, 0.2);
    border-color: rgba(16, 185, 129, 0.3);
}

.user-card-body {
    padding: 24px;
    flex: 1;
}

.user-name {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 8px 0;
}

.user-email {
    font-size: 13px;
    color: #718096;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.user-info {
    font-size: 14px;
    color: #4a5568;
    margin: 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-info i {
    color: #a0aec0;
    width: 16px;
}

.user-status-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #fef3c7;
    color: #92400e;
}

.status-banned {
    background: #fee2e2;
    color: #991b1b;
}

.status-approved {
    background: #dbeafe;
    color: #1e40af;
}

.status-pending {
    background: #fce7f3;
    color: #9f1239;
}

.status-verified {
    background: #dcfce7;
    color: #166534;
}

.status-unverified {
    background: #fef3c7;
    color: #854d0e;
}

.user-meta {
    padding-top: 12px;
    border-top: 1px solid #e9ecef;
}

.user-card-footer {
    padding: 16px 24px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card {
        padding: 20px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .stat-number {
        font-size: 24px;
    }
    
    .user-avatar,
    .user-avatar-default {
        width: 80px;
        height: 80px;
        font-size: 28px;
    }
}

.btn-group .dropdown-toggle::after {
    margin-left: 0;
}
</style>

@endsection
