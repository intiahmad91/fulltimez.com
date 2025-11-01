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

/* Professional Job Card Design - Clean & Modern */
.jobs_list {
    display: flex !important;
}

.jobs_list .item {
    display: flex !important;
    height: 100% !important;
}

.add-exp {
    display: flex !important;
    flex: 1 !important;
    width: 100% !important;
    min-height: 100% !important;
    padding: 0 !important;
    height: 100% !important;
}

.jobs-ad-card {
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
    overflow: hidden !important;
    width: 100% !important;
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
}

.jobs-ad-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
    transform: translateY(-2px) !important;
    border-color: #d1d5db !important;
}

.job-card-header {
    padding: 24px 24px 20px !important;
    background: #ffffff !important;
    border-bottom: 1px solid #f3f4f6 !important;
}

.header-top {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    margin-bottom: 16px !important;
}

.category-badge-top {
    background: #f3f4f6 !important;
    color: #374151 !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    padding: 5px 12px !important;
    border-radius: 6px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.3px !important;
    border: none !important;
}

.company-header {
    display: flex !important;
    align-items: center !important;
    gap: 14px !important;
    width: 100% !important;
}

.company-logo {
    width: 56px !important;
    height: 56px !important;
    border-radius: 10px !important;
    background: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
}

.company-logo img {
    width: 32px !important;
    height: 32px !important;
    object-fit: cover !important;
}

.company-name {
    flex: 1 !important;
    min-width: 0 !important;
}

.company-name h3 {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    word-wrap: break-word !important;
}

.job-card-body {
    padding: 0 24px 20px !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    background: #ffffff !important;
}

.job-title {
    margin-bottom: 16px !important;
}

.job-title a {
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    text-decoration: none !important;
    line-height: 1.5 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    transition: color 0.2s ease !important;
}

.job-title a:hover {
    color: #2563eb !important;
}

.job-meta {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 8px !important;
    margin-bottom: 16px !important;
}

.meta-badge {
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
    padding: 6px 12px !important;
    background: #f9fafb !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 6px !important;
    font-size: 12px !important;
    color: #6b7280 !important;
    transition: all 0.2s ease !important;
}

.meta-badge:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
}

.meta-badge span {
    font-weight: 600 !important;
    color: #374151 !important;
}

.location-info {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    color: #6b7280 !important;
    font-size: 13px !important;
    margin-bottom: 16px !important;
}

.location-info img {
    width: 16px !important;
    height: 16px !important;
    opacity: 0.6 !important;
}

.job-card-footer {
    padding: 16px 24px 24px !important;
    border-top: 1px solid #f3f4f6 !important;
    margin-top: auto !important;
    background: #ffffff !important;
    flex-shrink: 0 !important;
}

.price-ad {
    text-align: left !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
}

.price-ad p {
    margin: 0 !important;
    padding: 0 !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #059669 !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    white-space: normal !important;
    line-height: 1.4 !important;
    max-width: 100% !important;
    display: block !important;
}

.price-ad p span {
    font-size: 13px !important;
    font-weight: 400 !important;
    color: #9ca3af !important;
    margin-left: 4px !important;
}

