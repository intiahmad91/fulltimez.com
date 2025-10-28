<?php

use App\Http\Controllers\Auth\EmployerAuthController;
use App\Http\Controllers\Auth\JobseekerAuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employer\JobPostingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/get-started', function () {
    return view('auth.choose-role');
})->name('choose.role');

Route::get('/jobseeker/login', [JobseekerAuthController::class, 'showLogin'])->name('jobseeker.login');
Route::post('/jobseeker/login', [JobseekerAuthController::class, 'login'])->name('jobseeker.login.post');
Route::get('/jobseeker/register', [JobseekerAuthController::class, 'showRegister'])->name('jobseeker.register');
Route::post('/jobseeker/register', [JobseekerAuthController::class, 'register'])->name('jobseeker.register.post');
Route::get('/jobseeker/forgot-password', [JobseekerAuthController::class, 'showForgotPassword'])->name('jobseeker.forgot-password');
Route::post('/jobseeker/forgot-password', [JobseekerAuthController::class, 'forgotPassword'])->name('jobseeker.forgot-password.post');
Route::get('/jobseeker/reset-password/{token}', [JobseekerAuthController::class, 'showResetPassword'])->name('jobseeker.reset-password');
Route::post('/jobseeker/reset-password', [JobseekerAuthController::class, 'resetPassword'])->name('jobseeker.reset-password.post');

Route::get('/employer/login', [EmployerAuthController::class, 'showLogin'])->name('employer.login');
Route::post('/employer/login', [EmployerAuthController::class, 'login'])->name('employer.login.post');
Route::get('/employer/register', [EmployerAuthController::class, 'showRegister'])->name('employer.register');
Route::post('/employer/register', [EmployerAuthController::class, 'register'])->name('employer.register.post');
Route::get('/employer/forgot-password', [EmployerAuthController::class, 'showForgotPassword'])->name('employer.forgot-password');
Route::post('/employer/forgot-password', [EmployerAuthController::class, 'forgotPassword'])->name('employer.forgot-password.post');
Route::get('/employer/reset-password/{token}', [EmployerAuthController::class, 'showResetPassword'])->name('employer.reset-password');
Route::post('/employer/reset-password', [EmployerAuthController::class, 'resetPassword'])->name('employer.reset-password.post');

Route::get('/admin/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->name('admin.logout');

// API routes for dynamic dropdowns
Route::get('/api/cities/{country}', [App\Http\Controllers\Api\LocationController::class, 'getCitiesByCountry'])->name('api.cities.by-country');

Route::get('/login', [JobseekerAuthController::class, 'showLogin'])->name('login');
Route::post('/logout', function () {
    $user = auth()->user();
    
    // Send logout notification before logout
    if ($user) {
        $user->notify(new \App\Notifications\UserActionNotification(
            'logout_successful',
            'logged out successfully',
            'You have logged out of your FullTimez account.',
            route('home'),
            'Visit Homepage'
        ));
    }
    
    auth()->logout();
    return redirect()->route('home');
})->name('logout');

// Email verification: notice & resend require auth, verify link can be opened from email without login (signed)
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
});

Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/search', [JobController::class, 'search'])->name('jobs.search');
Route::get('/api/job-suggestions', [JobController::class, 'getSuggestions'])->name('jobs.suggestions');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');

Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/candidates/{id}', [CandidateController::class, 'show'])->name('candidates.show');

// Temporary public Stripe test route (remove after testing)
Route::get('/public-stripe-test', function(\Illuminate\Http\Request $request) {
    $amount = $request->get('amount', 149);
    $duration = $request->get('duration', 30);
    $jobTitle = $request->get('job_title', 'Test Job');
    
    return view('employer.payments.stripe', [
        'paymentIntent' => (object)[
            'id' => 'pi_test_123',
            'client_secret' => 'pi_test_123_secret_test',
            'amount' => $amount * 100,
            'currency' => 'aed'
        ],
        'amount' => $amount,
        'duration' => $duration,
        'jobTitle' => $jobTitle
    ]);
});

// Temporary public route that mimics employer route structure
Route::get('/employer/payments/stripe-test', function(\Illuminate\Http\Request $request) {
    $amount = $request->get('amount', 149);
    $duration = $request->get('duration', 30);
    $jobTitle = $request->get('job_title', 'Test Job');
    
    return view('employer.payments.stripe', [
        'paymentIntent' => (object)[
            'id' => 'pi_test_123',
            'client_secret' => 'pi_test_123_secret_test',
            'amount' => $amount * 100,
            'currency' => 'aed'
        ],
        'amount' => $amount,
        'duration' => $duration,
        'jobTitle' => $jobTitle
    ]);
});

// Test route to check if PaymentController works without authentication
Route::get('/test-payment-controller', [App\Http\Controllers\Employer\PaymentController::class, 'stripe']);

