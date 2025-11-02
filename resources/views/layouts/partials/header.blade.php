<style>
/* Top Navigation Menu Styling */
.top-nav {
    padding: 6px 6px 10px;
}

.top-nav .tabs {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.top-nav .tab {
    position: relative;
    margin: 0;
    padding: 0;
}

.top-nav .tab a {
    display: inline-block;
    padding: 10px 18px;
    color: #4a5568;
    text-decoration: none;
    font-size: 15px;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.top-nav .tab a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: left 0.3s ease;
    z-index: -1;
    border-radius: 8px;
}

.top-nav .tab a:hover::before {
    left: 0;
}

.top-nav .tab a:hover {
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Active state */
.top-nav .tab a.active,
.top-nav .tab a:focus {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    transform: translateY(-2px);
}

/* Current page highlight */
.top-nav .tab a[href="{{ url()->current() }}"] {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Auth Buttons Styling */
.auth-buttons {
    display: flex;
    gap: 10px;
    align-items: center;
}

.auth-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    white-space: nowrap;
}

.auth-btn.login-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.auth-btn.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

.auth-btn.register-btn {
    background: #ffffff;
    color: #667eea;
    border: 2px solid #667eea;
}

.auth-btn.register-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
    border-color: transparent;
}

.auth-btn.dashboard-btn {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
}

.auth-btn.dashboard-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(34, 197, 94, 0.4);
    color: #ffffff;
}

.auth-btn.logout-btn {
    background: #ffffff;
    color: #ef4444;
    border: 2px solid #ef4444;
}

.auth-btn.logout-btn:hover {
    background: #ef4444;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4);
}

/* Mobile Navigation Toggle */
.mobile-nav-toggle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.mobile-nav-toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
}

.mobile-nav-menu {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    padding: 20px;
    margin-top: 10px;
    position: absolute;
    right: 0;
    top: 100%;
    min-width: 200px;
    z-index: 1000;
    display: none;
}

.mobile-nav-menu.show {
    display: block;
}

.mobile-nav-menu a {
    display: block;
    padding: 12px 16px;
    color: #4a5568;
    text-decoration: none;
    border-radius: 8px;
    margin-bottom: 4px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.mobile-nav-menu a:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    transform: translateX(4px);
}

.mobile-auth-btn {
    display: block;
    padding: 10px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    margin-top: 8px;
    transition: all 0.3s ease;
}

.mobile-auth-btn.login-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
}

.mobile-auth-btn.register-btn {
    background: #ffffff;
    color: #667eea;
    border: 2px solid #667eea;
}

.mobile-auth-btn.dashboard-btn {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
}

.mobile-auth-btn.logout-btn {
    background: #ffffff;
    color: #ef4444;
    border: 2px solid #ef4444;
}

@media (max-width: 991px) {
    .top-nav .tabs {
        gap: 4px;
    }
    
    .top-nav .tab a {
        padding: 8px 14px;
        font-size: 14px;
    }
}

@media (max-width: 768px) {
    .auth-buttons {
        gap: 8px;
    }
    
    .auth-btn {
        padding: 8px 16px;
        font-size: 13px;
    }
}
</style>

<div class="container">
   <div class="app">
        <div class="row align-items-center"> 
         <div class="col-lg-3 col-md-6 col-6">
                  <div class="fulltimez-logo"><a href="{{ route('home') }}"><img src="{{ asset('images/full-timez-logo.png') }}" alt="FullTimez Logo"></a></div>
               </div>
                <div class="col-lg-6 d-mobile-none">
    <nav class="top-nav">
      <ul class="tabs">
        <li class="tab"><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li class="tab"><a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">Browse Jobs</a></li>
        <li class="tab"><a href="{{ route('candidates.index') }}" class="{{ request()->routeIs('candidates.*') ? 'active' : '' }}">Browse Resumes</a></li>
        <li class="tab"><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact Us</a></li>
      </ul>
    </nav>
</div>

