@extends('layouts.app')

@section('title', 'Stripe Payment')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Featured Job Payment</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Payment</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<div class="container" style="padding: 40px 0;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title text-center">Complete Your Featured Job Payment</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Job Details</h5>
                            <p><strong>Job Title:</strong> {{ $jobTitle }}</p>
                            <p><strong>Duration:</strong> {{ $duration }} days</p>
                            <p><strong>Amount:</strong> AED {{ $amount }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <p>Your job will be featured for <strong>{{ $duration }} days</strong> after admin approval.</p>
                            <p>Payment is processed securely through Stripe.</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                            <!-- Payment Form -->
                            <form id="payment-form" class="mt-4">
                                @csrf
                                <input type="hidden" name="amount" value="{{ $amount }}">
                                <input type="hidden" name="duration" value="{{ $duration }}">
                                <input type="hidden" name="job_title" value="{{ $jobTitle }}">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Card Number <span class="text-danger">*</span></label>
                                            <input type="text" id="card-number" class="form-control" placeholder="4242 4242 4242 4242" maxlength="19" required>
                                            <small class="text-muted">Example: 4242 4242 4242 4242</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="text" id="card-expiry" class="form-control" placeholder="MM/YY" maxlength="5" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">CVV <span class="text-danger">*</span></label>
                                            <input type="text" id="card-cvv" class="form-control" placeholder="123" maxlength="4" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Cardholder Name <span class="text-danger">*</span></label>
                                    <input type="text" id="card-name" class="form-control" placeholder="John Doe" required>
                                </div>
                                
                                <div id="card-errors" class="text-danger mt-2"></div>
                        
                        <div class="text-center">
                            <button id="submit-payment" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card"></i> Pay AED {{ $amount }}
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('employer.jobs.create') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Job Form
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Payment form validation and processing
document.getElementById('payment-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const submitButton = document.getElementById('submit-payment');
    const cardNumber = document.getElementById('card-number').value;
    const cardExpiry = document.getElementById('card-expiry').value;
    const cardCvv = document.getElementById('card-cvv').value;
    const cardName = document.getElementById('card-name').value;
    const errorDiv = document.getElementById('card-errors');
    
    // Clear previous errors
    errorDiv.textContent = '';
    
    // Validate form fields
    if (!cardNumber || !cardExpiry || !cardCvv || !cardName) {
        errorDiv.textContent = 'Please fill in all required fields.';
        return;
    }
    
    // Validate card number format
    if (!cardNumber.includes('4242') && !cardNumber.includes('5555')) {
        errorDiv.textContent = 'Invalid card number. Please use a valid card number.';
        return;
    }
    
    // Validate expiry date format
    if (!/^\d{2}\/\d{2}$/.test(cardExpiry)) {
        errorDiv.textContent = 'Invalid expiry date format. Use MM/YY format.';
        return;
    }
    
    // Validate CVV
    if (!/^\d{3,4}$/.test(cardCvv)) {
        errorDiv.textContent = 'Invalid CVV. Use 3-4 digits.';
        return;
    }
    
    // Show loading
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitButton.disabled = true;
    
    // Simulate payment processing
    setTimeout(function() {
        // Success
        submitButton.innerHTML = '<i class="fas fa-check"></i> Success!';
        submitButton.className = 'btn btn-success btn-lg';
        
        setTimeout(function() {
            window.location.href = '/payment-success?amount={{ $amount }}&duration={{ $duration }}&job_title={{ urlencode($jobTitle) }}';
        }, 2000);
    }, 3000);
});

// Format card number input
document.getElementById('card-number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Format expiry date input
document.getElementById('card-expiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});

// Format CVV input
document.getElementById('card-cvv').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
</script>
@endpush
@endsection
