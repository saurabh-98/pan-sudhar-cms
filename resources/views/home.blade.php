@extends('layout.app')

@section('content')

<!-- =========================================================
| HERO SECTION
========================================================= -->

<section class="hero-wrapper">

    <div id="heroCarousel"
         class="carousel slide carousel-fade"
         data-bs-ride="carousel"
         data-bs-interval="4000">

        <!-- Indicators -->
        <div class="carousel-indicators">

            @php $indicatorIndex = 0; @endphp

            @foreach($heroes as $hero)

                @if(is_array($hero->image))

                    @foreach($hero->image as $img)

                        <button type="button"
                                data-bs-target="#heroCarousel"
                                data-bs-slide-to="{{ $indicatorIndex }}"
                                class="{{ $indicatorIndex == 0 ? 'active' : '' }}"
                                aria-current="true">
                        </button>

                        @php $indicatorIndex++; @endphp

                    @endforeach

                @endif

            @endforeach

        </div>

        <!-- Slides -->
        <div class="carousel-inner">

            @php $slideIndex = 0; @endphp

            @foreach($heroes as $hero)

                @if(is_array($hero->image))

                    @foreach($hero->image as $img)

                        <div class="carousel-item {{ $slideIndex == 0 ? 'active' : '' }}">

                            <div class="hero-slide"
                                 style="
                                    background:
                                    linear-gradient(
                                        rgba(0,0,0,.35),
                                        rgba(0,0,0,.35)
                                    ),
                                    url('{{ asset('storage/' . $img) }}')
                                    center center / cover no-repeat;
                                 ">

                                <div class="container">

                                    <div class="hero-content text-center">

                                        

                                        
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        @php $slideIndex++; @endphp

                    @endforeach

                @endif

            @endforeach

        </div>

        <!-- Previous -->
        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="prev">

            <span class="carousel-control-prev-icon"></span>

        </button>

        <!-- Next -->
        <button class="carousel-control-next"
                type="button"
                data-bs-target="#heroCarousel"
                data-bs-slide="next">

            <span class="carousel-control-next-icon"></span>

        </button>

    </div>

</section>


<!-- =========================================================
| ADVANCED NOTICE SECTION
========================================================= -->

<section class="notice-section">

    <!-- BACKGROUND EFFECT -->
    <div class="notice-glow notice-glow-1"></div>
    <div class="notice-glow notice-glow-2"></div>

    <div class="container">

        <!-- HEADER -->
        <div class="notice-header text-center">

            <span class="notice-badge">

                <i class="fa-solid fa-bell"></i>

                SERVICE UPDATES

            </span>

            <h2 class="notice-title">

                
                <span>Latest Announcements</span>

            </h2>

          
        </div>

        <!-- NOTICE BOX -->
        <div class="notice-box">

            <!-- LIVE INDICATOR -->
            <div class="notice-live">

                <span class="live-dot"></span>

                Live Updates

            </div>

            <!-- ICON -->
            <div class="notice-icon">

                <i class="fa-solid fa-bullhorn"></i>

            </div>

            <!-- MARQUEE -->
            <div class="notice-marquee">

                <div class="notice-track">

                    @foreach($notices ?? [] as $notice)

                        <div class="notice-item">

                            <span class="notice-tag">

                                New

                            </span>

                            <span class="notice-text">

                                📢 {{ $notice->title }}

                            </span>

                        </div>

                    @endforeach

                    <!-- DUPLICATE FOR SMOOTH LOOP -->
                    @foreach($notices ?? [] as $notice)

                        <div class="notice-item">

                            <span class="notice-tag">

                                Update

                            </span>

                            <span class="notice-text">

                                📢 {{ $notice->title }}

                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
| ADVANCED QUICK STATS SECTION
========================================================= -->