<div class="col-lg-3 col-md-6 col-6">
<div class="d-flex gap-2 justify-content-end"> 
   <!-- Mobile Navigation Dropdown -->
   <div class="mobile-nav-dropdown d-mobile-only" style="margin-left: auto; position: relative;">
       <button class="mobile-nav-toggle" id="mobileNavToggle">
           <i class="fas fa-bars"></i>
           <span>Menu</span>
       </button>
       <div class="mobile-nav-menu" id="mobileNavMenu">
           <a href="{{ route('home') }}">Home</a>
           <a href="{{ route('jobs.index') }}">Browse Jobs</a>
           <a href="{{ route('candidates.index') }}">Browse Resumes</a>
           <a href="{{ route('contact') }}">Contact Us</a>
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

    @if(!(auth()->check() && auth()->user()->isEmployer() && request()->routeIs('dashboard')) && !request()->routeIs('candidates.index'))
    <section class="search-wrap">
      <form action="{{ route('jobs.index') }}" method="GET" id="headerSearchForm">
      <div class="search-barwrp">
        <div class="field">
          <span class="label">Country:</span>
          <select class="form-control" name="country" id="countrySelect">
              <option value="">Select Country</option>
              @php
                  $countries = \App\Models\Country::where('is_active', true)->orderBy('name')->get();
              @endphp
              @foreach($countries as $country)
                  <option value="{{ $country->name }}" {{ request('country') == $country->name ? 'selected' : '' }}>
                      {{ $country->name }}
                  </option>
              @endforeach
          </select>
        </div> 
        <div class="field">
          <span class="label">State/City:</span>
          <select class="form-control" name="location" id="citySelect">
              <option value="">Select Location</option>
              @php
                  $selectedCountry = request('country');
                  $cities = \App\Models\City::where('is_active', true)
                      ->when($selectedCountry, function($q) use ($selectedCountry) {
                          $q->whereHas('country', function($cq) use ($selectedCountry) {
                              $cq->where('name', 'like', '%' . $selectedCountry . '%');
                          });
                      })
                      ->orderBy('name')
                      ->get();
              @endphp
              @foreach($cities as $city)
                  <option value="{{ $city->name }}" {{ request('location') == $city->name ? 'selected' : '' }}>
                      {{ $city->name }}
                  </option>
              @endforeach
          </select>
        </div>
        <div class="field" style="position: relative;">
          <span class="label">Job Title:</span>
          <input type="text" class="form-control" id="jobTitleInput" name="title" placeholder="e.g. Developer, Designer" value="{{ request('title') }}" autocomplete="off">
          <div id="jobTitleSuggestions" class="autocomplete-suggestions"></div>
        </div>

        <div class="actions">
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
    
    <style>
    .search-wrap {
        padding: 25px 0;
        margin-top: 20px;
        border-radius: 8px;
    }
    
    .search-barwrp {
        display: flex;
        gap: 15px;
        align-items: flex-end;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .search-barwrp .field {
        flex: 1;
        min-width: 200px;
    }
    
    .search-barwrp .field .label {
        display: block;
        color: #333;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .search-barwrp .field .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 6px;
        background: white;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    
    .search-barwrp .field .form-control:focus {
        outline: none;
        border-color: #007bff;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
    }
    
    .search-barwrp .actions {
        flex-shrink: 0;
    }
    
    .search-barwrp .btn.primary {
        background: #007bff;
        color: white;
        border: 2px solid #007bff;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .search-barwrp .btn.primary:hover {
        background: #0056b3;
        border-color: #0056b3;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
    }
    
    .search-barwrp .btn.primary .icon {
        width: 20px;
        height: 20px;
    }
    
    .autocomplete-suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 2px solid #ddd;
        border-top: none;
        border-radius: 0 0 6px 6px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        display: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .autocomplete-suggestions .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.2s;
    }
    
    .autocomplete-suggestions .autocomplete-item:hover,
    .autocomplete-suggestions .autocomplete-item.active {
        background: #f8f9fa;
    }
    
    @media (max-width: 768px) {
        .search-barwrp {
            flex-direction: column;
            gap: 15px;
        }
        
        .search-barwrp .field {
            width: 100%;
            min-width: auto;
        }
        
        .search-barwrp .actions {
            width: 100%;
        }
        
        .search-barwrp .btn.primary {
            width: 100%;
            justify-content: center;
        }
    }
    </style>
    
    <script>
    // Dynamic city loading based on country selection
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('countrySelect');
        const citySelect = document.getElementById('citySelect');
        
        if (countrySelect && citySelect) {
            countrySelect.addEventListener('change', function() {
                const country = this.value;
                citySelect.innerHTML = '<option value="">Loading...</option>';
                citySelect.disabled = true;
                
                if (country) {
                    fetch(`{{ url('/api/cities') }}/${encodeURIComponent(country)}`)
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            citySelect.innerHTML = '<option value="">Select Location</option>';
                            if (data.success && data.cities && Array.isArray(data.cities)) {
                                data.cities.forEach(city => {
                                    const option = document.createElement('option');
                                    option.value = city.name;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });
                            }
                            citySelect.disabled = false;
                        })
                        .catch(error => {
                            console.error('Error loading cities:', error);
                            citySelect.innerHTML = '<option value="">Select Location</option>';
                            citySelect.disabled = false;
                        });
                } else {
                    // Clear cities when no country selected
                    citySelect.innerHTML = '<option value="">Select Location</option>';
                    citySelect.disabled = false;
                }
            });
        }
    });
    </script>
    @endif
    
  </div>
</div>

