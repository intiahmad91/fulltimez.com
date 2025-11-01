@extends('layouts.app')

@section('title', 'Browse Jobs')

@push('styles')
<style>
/* Ultra Modern Jobs Listing Page Design */
.jobs-listing-wrapper {
    background: #f0f4f8;
    padding: 40px 0 60px;
    min-height: 100vh;
}

.page-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 60px 0;
    margin-bottom: 40px;
    border-radius: 0 0 40px 40px;
    box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
}

.page-hero-content {
    text-align: center;
    color: #ffffff;
}

.page-hero-content h1 {
    font-size: 48px;
    font-weight: 800;
    margin: 0 0 16px 0;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: -1px;
}

.page-hero-content p {
    font-size: 18px;
    margin: 0;
    opacity: 0.95;
}

.filters-sidebar {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 28px;
    border: none;
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

.filters-sidebar::-webkit-scrollbar {
    width: 6px;
}

.filters-sidebar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.filters-sidebar::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.filters-sidebar::-webkit-scrollbar-thumb:hover {
    background: #5568d3;
}

.filters-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 3px solid #f3f4f6;
}

.filters-header i {
    font-size: 24px;
    color: #667eea;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4ff;
    border-radius: 12px;
}

.filters-header h3 {
    font-size: 22px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.filters-sidebar .input-group {
    margin-bottom: 24px;
}

.filters-sidebar label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filters-sidebar label i {
    color: #667eea;
    font-size: 14px;
}

.filters-sidebar .form-control,
.filters-sidebar select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    color: #111827;
    background: #ffffff;
    transition: all 0.3s ease;
}

