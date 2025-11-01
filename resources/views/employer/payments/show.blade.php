@extends('layouts.app')

@section('title', 'Payment Verification Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('dashboard.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Payment Verification Details</h4>
                    <a href="{{ route('employer.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Payments
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Package Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Package:</strong></td>
                                    <td>{{ $payment->package_display_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ $payment->payment_method ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Transaction ID:</strong></td>
                                    <td>{{ $payment->transaction_id ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Status Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $payment->status_badge }}">
                                            {{ $payment->status_text }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Submitted:</strong></td>
                                    <td>{{ $payment->created_at->format('M j, Y \a\t g:i A') }}</td>
                                </tr>
                                @if($payment->verified_at)
                                    <tr>
                                        <td><strong>Verified:</strong></td>
                                        <td>{{ $payment->verified_at->format('M j, Y \a\t g:i A') }}</td>
                                    </tr>
                                @endif
                                @if($payment->verifiedBy)
                                    <tr>
                                        <td><strong>Verified By:</strong></td>
                                        <td>{{ $payment->verifiedBy->name }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    @if($payment->job)
                        <div class="mt-4">
                            <h6 class="text-muted">Related Job</h6>
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="{{ route('jobs.show', $payment->job->slug) }}" class="text-decoration-none">
                                            {{ $payment->job->title }}
                                        </a>
                                    </h6>
                                    <p class="card-text text-muted">{{ Str::limit($payment->job->description, 150) }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $payment->job->location_city }}, {{ $payment->job->location_country }}
                                        <span class="ms-3">
                                            <i class="fas fa-clock"></i> {{ $payment->job->employment_type }}
                                        </span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($payment->payment_notes)
                        <div class="mt-4">
                            <h6 class="text-muted">Payment Notes</h6>
                            <div class="alert alert-light">
                                {{ $payment->payment_notes }}
                            </div>
                        </div>
                    @endif

                    @if($payment->admin_notes)
                        <div class="mt-4">
                            <h6 class="text-muted">Admin Notes</h6>
                            <div class="alert alert-info">
                                {{ $payment->admin_notes }}
                            </div>
                        </div>
                    @endif

                    @if($payment->payment_screenshot)
                        <div class="mt-4">
                            <h6 class="text-muted">Payment Screenshot</h6>
                            <div class="text-center">
                                <img src="{{ asset($payment->payment_screenshot) }}" 
                                     alt="Payment Screenshot" 
                                     class="img-fluid rounded shadow" 
                                     style="max-height: 400px; cursor: pointer;"
                                     onclick="openImageModal(this.src)">
                            </div>
                        </div>
                    @endif

                    @if($payment->status === 'verified')
                        <div class="mt-4">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Payment Verified!</strong> Your premium package has been activated successfully.
                                @if($payment->job && $payment->job->isPremiumActive())
                                    <br><small>The job "{{ $payment->job->title }}" now has premium features and extended visibility.</small>
                                @endif
                            </div>
                        </div>
                    @elseif($payment->status === 'rejected')
                        <div class="mt-4">
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Payment Rejected</strong> Please review the admin notes above and resubmit your payment verification if needed.
                            </div>
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-clock"></i>
                                <strong>Pending Verification</strong> Your payment verification is under review. You will be notified once the admin processes your request.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Screenshot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Payment Screenshot" class="img-fluid">
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

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endsection