<section class="stats-wrapper">

    <!-- BACKGROUND EFFECTS -->
    <div class="stats-bg-circle stats-bg-circle-1"></div>
    <div class="stats-bg-circle stats-bg-circle-2"></div>

    <div class="container">

        <!-- SECTION HEADER -->
        <div class="stats-section-header">

         
            <h2 class="stats-title">

                Fast & Secure
                <span>Digital Documentation Services</span>

            </h2>

           
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid">

            <!-- CARD -->
            <div class="stats-card">

                <div class="stats-pattern"></div>

                <div class="stats-icon">

                    <i class="fa-solid fa-users"></i>

                </div>

                <div class="stats-content">

                    <h2 class="counter"
                        data-target="{{ $stats['customers'] ?? 15000 }}">

                        0

                    </h2>

                    <p>

                        Happy Customers

                    </p>

                    <span class="stats-tag">

                        <i class="fa-solid fa-arrow-trend-up"></i>

                        Trusted by Thousands

                    </span>

                </div>

            </div>

            <!-- CARD -->
            <div class="stats-card">

                <div class="stats-pattern"></div>

                <div class="stats-icon">

                    <i class="fa-solid fa-id-card-clip"></i>

                </div>

                <div class="stats-content">

                    <h2 class="counter"
                        data-target="{{ $stats['pan_services'] ?? 8500 }}">

                        0

                    </h2>

                    <p>

                        PAN Services

                    </p>

                    <span class="stats-tag">

                        <i class="fa-solid fa-check-circle"></i>

                        New & Correction PAN

                    </span>

                </div>

            </div>

            <!-- CARD -->
            <div class="stats-card">

                <div class="stats-pattern"></div>

                <div class="stats-icon">

                    <i class="fa-solid fa-fingerprint"></i>

                </div>

                <div class="stats-content">

                    <h2 class="counter"
                        data-target="{{ $stats['aadhaar_services'] ?? 6200 }}">

                        0

                    </h2>

                    <p>

                        Aadhaar Services

                    </p>

                    <span class="stats-tag">

                        <i class="fa-solid fa-shield-halved"></i>

                        Secure Verification

                    </span>

                </div>

            </div>

            <!-- CARD -->
            <div class="stats-card">

                <div class="stats-pattern"></div>

                <div class="stats-icon">

                    <i class="fa-solid fa-award"></i>

                </div>

                <div class="stats-content">

                    <h2 class="counter"
                        data-target="{{ $stats['years'] ?? 10 }}">

                        0

                    </h2>

                    <p>

                        Years Experience

                    </p>

                    <span class="stats-tag">

                        <i class="fa-solid fa-star"></i>

                        Reliable Digital Services

                    </span>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- =========================================================
| ADVANCED FEATURES SECTION
========================================================= -->

<section class="features-section section"
         id="features">

    <!-- BACKGROUND GLOW -->
    <div class="features-glow features-glow-1"></div>
    <div class="features-glow features-glow-2"></div>

    <div class="container">

        <!-- HEADER -->
        <div class="features-header">

            <span class="features-badge">

                <i class="fa-solid fa-shield-check"></i>

                WHY CHOOSE US

            </span>

            <h2 class="features-title">

                Trusted Digital Services For
                <span>PAN & Aadhaar Solutions</span>

            </h2>

           

        </div>

        <!-- GRID -->
        <div class="row g-4">

            @foreach($features ?? [] as $index => $f)

            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="feature-card">

                    <!-- CARD NUMBER -->
                    <span class="feature-number">

                        0{{ $index + 1 }}

                    </span>

                    <!-- ICON -->
                    <div class="feature-icon">

                        <i class="{{ $f->icon }}"></i>

                    </div>

                    <!-- CONTENT -->
                    <div class="feature-content">

                        <h5>

                            {{ $f->title }}

                        </h5>

                        <p>

                            {{ $f->description }}

                        </p>

                    </div>

                    <!-- HOVER BUTTON -->
                    <div class="feature-hover">

                        <a href="#"
                           class="feature-btn">

                            Explore Service

                            <i class="fa-solid fa-arrow-right"></i>

                        </a>

                    </div>

                    <!-- GLOW -->
                    <div class="feature-blur"></div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</section>



<!-- =========================================================
| CUSTOM GALLERY SECTION
========================================================= -->

<section class="sg-gallery-section">

    <div class="sg-gallery-glow sg-gallery-glow-1"></div>
    <div class="sg-gallery-glow sg-gallery-glow-2"></div>

    <div class="container">

        <!-- HEADER -->
        <div class="sg-gallery-header text-center">

            <span class="sg-gallery-badge">

                <i class="fa-solid fa-camera-retro"></i>

                SERVICE GALLERY

            </span>

            <h2 class="sg-gallery-title">

                PAN & Aadhaar
                <span>Service Gallery</span>

            </h2>

        

        </div>

        <!-- GALLERY GRID -->
        <div class="row g-4 sg-gallery-grid">

            @foreach($gallery ?? [] as $index => $g)

            <div class="col-xl-3 col-lg-4 col-md-6 col-6 sg-gallery-item">

                <div class="sg-gallery-card">

                    <!-- IMAGE -->
                    <div class="sg-gallery-image">

                        <img src="{{ asset('storage/gallery/'.$g->file) }}"
                             alt="Gallery">

                    </div>

                    <!-- OVERLAY -->
                    <div class="sg-gallery-overlay">

                        <div class="sg-gallery-content">

                            <span class="sg-gallery-category">

                                Service Center

                            </span>

                            <h5>

                                PAN & Aadhaar Services

                            </h5>

                            <div class="sg-gallery-actions">

                                <a href="{{ asset('storage/gallery/'.$g->file) }}"
                                   class="sg-gallery-btn sg-gallery-popup">

                                    <i class="fa-solid fa-expand"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- NUMBER -->
                    <span class="sg-gallery-number">

                        0{{ $index + 1 }}

                    </span>

                </div>

            </div>

            @endforeach

        </div>

        <!-- BUTTON -->
        <div class="text-center sg-gallery-btn-wrap">

            <a href="{{ route('gallery.view') }}"
               class="sg-main-gallery-btn">

                View Full Gallery

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

