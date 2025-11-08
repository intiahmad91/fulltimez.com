@extends('layouts.admin')

@section('title', 'Payment Verification Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Payment Verification Details</h4>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Payments
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Employer Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $payment->employer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $payment->employer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company:</strong></td>
                                    <td>{{ $payment->employer->employerProfile->company_name ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $payment->employer->phone ?? 'Not provided' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Information</h6>
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
                    </div>

                    <div class="row mt-4">
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
                        <div class="col-md-6">
                            @if($payment->job)
                                <h6 class="text-muted">Related Job</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="{{ route('jobs.show', $payment->job->slug) }}" class="text-decoration-none">
                                                {{ $payment->job->title }}
                                            </a>
                                        </h6>
                                        <p class="card-text text-muted">{{ Str::limit($payment->job->description, 100) }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> {{ $payment->job->location_city }}, {{ $payment->job->location_country }}
                                            <span class="ms-3">
                                                <i class="fas fa-clock"></i> {{ $payment->job->employment_type }}
                                            </span>
                                        </small>
                                    </div>
                                </div>
                            @else
                                <h6 class="text-muted">Package Type</h6>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    This is a general premium package that will apply to all active jobs by this employer.
                                </div>
                            @endif
                        </div>
                    </div>

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
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($payment->status === 'pending')
                <div class="card">
                    <div class="card-header">
                        <h5>Verify Payment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="verified">Verify Payment</option>
                                    <option value="rejected">Reject Payment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="admin_notes" class="form-label">Admin Notes</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3" 
                                          placeholder="Add any notes about this verification..."></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Verify Payment
                                </button>
                                <button type="button" class="btn btn-danger" onclick="setRejectStatus()">
                                    <i class="fas fa-times"></i> Reject Payment
                                </button>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#emailModal">
                                    <i class="fas fa-envelope"></i> Send Email
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        <h5>Verification Status</h5>
                    </div>
                    <div class="card-body">
                        @if($payment->status === 'verified')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>Payment Verified</strong>
                                <br>
                                <small>This payment has been verified and the premium package has been activated.</small>
                            </div>
                        @elseif($payment->status === 'rejected')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Payment Rejected</strong>
                                <br>
                                <small>This payment has been rejected. The employer has been notified.</small>
                            </div>
                        @endif

                        @if($payment->verifiedBy)
                            <p><strong>Verified by:</strong> {{ $payment->verifiedBy->name }}</p>
                        @endif

                        @if($payment->verified_at)
                            <p><strong>Verified on:</strong> {{ $payment->verified_at->format('M j, Y \a\t g:i A') }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.show', $payment->employer) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> View Employer Profile
                        </a>
                        @if($payment->job)
                            <a href="{{ route('jobs.show', $payment->job->slug) }}" class="btn btn-outline-info">
                                <i class="fas fa-briefcase"></i> View Job Posting
                            </a>
                        @endif
                        <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this payment verification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash"></i> Delete Payment
                            </button>
                        </form>
                    </div>
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

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i> Send Email to Employer
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payments.send-email', $payment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email_to" class="form-label">To</label>
                        <input type="email" class="form-control" id="email_to" name="email_to" 
                               value="{{ $payment->employer->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="email_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="email_subject" name="email_subject" 
                               value="Payment Verification Update - {{ $payment->package_display_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_message" class="form-label">Message</label>
                        <textarea class="form-control" id="email_message" name="email_message" rows="8" required
                                  placeholder="Enter your message to the employer...">Dear {{ $payment->employer->name }},

Thank you for your payment verification submission.

Package: {{ $payment->package_display_name }}
Amount: {{ $payment->currency }} {{ number_format($payment->amount, 2) }}

We will review your payment and get back to you shortly.

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

<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

function setRejectStatus() {
    document.getElementById('status').value = 'rejected';
    document.getElementById('admin_notes').focus();
}
</script>
@endsection
