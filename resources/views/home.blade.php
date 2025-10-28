@extends('layouts.app')

@section('title', 'FullTimez - Home')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@push('styles')
<style>
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
    }
    
    .jobs_list .item {
        width: 100% !important;
        display: block !important;
        float: none !important;
        clear: both !important;
        margin-bottom: 20px !important;
    }
    
    .jobs-ad-card {
        margin-bottom: 20px !important;
        padding: 15px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        background: #fff !important;
        width: 100% !important;
        display: block !important;
        float: none !important;
        clear: both !important;
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





   <section class="category-wrap jobwrp popular-items mt-5">
      <div class="container">
         <div class="main_title">Featured Jobs {{ count($featuredJobs) }}</div>
         <div class="cate_list">
         <ul class="owl-carousel jobs_list">
            @foreach($featuredJobs as $job)
            <li class="item wow fadeInUp">
              <div class="add-exp">
                  <div class="jobs-ad-card ">
                     <div class="category-job d-flex align-items-center">
                        <div class="job-icons">
                           <img src="{{ asset('images/job.svg') }}" alt="job-ico" class="img-fluid">
                        </div>
                        <div class="categery-name">
                           <span>Category</span>
                           <h3>{{ optional($job->category)->name ?? 'N/A' }}
                           </h3>
                        </div>
                     </div>
                     <span class="boosted-popular-premium">Featured</span>
                  </div>
                  <div class="catebox">
                     <h3 class="mt-0 add-title"><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                     <div class="meta-ad">
                        <div class="owner-ad-list d-flex align-items-center">
                           <img src="{{ asset('images/avatar.jpg') }}" alt="logo">
                           <span class="full-name-list">{{ optional($job->employer->employerProfile)->company_name ?? 'Company' }}</span>
                        </div>
                     </div>
                     <ul class="carinfo ad-features-parent">
                        <li>Type <span>{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span></li>
                        <li>Experience <span>{{ $job->experience_years ?? 'N/A' }}</span></li>
                        <li>EDucation <span>{{ $job->education_level ?? 'N/A' }}</span>
                        </li>
                     </ul>
                     <div class="cartbx d-flex"><a href="#"><img src="{{ asset('images/location.svg') }}" alt="logo"> {{ $job->location_city }}</a>
                     </div>
                     <div class="price-ad">
                        <p>
                            @if(!empty($job->salary_min) && !empty($job->salary_max))
                                {{ $job->salary_currency ?? 'AED' }} {{ number_format((float)$job->salary_min) }} - {{ number_format((float)$job->salary_max) }} <span>{{ ucfirst($job->salary_period ?? 'monthly') }}</span>
                            @else
                                Negotiable
                            @endif
                        </p>
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
