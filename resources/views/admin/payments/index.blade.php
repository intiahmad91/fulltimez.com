@extends('layouts.admin')

@section('title', 'Payment Verifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Verifications</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['total'] }}</h4>
                                            <p class="mb-0">Total Payments</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-credit-card fa-2x text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['pending'] }}</h4>
                                            <p class="mb-0">Pending</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['verified'] }}</h4>
                                            <p class="mb-0">Verified</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-dark">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['rejected'] }}</h4>
                                            <p class="mb-0">Rejected</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x text-dark"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.payments.index') }}" 
                                   class="btn {{ request('status') == '' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    All ({{ $stats['total'] }})
                                </a>
                                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" 
                                   class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Pending ({{ $stats['pending'] }})
                                </a>
                                <a href="{{ route('admin.payments.index', ['status' => 'verified']) }}" 
                                   class="btn {{ request('status') == 'verified' ? 'btn-success' : 'btn-outline-success' }}">
                                    Verified ({{ $stats['verified'] }})
                                </a>
                                <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" 
                                   class="btn {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    Rejected ({{ $stats['rejected'] }})
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Information -->
                    <div class="alert alert-info">
                        <strong>Debug Info:</strong>
                        Total Payments: {{ $stats['total'] }}, 
                        Pending: {{ $stats['pending'] }}, 
                        Verified: {{ $stats['verified'] }}, 
                        Rejected: {{ $stats['rejected'] }}
                        <br>
                        Payments in Query: {{ $payments->count() }}
                        @if($payments->count() > 0)
                            <br>
                            Latest Payment: ID {{ $payments->first()->id }}, 
                            Employer: {{ $payments->first()->employer->name ?? 'Unknown' }}, 
                            Package Type: {{ $payments->first()->package_type ?? 'No Type' }}, 
                            Status: {{ $payments->first()->status }}, 
                            Created: {{ $payments->first()->created_at->format('Y-m-d H:i:s') }}
                        @endif
                    </div>

                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employer</th>
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
                                                <div>
                                                    <strong>{{ $payment->employer->name ?? 'Unknown Employer' }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $payment->employer->employerProfile->company_name ?? 'No company name' }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">{{ $payment->employer->email ?? 'No email' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $payment->package_display_name ?? 'No Package' }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $payment->package_type ?? 'N/A' }}</small>
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
                                                        {{ $payment->verified_at->format('M j, Y') }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment->job)
                                                    <a href="{{ route('jobs.show', $payment->job->slug) }}" class="text-decoration-none">
                                                        {{ $payment->job->title }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No Job Linked</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $payment->created_at->format('M j, Y') }}
                                                <br>
                                                <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.payments.show', $payment) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($payment->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                                onclick="verifyPayment({{ $payment->id }}, 'verified')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                onclick="verifyPayment({{ $payment->id }}, 'rejected')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                    <form action="{{ route('admin.payments.destroy', $payment) }}" 
                                                          method="POST" class="d-inline" 
                                                          onsubmit="return confirm('Are you sure you want to delete this payment verification?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                        <div class="pagination-wrapper mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="pagination-info">
                                    <span class="text-muted">
                                        Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} payments
                                    </span>
                                </div>
                                <div class="pagination-controls">
                                    {{ $payments->appends(request()->query())->links() }}
                                </div>
                            </div>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <h5>No Payment Verifications</h5>
                            <p class="text-muted">No payment verification requests found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verify Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verificationForm" method="POST">
                @csrf
                <div class="modal-body">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Verification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verifyPayment(paymentId, status) {
    const form = document.getElementById('verificationForm');
    const statusSelect = document.getElementById('status');
    const modal = new bootstrap.Modal(document.getElementById('verificationModal'));
    
    form.action = `/admin/payments/${paymentId}/verify`;
    statusSelect.value = status;
    
    modal.show();
}
</script>
@endsection
