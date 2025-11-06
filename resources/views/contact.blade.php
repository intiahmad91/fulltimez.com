@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')
<section class="breadcrumb-section">
    <div class="container-auto">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-12">
                <div class="page-title">
                    <h1>Contact Us</h1>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-12">
                <nav aria-label="breadcrumb" class="theme-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-header text-center mb-5">
            <h2 class="contact-main-title">Get In Touch</h2>
            <p class="contact-subtitle">Have a question or need help? We're here to assist you. Reach out to us and we'll get back to you as soon as possible.</p>
        </div>

        <div class="row g-4">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-card">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user"></i> Your Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter your full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required placeholder="your.email@example.com">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="fas fa-phone"></i> Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="+971 50 123 4567">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject" class="form-label">
                                        <i class="fas fa-tag"></i> Subject <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" required placeholder="What is this regarding?">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="message" class="form-label">
                                        <i class="fas fa-comment"></i> Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="message" id="message" class="form-control" rows="6" required placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn-submit-contact">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-5">
                <div class="contact-info-card">
                    <h3 class="contact-info-title">Contact Information</h3>
                    <p class="contact-info-subtitle">Feel free to reach out to us through any of the following channels</p>

                    <div class="contact-info-list">
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h5>Address</h5>
                                <p>123 Business Street<br>Dubai, UAE</p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h5>Phone</h5>
                                <p><a href="tel:+971501234567">+971 50 123 4567</a></p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h5>Email</h5>
                                <p><a href="mailto:info@fulltimez.com">info@fulltimez.com</a></p>
                            </div>
                        </div>

                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h5>Working Hours</h5>
                                <p>
                                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                                    Saturday: 10:00 AM - 4:00 PM<br>
                                    Sunday: Closed
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links (Optional) -->
                    <div class="social-links mt-4">
                        <h5 class="mb-3">Follow Us</h5>
                        <div class="social-icons">
                            <a href="#" class="social-icon" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-icon" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="social-icon" title="Instagram"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.contact-section {
    padding: 60px 0;
    background: #f8f9fa;
}

.contact-header {
    margin-bottom: 50px;
}

.contact-main-title {
    font-size: 42px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 16px;
}

.contact-subtitle {
    font-size: 18px;
    color: #718096;
    max-width: 700px;
    margin: 0 auto;
}

/* Contact Form Card */
.contact-form-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.contact-form .form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.contact-form .form-label i {
    color: #6c6c6c;
    font-size: 14px;
}

.contact-form .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #ffffff;
}

.contact-form .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    outline: none;
}

.contact-form textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.btn-submit-contact {
    background: #0061da;
    color: #ffffff;
    border: none;
    padding: 14px 40px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-submit-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

.btn-submit-contact:active {
    transform: translateY(0);
}

/* Contact Info Card */
.contact-info-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    height: 100%;
}

.contact-info-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 12px;
}

.contact-info-subtitle {
    font-size: 15px;
    color: #718096;
    margin-bottom: 32px;
}

.contact-info-list {
    display: flex;
    flex-direction: column;
    gap: 28px;
}

.contact-info-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding-bottom: 28px;
    border-bottom: 1px solid #e9ecef;
}

.contact-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-icon {
    width: 50px;
    height: 50px;
    background:#007bff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.info-icon i {
    color: #ffffff;
    font-size: 20px;
}

.info-content h5 {
    font-size: 16px;
    font-weight: 700;
    color: #2d3748;
    margin: 0 0 8px 0;
}

.info-content p {
    font-size: 15px;
    color: #4a5568;
    margin: 0;
    line-height: 1.6;
}

.info-content a {
    color: #667eea;
    text-decoration: none;
    transition: color 0.3s ease;
}

.info-content a:hover {
    color: #764ba2;
    text-decoration: underline;
}

/* Social Links */
.social-links h5 {
    font-size: 18px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 16px;
}

.social-icons {
    display: flex;
    gap: 12px;
}

.social-icon {
    width: 42px;
    height: 42px;
    background: #242628; 
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: #007bff;
    border-color: #667eea;
    color: #ffffff; 
}

/* Alerts */
.alert {
    border-radius: 10px;
    border: none;
    padding: 16px 20px;
    margin-bottom: 24px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

/* Responsive */
@media (max-width: 992px) {
    .contact-main-title {
        font-size: 36px;
    }
    
    .contact-form-card,
    .contact-info-card {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .contact-section {
        padding: 40px 0;
    }
    
    .contact-main-title {
        font-size: 32px;
    }
    
    .contact-subtitle {
        font-size: 16px;
    }
    
    .contact-form-card,
    .contact-info-card {
        padding: 24px;
    }
    
    .contact-info-item {
        gap: 16px;
    }
    
    .info-icon {
        width: 45px;
        height: 45px;
    }
    
    .info-icon i {
        font-size: 18px;
    }
}
</style>

@endsection
