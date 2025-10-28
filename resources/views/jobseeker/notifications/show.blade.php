@extends('layouts.app')

@section('title', 'Notification Details')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-bell"></i> Notification Details
                            </h4>
                            <a href="{{ route('notifications.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Notifications
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="notification-detail">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-envelope-open text-primary me-3 fa-2x"></i>
                                <div>
                                    <h5 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h5>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                                        ({{ $notification->created_at->diffForHumans() }})
                                    </small>
                                </div>
                            </div>
                            
                            <div class="notification-content">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> Message</h6>
                                    <p class="mb-0">{{ $notification->data['message'] ?? 'No message available' }}</p>
                                </div>
                                
                                @if(isset($notification->data['job_title']) || isset($notification->data['company_name']))
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-briefcase"></i> Job Details</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(isset($notification->data['job_title']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Job Title:</strong></div>
                                                    <div class="col-sm-9">{{ $notification->data['job_title'] }}</div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['company_name']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Company:</strong></div>
                                                    <div class="col-sm-9">{{ $notification->data['company_name'] }}</div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['salary']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Salary:</strong></div>
                                                    <div class="col-sm-9">{{ $notification->data['salary'] }}</div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['start_date']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Start Date:</strong></div>
                                                    <div class="col-sm-9">{{ $notification->data['start_date'] }}</div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['employer_name']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Contact Person:</strong></div>
                                                    <div class="col-sm-9">{{ $notification->data['employer_name'] }}</div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['employer_email']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Email:</strong></div>
                                                    <div class="col-sm-9">
                                                        <a href="mailto:{{ $notification->data['employer_email'] }}" class="text-decoration-none">
                                                            {{ $notification->data['employer_email'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if(isset($notification->data['employer_phone']))
                                                <div class="row mb-2">
                                                    <div class="col-sm-3"><strong>Phone:</strong></div>
                                                    <div class="col-sm-9">
                                                        <a href="tel:{{ $notification->data['employer_phone'] }}" class="text-decoration-none">
                                                            {{ $notification->data['employer_phone'] }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($notification->data['old_status']) && isset($notification->data['new_status']))
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6 class="mb-0"><i class="fas fa-exchange-alt"></i> Status Change</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="text-center">
                                                        <small class="text-muted">Previous Status</small>
                                                        <div class="badge bg-secondary">{{ ucfirst($notification->data['old_status']) }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="text-center">
                                                        <small class="text-muted">New Status</small>
                                                        <div class="badge bg-primary">{{ ucfirst($notification->data['new_status']) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if(isset($notification->data['action_url']))
                                    <div class="d-grid gap-2">
                                        <a href="{{ $notification->data['action_url'] }}" class="btn btn-primary">
                                            <i class="fas fa-external-link-alt me-2"></i>
                                            {{ $notification->data['action_text'] ?? 'View Details' }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                Notification ID: {{ $notification->id }}
                            </small>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteNotification('{{ $notification->id }}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function deleteNotification(notificationId) {
    if (confirm('Delete this notification?')) {
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("notifications.index") }}';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete notification');
        });
    }
}
</script>
@endsection
