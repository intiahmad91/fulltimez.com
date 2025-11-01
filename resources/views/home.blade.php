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
    display: flex;
}

.jobs_list .item {
    display: flex;
    height: 100%;
}

.add-exp {
    display: flex;
    flex: 1;
    width: 100%;
    min-height: 100%;
}

.jobs-ad-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.jobs-ad-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
    border-color: #d1d5db;
}

.add-exp {
    padding: 0;
    width: 100%;
    height: 100%;
    display: flex;
    flex: 1;
}

.job-card-header {
    padding: 24px 24px 20px;
    background: #ffffff;
    border-bottom: 1px solid #f3f4f6;
}

.header-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.category-badge-top {
    background: #f3f4f6;
    color: #374151;
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: none;
}

.company-header {
    display: flex;
    align-items: center;
    gap: 14px;
    width: 100%;
}

.company-logo {
    width: 56px;
    height: 56px;
    border-radius: 10px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.company-logo img {
    width: 32px;
    height: 32px;
    object-fit: cover;
}

.company-name {
    flex: 1;
    min-width: 0;
}

.company-name h3 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    line-height: 1.4;
    word-wrap: break-word;
}

.job-card-body {
    padding: 0 24px 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #ffffff;
}

.job-title {
    margin-bottom: 16px;
}

.job-title a {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    text-decoration: none;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}

.job-title a:hover {
    color: #2563eb;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 12px;
    color: #6b7280;
    transition: all 0.2s ease;
}

.meta-badge:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.meta-badge span {
    font-weight: 600;
    color: #374151;
}

.location-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 16px;
}

.location-info img {
    width: 16px;
    height: 16px;
    opacity: 0.6;
}

.job-card-footer {
    padding: 16px 24px 24px;
    border-top: 1px solid #f3f4f6;
    margin-top: auto;
    background: #ffffff;
    flex-shrink: 0;
}

.price-ad {
    text-align: left;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.price-ad p {
    margin: 0;
    padding: 0;
    font-size: 16px;
    font-weight: 600;
    color: #059669;
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
    line-height: 1.4;
    max-width: 100%;
    display: block;
}

.price-ad p span {
    font-size: 13px;
    font-weight: 400;
    color: #9ca3af;
    margin-left: 4px;
}

@media (max-width: 768px) {
    .header-top {
        margin-bottom: 14px;
    }
    
    .category-badge-top {
        font-size: 10px;
        padding: 4px 10px;
    }
    
    .company-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .company-logo {
        margin: 0 auto;
        width: 52px;
        height: 52px;
    }
    
    .company-name h3 {
        text-align: center;
        font-size: 15px;
    }
    
    .job-card-body {
        padding: 0 20px 18px;
    }
    
    .job-title a {
        text-align: center;
        font-size: 17px;
    }
    
    .job-meta {
        justify-content: center;
    }
    
    .location-info {
        justify-content: center;
    }
    
    .job-card-footer {
        padding: 14px 20px 20px;
    }
    
    .price-ad {
        text-align: center;
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
