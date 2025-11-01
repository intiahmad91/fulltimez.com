<div class="col-lg-3 d-lg-block mb-lg-0 mb-4 dashside">
    <div class="dashbrd-menu">
        <div class="profile">
            @if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture)
                <img src="{{ asset(auth()->user()->seekerProfile->profile_picture) }}" alt="" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
            @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo)
                <img src="{{ asset(auth()->user()->employerProfile->company_logo) }}" alt="" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
            @else
                <img src="{{ asset('images/avatar2.jpg') }}" alt="" class="img-fluid">
            @endif
            <h3>{{ auth()->user()->name }}</h3>
            <span>{{ ucfirst(auth()->user()->role->slug) }}</span>
        </div>
        <div class="menu-toggle">
            <div class="dash-menu">
                <ul>
                    <li><a href="{{ route('admin.dashboard') }}" title="" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.users.index') }}" title="" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fas fa-users"></i>Manage Users</a></li>
                    <li><a href="{{ route('admin.jobs.index') }}" title="" class="{{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}"><i class="fas fa-briefcase"></i>Manage Jobs</a></li>
                    <li><a href="{{ route('admin.applications.index') }}" title="" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i>Manage Applications</a></li>
                    <li><a href="{{ route('change.password') }}" title="" class="{{ request()->routeIs('change.password') ? 'active' : '' }}"><i class="fas fa-lock"></i>Change Password</a></li>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title=""><i class="fas fa-sign-out-alt"></i>Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

