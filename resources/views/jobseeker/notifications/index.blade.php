@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-bell"></i> Notifications
                            </h4>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light btn-sm" onclick="markAllAsRead()">
                                    <i class="fas fa-check-double"></i> Mark All Read
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm" onclick="clearAllNotifications()">
                                    <i class="fas fa-trash"></i> Clear All
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($notifications->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($notifications as $notification)
                                    <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}" id="notification-{{ $notification->id }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    @if($notification->read_at)
                                                        <i class="fas fa-envelope-open text-muted me-2"></i>
                                                    @else
                                                        <i class="fas fa-envelope text-primary me-2"></i>
                                                    @endif
                                                    
                                                    <h6 class="mb-0 {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                                                        {{ $notification->data['title'] ?? 'Notification' }}
                                                    </h6>
                                                    
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-primary ms-2">New</span>
                                                    @endif
                                                </div>
                                                
                                                <p class="mb-2 {{ $notification->read_at ? 'text-muted' : '' }}">
                                                    {{ $notification->data['message'] ?? 'No message available' }}
                                                </p>
                                                
                                                <div class="d-flex align-items-center text-muted small">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                    
                                                    @if(isset($notification->data['action_url']))
                                                        <span class="mx-2">•</span>
                                                        <a href="{{ $notification->data['action_url'] }}" class="text-decoration-none">
                                                            <i class="fas fa-external-link-alt me-1"></i>
                                                            {{ $notification->data['action_text'] ?? 'View Details' }}
                                                        </a>
                                                    @endif
                                                </div>
                                                
                                                <!-- Additional notification data -->
                                                @if(isset($notification->data['job_title']))
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <strong>Job:</strong> {{ $notification->data['job_title'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($notification->data['company_name']))
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <strong>Company:</strong> {{ $notification->data['company_name'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($notification->data['salary']))
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <strong>Salary:</strong> {{ $notification->data['salary'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                                
                                                @if(isset($notification->data['start_date']))
                                                    <div class="mt-1">
                                                        <small class="text-muted">
                                                            <strong>Start Date:</strong> {{ $notification->data['start_date'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="d-flex flex-column gap-1">
                                                @if(!$notification->read_at)
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="markAsRead('{{ $notification->id }}')" title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteNotification('{{ $notification->id }}')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            <div class="card-footer">
                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Notifications</h5>
                                <p class="text-muted">You don't have any notifications yet. When employers update your application status or send job offers, you'll see them here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.getElementById(`notification-${notificationId}`);
            notificationElement.classList.remove('bg-light');
            notificationElement.classList.add('bg-white');
            
            // Update the envelope icon
            const icon = notificationElement.querySelector('.fa-envelope');
            if (icon) {
                icon.classList.remove('fa-envelope', 'text-primary');
                icon.classList.add('fa-envelope-open', 'text-muted');
            }
            
            // Remove "New" badge
            const badge = notificationElement.querySelector('.badge');
            if (badge) {
                badge.remove();
            }
            
            // Update text colors
            const title = notificationElement.querySelector('h6');
            if (title) {
                title.classList.remove('fw-bold');
                title.classList.add('text-muted');
            }
            
            const message = notificationElement.querySelector('p');
            if (message) {
                message.classList.add('text-muted');
            }
            
            // Remove mark as read button
            const markAsReadBtn = notificationElement.querySelector('button[onclick*="markAsRead"]');
            if (markAsReadBtn) {
                markAsReadBtn.remove();
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark notification as read');
    });
}

function markAllAsRead() {
    if (confirm('Mark all notifications as read?')) {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to mark all notifications as read');
        });
    }
}

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
                document.getElementById(`notification-${notificationId}`).remove();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete notification');
        });
    }
}

function clearAllNotifications() {
    if (confirm('Clear all notifications? This action cannot be undone.')) {
        fetch('/notifications/clear-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to clear all notifications');
        });
    }
}
</script>
@endsection
