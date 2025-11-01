@php
    use Illuminate\Support\Facades\Storage;
@endphp

<style>
/* Modern Professional Sidebar Styles */
.modern-sidebar {
    background: #eceff8 !important;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    position: sticky;
    top: 20px;
    border: 1px solid #e0e0e0;
}

.sidebar-header {
    background: #eceff8 !important;
    padding: 25px 20px;
    text-align: center;
    border-bottom: 1px solid #d0d0d0;
}

.sidebar-title {
    color: #333;
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.profile-section {
    padding: 25px 20px;
    text-align: center;
    background: #eceff8 !important;
    border-bottom: 1px solid #d0d0d0;
}

.profile-image-container {
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
}

.profile-image {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #d0d0d0;
    transition: all 0.3s ease;
}

.profile-image:hover {
    transform: scale(1.05);
    border-color: #007bff;
}

.default-user-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #d0d0d0;
    border: 3px solid #d0d0d0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.default-user-icon:hover {
    transform: scale(1.05);
    border-color: #007bff;
    background: #d0d0d0;
}

.default-user-icon i {
    font-size: 32px;
    color: #666;
}

.online-indicator {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 16px;
    height: 16px;
    background: #28a745;
    border: 3px solid #ffffff;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
    100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
}

.profile-name {
    color: #333;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

.profile-role {
    color: #666;
    font-size: 14px;
    margin-top: 5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.navigation-menu {
    padding: 0;
    margin: 0;
    list-style: none;
    background: #eceff8 !important;
}

.nav-item {
    margin: 0;
    border-bottom: 1px solid #d0d0d0;
}

.nav-item:last-child {
    border-bottom: none;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    font-weight: 500;
    background: #eceff8 !important;
}

.nav-link:hover {
    background: #d0d5e5 !important;
    color: #007bff;
    transform: translateX(5px);
}

.nav-link.active {
    background: #d0d5e5 !important;
    color: #007bff;
    border-left: 4px solid #007bff;
    font-weight: 600;
}

.nav-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #007bff;
}

.nav-icon {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    font-size: 16px;
    text-align: center;
    flex-shrink: 0;
    color: #666;
}

.nav-link:hover .nav-icon,
.nav-link.active .nav-icon {
    color: #007bff;
}

.nav-text {
    flex: 1;
    font-size: 15px;
}

.notification-badge {
    background: #dc3545;
    color: #ffffff;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 6px;
    border-radius: 10px;
    margin-left: auto;
    min-width: 18px;
    text-align: center;
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-3px); }
    60% { transform: translateY(-2px); }
}

.disabled-link {
    opacity: 0.6;
    cursor: not-allowed;
    position: relative;
}

.disabled-link:hover {
    transform: none;
    background: #eceff8 !important;
    color: #999;
}

.disabled-link::after {
    content: '⚠️';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
}

.logout-section {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #d0d0d0;
}

.logout-link {
    color: #dc3545 !important;
    font-weight: 600;
    background: #eceff8 !important;
}

.logout-link:hover {
    background: #f8d7da !important;
    color: #c82333 !important;
}

/* Responsive Design */
@media (max-width: 991px) {
    .modern-sidebar {
        margin-bottom: 30px;
        position: relative;
        top: 0;
    }
}

@media (max-width: 768px) {
    .profile-image {
        width: 60px;
        height: 60px;
    }
    
    .default-user-icon {
        width: 60px;
        height: 60px;
    }
    
    .default-user-icon i {
        font-size: 24px;
    }
    
    .nav-link {
        padding: 12px 15px;
    }
    
    .nav-text {
        font-size: 14px;
    }
}
</style>

<div class="col-lg-3">
    <div class="modern-sidebar">
        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <h3 class="sidebar-title">My Dashboard</h3>
        </div>
        
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-image-container">
                @php
                    $profileImage = null;
                    
                    if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture) {
                        $profileImage = auth()->user()->seekerProfile->profile_picture;
                    } elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->profile_picture) {
                        $profileImage = auth()->user()->employerProfile->profile_picture;
                    } elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo) {
                        $profileImage = auth()->user()->employerProfile->company_logo;
                    }
                @endphp
                
                @if($profileImage)
                    <img src="{{ asset($profileImage) }}" class="profile-image" alt="Profile Picture" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="default-user-icon" style="display: none;">
                        <i class="fas fa-user"></i>
                    </div>
                @else
                    <div class="default-user-icon">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <span class="online-indicator" data-toggle="tooltip" data-placement="top" title="Online"></span>
            </div>
            <h4 class="profile-name">{{ auth()->user()->name }}</h4>
            <p class="profile-role">{{ ucfirst(auth()->user()->role->slug) }}</p>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="navigation-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="fas fa-user nav-icon"></i>
                    <span class="nav-text">My Profile</span>
                </a>
            </li>
            
            @if(auth()->user()->isSeeker())
                <li class="nav-item">
                    <a href="{{ route('seeker.cv.create') }}" class="nav-link {{ request()->routeIs('seeker.cv.create') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Create CV</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('applications.index') }}" class="nav-link {{ request()->routeIs('applications.index') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane nav-icon"></i>
                        <span class="nav-text">My Applications</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell nav-icon"></i>
                        <span class="nav-text">Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
            @endif
            
            @if(auth()->user()->isEmployer())
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
                
                <li class="nav-item">
                    @if($allDocumentsApproved)
                        <a href="{{ route('employer.jobs.create') }}" class="nav-link {{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle nav-icon"></i>
                            <span class="nav-text">Post Job</span>
                        </a>
                    @else
                        <a href="{{ route('employer.documents.index') }}" class="nav-link disabled-link" title="Complete document verification to post jobs">
                            <i class="fas fa-plus-circle nav-icon"></i>
                            <span class="nav-text">Post Job</span>
                        </a>
                    @endif
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employer.jobs.index') }}" class="nav-link {{ request()->routeIs('employer.jobs.index') || request()->routeIs('employer.jobs.edit') || request()->routeIs('employer.jobs.show') ? 'active' : '' }}">
                        <i class="fas fa-briefcase nav-icon"></i>
                        <span class="nav-text">My Jobs</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employer.applications') }}" class="nav-link {{ request()->routeIs('employer.applications') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Applications</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employer.documents.index') }}" class="nav-link {{ request()->routeIs('employer.documents.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <span class="nav-text">Document Verification</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('employer.notifications.index') }}" class="nav-link {{ request()->routeIs('employer.notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell nav-icon"></i>
                        <span class="nav-text">Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>
            @endif
            
            <li class="nav-item">
                <a href="{{ route('change.password') }}" class="nav-link {{ request()->routeIs('change.password') ? 'active' : '' }}">
                    <i class="fas fa-lock nav-icon"></i>
                    <span class="nav-text">Change Password</span>
                </a>
            </li>
            
            <li class="nav-item logout-section">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link logout-link">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span class="nav-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>

