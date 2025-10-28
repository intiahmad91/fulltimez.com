@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="mb-0"><i class="fas fa-bell"></i> Notifications</h3>
                    <div class="d-flex gap-2">
                        <form action="{{ route('employer.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary">Mark all as read</button>
                        </form>
                        <form action="{{ route('employer.notifications.clear-all') }}" method="POST" onsubmit="return confirm('Clear all notifications?');">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Clear all</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        @forelse($notifications as $notification)
                            <a href="{{ route('employer.notifications.show', $notification->id) }}" class="d-block text-decoration-none" style="color: inherit;">
                                <div class="p-3 border-bottom" style="background: {{ $notification->read_at ? '#ffffff' : '#fef3c7' }};">
                                    <div class="d-flex align-items-start">
                                        @if($notification->read_at)
                                            <i class="fas fa-envelope-open me-2 text-muted"></i>
                                        @else
                                            <i class="fas fa-bell me-2 text-primary"></i>
                                        @endif
                                        <div style="flex:1;">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ data_get($notification->data, 'title', 'Notification') }}</strong>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="text-muted">{{ data_get($notification->data, 'message') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                No notifications yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-3">
                    {{ $notifications->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