</section>


<!-- =========================================================
| ADVANCED SUPPORT SECTION
========================================================= -->

<section class="home-support-section">

    <!-- GLOW EFFECTS -->

    <div class="support-glow support-glow-1"></div>
    <div class="support-glow support-glow-2"></div>

    <div class="container">

        <div class="home-support-box">

            <!-- LEFT -->

            <div class="home-support-content">

                <span class="home-support-badge">

                    <i class="fa-solid fa-headset"></i>

                    24/7 CUSTOMER SUPPORT

                </span>

                <h2 class="home-support-title">

                    Need Help With
                    <span>PAN or Aadhaar Services?</span>

                </h2>


                <!-- FEATURES -->

           

            </div>

            <!-- RIGHT -->

            <div class="home-support-actions">

                <div class="support-card-box">

                    <div class="support-icon">

                        <i class="fa-solid fa-ticket"></i>

                    </div>

                    <h4>

                        Raise Support Ticket

                    </h4>

                    <p>

                        Submit your issue or service request
                        directly to our support team.

                    </p>

                    <a href=""
                       class="support-main-btn">

                        Create Ticket

                    </a>

                </div>

                <div class="support-card-box">

                    <div class="support-icon track">

                        <i class="fa-solid fa-magnifying-glass"></i>

                    </div>

                    <h4>

                        Track Existing Request

                    </h4>

                    <p>

                        Track application status and
                        support replies online.

                    </p>

                    <a href=""
                       class="track-main-btn">

                        Track Request

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =========================================================
| ADVANCED CTA SECTION
========================================================= -->

<section class="sg-cta-section">

    <!-- BACKGROUND GLOW -->
    <div class="sg-cta-glow sg-cta-glow-1"></div>
    <div class="sg-cta-glow sg-cta-glow-2"></div>

    <div class="container">

        <div class="sg-cta-box text-center">

            <!-- BADGE -->
            <span class="sg-cta-badge">

                <i class="fa-solid fa-id-card"></i>

                ONLINE SERVICES AVAILABLE

            </span>

            <!-- TITLE -->
            <h2 class="sg-cta-title">

                Apply For
                <span>PAN & Aadhaar Services</span>
                Today

            </h2>

            <!-- SUBTITLE -->
            <p class="sg-cta-subtitle">

                Get fast, secure, and reliable PAN Card,
                Aadhaar update, correction, verification,
                and online documentation services from our
                trusted digital service center.

            </p>

            <!-- BUTTONS -->
            <div class="sg-cta-actions">

                <a href=""
                   class="sg-cta-btn sg-cta-btn-primary">

                    <i class="fa-solid fa-paper-plane"></i>

                    Apply Now

                </a>

                <a href="#features"
                   class="sg-cta-btn sg-cta-btn-outline">

                    <i class="fa-solid fa-arrow-right"></i>

                    Explore Services

                </a>

            </div>

            <!-- MINI STATS -->
            <div class="sg-cta-stats">

                <div class="sg-cta-stat">

                    <h4>

                        15000+

                    </h4>

                    <p>

                        Happy Customers

                    </p>

                </div>

                <div class="sg-cta-stat">

                    <h4>

                        8500+

                    </h4>

                    <p>

                        PAN Services

                    </p>

                </div>

                <div class="sg-cta-stat">

                    <h4>

                        10+

                    </h4>

                    <p>

                        Years Experience

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection


@section('scripts')

<script>

/* =========================================================
| COUNTER ANIMATION
========================================================= */

document.addEventListener("DOMContentLoaded", () => {

    const counters = document.querySelectorAll('.counter');

    const speed = 120;

    counters.forEach(counter => {

        const animate = () => {

            const target =
                +counter.getAttribute('data-target');

            const current =
                +counter.innerText;

            const increment =
                Math.ceil(target / speed);

            if(current < target){

                counter.innerText =
                    current + increment;

                setTimeout(animate, 20);

            }else{

                counter.innerText = target;
            }
        };

        animate();
    });

});

