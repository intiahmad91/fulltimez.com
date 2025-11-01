<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel') - FullTimeZ</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    <!-- Responsive Improvements -->
    <link href="{{ asset('css/responsive-improvements.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Admin Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <div class="admin-logo">
                    <i class="fas fa-shield-alt"></i>
                    <span>Admin Panel</span>
                </div>
                <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="admin-profile">
                <div class="admin-avatar">
                    @if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture)
                        <img src="{{ asset(auth()->user()->seekerProfile->profile_picture) }}" alt="Admin Avatar">
                    @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo)
                        <img src="{{ asset(auth()->user()->employerProfile->company_logo) }}" alt="Admin Avatar">
                    @else
                        <div class="default-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="admin-info">
                    <h4>{{ auth()->user()->name }}</h4>
                    <span class="admin-role">Administrator</span>
                </div>
            </div>
            
            <nav class="admin-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <span>Manage Users</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.jobs.index') }}" class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                            <i class="fas fa-briefcase"></i>
                            <span>Manage Jobs</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Applications</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.documents.index') }}" class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Document Verification</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags"></i>
                            <span>Job Categories</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.countries.index') }}" class="nav-link {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                            <i class="fas fa-globe"></i>
                            <span>Countries</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.cities.index') }}" class="nav-link {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                            <i class="fas fa-city"></i>
                            <span>Cities</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.packages.index') }}" class="nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Package Management</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.change-password') }}" class="nav-link {{ request()->routeIs('admin.change-password') ? 'active' : '' }}">
                            <i class="fas fa-lock"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="back-to-site">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Back to Site</span>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="sidebar-toggle d-lg-none" id="sidebarToggleMobile">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title', 'Admin Dashboard')</h1>
                </div>
                
                <div class="admin-header-right">
                    <div class="admin-notifications" style="position: relative;">
                        <button class="notification-btn" type="button" aria-expanded="false" aria-haspopup="true" style="position: relative; background: none; border: none; padding: 8px; cursor: pointer; border-radius: 50%; transition: background-color 0.2s;">
                            <i class="fas fa-bell" style="font-size: 18px; color: #6b7280;"></i>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span class="notification-badge" style="position: absolute; top: 0; right: 0; background: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold;">{{ auth()->user()->unreadNotifications()->count() }}</span>
                            @endif
                        </button>
                        <div class="notification-dropdown" role="menu" style="display: none; position: absolute; right: 0; top: 100%; z-index: 1050; min-width: 360px; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,.08); overflow: hidden; margin-top: 8px;">
                            <div class="p-3 border-bottom" style="background: #f8fafc;">
                                <strong>Notifications</strong>
                                <span class="badge bg-primary ms-2">{{ auth()->user()->unreadNotifications()->count() }} unread</span>
                            </div>
                            <ul class="list-unstyled mb-0" style="max-height: 420px; overflow: auto;">
                                @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $notification)
                                    <li class="p-3 border-bottom" style="border-bottom: 1px solid #f3f4f6; {{ $notification->read_at ? 'background-color: #f9fafb;' : 'background-color: #fef3c7;' }}">
                                        <div class="d-flex align-items-start">
                                            @if($notification->read_at)
                                                <i class="fas fa-envelope-open me-2 text-muted"></i>
                                            @else
                                                <i class="fas fa-bell me-2 text-primary"></i>
                                            @endif
                                            <div style="flex: 1;">
                                                <div class="fw-semibold" style="font-weight: 600; color: #1f2937;">
                                                    {{ data_get($notification->data, 'title', 'Notification') }}
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-primary ms-1" style="font-size: 10px;">New</span>
                                                    @endif
                                                </div>
                                                <div class="small text-muted mb-1" style="font-size: 12px; color: #6b7280;">{{ $notification->created_at->diffForHumans() }}</div>
                                                <div style="color: #374151; font-size: 14px;">{{ data_get($notification->data, 'message') }}</div>
                                                @if(data_get($notification->data, 'action_url'))
                                                    <a href="{{ data_get($notification->data, 'action_url') }}" class="small text-primary text-decoration-none" style="font-size: 12px;">{{ data_get($notification->data, 'action_text', 'Open') }}</a>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="p-3 border-bottom" style="border-bottom: 1px solid #f3f4f6;">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-circle-check me-2 text-success"></i>
                                            <div>
                                                <div class="fw-semibold" style="font-weight: 600; color: #1f2937;">No notifications</div>
                                                <small class="text-muted" style="color: #6b7280;">You don't have any notifications yet</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                            <div class="p-2 d-flex justify-content-between align-items-center" style="background: #f8fafc; border-top: 1px solid #e5e7eb;">
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.notifications.readAll') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 12px; padding: 4px 8px;">Mark all as read</button>
                                    </form>
                                    @if(auth()->user()->notifications()->count() > 10)
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary" style="font-size: 12px; padding: 4px 8px;">View All</a>
                                    @endif
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="small text-decoration-none" style="font-size: 12px; color: #6b7280;">Refresh</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="admin-user-menu">
                        <div class="dropdown">
                            <button class="user-menu-btn" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    @if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture)
                                        <img src="{{ asset(auth()->user()->seekerProfile->profile_picture) }}" alt="User">
                                    @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo)
                                        <img src="{{ asset(auth()->user()->employerProfile->company_logo) }}" alt="User">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.change-password') }}"><i class="fas fa-lock"></i> Change Password</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error') || $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> 
                        {{ session('error') }}
                        @if($errors->any())
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const sidebar = document.querySelector('.admin-sidebar');
            const main = document.querySelector('.admin-main');
            
            function toggleSidebar() {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('sidebar-collapsed');
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    if (!sidebar.contains(e.target) && !sidebarToggleMobile.contains(e.target)) {
                        sidebar.classList.remove('collapsed');
                        main.classList.remove('sidebar-collapsed');
                    }
                }
            });
        });
        
        // Mobile menu functionality
        const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
        const sidebar = document.querySelector('.admin-sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        if (sidebarToggleMobile) {
            sidebarToggleMobile.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
                mobileOverlay.classList.toggle('active');
            });
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            });
        }
        
        // Close mobile menu when clicking on nav links
        const navLinks = document.querySelectorAll('.admin-sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('mobile-open');
                    mobileOverlay.classList.remove('active');
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                mobileOverlay.classList.remove('active');
            }
        });
        
        // Notifications dropdown toggle
        (function(){
            const container = document.querySelector('.admin-notifications');
            if (!container) {
                console.log('Admin notifications container not found');
                return;
            }
            
            const btn = container.querySelector('.notification-btn');
            const dropdown = container.querySelector('.notification-dropdown');
            
            if (!btn) {
                console.log('Notification button not found');
                return;
            }
            
            if (!dropdown) {
                console.log('Notification dropdown not found');
                return;
            }

            console.log('Notification elements found, setting up event listeners');

            function closeDropdown(){
                dropdown.style.display = 'none';
                container.classList.remove('open');
                btn.setAttribute('aria-expanded', 'false');
                console.log('Dropdown closed');
            }

            function openDropdown(){
                dropdown.style.display = 'block';
                container.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
                console.log('Dropdown opened');
            }

            btn.addEventListener('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                console.log('Notification button clicked');
                
                const isOpen = dropdown.style.display === 'block';
                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            // Close on outside click
            document.addEventListener('click', function(e){
                if (!container.contains(e.target)) {
                    closeDropdown();
                }
            });

            // Close on Escape
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') {
                    closeDropdown();
                }
            });

            // Add hover effect
            btn.addEventListener('mouseenter', function(){
                btn.style.backgroundColor = '#f3f4f6';
            });

            btn.addEventListener('mouseleave', function(){
                btn.style.backgroundColor = 'transparent';
            });
        })();

        // Force table scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.table-responsive');
            tables.forEach(function(table) {
                // Force horizontal scroll
                table.style.overflowX = 'scroll';
                table.style.overflowY = 'visible';
                
                // Add scroll indicators
                const adminTable = table.querySelector('.admin-table');
                if (adminTable) {
                    adminTable.style.minWidth = '1200px';
                    adminTable.style.width = '100%';
                }
                
                // Add scroll event listener for visual feedback
                table.addEventListener('scroll', function() {
                    const scrollLeft = table.scrollLeft;
                    const maxScroll = table.scrollWidth - table.clientWidth;
                    
                    // Add/remove scroll indicators
                    if (scrollLeft > 0) {
                        table.classList.add('scrolled-left');
                    } else {
                        table.classList.remove('scrolled-left');
                    }
                    
                    if (scrollLeft < maxScroll - 1) {
                        table.classList.add('scrolled-right');
                    } else {
                        table.classList.remove('scrolled-right');
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

