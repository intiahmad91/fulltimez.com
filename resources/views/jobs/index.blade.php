@extends('layouts.app')

@section('title', 'Browse Jobs')

@push('styles')
<style>
/* Modern Jobs Listing Page Design */
.jobs-listing-wrapper {
    background: #f9fafb;
    padding: 40px 0;
}

.page-header-card {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 32px;
    margin-bottom: 32px;
    border: 1px solid #e5e7eb;
}

.page-header-card h1 {
    font-size: 36px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    letter-spacing: -0.5px;
}

.filters-sidebar {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    padding: 24px;
    border: 1px solid #e5e7eb;
    position: sticky;
    top: 20px;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
}

.filters-sidebar h3 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 24px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f3f4f6;
}

.filters-sidebar .input-group {
    margin-bottom: 20px;
}

.filters-sidebar label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.filters-sidebar .form-control,
.filters-sidebar select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    background: #ffffff;
    transition: all 0.2s ease;
}

.filters-sidebar .form-control:focus,
.filters-sidebar select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.apply_btn {
    width: 100%;
    padding: 12px 24px;
    background: #667eea;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 8px;
}

.apply_btn:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.jobs-listing-content {
    padding-left: 24px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 24px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #f3f4f6;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

.jobs-grid .jobs-ad-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.jobs-grid .jobs-ad-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
    border-color: #cbd5e1;
}

.jobs-grid .job-card-header {
    padding: 20px 20px 16px;
    background: #667eea;
    position: relative;
}

.jobs-grid .category-job {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 0;
}

.jobs-grid .job-icons {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.jobs-grid .job-icons img {
    width: 32px;
    height: 32px;
    filter: brightness(0) invert(1);
}

.jobs-grid .categery-name h3 {
    font-size: 16px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    line-height: 1.4;
}

.jobs-grid .boosted-popular-premium {
    position: absolute;
    top: 16px;
    right: 16px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.jobs-grid .catebox {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.jobs-grid .add-title {
    margin-bottom: 12px;
}

.jobs-grid .add-title a {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    text-decoration: none;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}

.jobs-grid .add-title a:hover {
    color: #667eea;
}

.jobs-grid .owner-ad-list {
    margin-bottom: 14px;
}

.jobs-grid .owner-ad-list img {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 10px;
}

.jobs-grid .full-name-list {
    font-size: 14px;
    font-weight: 600;
    color: #667eea;
}

.jobs-grid .carinfo {
    list-style: none;
    padding: 0;
    margin: 0 0 14px 0;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.jobs-grid .carinfo li {
    font-size: 12px;
    color: #6b7280;
    background: #f9fafb;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.jobs-grid .carinfo li span {
    font-weight: 700;
    color: #374151;
    margin-left: 4px;
}

.jobs-grid .cartbx {
    margin-bottom: 14px;
}

.jobs-grid .cartbx a {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 13px;
    text-decoration: none;
}

.jobs-grid .cartbx img {
    width: 16px;
    height: 16px;
    opacity: 0.7;
}

.jobs-grid .price-ad {
    margin-top: auto;
    padding-top: 14px;
    border-top: 1px solid #f3f4f6;
}

.jobs-grid .price-ad p {
    font-size: 16px;
    font-weight: 700;
    color: #059669;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: nowrap;
}

.jobs-grid .price-ad p span {
    font-size: 13px;
    font-weight: 500;
    color: #6b7280;
}

.pagination-wrapper {
    margin-top: 32px;
    display: flex;
    justify-content: center;
}

@media (max-width: 1200px) {
    .jobs-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .jobs-listing-wrapper {
        padding: 20px 0;
    }
    
    .page-header-card {
        padding: 24px;
    }
    
    .page-header-card h1 {
        font-size: 28px;
    }
    
    .filters-sidebar {
        position: static;
        margin-bottom: 24px;
        max-height: none;
    }
    
    .jobs-listing-content {
        padding-left: 0;
    }
    
    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .section-title {
        font-size: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="jobs-listing-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header-card">
            <h1>Browse Jobs</h1>
        </div>
        
        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <h3><i class="fas fa-filter"></i> Filters</h3>
                    
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
                    <h2 class="section-title">
                        <i class="fas fa-star"></i> Featured Jobs
                    </h2>
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
                                                <span class="price-negotiable">Negotiable</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <h2 class="section-title">
                        <i class="fas fa-briefcase"></i> Recommended Jobs
                    </h2>
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
                                                <span class="price-amount">{{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_min) }}</span>
                                                <span class="price-period">/ {{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                                            @else
                                                <span class="price-negotiable">Negotiable</span>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
