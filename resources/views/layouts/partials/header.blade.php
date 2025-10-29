<div class="container">
   <div class="app">
        <div class="row align-items-center"> 
         <div class="col-lg-3 col-md-6 col-6">
                  <div class="fulltimez-logo"><a href="{{ route('home') }}"><img src="{{ asset('images/full-timez-logo.png') }}" alt="FullTimez Logo"></a></div>
               </div>
                <div class="col-lg-6 d-mobile-none">
    <nav class="top-nav">
      <ul class="tabs">
        <li class="tab"><a href="{{ route('home') }}">Home</a></li>
        <li class="tab"><a href="{{ route('jobs.index') }}">Jobseeker</a></li>
        <li class="tab"><a href="{{ route('employer.login') }}">Employer</a></li> 
        <li class="tab"><a href="#">Packages</a></li>
        <li class="tab"><a href="#">Book Meeting</a></li>
      </ul>
    </nav>
</div>

<div class="col-lg-3 col-md-6 col-6">
<div class="d-flex gap-2 justify-content-end"> 
   <!-- Mobile Navigation Dropdown -->
   <div class="mobile-nav-dropdown d-mobile-only" style="margin-left: auto;">
       <button class="mobile-nav-toggle" id="mobileNavToggle">
           <i class="fas fa-bars"></i>
           <span>Menu</span>
       </button>
       <div class="mobile-nav-menu" id="mobileNavMenu">
           <a href="{{ route('home') }}">Home</a>
           <a href="{{ route('jobs.index') }}">Jobseeker</a>
           <a href="{{ route('employer.login') }}">Employer</a>
           <a href="#">Packages</a>
           <a href="#">Book Meeting</a>
           <hr>
           @auth
           <a href="{{ route('dashboard') }}" class="mobile-auth-btn dashboard-btn">Dashboard</a>
           <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="mobile-auth-btn logout-btn">Logout</a>
           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
               @csrf
           </form>
           @else
           <a href="{{ route('login') }}" class="mobile-auth-btn login-btn">LOGIN</a>
           <a href="{{ route('choose.role') }}" class="mobile-auth-btn register-btn">REGISTER</a>
           @endauth
       </div>
   </div>
   
   <!-- Desktop Auth Links -->
   <div class="d-mobile-none auth-buttons">
   @auth
   <a href="{{ route('dashboard') }}" class="auth-btn dashboard-btn">Dashboard</a>
   <form action="{{ route('logout') }}" method="POST" style="display: inline;">
      @csrf
      <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="auth-btn logout-btn">Logout</a>
   </form>
   @else
   <a href="{{ route('login') }}" class="auth-btn login-btn">LOGIN</a>
   <a href="{{ route('choose.role') }}" class="auth-btn register-btn">REGISTER</a>
   @endauth
   </div>
             </div> 
</div>


<hr class="mt-4">
 
</div>

    @if(!(auth()->check() && auth()->user()->isEmployer() && request()->routeIs('dashboard')))
    <section class="search-wrap">
      <form action="{{ route('jobs.index') }}" method="GET">
      <div class="search-barwrp">
        <div class="field">
          <span class="label">Country:</span>
          <select class="form-control" name="country">
              <option value="">Select Country</option>
              <option value="Pakistan">Pakistan</option>
              <option value="United States">United States</option>
              <option value="Canada">Canada</option>
              <option value="Australia">Australia</option>
              <option value="UAE">UAE</option>
              <option value="United Kingdom">United Kingdom</option>
          </select>
        </div> 
        <div class="field">
          <span class="label">State/City:</span>
          <select class="form-control" name="location">
              <option value="">Select Location</option>
              <option value="Punjab">Punjab</option>
              <option value="Sindh">Sindh</option>
              <option value="KPK">KPK</option>
              <option value="Dubai">Dubai</option>
              <option value="Abu Dhabi">Abu Dhabi</option>
          </select>
        </div>
        <div class="field" style="position: relative;">
          <span class="label">Job Title:</span>
          <input type="text" class="form-control" id="jobTitleInput" name="title" placeholder="e.g. Developer, Designer" value="{{ request('title') }}" autocomplete="off">
          <div id="jobTitleSuggestions" class="autocomplete-suggestions"></div>
        </div>

        <div class="actions">
          <a href="{{ route('jobs.index') }}" class="btn ghost">Advanced</a> 
          <button type="submit" class="btn primary" id="searchBtn" data-text="Search">
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
              <circle cx="11" cy="11" r="6" stroke="currentColor" fill="none" stroke-width="2"></circle>
              <line x1="16.5" y1="16.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
            </svg>
            <span class="search-text">Search</span>
          </button>
        </div>
      </div>
      </form>
    </section>
    @endif
    
  </div>
</div>

