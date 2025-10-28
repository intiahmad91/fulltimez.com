@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-users"></i> All Users</h5>
        <span class="admin-badge badge-primary">{{ $users->total() }} Total</span>
    </div>
    <div class="admin-card-body">

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <select name="role" class="form-control">
                                            <option value="">All Roles</option>
                                            <option value="seeker" {{ request('role') == 'seeker' ? 'selected' : '' }}>Seekers</option>
                                            <option value="employer" {{ request('role') == 'employer' ? 'selected' : '' }}>Employers</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admins</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                                    </div>
                                </form>
                            </div>
                        </div>

        <div class="table-responsive">
            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->isSeeker() && $user->seekerProfile)
                                                <br><small class="text-muted">{{ $user->seekerProfile->current_position ?? 'Job Seeker' }}</small>
                                            @elseif($user->isEmployer() && $user->employerProfile)
                                                <br><small class="text-muted">{{ $user->employerProfile->company_name }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td><span class="admin-badge badge-info">{{ ucfirst($user->role->slug) }}</span></td>
                                        <td>
                                            @if($user->status == 'active')
                                                <span class="admin-badge badge-success">Active</span>
                                            @elseif($user->status == 'inactive')
                                                <span class="admin-badge badge-secondary">Inactive</span>
                                            @else
                                                <span class="admin-badge badge-danger">Banned</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if(!$user->isAdmin())
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a href="{{ route('admin.users.show', $user) }}" class="dropdown-item">
                                                            <i class="fas fa-eye"></i> Review
                                                        </a>
                                                    </li>
                                                    
                                                    @if($user->isEmployer() && $user->employerProfile && $user->employerProfile->verification_status !== 'verified')
                                                    <li>
                                                        <form action="{{ route('admin.users.approve-employer', $user) }}" method="POST" onsubmit="return confirm('Approve this employer profile?');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle"></i> Approve Employer
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                                    @if($user->isSeeker() && $user->seekerProfile && $user->seekerProfile->verification_status !== 'verified')
                                                    <li>
                                                        <form action="{{ route('admin.users.approve-seeker', $user) }}" method="POST" onsubmit="return confirm('Approve this jobseeker profile?');">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-check-circle"></i> Approve Jobseeker
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                    
                                                    <li><hr class="dropdown-divider"></li>
                                                    
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="fas fa-toggle-on"></i> Activate
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="inactive">
                                                            <button type="submit" class="dropdown-item text-warning">
                                                                <i class="fas fa-toggle-off"></i> Deactivate
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.users.update-status', $user) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="banned">
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-ban"></i> Ban
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No users found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

        <!-- Pagination Info -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="pagination-info">
                    <p class="text-muted mb-0">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="per-page-selector">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="d-inline">
                        @foreach(request()->query() as $key => $value)
                            @if($key !== 'per_page')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50 per page</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100 per page</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="pagination-controls">
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Ads Section -->
<div class="admin-card mt-4">
    <div class="admin-card-header">
        <h5><i class="fas fa-star"></i> Featured Ads Requests</h5>
        <span class="admin-badge badge-warning">{{ \App\Models\JobPosting::where('status', 'featured_pending')->count() }} Pending</span>
    </div>
    <div class="admin-card-body">
        @php
            $featuredAds = \App\Models\JobPosting::where('status', 'featured_pending')
                ->with(['employer.employerProfile', 'category'])
                ->latest()
                ->get();
        @endphp

        @if($featuredAds->count() > 0)
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Employer</th>
                            <th>Job Title</th>
                            <th>Duration</th>
                            <th>Amount</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($featuredAds as $ad)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="admin-avatar-sm me-2">
                                            @if($ad->employer->employerProfile && $ad->employer->employerProfile->company_logo)
                                                <img src="{{ Storage::url($ad->employer->employerProfile->company_logo) }}" alt="Company Logo">
                                            @else
                                                <div class="default-avatar-sm">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $ad->employer->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ad->employer->employerProfile->company_name ?? 'No Company' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $ad->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $ad->category->name ?? 'No Category' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $ad->featured_duration ?? 7 }} days</span>
                                </td>
                                <td>
                                    <strong class="text-success">AED {{ $ad->featured_amount ?? 49 }}</strong>
                                </td>
                                <td>
                                    <small>{{ $ad->created_at->format('M d, Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#emailModal{{ $ad->id }}">
                                            <i class="fas fa-envelope"></i> Send Email
                                        </button>
                                        <form action="{{ route('admin.jobs.approve', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve this featured ad?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.jobs.reject', $ad) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this featured ad?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-star fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Featured Ads Requests</h5>
                <p class="text-muted">No pending featured ad requests at the moment.</p>
            </div>
        @endif
    </div>
</div>

<!-- Email Modals -->
@foreach($featuredAds as $ad)
<div class="modal fade" id="emailModal{{ $ad->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i> Send Payment Link to {{ $ad->employer->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.featured-ads.send-email', $ad) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email_to{{ $ad->id }}" class="form-label">To</label>
                        <input type="email" class="form-control" id="email_to{{ $ad->id }}" name="email_to" 
                               value="{{ $ad->employer->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email_subject{{ $ad->id }}" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="email_subject{{ $ad->id }}" name="email_subject" 
                               value="Featured Ad Payment Link - {{ $ad->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_link{{ $ad->id }}" class="form-label">Payment Link</label>
                        <input type="url" class="form-control" id="payment_link{{ $ad->id }}" name="payment_link" 
                               placeholder="https://example.com/payment/..." required>
                    </div>
                    <div class="mb-3">
                        <label for="email_message{{ $ad->id }}" class="form-label">Message</label>
                        <textarea class="form-control" id="email_message{{ $ad->id }}" name="email_message" rows="6" required
                                  placeholder="Enter your message to the employer...">Dear {{ $ad->employer->name }},

Thank you for your featured ad request.

Job Title: {{ $ad->title }}
Duration: {{ $ad->featured_duration ?? 7 }} days
Amount: AED {{ $ad->featured_amount ?? 49 }}

Please complete your payment using the link below to activate your featured ad.

Payment Link: [PAYMENT_LINK]

Once payment is confirmed, your job will be published and featured for the selected duration.

Best regards,
FullTimez Team</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection


