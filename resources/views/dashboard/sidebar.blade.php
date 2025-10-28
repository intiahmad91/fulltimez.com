@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="col-lg-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">My Dashboard</h3>
        </div>
        <div class="card-body text-center item-user border-bottom">
            <div class="profile-pic">
                <div class="profile-pic-img">
                    <span class="bg-success dots" data-toggle="tooltip" data-placement="top" title="" data-original-title="online"></span>
                    @if(auth()->user()->isSeeker() && auth()->user()->seekerProfile && auth()->user()->seekerProfile->profile_picture)
                        <img src="{{ Storage::url(auth()->user()->seekerProfile->profile_picture) }}" class="brround" alt="user" style="width: 100px; height: 100px; object-fit: cover;">
                    @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->profile_picture)
                        <img src="{{ Storage::url(auth()->user()->employerProfile->profile_picture) }}" class="brround" alt="profile" style="width: 100px; height: 100px; object-fit: cover;">
                    @elseif(auth()->user()->isEmployer() && auth()->user()->employerProfile && auth()->user()->employerProfile->company_logo)
                        <img src="{{ Storage::url(auth()->user()->employerProfile->company_logo) }}" class="brround" alt="company" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/profile_img.jpg') }}" class="brround" alt="user">
                    @endif
                </div>
                <h4 class="mt-3 mb-0 font-weight-semibold">{{ auth()->user()->name }}</h4>
            </div>
        </div>
        <div class="dash_widget-sec">
            <ul>
                <li><a href="{{ route('dashboard') }}" title="" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Dashboard</a></li>
                <li><a href="{{ route('profile') }}" title="" class="{{ request()->routeIs('profile') ? 'active' : '' }}"><i class="fas fa-user"></i>My Profile</a></li>
                @if(auth()->user()->isSeeker())
                    <li><a href="{{ route('seeker.cv.create') }}" title="" class="{{ request()->routeIs('seeker.cv.create') ? 'active' : '' }}"><i class="fas fa-file-alt"></i>Create CV</a></li>
                    <li><a href="{{ route('applications.index') }}" title="" class="{{ request()->routeIs('applications.index') ? 'active' : '' }}"><i class="fas fa-paper-plane"></i>My Applications</a></li>
                    <li><a href="{{ route('notifications.index') }}" title="" class="{{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>Notifications
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a></li>
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
                    <li>
                        @if($allDocumentsApproved)
                            <a href="{{ route('employer.jobs.create') }}" title="Post Job" class="{{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle"></i>Post Job
                            </a>
                        @else
                            <a href="{{ route('employer.documents.index') }}" title="Complete document verification to post jobs" class="text-muted" style="opacity: 0.6;">
                                <i class="fas fa-plus-circle"></i>Post Job <small class="text-warning">(Documents Required)</small>
                            </a>
                        @endif
                    </li>
                    <li><a href="{{ route('employer.jobs.index') }}" title="" class="{{ request()->routeIs('employer.jobs.index') || request()->routeIs('employer.jobs.edit') || request()->routeIs('employer.jobs.show') ? 'active' : '' }}"><i class="fas fa-briefcase"></i>My Jobs</a></li>
                    <li><a href="{{ route('employer.applications') }}" title="" class="{{ request()->routeIs('employer.applications') ? 'active' : '' }}"><i class="fas fa-file-alt"></i>Applications</a></li>
                    <li><a href="{{ route('employer.documents.index') }}" title="" class="{{ request()->routeIs('employer.documents.*') ? 'active' : '' }}"><i class="fas fa-file-alt"></i>Document Verification</a></li>
                    <li><a href="{{ route('employer.notifications.index') }}" title="" class="{{ request()->routeIs('employer.notifications.*') ? 'active' : '' }}"><i class="fas fa-bell"></i>Notifications
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger ms-1">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a></li>
                @endif
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

