@extends('layout.app')

@section('content')

<section class="gallery-view-wrapper">

    <!-- BACKGROUND GLOW -->

    <div class="gallery-glow gallery-glow-1"></div>
    <div class="gallery-glow gallery-glow-2"></div>

    <div class="container">

        <!-- HEADER -->

        <div class="gallery-header">

            <span class="gallery-badge">

                <i class="fa-solid fa-image"></i>

                SCHOOL GALLERY

            </span>

            <h1>

                Explore Our
                <span>Campus Moments</span>

            </h1>

            <p>

                Discover student activities, school events,
                celebrations, classroom moments, and campus life.

            </p>

        </div>

        <!-- GALLERY GRID -->

        <div class="row g-4 sg-gallery-grid">

            @forelse($gallery ?? [] as $index => $g)

            <div class="col-xl-3 col-lg-4 col-md-6 col-6 sg-gallery-item">

                <div class="sg-gallery-card">

                    <!-- IMAGE -->

                    <div class="sg-gallery-image">

                        <img src="{{ asset('uploads/gallery/'.$g->file) }}"
                             alt="Gallery Image">

                    </div>

                    <!-- OVERLAY -->

                    <div class="sg-gallery-overlay">

                        <div class="sg-gallery-content">

                            <span class="sg-gallery-category">

                                School Gallery

                            </span>

                            <h5>

                                School Activity

                            </h5>

                            <div class="sg-gallery-actions">

                                <!-- VIEW -->

                                <a href="{{ asset('uploads/gallery/'.$g->file) }}"
                                   class="sg-gallery-btn sg-gallery-popup">

                                    <i class="fa-solid fa-expand"></i>

                                </a>

                                <!-- DOWNLOAD -->

                                <a href="{{ asset('uploads/gallery/'.$g->file) }}"
                                   download
                                   class="sg-gallery-btn">

                                    <i class="fa-solid fa-download"></i>

                                </a>

                            </div>

                        </div>

                    </div>

                    <!-- NUMBER -->

                    <span class="sg-gallery-number">

                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}

                    </span>

                </div>

            </div>

            @empty

            <div class="col-12">

                <div class="gallery-empty">

                    <i class="fa-solid fa-image"></i>

                    <h3>

                        No Gallery Found

                    </h3>

                    <p>

                        Gallery images will appear here soon.

                    </p>

                </div>

            </div>

            @endforelse

        </div>

    </div>

</section>

@endsection

@section('scripts')

<!-- GLIGHTBOX -->

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>

const lightbox = GLightbox({

    selector: '.sg-gallery-popup'

});
</script>

@endsection