// Direct Stripe payment route without authentication (for testing)
Route::get('/stripe-payment', function(\Illuminate\Http\Request $request) {
    $amount = $request->get('amount', 89);
    $duration = $request->get('duration', 15);
    $jobTitle = $request->get('job_title', 'Test Job');
    
    return view('employer.payments.stripe', [
        'paymentIntent' => (object)[
            'id' => 'pi_test_' . time(),
            'client_secret' => 'pi_test_' . time() . '_secret_test',
            'amount' => $amount * 100,
            'currency' => 'aed'
        ],
        'amount' => $amount,
        'duration' => $duration,
        'jobTitle' => $jobTitle
    ]);
});

// Payment success page
Route::get('/payment-success', function(\Illuminate\Http\Request $request) {
    $amount = $request->get('amount', 89);
    $duration = $request->get('duration', 15);
    $jobTitle = $request->get('job_title', 'Test Job');
    
    return '<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                        <h3 class="text-success mb-3">Payment Successful!</h3>
                        <p><strong>Job:</strong> ' . htmlspecialchars($jobTitle) . '</p>
                        <p><strong>Duration:</strong> ' . $duration . ' days</p>
                        <p><strong>Amount:</strong> AED ' . $amount . '</p>
                        <div class="alert alert-info">
                            Your job has been submitted for admin approval.
                        </div>
                        <a href="/" class="btn btn-primary">Go to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
});

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Storage fallback route for missing images
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    // Check if file exists
    if (file_exists($fullPath)) {
        return response()->file($fullPath);
    }
    
    // If file doesn't exist, return default user icon
    $defaultImagePath = public_path('images/profile_img.jpg');
    
    if (file_exists($defaultImagePath)) {
        return response()->file($defaultImagePath);
    }
    
    // If even default image doesn't exist, create a simple SVG
    $svg = '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="50" fill="#e0e0e0"/>
        <circle cx="50" cy="35" r="15" fill="#999"/>
        <path d="M20 80 Q50 60 80 80" stroke="#999" stroke-width="8" fill="none"/>
    </svg>';
    
    return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
})->where('path', '.*');

Route::get('/australia', function () {
    return view('pages.australia');
})->name('pages.australia');

