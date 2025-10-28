@extends('layouts.app')

@section('title', 'Notification Details')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-bell"></i> Notification Details</h5>
                        <a href="{{ route('employer.notifications.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-2">{{ data_get($notification->data, 'title', 'Notification') }}</h5>
                        <p class="text-muted mb-2">{{ $notification->created_at->format('F j, Y g:i A') }}</p>
                        <p>{{ data_get($notification->data, 'message') }}</p>

                        @if(data_get($notification->data, 'action_url'))
                            <a href="{{ data_get($notification->data, 'action_url') }}" class="btn btn-primary">
                                {{ data_get($notification->data, 'action_text', 'Open') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
