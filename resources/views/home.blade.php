@extends('layouts.app')

@section('title', 'FullTimez - Home')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
/* Global Container Fix */
body {
    overflow-x: hidden !important;
}

.container {
    max-width: 100% !important;
    overflow-x: hidden !important;
}

/* Desktop Container Fix */
@media (min-width: 769px) {
    .category-wrap {
        overflow-x: hidden !important;
    }
    
    .jobs_list {
        overflow-x: hidden !important;
    }
    
    .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        overflow-x: hidden !important;
    }
}

/* Featured Jobs Section Styles */
.featured-jobs-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.featured-jobs-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.featured-jobs-container {
    position: relative;
    z-index: 2;
}

.section-header .main_title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 15px;
    position: relative;
}

.section-header .main_title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745);
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 0;
    font-weight: 400;
}

.featured-jobs-slider {
    margin-top: 40px;
    position: relative;
}

.featured-jobs-carousel {
    overflow: hidden;
}

.featured-jobs-carousel .item {
    padding: 0 15px;
}

.featured-jobs-carousel .owl-stage {
    display: flex;
    align-items: stretch;
}

.featured-jobs-carousel .owl-item {
    height: auto;
}

.featured-job-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
    border: 1px solid rgba(0, 123, 255, 0.1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.featured-job-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #007bff, #28a745, #ffc107);
}

.featured-job-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.job-card-header {
    padding: 20px 20px 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 1px solid #e9ecef;
    flex-shrink: 0;
}

.job-category-info {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.category-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    box-shadow: 0 3px 12px rgba(0, 123, 255, 0.3);
}

.category-icon img {
    width: 20px;
    height: 20px;
    filter: brightness(0) invert(1);
}

