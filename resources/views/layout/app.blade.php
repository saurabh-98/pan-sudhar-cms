<!DOCTYPE html>
<html>
<head>
    <title>Digital Services</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
   

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/nav-desktop.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nav-mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/public-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/hero-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/quick-stats.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/feature-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/notice-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gallery-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cta-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/public-support.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home-support-section.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gallery-view.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/retailer-login.css') }}">
     <link rel="stylesheet" href="{{ asset('assets/css/executive-login.css') }}">
     <link rel="stylesheet" href="{{ asset('assets/css/distributor-login.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/retailer-register.css') }}">





</head>

<body>

<!-- HEADER -->
@include('layout.header')

<div class="main-wrapper">
    @yield('content')
</div>

<!-- FOOTER -->
@include('layout.footer')




<!-- =========================================================
| JS LIBRARIES
========================================================= -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- =========================================================
| MOBILE MENU
========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function(){

    const mobileToggle =
    document.getElementById('mobileToggle');

    const mobileMenu =
    document.getElementById('mobileMenu');

    const mobileOverlay =
    document.getElementById('mobileOverlay');

    if(mobileToggle && mobileMenu && mobileOverlay){

        mobileToggle.addEventListener('click', function(){

            mobileToggle.classList.toggle('active');

            mobileMenu.classList.toggle('show');

            mobileOverlay.classList.toggle('show');

            document.body.classList.toggle('menu-open');

        });

        mobileOverlay.addEventListener('click', function(){

            mobileToggle.classList.remove('active');

            mobileMenu.classList.remove('show');

            mobileOverlay.classList.remove('show');

            document.body.classList.remove('menu-open');

        });

    }

});

</script>

<!-- =========================================================
| HEADER SCROLL
========================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function(){

    const header =
    document.getElementById('mainHeader');

    window.addEventListener('scroll', () => {

        if(window.scrollY > 80){

            header?.classList.add('scrolled');

        }else{

            header?.classList.remove('scrolled');
        }
    });

});

</script>

<!-- =========================================================
| HERO CAROUSEL
========================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    const heroCarousel =
    document.querySelector('#heroCarousel');

    if(heroCarousel){

        new bootstrap.Carousel(heroCarousel, {

            interval: 4000,

            ride: 'carousel',

            pause: false,

            wrap: true,

            touch: true

        });

    }

});

</script>

<!-- =========================================================
| SWIPER
========================================================= -->

<script>

document.addEventListener("DOMContentLoaded", function () {

    if(document.querySelector(".heroSwiper")){

        new Swiper(".heroSwiper", {

            loop: true,

            slidesPerView: 1,

            autoplay: {
                delay: 4000
            },

            speed: 800,

            pagination: {
                el: ".swiper-pagination",
                clickable: true
            }

        });

    }

    if(document.querySelector(".campaignSwiper")){

        new Swiper(".campaignSwiper", {

            loop: true,

            slidesPerView: 1,

            autoplay: {
                delay: 3000
            },

            pagination: {
                el: ".swiper-pagination",
                clickable: true
            }

        });

    }

});

</script>


<script>

document.addEventListener('DOMContentLoaded', function(){

    const loginDropdown =
    document.querySelector('.login-dropdown');

    const loginBtn =
    document.querySelector('.login-dropdown-btn');

    if(loginBtn && loginDropdown){

        loginBtn.addEventListener('click', function(e){

            if(window.innerWidth <= 991){

                e.preventDefault();

                e.stopPropagation();

                loginDropdown.classList.toggle('active');
            }

        });

        document.addEventListener('click', function(){

            loginDropdown.classList.remove('active');

        });

    }

});

</script>

@yield('scripts')

</body>
</html>