Route::get('/canada', function () {
    return view('pages.canada');
})->name('pages.canada');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [DashboardController::class, 'changePassword'])->name('change.password');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');
    Route::get('/my-applications', [JobApplicationController::class, 'index'])->name('applications.index');
    
    Route::get('/create-cv', [App\Http\Controllers\CVController::class, 'create'])->name('seeker.cv.create');
    Route::post('/create-cv', [App\Http\Controllers\CVController::class, 'store'])->name('seeker.cv.store');
    
    Route::get('/resume/preview', [App\Http\Controllers\ResumeController::class, 'preview'])->name('resume.preview');
    Route::get('/resume/download', [App\Http\Controllers\ResumeController::class, 'download'])->name('resume.download');
    
    // Jobseeker Notifications
    Route::get('/notifications', [App\Http\Controllers\Jobseeker\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [App\Http\Controllers\Jobseeker\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\Jobseeker\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Jobseeker\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\Jobseeker\NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-all', [App\Http\Controllers\Jobseeker\NotificationController::class, 'clearAll'])->name('notifications.clear-all');
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\Jobseeker\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

Route::middleware(['auth', 'verified', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::resource('jobs', JobPostingController::class);
    Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates');
    Route::get('/applications', [App\Http\Controllers\Employer\ApplicationController::class, 'index'])->name('applications');
    Route::put('/applications/{application}/status', [App\Http\Controllers\Employer\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    
    // Payment Verifications
    Route::get('/payments', [App\Http\Controllers\Employer\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [App\Http\Controllers\Employer\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [App\Http\Controllers\Employer\PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [App\Http\Controllers\Employer\PaymentController::class, 'show'])->name('payments.show');
    
    // Stripe Payment Routes
    Route::get('/payments/stripe', [App\Http\Controllers\Employer\PaymentController::class, 'stripe'])->name('payments.stripe');
    Route::get('/payments/stripe/success', [App\Http\Controllers\Employer\PaymentController::class, 'stripeSuccess'])->name('payments.stripe.success');
    Route::get('/payments/stripe/cancel', [App\Http\Controllers\Employer\PaymentController::class, 'stripeCancel'])->name('payments.stripe.cancel');
    
    // Test route for Stripe (temporary)
    Route::get('/test-stripe', function() {
        return 'Stripe test route is working!';
    });
    

    // Employer Notifications
    Route::get('/notifications', [App\Http\Controllers\Employer\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [App\Http\Controllers\Employer\NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Employer\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/clear-all', [App\Http\Controllers\Employer\NotificationController::class, 'clearAll'])->name('notifications.clear-all');

    // Employer Document Verification
    Route::get('/documents', [App\Http\Controllers\Employer\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [App\Http\Controllers\Employer\DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [App\Http\Controllers\Employer\DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [App\Http\Controllers\Employer\DocumentController::class, 'show'])->name('documents.show');
    Route::delete('/documents/{document}', [App\Http\Controllers\Employer\DocumentController::class, 'destroy'])->name('documents.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.update-status');
    Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('/users/{user}/approve-employer', [App\Http\Controllers\Admin\UserController::class, 'approveEmployer'])->name('users.approve-employer');
    Route::post('/users/{user}/approve-seeker', [App\Http\Controllers\Admin\UserController::class, 'approveSeeker'])->name('users.approve-seeker');
    Route::get('/users/{user}/download-cv', [App\Http\Controllers\Admin\UserController::class, 'downloadCv'])->name('users.download-cv');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    // Featured Ads Email Route
    Route::post('/featured-ads/{job}/send-email', [App\Http\Controllers\Admin\UserController::class, 'sendFeaturedAdEmail'])->name('featured-ads.send-email');
    
    Route::get('/jobs', [App\Http\Controllers\Admin\JobController::class, 'index'])->name('jobs.index');
    Route::post('/jobs/{job}/approve', [App\Http\Controllers\Admin\JobController::class, 'approve'])->name('jobs.approve');
    Route::post('/jobs/{job}/reject', [App\Http\Controllers\Admin\JobController::class, 'reject'])->name('jobs.reject');
    Route::put('/jobs/{job}/status', [App\Http\Controllers\Admin\JobController::class, 'updateStatus'])->name('jobs.update-status');
    Route::delete('/jobs/{job}', [App\Http\Controllers\Admin\JobController::class, 'destroy'])->name('jobs.destroy');
    
    Route::get('/applications', [App\Http\Controllers\Admin\ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [App\Http\Controllers\Admin\ApplicationController::class, 'show'])->name('applications.show');
    Route::put('/applications/{application}', [App\Http\Controllers\Admin\ApplicationController::class, 'update'])->name('applications.update');
    Route::put('/applications/{application}/status', [App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::delete('/applications/{application}', [App\Http\Controllers\Admin\ApplicationController::class, 'destroy'])->name('applications.destroy');
    
    // Admin Profile & Settings
    Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [App\Http\Controllers\Admin\AdminController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [App\Http\Controllers\Admin\AdminController::class, 'changePassword'])->name('change-password.post');
    
    // Admin Notifications
    Route::post('/notifications/read-all', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.readAll');

    // Admin Document Verification
    Route::get('/documents', [App\Http\Controllers\Admin\DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [App\Http\Controllers\Admin\DocumentController::class, 'show'])->name('documents.show');
    Route::post('/documents/{document}/approve', [App\Http\Controllers\Admin\DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/reject', [App\Http\Controllers\Admin\DocumentController::class, 'reject'])->name('documents.reject');
    Route::post('/documents/bulk-approve', [App\Http\Controllers\Admin\DocumentController::class, 'bulkApprove'])->name('documents.bulk-approve');
    Route::post('/documents/bulk-approve-by-employer', [App\Http\Controllers\Admin\DocumentController::class, 'bulkApproveByEmployer'])->name('documents.bulk-approve-by-employer');
    Route::get('/api/documents/statistics', [App\Http\Controllers\Admin\DocumentController::class, 'statistics'])->name('documents.statistics');
    
    // Payment Verifications
    Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/send-email', [App\Http\Controllers\Admin\PaymentController::class, 'sendEmail'])->name('payments.send-email');
    Route::delete('/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'destroy'])->name('payments.destroy');
    
    // Admin CRUD for Jobs
    Route::get('/jobs/create', [App\Http\Controllers\Admin\JobController::class, 'create'])->name('jobs.create');
    Route::post('/jobs', [App\Http\Controllers\Admin\JobController::class, 'store'])->name('jobs.store');
    Route::get('/jobs/{job}', [App\Http\Controllers\Admin\JobController::class, 'show'])->name('jobs.show');
    Route::get('/jobs/{job}/edit', [App\Http\Controllers\Admin\JobController::class, 'edit'])->name('jobs.edit');
    Route::put('/jobs/{job}', [App\Http\Controllers\Admin\JobController::class, 'update'])->name('jobs.update');
    
    // Admin CRUD for Dropdowns
    Route::resource('categories', App\Http\Controllers\Admin\JobCategoryController::class);
    Route::resource('countries', App\Http\Controllers\Admin\CountryController::class);
    Route::resource('cities', App\Http\Controllers\Admin\CityController::class);
    
    // Package Management
    Route::resource('packages', App\Http\Controllers\Admin\PackageController::class);
    Route::post('/packages/{package}/toggle-status', [App\Http\Controllers\Admin\PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::post('/packages/{package}/toggle-featured', [App\Http\Controllers\Admin\PackageController::class, 'toggleFeatured'])->name('packages.toggle-featured');
    Route::post('/notifications/read-all', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');
});