@media (max-width: 768px) {
    .header-top {
        margin-bottom: 14px !important;
    }
    
    .category-badge-top {
        font-size: 10px !important;
        padding: 4px 10px !important;
    }
    
    .company-header {
        flex-direction: column !important;
        text-align: center !important;
        gap: 12px !important;
    }
    
    .company-logo {
        margin: 0 auto !important;
        width: 52px !important;
        height: 52px !important;
    }
    
    .company-name h3 {
        text-align: center !important;
        font-size: 15px !important;
    }
    
    .job-card-body {
        padding: 0 20px 18px !important;
    }
    
    .job-title a {
        text-align: center !important;
        font-size: 17px !important;
    }
    
    .job-meta {
        justify-content: center !important;
    }
    
    .location-info {
        justify-content: center !important;
    }
    
    .job-card-footer {
        padding: 14px 20px 20px !important;
    }
    
    .price-ad {
        text-align: center !important;
    }
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
        display: flex !important;
        float: none !important;
        clear: both !important;
        margin-bottom: 20px !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        height: auto !important;
    }
    
    .add-exp {
        width: 100% !important;
        height: 100% !important;
        display: flex !important;
        flex: 1 !important;
    }
    
    .jobs-ad-card {
        height: 100% !important;
        min-height: 100% !important;
    }
    
    .jobs-ad-card {
        margin-bottom: 20px !important;
        border-radius: 14px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }
    
    .job-card-header {
        padding: 20px 18px 18px !important;
    }
    
    .header-top {
        margin-bottom: 14px !important;
    }
    
    .company-header {
        flex-direction: column !important;
        text-align: center !important;
        gap: 12px !important;
    }
    
    .company-logo {
        width: 52px !important;
        height: 52px !important;
        margin: 0 auto !important;
    }
    
    .company-name h3 {
        font-size: 15px !important;
        text-align: center !important;
    }
    
    .category-badge-top {
        font-size: 10px !important;
        padding: 4px 10px !important;
    }
    
    .job-card-body {
        padding: 0 18px 18px !important;
    }
    
    .job-title a {
        font-size: 17px !important;
        text-align: center !important;
    }
    
    .job-meta {
        justify-content: center !important;
        gap: 8px !important;
    }
    
    .meta-badge {
        font-size: 11px !important;
        padding: 5px 10px !important;
    }
    
    .location-info {
        justify-content: center !important;
        font-size: 12px !important;
    }
    
    .job-card-footer {
        padding: 14px 18px 18px !important;
    }
    
    .price-ad {
        text-align: center !important;
    }
    
    .price-ad p {
        font-size: 15px !important;
    }
}

@media (max-width: 480px) {
    .main_title {
        font-size: 24px !important;
    }
    
    .jobs-ad-card {
        border-radius: 12px !important;
    }
    
    .job-card-header {
        padding: 18px 16px 16px !important;
    }
    
    .header-top {
        margin-bottom: 12px !important;
    }
    
    .job-card-body {
        padding: 0 16px 16px !important;
    }
    
    .job-card-footer {
        padding: 12px 16px 16px !important;
    }
    
    .company-logo {
        width: 48px !important;
        height: 48px !important;
    }
    
    .company-name h3 {
        font-size: 14px !important;
    }
    
    .category-badge-top {
        font-size: 9px !important;
        padding: 4px 9px !important;
    }
    
    .job-title a {
        font-size: 16px !important;
    }
    
    .meta-badge {
        font-size: 10px !important;
        padding: 5px 9px !important;
    }
    
    .price-ad {
        text-align: center !important;
    }
    
    .price-ad p {
        font-size: 14px !important;
    }
}
</style>
@endpush

@section('content')






   <section class="category-wrap jobwrp popular-items mt-5">
      <div class="container">
         <div class="main_title">Featured Jobs</div>
         <div class="cate_list">
         <ul class="owl-carousel jobs_list">
            @foreach($featuredJobs as $job)
            <li class="item wow fadeInUp">
              <div class="add-exp">
                  <div class="jobs-ad-card">
                     <div class="job-card-header">
                        <div class="header-top">
                           <span class="category-badge-top">{{ optional($job->category)->name ?? 'N/A' }}</span>
                        </div>
                        <div class="company-header">
                           <div class="company-logo">
                              <img src="{{ asset('images/job.svg') }}" alt="company-logo">
                           </div>
                           <div class="company-name">
                              <h3>{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</h3>
                           </div>
                        </div>
                     </div>
                     <div class="job-card-body">
                        <div class="job-title">
                           <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                        </div>
                        <div class="job-meta">
                           <div class="meta-badge">
                              Type: <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                           </div>
                           <div class="meta-badge">
                              Experience: <span>{{ $job->experience_years ?? 'N/A' }}</span>
                           </div>
                        </div>
                        <div class="location-info">
                           <img src="{{ asset('images/location.svg') }}" alt="location">
                           <span>{{ $job->location_city }}</span>
                        </div>
                     </div>
                     <div class="job-card-footer">
                        <div class="price-ad">
                           <p>
                              @if(!empty($job->salary_min) && !empty($job->salary_max))
                                  {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }} <span>/ {{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                              @else
                                  Negotiable
                              @endif
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </li>
            @endforeach

         </ul>
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
                           <img src="{{ $seeker->seekerProfile && $seeker->seekerProfile->profile_picture ? asset($seeker->seekerProfile->profile_picture) : asset('images/avatar2.jpg') }}" alt="job-ico" class="img-fluid" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
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
