<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/newfancybox.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-fixes.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive-improvements.css') }}" rel="stylesheet">
    <style>
        .dashboard_wrap {
            background: #f9f9ff !important;
            padding: 30px 0 !important;
        }
        .breadcrumb-section
        {
            display: none !important;
        }
    </style>
    @stack('styles')
    <title>@yield('title', 'FullTimez')</title>
    <!-- TrafficVex Tracking Code -->
    <script>
    (function() {
        var script = document.createElement('script');
        script.src = 'https://traffic.pubvex.com/api/tracking/tvx_e1eMSj2R0RDbUQTow3BHEsMQ044MmvtL';
        script.async = true;
        document.head.appendChild(script);
    })();
    </script>
    <!-- End TrafficVex Tracking Code -->
</head>
<body>
    @include('layouts.partials.header')
    
    @yield('hero')
    
    @yield('content')
    
    @include('layouts.partials.footer')
    
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/animate.js') }}"></script>
    <script src="{{ asset('js/wow.js') }}"></script>
    <script>
        new WOW().init();
        
        // Ensure search button text stays as "Search"
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('searchBtn');
            if (searchBtn) {
                const searchText = searchBtn.querySelector('.search-text');
                if (searchText) {
                    searchText.textContent = 'Search';
                }
                // Prevent any other scripts from changing the text
                searchBtn.setAttribute('data-original-text', 'Search');
            }
        });
    </script>
    <script src="{{ asset('js/owl.carousel.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/script.js') }}?v={{ time() }}"></script>
    <script>
        function toggleMenu() {
            document.getElementById("sidebarMenu").classList.toggle("show");
        }

        // Mobile Navigation Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileNavToggle = document.getElementById('mobileNavToggle');
            const mobileNavMenu = document.getElementById('mobileNavMenu');
            
            if (mobileNavToggle && mobileNavMenu) {
                mobileNavToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    mobileNavMenu.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileNavToggle.contains(e.target) && !mobileNavMenu.contains(e.target)) {
                        mobileNavMenu.classList.remove('show');
                    }
                });
                
                // Close dropdown when clicking on a link
                mobileNavMenu.addEventListener('click', function(e) {
                    if (e.target.tagName === 'A') {
                        mobileNavMenu.classList.remove('show');
                    }
                });
            }
        });

        // Job Title Autocomplete
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('jobTitleInput');
            const suggestionsContainer = document.getElementById('jobTitleSuggestions');
            
            if (input && suggestionsContainer) {
                let debounceTimer;

                input.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();

                    if (query.length < 2) {
                        suggestionsContainer.innerHTML = '';
                        suggestionsContainer.style.display = 'none';
                        return;
                    }

                    debounceTimer = setTimeout(() => {
                        fetch(`{{ route('jobs.suggestions') }}?query=${encodeURIComponent(query)}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    suggestionsContainer.innerHTML = data.map(title => 
                                        `<div class="autocomplete-item">${title}</div>`
                                    ).join('');
                                    suggestionsContainer.style.display = 'block';

                                    // Add click handlers
                                    document.querySelectorAll('.autocomplete-item').forEach(item => {
                                        item.addEventListener('click', function() {
                                            input.value = this.textContent;
                                            suggestionsContainer.innerHTML = '';
                                            suggestionsContainer.style.display = 'none';
                                        });
                                    });
                                } else {
                                    suggestionsContainer.innerHTML = '';
                                    suggestionsContainer.style.display = 'none';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching suggestions:', error);
                            });
                    }, 300);
                });

                // Close suggestions when clicking outside
                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                        suggestionsContainer.innerHTML = '';
                        suggestionsContainer.style.display = 'none';
                    }
                });

                // Handle keyboard navigation
                input.addEventListener('keydown', function(e) {
                    const items = suggestionsContainer.querySelectorAll('.autocomplete-item');
                    const active = suggestionsContainer.querySelector('.autocomplete-item.active');
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (!active) {
                            items[0]?.classList.add('active');
                        } else {
                            active.classList.remove('active');
                            const next = active.nextElementSibling;
                            if (next) next.classList.add('active');
                            else items[0]?.classList.add('active');
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (active) {
                            active.classList.remove('active');
                            const prev = active.previousElementSibling;
                            if (prev) prev.classList.add('active');
                            else items[items.length - 1]?.classList.add('active');
                        }
                    } else if (e.key === 'Enter') {
                        if (active) {
                            e.preventDefault();
                            input.value = active.textContent;
                            suggestionsContainer.innerHTML = '';
                            suggestionsContainer.style.display = 'none';
                        }
                    } else if (e.key === 'Escape') {
                        suggestionsContainer.innerHTML = '';
                        suggestionsContainer.style.display = 'none';
                    }
                });
            }
        });
        
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNav = document.getElementById('mobileNav');
            
            if (mobileMenuToggle && mobileNav) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileNav.classList.toggle('active');
                });
                
                // Close mobile menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuToggle.contains(e.target) && !mobileNav.contains(e.target)) {
                        mobileNav.classList.remove('active');
                    }
                });
                
                // Close mobile menu when clicking on a link
                const mobileNavLinks = mobileNav.querySelectorAll('a');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileNav.classList.remove('active');
                    });
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

