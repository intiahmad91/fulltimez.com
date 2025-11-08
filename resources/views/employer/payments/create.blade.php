@extends('layouts.app')

@section('title', 'Submit Payment Verification')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            @include('dashboard.sidebar')
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4>Submit Payment Verification</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('pending_job_data'))
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Premium Job Posting</h6>
                            <p class="mb-0">You are creating a premium job posting for: <strong>{{ session('pending_job_data.title') }}</strong></p>
                        </div>
                    @endif

                    <form action="{{ route('employer.payments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Package Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>Select Premium Package</h5>
                                <div class="row">
                                    @foreach($packages as $package)
                                        <div class="col-md-4 mb-3">
                                            <div class="card package-card {{ $selectedPackage && $selectedPackage->id == $package->id ? 'border-primary' : '' }}" 
style="cursor: pointer; {{ $selectedPackage && $selectedPackage->id == $package->id ? 'border-width: 2px;' : '' }}">
                                                <div class="card-body text-center">
                                                    <h6 class="card-title">{{ $package->name }}</h6>
                                                    <h4 class="text-primary">{{ $package->formatted_price }}</h4>
                                                    <p class="text-muted">{{ $package->duration_text }}</p>
                                                    <ul class="list-unstyled small">
                                                        <li><i class="fas fa-check text-success"></i> Priority listing</li>
                                                        <li><i class="fas fa-check text-success"></i> Featured badge</li>
                                                        <li><i class="fas fa-check text-success"></i> Extended visibility</li>
                                                        <li><i class="fas fa-check text-success"></i> {{ $package->duration_text }} duration</li>
                                                    </ul>
                                                    <input type="radio" name="package_id" value="{{ $package->id }}" 
                                                           {{ $selectedPackage && $selectedPackage->id == $package->id ? 'checked' : '' }} 
                                                           class="package-radio" style="display: none;">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Job Selection (Optional) -->
                        <div class="mb-3">
                            <label for="job_id" class="form-label">Select Job (Optional)</label>
                            <select name="job_id" id="job_id" class="form-select">
                                <option value="">General Premium Package</option>
                                @if($job)
                                    <option value="{{ $job->id }}" selected>{{ $job->title }}</option>
                                @else
                                    @php
                                        $employerJobs = \App\Models\JobPosting::where('employer_id', auth()->id())
                                            ->where('status', 'active')
                                            ->latest()
                                            ->get();
                                    @endphp
                                    @foreach($employerJobs as $employerJob)
                                        <option value="{{ $employerJob->id }}">{{ $employerJob->title }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="form-text">Leave empty for general premium package that applies to all your jobs.</div>
                        </div>

                        <!-- Payment Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount Paid</label>
                                    <div class="input-group">
                                        <input type="number" name="amount" id="amount" class="form-control" 
                                               step="0.01" min="0.01" required>
                                        <select name="currency" class="form-select" style="max-width: 100px;">
                                            <option value="USD">USD</option>
                                            <option value="PKR">PKR</option>
                                            <option value="EUR">EUR</option>
                                            <option value="GBP">GBP</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select">
                                        <option value="">Select Payment Method</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="debit_card">Debit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="easypaisa">EasyPaisa</option>
                                        <option value="jazzcash">JazzCash</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID <span class="text-danger">*</span></label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   placeholder="Enter transaction reference number" required>
                            @error('transaction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Payment Screenshot -->
                        <div class="mb-3">
                            <label for="payment_screenshot" class="form-label">Payment Screenshot *</label>
                            <input type="file" name="payment_screenshot" id="payment_screenshot" 
                                   class="form-control" accept="image/*" required>
                            <div class="form-text">
                                Upload a clear screenshot or photo of your payment confirmation. 
                                Supported formats: JPG, PNG, GIF (Max: 2MB)
                            </div>
                        </div>

                        <!-- Payment Notes -->
                        <div class="mb-3">
                            <label for="payment_notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea name="payment_notes" id="payment_notes" class="form-control" rows="3" 
                                      placeholder="Any additional information about your payment..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employer.payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Payments
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Payment Verification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.package-card {
    transition: all 0.3s ease;
}

.package-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.package-card.border-primary {
    background-color: #f8f9ff;
}

@media (min-width: 992px) {
    .col-lg-3 {
        flex: 0 0 auto;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Package selection
    const packageCards = document.querySelectorAll('.package-card');
    const packageRadios = document.querySelectorAll('.package-radio');
    const amountInput = document.getElementById('amount');

    packageCards.forEach((card, index) => {
        card.addEventListener('click', function() {
            // Remove active class from all cards
            packageCards.forEach(c => c.classList.remove('border-primary'));
            packageCards.forEach(c => c.style.borderWidth = '1px');
            
            // Add active class to clicked card
            card.classList.add('border-primary');
            card.style.borderWidth = '2px';
            
            // Check the corresponding radio button
            packageRadios.forEach(radio => radio.checked = false);
            packageRadios[index].checked = true;
            
            // Update amount based on selected package
            const packageId = packageRadios[index].value;
            const packages = @json($packages);
            const selectedPackage = packages.find(pkg => pkg.id == packageId);
            if (selectedPackage) {
                amountInput.value = selectedPackage.price;
            }
        });
    });

    // Set initial amount if package is pre-selected
    const selectedRadio = document.querySelector('.package-radio:checked');
    if (selectedRadio) {
        const packages = @json($packages);
        const packageId = selectedRadio.value;
        const selectedPackage = packages.find(pkg => pkg.id == packageId);
        if (selectedPackage) {
            amountInput.value = selectedPackage.price;
        }
    }
});
</script>
@endsection
