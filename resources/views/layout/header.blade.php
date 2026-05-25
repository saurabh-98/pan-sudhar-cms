<!-- =========================================================
| ADVANCED MODERN HEADER
========================================================= -->

<header class="modern-header"
        id="mainHeader">

    <!-- =====================================================
    | TOPBAR
    ===================================================== -->

    <div class="header-topbar">

        <div class="container-custom topbar-wrapper">

            <!-- LEFT -->
            <div class="topbar-contact">

                <a href="tel:{{ $settings['phone'] ?? '+919876543210' }}"
                   class="topbar-link">

                    <i class="fa-solid fa-phone-volume"></i>

                    <span>

                        {{ $settings['phone'] ?? '+91 9876543210' }}

                    </span>

                </a>

                <a href="mailto:{{ $settings['email'] ?? 'support@panaadhaarsuvidha.com' }}"
                   class="topbar-link">

                    <i class="fa-solid fa-envelope"></i>

                    <span>

                        {{ $settings['email'] ?? 'support@panaadhaarsuvidha.com' }}

                    </span>

                </a>

                <div class="topbar-time">

                    <i class="fa-solid fa-clock"></i>

                    <span>

                        Mon - Sat : 9:00 AM - 7:00 PM

                    </span>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="topbar-right">

                <!-- SOCIAL -->
                <div class="topbar-social">

                    <a href="#">

                        <i class="fa-brands fa-facebook-f"></i>

                    </a>

                    <a href="#">

                        <i class="fa-brands fa-instagram"></i>

                    </a>

                    <a href="#">

                        <i class="fa-brands fa-youtube"></i>

                    </a>

                    <a href="#">

                        <i class="fa-brands fa-linkedin-in"></i>

                    </a>

                </div>


            </div>

        </div>

    </div>

    <!-- =====================================================
    | MAIN NAVBAR
    ===================================================== -->

    <div class="header-navbar">

        <div class="container-custom navbar-wrapper">

            <!-- =================================================
            | LOGO
            ================================================= -->

            <a href="{{ url('/') }}"
               class="brand-logo">

                <!-- =====================================================
                | LOGO ICON / IMAGE / TEXT
                ===================================================== -->

                <div class="logo-icon">

                    @if(($settings['logo_type'] ?? '') === 'image'
                        && !empty($settings['logo']))

                        <!-- IMAGE LOGO -->
                        <img src="{{ asset($settings['logo']) }}"
                             alt="Portal Logo">

                    @elseif(($settings['logo_type'] ?? '') === 'text')

                        <!-- TEXT LOGO -->
                        <span class="dynamic-logo-text">

                            {{ $settings['logo_text'] ?? 'PS' }}

                        </span>

                    @else

                        <!-- DEFAULT -->
                        🪪

                    @endif

                </div>

                <!-- =====================================================
                | TEXT
                ===================================================== -->

                <div class="logo-text">

                    <h2>

                        {{ $settings['site_name']
                            ?? $settings['logo_text']
                            ?? 'PAN Suvidha Portal' }}

                    </h2>

                </div>

            </a>

            <!-- =================================================
            | MOBILE TOGGLE
            ================================================= -->

            <button class="mobile-toggle"
                    id="mobileToggle"
                    aria-label="Toggle Navigation">

                <span></span>
                <span></span>
                <span></span>

            </button>

            <!-- =================================================
            | NAVIGATION CONTAINER
            ================================================= -->

            <div class="nav-container"
                 id="mobileMenu">

                <!-- NAVIGATION -->
                <nav class="main-nav">

                    @foreach($navMenus as $menu)

                        <a href="{{ url($menu->url) }}"
                           class="{{ request()->is(ltrim($menu->url,'/').'*') ? 'active' : '' }}">

                            {{ $menu->name }}

                        </a>

                    @endforeach

                </nav>

               <!-- ACTIONS -->
                <div class="nav-actions">

                    <!-- =====================================================
                    | RETAILER LOGIN
                    ===================================================== -->

                    <a href="{{ route('retailer.login') }}"
                    class="action-btn parent-btn">

                        <i class="fa-solid fa-shop"></i>

                        <span>

                            Retailer Login

                        </span>

                    </a>

                    <!-- =====================================================
                    | RETAILER REGISTRATION
                    ===================================================== -->

                    <a href="{{ route('retailer.register') }}"
                    class="action-btn retailer-register-btn">

                        <i class="fa-solid fa-user-plus"></i>

                        <span>

                            Retailer Register

                        </span>

                    </a>

                    <!-- =====================================================
                    | BUSINESS DEVELOPMENT EXECUTIVE
                    ===================================================== -->

                    <a href=""
                    class="action-btn bde-btn">

                        <i class="fa-solid fa-briefcase"></i>

                        <span>

                            BDE Login

                        </span>

                    </a>

                    <!-- =====================================================
                    | ADMIN LOGIN
                    ===================================================== -->

                    <a href="{{ route('login') }}"
                    class="action-btn dept-btn">

                        <i class="fa-solid fa-user-shield"></i>

                        <span>

                            Admin Login

                        </span>

                    </a>

                </div>

            </div>

        </div>

    </div>

</header>

<!-- =========================================================
| MOBILE OVERLAY
========================================================= -->

<div class="mobile-overlay"
     id="mobileOverlay"></div>