.filters-sidebar .form-control:focus,
.filters-sidebar select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.apply_btn {
    width: 100%;
    padding: 16px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 12px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.apply_btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.apply_btn:active {
    transform: translateY(0);
}

.jobs-listing-content {
    padding-left: 32px;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding-bottom: 16px;
    border-bottom: 3px solid #e5e7eb;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
    font-weight: 800;
    color: #111827;
    margin: 0;
}

.section-title i {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    border-radius: 12px;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    margin-bottom: 40px;
}

.jobs-ad-card {
    background: #ffffff;
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.jobs-ad-card:hover {
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
    transform: translateY(-8px);
    border-color: transparent;
}

.job-card-header {
    padding: 24px 24px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.job-card-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 3s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

.category-job {
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
    z-index: 1;
}

.job-icons {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.job-icons img {
    width: 32px;
    height: 32px;
    filter: brightness(0) invert(1);
}

.categery-name h3 {
    font-size: 18px;
    font-weight: 800;
    color: #ffffff;
    margin: 0;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    letter-spacing: -0.3px;
}

.boosted-popular-premium {
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    padding: 6px 14px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border: 2px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    z-index: 2;
}

.catebox {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.add-title {
    margin-bottom: 16px;
}

.add-title a {
    font-size: 20px;
    font-weight: 800;
    color: #111827;
    text-decoration: none;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.3s ease;
    letter-spacing: -0.3px;
}

.add-title a:hover {
    color: #667eea;
}

.owner-ad-list {
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.company-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 800;
    color: #ffffff;
    text-transform: uppercase;
    flex-shrink: 0;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.full-name-list {
    font-size: 15px;
    font-weight: 700;
    color: #667eea;
}

.carinfo {
    list-style: none;
    padding: 0;
    margin: 0 0 16px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.carinfo li {
    font-size: 12px;
    color: #6b7280;
    background: #f9fafb;
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-weight: 600;
    transition: all 0.2s ease;
}

.carinfo li:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
    transform: translateY(-1px);
}

.carinfo li span {
    font-weight: 700;
    color: #374151;
    margin-left: 6px;
}

.cartbx {
    margin-bottom: 16px;
}

.cartbx a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #6b7280;
    font-size: 14px;
    text-decoration: none;
    font-weight: 600;
}

.cartbx img {
    width: 18px;
    height: 18px;
    opacity: 0.8;
}

.price-ad {
    margin-top: auto;
    padding-top: 18px;
    border-top: 2px solid #f3f4f6;
}

.price-ad p {
    font-size: 18px;
    font-weight: 800;
    color: #059669;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.price-ad p .price-amount {
    font-size: 18px;
    color: #059669;
    white-space: nowrap;
}

.price-ad p .price-period {
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    white-space: nowrap;
}

.pagination-wrapper {
    margin-top: 48px;
    display: flex;
    justify-content: center;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-state i {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 24px;
}

.empty-state h3 {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 12px 0;
}

.empty-state p {
    font-size: 16px;
    color: #6b7280;
    margin: 0;
}

@media (max-width: 1200px) {
    .jobs-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .jobs-listing-wrapper {
        padding: 20px 0 40px;
    }
    
    .page-hero {
        padding: 40px 0;
        border-radius: 0 0 30px 30px;
    }
    
    .page-hero-content h1 {
        font-size: 32px;
    }
    
    .page-hero-content p {
        font-size: 16px;
    }
    
    .filters-sidebar {
        position: static;
        margin-bottom: 32px;
        max-height: none;
        border-radius: 16px;
    }
    
    .filters-header h3 {
        font-size: 20px;
    }
    
    .jobs-listing-content {
        padding-left: 0;
    }
    
    .section-title {
        font-size: 24px;
    }
    
    .section-title i {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .job-card-header {
        padding: 20px 20px 16px;
    }
    
    .catebox {
        padding: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="jobs-listing-wrapper">
    <!-- Hero Section -->
    <div class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <h1><i class="fas fa-briefcase"></i> Discover Your Dream Job</h1>
                <p>Find the perfect opportunity from thousands of listings</p>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <div class="filters-header">
                        <i class="fas fa-sliders-h"></i>
                        <h3>Filters</h3>
                    </div>
                    
                    <form action="{{ route('jobs.index') }}" method="GET">
                        <div class="input-group">
                            <label><i class="fas fa-briefcase"></i> Type</label>
                            <select class="form-control" name="type">
                                <option value="">All Types</option>
                                <option value="jobs" {{ request('type') == 'jobs' ? 'selected' : '' }}>Job Opportunities</option>
                                <option value="seekers" {{ request('type') == 'seekers' ? 'selected' : '' }}>Job Seekers</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-search"></i> Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search jobs...">
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-tag"></i> Category</label>
                            <select class="form-control" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-graduation-cap"></i> Education</label>
                            <select class="form-control" name="education">
                                <option value="">All Education Levels</option>
                                <option value="high-school" {{ request('education') == 'high-school' ? 'selected' : '' }}>High-School / Secondary</option>
                                <option value="bachelors" {{ request('education') == 'bachelors' ? 'selected' : '' }}>Bachelors Degree</option>
                                <option value="masters" {{ request('education') == 'masters' ? 'selected' : '' }}>Masters Degree</option>
                                <option value="phd" {{ request('education') == 'phd' ? 'selected' : '' }}>PhD</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-dollar-sign"></i> Desired Salary</label>
                            <select class="form-control" name="salary">
                                <option value="">All Salary Ranges</option>
                                <option value="0-1999" {{ request('salary') == '0-1999' ? 'selected' : '' }}>0 - 1,999</option>
                                <option value="2000-3999" {{ request('salary') == '2000-3999' ? 'selected' : '' }}>2,000 - 3,999</option>
                                <option value="4000-5999" {{ request('salary') == '4000-5999' ? 'selected' : '' }}>4,000 - 5,999</option>
                                <option value="6000-9999" {{ request('salary') == '6000-9999' ? 'selected' : '' }}>6,000 - 9,999</option>
                                <option value="10000-14999" {{ request('salary') == '10000-14999' ? 'selected' : '' }}>10,000 - 14,999</option>
                                <option value="15000+" {{ request('salary') == '15000+' ? 'selected' : '' }}>15,000+</option>
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-map-marker-alt"></i> Current Location</label>
                            <select class="form-control" name="location">
                                <option value="">All Locations</option>
                                @if(isset($cities) && $cities->count() > 0)
                                    @foreach($cities as $city)
                                        <option value="{{ $city->name }}" {{ request('location') == $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                @else
                                    <option value="Dubai" {{ request('location') == 'Dubai' ? 'selected' : '' }}>Dubai</option>
                                    <option value="Abu Dhabi" {{ request('location') == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
                                    <option value="Sharjah" {{ request('location') == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
                                    <option value="Ajman" {{ request('location') == 'Ajman' ? 'selected' : '' }}>Ajman</option>
                                    <option value="RAK" {{ request('location') == 'RAK' ? 'selected' : '' }}>Ras Al Khaimah</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="input-group">
                            <label><i class="fas fa-globe"></i> Nationality</label>
                            <select class="form-control" name="nationality">
                                <option value="">All Nationalities</option>
                                <option value="UAE" {{ request('nationality') == 'UAE' ? 'selected' : '' }}>UAE</option>
                                <option value="India" {{ request('nationality') == 'India' ? 'selected' : '' }}>India</option>
                                <option value="Pakistan" {{ request('nationality') == 'Pakistan' ? 'selected' : '' }}>Pakistan</option>
                                <option value="Egypt" {{ request('nationality') == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                <option value="USA" {{ request('nationality') == 'USA' ? 'selected' : '' }}>USA</option>
                                <option value="UK" {{ request('nationality') == 'UK' ? 'selected' : '' }}>UK</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="apply_btn">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Jobs Content -->
            <div class="col-lg-9">
                <div class="jobs-listing-content">
                    @if(isset($featuredJobs) && $featuredJobs->count() > 0)
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-star"></i>
                            <span>Featured Jobs</span>
                        </h2>
                    </div>
                    <div class="jobs-grid">
                        @foreach($featuredJobs as $job)
                        <div class="featured-job-card">
                            <div class="jobs-ad-card">
                                <div class="job-card-header">
                                    <div class="category-job d-flex align-items-center">
                                        <div class="job-icons">
                                            <img src="{{ asset('images/job.svg') }}" alt="job-ico" class="img-fluid">
                                        </div>
                                        <div class="categery-name">
                                            <h3>{{ optional($job->category)->name ?? 'N/A' }}</h3>
                                        </div>
                                    </div>
                                    <span class="boosted-popular-premium">Featured</span>
                                </div>
                                <div class="catebox">
                                    <h3 class="add-title">
                                        <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                    </h3>
                                    <div class="owner-ad-list d-flex align-items-center">
                                        <div class="company-avatar">
                                            {{ strtoupper(substr($job->employer->employerProfile->company_name ?? 'Company', 0, 1)) }}
                                        </div>
                                        <span class="full-name-list">{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</span>
                                    </div>
                                    <ul class="carinfo ad-features-parent">
                                        <li>Type <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span></li>
                                        <li>Experience <span>{{ $job->experience_years ?? 'N/A' }}</span></li>
                                    </ul>
                                    <div class="cartbx d-flex">
                                        <a href="#"><img src="{{ asset('images/location.svg') }}" alt="logo"> {{ $job->location_city }}</a>
                                    </div>
                                    <div class="price-ad">
                                        <p>
                                            @if(!empty($job->salary_min) && !empty($job->salary_max))
                                                <span class="price-amount">{{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }}</span>
                                                <span class="price-period">/ {{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                                            @else
                                                <span style="color: #059669;">Negotiable</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-briefcase"></i>
                            <span>Recommended Jobs</span>
                        </h2>
                    </div>
                    
                    @if($recommendedJobs->count() > 0)
                    <div class="jobs-grid">
                        @foreach($recommendedJobs as $job)
                        <div class="recommended-job-card">
                            <div class="jobs-ad-card">
                                <div class="job-card-header">
                                    <div class="category-job d-flex align-items-center">
                                        <div class="job-icons">
                                            <img src="{{ asset('images/job.svg') }}" alt="job-ico" class="img-fluid">
                                        </div>
                                        <div class="categery-name">
                                            <h3>{{ optional($job->category)->name ?? 'N/A' }}</h3>
                                        </div>
                                    </div>
                                    <span class="boosted-popular-premium">{{ $job->isFeatured() ? 'Featured' : ucfirst($job->priority ?? 'Normal') }}</span>
                                </div>
                                <div class="catebox">
                                    <h3 class="add-title">
                                        <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                    </h3>
                                    <div class="owner-ad-list d-flex align-items-center">
                                        <div class="company-avatar">
                                            {{ strtoupper(substr($job->employer->employerProfile->company_name ?? 'Company', 0, 1)) }}
                                        </div>
                                        <span class="full-name-list">{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</span>
                                    </div>
                                    <ul class="carinfo ad-features-parent">
                                        <li>Type <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span></li>
                                        <li>Experience <span>{{ $job->experience_years ?? 'N/A' }}</span></li>
                                    </ul>
                                    <div class="cartbx d-flex">
                                        <a href="#"><img src="{{ asset('images/location.svg') }}" alt="logo"> {{ $job->location_city }}</a>
                                    </div>
                                    <div class="price-ad">
                                        <p>
                                            @if(!empty($job->salary_min) && !empty($job->salary_max))
                                                <span class="price-amount">{{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }}</span>
                                                <span class="price-period">/ {{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                                            @else
                                                <span style="color: #059669;">Negotiable</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($recommendedJobs->hasPages())
                    <div class="pagination-wrapper">
                        {!! $recommendedJobs->links() !!}
                    </div>
                    @endif
                    @else
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h3>No jobs found</h3>
                        <p>Try adjusting your filters to see more results</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
