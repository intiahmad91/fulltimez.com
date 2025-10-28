@extends('layouts.app')

@section('title', 'Payment Verifications')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('dashboard.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Payment Verifications</h4>
                    <a href="{{ route('employer.payments.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Submit Payment
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Job</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <strong>{{ $payment->package_display_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $payment->package_type }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong>
                                                @if($payment->payment_method)
                                                    <br>
                                                    <small class="text-muted">{{ $payment->payment_method }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payment->status_badge }}">
                                                    {{ $payment->status_text }}
                                                </span>
                                                @if($payment->verified_at)
                                                    <br>
                                                    <small class="text-muted">
                                                        Verified: {{ $payment->verified_at->format('M j, Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->job)
                                                    <a href="{{ route('jobs.show', $payment->job->slug) }}" class="text-decoration-none">
                                                        {{ $payment->job->title }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">General Package</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $payment->created_at->format('M j, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('employer.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5>No Payment Verifications</h5>
                            <p class="text-muted">You haven't submitted any payment verification requests yet.</p>
                            <a href="{{ route('employer.payments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Submit Your First Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media (min-width: 992px) {
    .col-lg-3 {
        flex: 0 0 auto;
        width: 100%;
    }
}
</style>
@endsection
