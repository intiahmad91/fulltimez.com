@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
 <section class="breadcrumb-section">
        <div class="container-auto">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="page-title">
                        <h1>Job Seekers</h1>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Job Seekers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

<section class="category-wrap innerseeker popular-items mt-5">
      <div class="container">
         <div class="main_title">Current Jobs</div>
<div class="row">
            <div class="col-lg-3 fadeInLeft">
<div class="filters">
    <h3>Filters</h3>

<form action="{{ route('jobs.index') }}" method="GET">
<div class="input-group">
<label>Category</label>
 <select class="form-control" name="type">
    <option value="">All Types</option>
    <option value="jobs" {{ request('type') == 'jobs' ? 'selected' : '' }}>Job Opportunities</option>
    <option value="seekers" {{ request('type') == 'seekers' ? 'selected' : '' }}>Jobs Seekers</option>
 </select>
</div>

<div class="input-group">
<label>Search</label>
<input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search">
 
</div>

<div class="input-group">
<label>Category</label> 
 <select class="form-control" name="category">
    <option value="">All Categories</option>
    @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
    @endforeach
 </select>
</div>


<div class="input-group">
<label>Education</label> 
 <select class="form-control" name="education"> 
    <option value="">All Education Levels</option>
    <option value="high-school" {{ request('education') == 'high-school' ? 'selected' : '' }}>High-School / Secondary</option>
    <option value="bachelors" {{ request('education') == 'bachelors' ? 'selected' : '' }}>Bachelors Degree</option>
    <option value="masters" {{ request('education') == 'masters' ? 'selected' : '' }}>Masters Degree</option>
    <option value="phd" {{ request('education') == 'phd' ? 'selected' : '' }}>PhD</option>
 </select>
</div>


<div class="input-group">
<label>Desired Salary</label> 
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
<label>Current Location</label> 
 <select class="form-control" name="location"> 
    <option value="">All Locations</option>
    <option value="Dubai" {{ request('location') == 'Dubai' ? 'selected' : '' }}>Dubai</option>
    <option value="Abu Dhabi" {{ request('location') == 'Abu Dhabi' ? 'selected' : '' }}>Abu Dhabi</option>
    <option value="Sharjah" {{ request('location') == 'Sharjah' ? 'selected' : '' }}>Sharjah</option>
    <option value="Ajman" {{ request('location') == 'Ajman' ? 'selected' : '' }}>Ajman</option>
    <option value="RAK" {{ request('location') == 'RAK' ? 'selected' : '' }}>Ras Al Khaimah</option>
 </select>
</div>

<div class="input-group">
<label>Nationality</label> 
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

<div class="input-group justify-content-center">

<input type="submit" value="Apply Filter" class="apply_btn">

</div>


</form>


</div>
                
</div>

 <div class="col-lg-9 fadeInLeft">
<div class="cate_list m-0">
         @if(isset($featuredJobs) && $featuredJobs->count())
         <div class="mb-3"><strong>Featured Jobs</strong></div>
         <ul class="jobs_box row">
            @foreach($featuredJobs as $job)
            <li class="col-lg-4 wow fadeInUp">
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
         @endif

         <div class="mt-4 mb-3"><strong>Recommended Jobs</strong></div>
         <ul class="jobs_box row">
            @foreach($recommendedJobs as $job)
            <li class="col-lg-4 wow fadeInUp">
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
                     <span class="boosted-popular-premium">{{ $job->isFeatured() ? 'Featured' : ucfirst($job->priority) }}</span>
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

         <div class="mt-3">
             @include('partials.simple-pagination', ['paginator' => $recommendedJobs->withQueryString()])
         </div>
      </div> 


    </div>


</div>


       


      </div> 
   </section>
@endsection
