@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
/* Professional Dashboard Styles */
.dashboard-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px 0;
}

.dashboard-header {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    padding: 30px;
}

.welcome-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.welcome-content h1 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 700;
    margin: 0;
}

.welcome-content p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 5px 0 0 0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #3498db;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: 600;
}

.user-details h4 {
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.user-details span {
    color: #7f8c8d;
    font-size: 14px;
}

.stats-row {
    display: flex;
    justify-content: flex-end;
    gap: 30px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #ecf0f1;
}

.simple-stat {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.simple-stat .stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.simple-stat .stat-label {
    color: #7f8c8d;
    font-size: 12px;
    margin: 5px 0 0 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.quick-actions {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
}

.section-title {
    color: #2c3e50;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title i {
    color: #3498db;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-card {
    background: #ffffff;
    border: 2px solid #ecf0f1;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    border-color: #3498db;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 24px;
    color: #3498db;
    transition: all 0.3s ease;
}

.action-card:hover .action-icon {
    background: #3498db;
    color: white;
    transform: scale(1.1);
}

.action-title {
    color: #2c3e50;
    font-size: 16px;
    font-weight: 600;
    margin: 0;
}

.action-description {
    color: #7f8c8d;
    font-size: 13px;
    margin: 8px 0 0 0;
}

.recent-activity {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    padding: 30px;
    margin-top: 30px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #ecf0f1;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #3498db;
}

.activity-content h6 {
    color: #2c3e50;
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

.activity-content p {
    color: #7f8c8d;
    font-size: 12px;
    margin: 2px 0 0 0;
}

.activity-time {
    color: #95a5a6;
    font-size: 12px;
    margin-left: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .welcome-section {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-row {
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .actions-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
    
    .dashboard-header,
    .quick-actions,
    .recent-activity {
        padding: 20px;
    }
}
</style>

<section class="dashboard-container">
    <div class="container">
        <div class="row">
            @include('dashboard.sidebar')
            <div class="col-lg-9">
                <!-- Welcome Header with Stats -->
                <div class="dashboard-header">
                    <div class="welcome-section">
                        <div class="welcome-content">
                            <h1>Welcome back, {{ auth()->user()->name }}!</h1>
                            <p>Here's what's happening with your {{ auth()->user()->isSeeker() ? 'job search' : 'business' }} today.</p>
                        </div>
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <h4>{{ auth()->user()->name }}</h4>
                                <span>{{ ucfirst(auth()->user()->role->slug) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Simple Statistics Row -->
                    <div class="stats-row">
                        @if(auth()->user()->isSeeker())
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->applications()->count() }}</span>
                                <span class="stat-label">Applications Sent</span>
                            </div>
                            
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->applications()->where('status', 'reviewed')->count() }}</span>
                                <span class="stat-label">Profile Views</span>
                            </div>
                            
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->unreadNotifications->count() }}</span>
                                <span class="stat-label">New Notifications</span>
                            </div>
                        @elseif(auth()->user()->isEmployer())
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->jobPostings()->count() }}</span>
                                <span class="stat-label">Active Jobs</span>
                            </div>
                            
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->jobPostings()->withCount('applications')->get()->sum('applications_count') }}</span>
                                <span class="stat-label">Total Applications</span>
                            </div>
                            
                            <div class="simple-stat">
                                <span class="stat-number">{{ auth()->user()->unreadNotifications->count() }}</span>
                                <span class="stat-label">New Notifications</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h2 class="section-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h2>
                    <div class="actions-grid">
                        @if(auth()->user()->isSeeker())
                            <a href="{{ route('profile') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h5 class="action-title">Update Profile</h5>
                                <p class="action-description">Complete your profile information</p>
                            </a>
                            
                            <a href="{{ route('seeker.cv.create') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h5 class="action-title">Create CV</h5>
                                <p class="action-description">Build your professional resume</p>
                            </a>
                            
                            <a href="{{ route('applications.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <h5 class="action-title">My Applications</h5>
                                <p class="action-description">Track your job applications</p>
                            </a>
                            
                            <a href="{{ route('jobs.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h5 class="action-title">Browse Jobs</h5>
                                <p class="action-description">Find your next opportunity</p>
                            </a>
                        @elseif(auth()->user()->isEmployer())
                            @php
                                $user = auth()->user();
                                $requiredTypes = ['trade_license', 'office_landline', 'company_email'];
                                $approvedDocuments = $user->employerDocuments()
                                    ->whereIn('document_type', $requiredTypes)
                                    ->where('status', 'approved')
                                    ->get();
                                $approvedTypes = $approvedDocuments->pluck('document_type')->toArray();
                                $allDocumentsApproved = count(array_intersect($requiredTypes, $approvedTypes)) === count($requiredTypes);
                            @endphp
                            
                            @if($allDocumentsApproved)
                                <a href="{{ route('employer.jobs.create') }}" class="action-card">
                                    <div class="action-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <h5 class="action-title">Post New Job</h5>
                                    <p class="action-description">Create a new job posting</p>
                                </a>
                            @else
                                <a href="{{ route('employer.documents.index') }}" class="action-card" style="opacity: 0.6;">
                                    <div class="action-icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </div>
                                    <h5 class="action-title">Post New Job</h5>
                                    <p class="action-description">Complete verification first</p>
                                </a>
                            @endif
                            
                            <a href="{{ route('employer.jobs.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <h5 class="action-title">Manage Jobs</h5>
                                <p class="action-description">View and edit your job postings</p>
                            </a>
                            
                            <a href="{{ route('employer.applications') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h5 class="action-title">View Applications</h5>
                                <p class="action-description">Review candidate applications</p>
                            </a>
                            
                            <a href="{{ route('profile') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h5 class="action-title">Company Profile</h5>
                                <p class="action-description">Update company information</p>
                            </a>
                        @endif
                        
                        <a href="{{ route('change.password') }}" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="action-title">Change Password</h5>
                            <p class="action-description">Update your account security</p>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="recent-activity">
                    <h2 class="section-title">
                        <i class="fas fa-clock"></i>
                        Recent Activity
                    </h2>
                    <div class="activity-list">
                        @if(auth()->user()->notifications()->latest()->take(5)->count() > 0)
                            @foreach(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h6>{{ $notification->data['title'] ?? 'New Notification' }}</h6>
                                        <p>{{ $notification->data['message'] ?? 'You have a new notification' }}</p>
                                    </div>
                                    <div class="activity-time">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h6>No Recent Activity</h6>
                                    <p>Your recent activities will appear here</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