/* =========================================================
| INTERSECTION ANIMATION
========================================================= */

const observer = new IntersectionObserver(entries => {

    entries.forEach(entry => {

        if(entry.isIntersecting){

            entry.target.classList.add('show-card');
        }
    });

},{

    threshold:0.2
});

document.querySelectorAll('.stats-card')
.forEach(card => {

    observer.observe(card);
});

</script>
<script>

    /* =========================================================
| FILTER FUNCTIONALITY
========================================================= */

const filterButtons =
document.querySelectorAll('.gallery-filter');

const galleryItems =
document.querySelectorAll('.gallery-item');

filterButtons.forEach(button => {

    button.addEventListener('click', () => {

        document
        .querySelector('.gallery-filter.active')
        .classList.remove('active');

        button.classList.add('active');

        const filter =
            button.getAttribute('data-filter');

        galleryItems.forEach(item => {

            const category =
                item.getAttribute('data-category');

            if(filter === 'all' || category === filter){

                item.style.display = 'block';

                setTimeout(() => {

                    item.style.opacity = '1';

                    item.style.transform =
                        'scale(1)';

                },100);

            }else{

                item.style.opacity = '0';

                item.style.transform =
                    'scale(.8)';

                setTimeout(() => {

                    item.style.display = 'none';

                },300);
            }
        });

    });

});

/* =========================================================
| LIGHTBOX
========================================================= */

document.querySelectorAll('.gallery-popup')
.forEach(link => {

    link.addEventListener('click', e => {

        e.preventDefault();

        const src =
            link.getAttribute('href');

        const overlay =
            document.createElement('div');

        overlay.className =
            'gallery-lightbox';

        overlay.innerHTML = `
            <div class="gallery-lightbox-content">
                <img src="${src}">
                <span class="gallery-close">
                    &times;
                </span>
            </div>
        `;

        document.body.appendChild(overlay);

        overlay.addEventListener('click', () => {

            overlay.remove();
        });
    });

});

/* =========================================================
| SCROLL REVEAL
========================================================= */

const galleryObserver =
new IntersectionObserver(entries => {

    entries.forEach(entry => {

        if(entry.isIntersecting){

            entry.target.classList.add('show-gallery');
        }
    });

},{
    threshold:.2
});

galleryItems.forEach(item => {

    galleryObserver.observe(item);
});
</script>

<script>

    /* =========================================================
| NOTICE PAUSE ON TAB HIDDEN
========================================================= */

document.addEventListener("visibilitychange", () => {

    const track = document.querySelector('.notice-track');

    if(!track) return;

    if(document.hidden){

        track.style.animationPlayState = 'paused';

    }else{

        track.style.animationPlayState = 'running';
    }
});

/* =========================================================
| TOUCH DRAG SUPPORT
========================================================= */

const marquee = document.querySelector('.notice-marquee');

if(marquee){

    let isDown = false;

    let startX;

    let scrollLeft;

    marquee.addEventListener('mousedown', e => {

        isDown = true;

        startX = e.pageX - marquee.offsetLeft;

        scrollLeft = marquee.scrollLeft;
    });

    marquee.addEventListener('mouseleave', () => {

        isDown = false;
    });

    marquee.addEventListener('mouseup', () => {

        isDown = false;
    });

    marquee.addEventListener('mousemove', e => {

        if(!isDown) return;

        e.preventDefault();

        const x = e.pageX - marquee.offsetLeft;

        const walk = (x - startX) * 2;

        marquee.scrollLeft = scrollLeft - walk;
    });
}
</script>

<script>

    /* =========================================================
| FEATURE CARD INTERACTION
========================================================= */

document.querySelectorAll('.feature-card')
.forEach(card => {

    card.addEventListener('mousemove', e => {

        const rect = card.getBoundingClientRect();

        const x = e.clientX - rect.left;

        const y = e.clientY - rect.top;

        card.style.transform =
            `
            perspective(1000px)
            rotateX(${(y - rect.height / 2) / 25}deg)
            rotateY(${-(x - rect.width / 2) / 25}deg)
            translateY(-10px)
            `;
    });

    card.addEventListener('mouseleave', () => {

        card.style.transform =
            `
            perspective(1000px)
            rotateX(0)
            rotateY(0)
            translateY(0)
            `;
    });

});

/* =========================================================
| INTERSECTION ANIMATION
========================================================= */

const featureObserver = new IntersectionObserver(entries => {

    entries.forEach(entry => {

        if(entry.isIntersecting){

            entry.target.classList.add('show-feature');
        }
    });

},{

    threshold:0.2
});

document.querySelectorAll('.feature-card')
.forEach(card => {

    featureObserver.observe(card);
});
</script>


@endsection