.category-details .category-label {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-details .category-name {
    font-size: 1rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 2px 0 0;
}

.featured-badge {
    position: absolute;
    top: 15px;
    right: 15px;
}

.badge-text {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    color: #ffffff;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 3px 12px rgba(255, 193, 7, 0.4);
}

.job-card-content {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.job-title {
    margin-bottom: 15px;
}

.job-title a {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    text-decoration: none;
    line-height: 1.3;
    transition: color 0.3s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-title a:hover {
    color: #007bff;
}

.company-info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 8px 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.company-logo {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
    border: 2px solid #e9ecef;
}

.company-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.company-name {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.job-details-compact {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.detail-item-compact {
    background: #f8f9fa;
    padding: 6px 10px;
    border-radius: 6px;
    text-align: center;
    border: 1px solid #e9ecef;
    flex: 1;
    min-width: 60px;
}

.detail-label-compact {
    display: block;
    font-size: 0.7rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 2px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value-compact {
    display: block;
    font-size: 0.8rem;
    color: #2c3e50;
    font-weight: 600;
}

.job-location-compact {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 8px 12px;
    background: #e3f2fd;
    border-radius: 6px;
    border-left: 3px solid #007bff;
}

.job-location-compact img {
    width: 14px;
    height: 14px;
    margin-right: 6px;
    opacity: 0.7;
}

.job-location-compact span {
    color: #495057;
    font-weight: 500;
    font-size: 0.9rem;
}

.job-salary-compact {
    text-align: center;
    margin-bottom: 15px;
    margin-top: auto;
}

.salary-text-compact {
    font-size: 1rem;
    font-weight: 700;
    color: #28a745;
    margin: 0;
    padding: 10px;
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border-radius: 8px;
    border: 1px solid #c3e6cb;
}

.salary-period-compact {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 500;
}

.negotiable-compact {
    color: #ffc107;
    font-weight: 600;
}

.job-card-footer {
    padding: 0 20px 20px;
    flex-shrink: 0;
}

.view-job-btn {
    display: block;
    width: 100%;
    padding: 10px 15px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #ffffff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 3px 12px rgba(0, 123, 255, 0.3);
    font-size: 0.9rem;
}

.view-job-btn:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    color: #ffffff;
    text-decoration: none;
}

/* Featured Jobs Responsive Styles */
@media (max-width: 768px) {
    .featured-jobs-section {
        padding: 40px 0;
    }
    
    .section-header .main_title {
        font-size: 2rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
    
    .featured-jobs-slider {
        margin-top: 30px;
    }
    
    .featured-jobs-carousel .item {
        padding: 0 10px;
    }
    
    .featured-job-card {
        border-radius: 12px;
    }
    
    .job-card-header {
        padding: 15px;
    }
    
    .job-card-content {
        padding: 15px;
    }
    
    .job-card-footer {
        padding: 0 15px 15px;
    }
    
    .category-icon {
        width: 35px;
        height: 35px;
    }
    
    .category-icon img {
        width: 18px;
        height: 18px;
    }
    
    .job-title a {
        font-size: 1rem;
    }
    
    .job-details-compact {
        gap: 6px;
    }
    
    .detail-item-compact {
        padding: 5px 8px;
        min-width: 50px;
    }
    
    .detail-label-compact {
        font-size: 0.65rem;
    }
    
    .detail-value-compact {
        font-size: 0.75rem;
    }
    
    .salary-text-compact {
        font-size: 0.9rem;
        padding: 8px;
    }
    
    .view-job-btn {
        padding: 8px 12px;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .featured-jobs-section {
        padding: 30px 0;
    }
    
    .section-header .main_title {
        font-size: 1.8rem;
    }
    
    .featured-jobs-carousel .item {
        padding: 0 5px;
    }
    
    .job-card-header {
        padding: 12px;
    }
    
    .job-card-content {
        padding: 12px;
    }
    
    .job-card-footer {
        padding: 0 12px 12px;
    }
    
    .category-icon {
        width: 30px;
        height: 30px;
        margin-right: 8px;
    }
    
    .category-icon img {
        width: 16px;
        height: 16px;
    }
    
    .job-title a {
        font-size: 0.95rem;
    }
    
    .company-logo {
        width: 28px;
        height: 28px;
    }
    
    .company-name {
        font-size: 0.8rem;
    }
    
    .detail-item-compact {
        padding: 4px 6px;
        min-width: 45px;
    }
    
    .detail-label-compact {
        font-size: 0.6rem;
    }
    
    .detail-value-compact {
        font-size: 0.7rem;
    }
    
    .job-location-compact {
        padding: 6px 8px;
    }
    
    .job-location-compact img {
        width: 12px;
        height: 12px;
    }
    
    .job-location-compact span {
        font-size: 0.8rem;
    }
    
    .salary-text-compact {
        font-size: 0.85rem;
        padding: 6px;
    }
    
    .view-job-btn {
        padding: 6px 10px;
        font-size: 0.8rem;
    }
}

/* Home Page Responsive Improvements */
@media (max-width: 768px) {
    .main_title {
        font-size: 28px !important;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .jobs_list {
        padding: 0 15px !important;
        display: block !important;
        width: 100% !important;
        overflow-x: hidden !important;
    }
    
    .category-wrap {
        overflow-x: hidden !important;
        padding: 0 15px !important;
    }
    
    .container {
        padding: 0 15px !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    
    .jobs_list .item {
        width: 100% !important;
        display: block !important;
        float: none !important;
        clear: both !important;
        margin-bottom: 20px !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .jobs-ad-card {
        margin-bottom: 20px !important;
        padding: 15px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        background: #fff !important;
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
        float: none !important;
        clear: both !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    
    .category-job {
        flex-direction: column !important;
        text-align: center !important;
        margin-bottom: 15px !important;
        width: 100% !important;
        display: block !important;
        float: none !important;
        clear: both !important;
    }
    
    .job-icons {
        margin-bottom: 10px;
    }
    
    .job-icons img {
        width: 40px;
        height: 40px;
    }
    
    .categery-name h3 {
        font-size: 18px;
        margin: 5px 0;
    }
    
    .add-title {
        font-size: 20px !important;
        line-height: 1.4 !important;
        margin-bottom: 15px !important;
        word-wrap: break-word !important;
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: unset !important;
    }
    
    .add-title a {
        color: #333 !important;
        text-decoration: none !important;
        word-wrap: break-word !important;
        white-space: normal !important;
    }
    
    .add-title a:hover {
        color: #007bff !important;
    }
    
    .meta-ad {
        margin-bottom: 15px !important;
        word-wrap: break-word !important;
        white-space: normal !important;
        overflow: visible !important;
    }
    
    .owner-ad-list {
        justify-content: center;
        margin-bottom: 10px;
    }
    
    .owner-ad-list img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        margin-right: 8px;
    }
    
    .carinfo {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .carinfo li {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .cartbx {
        margin-bottom: 15px;
    }
    
    .cartbx a {
        display: flex;
        align-items: center;
        color: #666;
        text-decoration: none;
    }
    
    .cartbx img {
        width: 16px;
        height: 16px;
        margin-right: 5px;
    }
    
    .price-ad {
        text-align: center;
    }
    
    .price-ad p {
        font-size: 16px;
        font-weight: 600;
        color: #007bff;
    }
}

@media (max-width: 480px) {
    .main_title {
        font-size: 24px !important;
    }
    
    .jobs-ad-card {
        padding: 12px;
    }
    
    .add-title {
        font-size: 18px;
    }
    
    .carinfo li {
        font-size: 12px;
        padding: 4px 8px;
    }
}
</style>
@endpush

@section('content')





   <!-- Featured Jobs Section -->
   <section class="featured-jobs-section mt-5 mb-5">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-12">
               <div class="featured-jobs-container">
                  <div class="section-header text-center mb-4">
                     <h2 class="main_title">Featured Jobs</h2>
                     <p class="section-subtitle">Discover the best opportunities waiting for you</p>
                  </div>
                  
                  <div class="featured-jobs-slider">
                     <div class="owl-carousel featured-jobs-carousel">
                        @foreach($featuredJobs as $job)
                        <div class="item">
                           <div class="featured-job-card wow fadeInUp" data-wow-delay="{{ $loop->index * 0.1 }}s">
                              <div class="job-card-header">
                                 <div class="job-category-info">
                                    <div class="category-icon">
                                       <img src="{{ asset('images/job.svg') }}" alt="job-icon" class="img-fluid">
                                    </div>
                                    <div class="category-details">
                                       <span class="category-label">Category</span>
                                       <h4 class="category-name">{{ optional($job->category)->name ?? 'N/A' }}</h4>
                                    </div>
                                 </div>
                                 <div class="featured-badge">
                                    <span class="badge-text">Featured</span>
                                 </div>
                              </div>
                              
                              <div class="job-card-content">
                                 <h3 class="job-title">
                                    <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                                 </h3>
                                 
                                 <div class="company-info">
                                    <div class="company-logo">
                                       <img src="{{ asset('images/avatar.jpg') }}" alt="company-logo">
                                    </div>
                                    <span class="company-name">{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</span>
                                 </div>
                                 
                                 <div class="job-details-compact">
                                    <div class="detail-item-compact">
                                       <span class="detail-label-compact">Type</span>
                                       <span class="detail-value-compact">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                    </div>
                                    <div class="detail-item-compact">
                                       <span class="detail-label-compact">Exp</span>
                                       <span class="detail-value-compact">{{ $job->experience_years ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item-compact">
                                       <span class="detail-label-compact">Edu</span>
                                       <span class="detail-value-compact">{{ $job->education_level ?? 'N/A' }}</span>
                                    </div>
                                 </div>
                                 
                                 <div class="job-location-compact">
                                    <img src="{{ asset('images/location.svg') }}" alt="location">
                                    <span>{{ $job->location_city }}</span>
                                 </div>
                                 
                                 <div class="job-salary-compact">
                                    <p class="salary-text-compact">
                                       @if(!empty($job->salary_min) && !empty($job->salary_max))
                                          {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }} 
                                          <span class="salary-period-compact">{{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                                       @else
                                          <span class="negotiable-compact">Negotiable</span>
                                       @endif
                                    </p>
                                 </div>
                              </div>
                              
                              <div class="job-card-footer">
                                 <a href="{{ route('jobs.show', $job->slug) }}" class="view-job-btn">View Details</a>
                              </div>
                           </div>
                        </div>
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
 

<div class="container">
      <div class="zoom_wrap text-center">
        <div class="row profile-card align-items-center">
        <div class="zoom"><img src="{{ asset('images/zoomm.jpeg') }}" alt=""></div> 
                <p class="subtext mb-4">With connections, you will get job information, <span>and don't forget to show off your work too.</span></p>
                <div class="readmore"><a href="#">BOOK MEETING WITH
JOBSEEKER</a></div>
            
        </div>
    </div>
</div>



<div class="jobs-wrap">
   <div class="container">
       <div class="title title_center">
         <h1>Recommended Jobs</h1>
      </div>
      <div class="row">
         

         <div class="col-lg-12">
<div class="row">
@foreach($recommendedJobs as $job)
<div class="col-lg-4 col-md-6">
<div class="jobs" style="height: 100%; display: flex; flex-direction: column;">
   <div class="job-content" style="flex: 1;">
      <div class="jobdate">{{ $job->created_at->format('d/m/y') }}</div>
      <p class="m-0">{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</p>
   <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
   <ul class="tags">
              @php
                  $skills = is_array($job->skills_required) ? $job->skills_required : json_decode($job->skills_required, true);
              @endphp
              @if($skills && is_array($skills))
                  @foreach(array_slice($skills, 0, 4) as $skill)
                      <li><a href="#">{{ $skill }}</a></li>
                  @endforeach
              @endif
            </ul>
         </div>

         <div class="d-flex align-items-center justify-content-between">
         <div class="job_price">${{ number_format((float)($job->salary_min ?? 250)) }}/{{ $job->salary_period ?? 'hr' }} <span>{{ $job->location_city }}</span></div>
         <div class="readmore m-0"><a href="{{ route('jobs.show', $job->slug) }}">Details</a></div>
      </div>

</div>
</div>
@endforeach
</div>
</div>


</div>

   </div>
</div>



<div class="container">
        <div class="row profile-card align-items-center">
            <div class="col-md-6">
                <div class="flex-column">
                    <div class="text-center"><img src="{{ asset('images/women2.png') }}" alt=""></div>
                    <div class="profile-info">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <h2 class="profile-name">{{ $featuredJobSeeker->seekerProfile->full_name ?? $featuredJobSeeker->name ?? 'Job Seeker' }}</h2>
                            <button class="open-work-btn">Open to Work</button>
                        </div>
                        <div class="stats d-flex gap-4 mb-3">
                            <span>{{ rand(500, 2000) }}+</span>
                            <span>{{ $featuredJobSeeker->seekerProfile->experience_years ?? rand(1, 5) }}+ years</span>
                        </div>
                        <span class="status-badge">Active</span>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <h1 class="heading-text mb-3">Expand your connections so people know you</h1>
                <p class="subtext mb-4">With connections, you will get job information, and don't forget to show off your work too.</p>
                <a href="{{ route('candidates.index') }}" class="show-featured-btn">Show All Featured</a>
            </div>
        </div>
    </div>




   <section class="category-wrap seekerwrp popular-items mt-5">
      <div class="container">
         <div class="main_title">Job Seekers</div>
         <div class="cate_list">
         <ul class="owl-carousel jobs_list">
            @foreach($jobSeekers as $seeker)
            <li class="item wow fadeInUp">
              <div class="add-exp">
                  <div class="jobs-ad-card ">
                     <div class="category-job d-flex align-items-center">
                        <div class="job-icons">
                           <img src="{{ $seeker->seekerProfile && $seeker->seekerProfile->profile_picture ? Storage::url($seeker->seekerProfile->profile_picture) : asset('images/avatar2.jpg') }}" alt="job-ico" class="img-fluid" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="categery-name"> 
                           <h3>{{ $seeker->seekerProfile->full_name ?? $seeker->name }}
                           </h3>
                        </div>
                     </div> 
                  </div>
                  <div class="catebox">
                     <h3 class="mt-0 add-title"><a href="{{ route('candidates.show', $seeker->id) }}">{{ $seeker->seekerProfile->current_position ?? 'Job Seeker' }}</a></h3>
                      
                     <ul class="carinfo ad-features-parent">
                        <li>Experience  <span>{{ $seeker->seekerProfile->experience_years ?? 'N/A' }}</span></li>
                        <li>Commitment  <span>Full Time</span></li>
                         
                     </ul>
                     <div class="cartbx d-flex"><a href="#"><img src="{{ asset('images/location.svg') }}" alt="logo"> {{ $seeker->seekerProfile->city ?? 'UAE' }}</a>
                     </div>
                     <div class="price-ad">
                        <p>{{ $seeker->seekerProfile->expected_salary ?? 'Negotiable' }}</p>
                     </div>
                  </div>
               </div>
            </li>
            @endforeach

         </ul>
      </div> 
      </div> 
   </section>
 
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Featured Jobs Carousel
    $('.featured-jobs-carousel').owlCarousel({
        loop: true,
        margin: 20,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1,
                margin: 10
            },
            576: {
                items: 2,
                margin: 15
            },
            768: {
                items: 2,
                margin: 20
            },
            992: {
                items: 3,
                margin: 20
            },
            1200: {
                items: 3,
                margin: 20
            }
        },
        navText: [
            '<i class="fa fa-chevron-left"></i>',
            '<i class="fa fa-chevron-right"></i>'
        ],
        navClass: ['owl-prev', 'owl-next']
    });
});
</script>
@endpush
