@extends('layout.app')

@php use Illuminate\Support\Str; @endphp

@section('content')

<!-- HERO -->
@if($heroes)
<section class="hero-slider">

    <div class="swiper heroSwiper">

        <div class="swiper-wrapper">

            @foreach($heroes as $hero)

                @php
                   
                    $images = $hero->image ?? [];
                @endphp

                @foreach($images as $img)
                    <div class="swiper-slide"
                        style="background-image: url('{{ asset('storage/'.$img) }}');">
                      
                        
                        <div class="hero-overlay"></div>

                    </div>
                @endforeach

            @endforeach

        </div>

        <!-- ✅ SINGLE CONTENT OUTSIDE LOOP -->
        <div class="hero-content text-center">

            <h1>{{ $heroes[0]->title ?? 'Delicious Food Delivered Fast' }}</h1>
            <p>{{ $heroes[0]->subtitle ?? 'Fresh • Fast • Premium Taste' }}</p>

           <div class="search-box">
                <input type="text" id="heroSearch" placeholder="Search your favorite food...">
                <button onclick="heroSearchTrigger()">Explore</button>
            </div>

            <a href="{{ route('menu') }}" class="btn-main">
                {{ $heroes[0]->button_text ?? 'Explore Menu' }}
            </a>

        </div>

        <div class="swiper-pagination"></div>

    </div>

</section>
@endif

<!-- CATEGORY PILLS -->
@if($categories->count())
<div class="category-wrapper">
    <div class="category-pills">
        @foreach($categories as $cat)
            <a href="{{ route('menu',['category'=>$cat->id]) }}" class="pill">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>
</div>
@endif


<!-- CAMPAIGN -->
@if($campaigns->count())
<section class="campaign-section">

    <div class="swiper campaignSwiper">
                <div class="swiper-wrapper">

                    @foreach($campaigns as $campaign)
                    <div class="swiper-slide">

                        <div class="campaign-slide">

                            <div class="campaign-left">
                                <h5>{{ $campaign->tag }}</h5>
                                <h1>{{ $campaign->title }}</h1>
                                <p>{{ $campaign->description }}</p>

                                <div class="campaign-actions">
                                    <a href="{{ route('menu') }}" class="btn-primary">Order Now</a>
                                    <span class="price-tag">₹{{ $campaign->price }}</span>
                                </div>
                            </div>

                            <div class="campaign-right">
                                <img src="{{ asset('storage/'.$campaign->image) }}">
                            </div>

                        </div>

                </div>
                @endforeach

            </div>

            <div class="swiper-pagination"></div>
    </div>

</section>
@endif

<section class="trending-v2">

    <div class="tv2-wrapper"> <!-- 🔥 CENTER FIX -->

        <div class="tv2-header">
            <h2 class="tv2-title">🔥 Trending Now</h2>
            <a href="{{ route('menu') }}" class="tv2-view-all">View All →</a>
        </div>

        <div class="tv2-grid">
            @foreach($menus as $item)
            <div class="tv2-card">

                <!-- IMAGE -->
                <div class="tv2-img">
                    <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}">

                    <span class="tv2-badge {{ $item->type == 'veg' ? 'veg':'nonveg' }}">
                        {{ strtoupper($item->type) }}
                    </span>
                </div>

                <!-- CONTENT -->
                <div class="tv2-info">

                    <h4 class="tv2-title-text">{{ $item->name }}</h4>

                    <div class="tv2-rating">
                        ⭐ {{ $item->rating ?? '4.5' }}
                    </div>

                    <div class="tv2-bottom">
                        <span class="tv2-price">₹{{ $item->price }}</span>

                        <div class="tv2-actions">
                            <a href="{{ route('menu.detail', $item->id) }}" class="tv2-details">
                                Details
                            </a>

                            <button class="tv2-add tv2-addclick" data-id="{{ $item->id }}">
                                Add
                            </button>
                        </div>
                    </div>

                </div>

            </div>
            @endforeach
        </div>

    </div>

</section>
<!-- DELIVERY (MAKE DYNAMIC OPTIONAL) -->
@if(isset($delivery))
<section class="delivery-section">
    <div class="delivery-left">
        <h5>{{ $delivery->title }}</h5>
        <h1>{{ $delivery->heading }}</h1>
        <p>{{ $delivery->description }}</p>

        <a href="{{ route('menu') }}" class="btn-primary">Order Now</a>
    </div>

    <div class="delivery-right">
        <img src="{{ asset('storage/'.$delivery->image) }}">
    </div>
</section>
@endif


<!-- CHEF SPECIAL -->
@if($chef)
<section class="featured-dish">

    <div class="featured-left">
        <h5>Chef Special</h5>
        <h2>{{ $chef->name }}</h2>
        <p>{{ $chef->description }}</p>

        <div class="price">₹{{ $chef->price }}</div>

        <a href="{{ route('menu') }}" class="btn-primary">Order Now</a>
    </div>

    <div class="featured-right">
        <img src="{{ asset('storage/'.$chef->image) }}">
    </div>

</section>
@endif


<!-- FEATURES -->
@if($features->count())
<section class="why-section">

    <h2 class="section-title">Why Choose Us</h2>

    <div class="why-grid">
        @foreach($features as $item)
        <div class="why-card">
            <div class="icon">{!! $item->icon !!}</div>
            <h4>{{ $item->title }}</h4>
            <p>{!! $item->description !!}</p>
        </div>
        @endforeach
    </div>

</section>
@endif


@if($categories->count())
<section class="category-grid-section">

    <h2 class="section-title">🍽 Explore Menu</h2>

    <div class="category-grid">
        @foreach($categories as $cat)
        <a href="{{ route('menu',['category'=>$cat->id]) }}" class="category-box">

            <div class="category-img">
                <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}">
            </div>

            <div class="category-overlay">
                <h4>{{ $cat->name }}</h4>
                <span class="category-btn">Explore →</span>
            </div>

        </a>
        @endforeach
    </div>

</section>
@endif

@if($news->count())
<section class="news-x">

    <div class="news-x-wrapper">

        <h2 class="news-x-title">📰 News & Highlights</h2>

        <!-- FEATURED -->
        <div class="news-x-featured">

            <img src="{{ asset('storage/'.$news->first()->image) }}" alt="{{ $news->first()->title }}">

            <div class="news-x-featured-overlay">
                <span class="news-x-badge">🔥 Trending</span>

                <h2>{{ $news->first()->title }}</h2>

                <a href="{{ route('news.show',$news->first()->slug) }}" class="news-x-btn">
                    Read Full Story →
                </a>
            </div>

        </div>

        <!-- GRID + SIDEBAR -->
        <div class="news-x-layout">

            <!-- GRID -->
            <div class="news-x-grid">

                @foreach($news->skip(1)->take(3) as $item)

                <a href="{{ route('news.show',$item->slug) }}" class="news-x-card">

                    <!-- IMAGE -->
                    <div class="news-x-img">
                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}">

                        <!-- HOVER -->
                        <div class="news-x-hover">
                            <span>View</span>
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <div class="news-x-content">
                        <h4>{{ $item->title }}</h4>
                        <span class="news-x-read">Read more →</span>
                    </div>

                </a>

                @endforeach

            </div>

            <!-- SIDEBAR -->
            <div class="news-x-sidebar">

                <h4>Latest Updates</h4>

                <ul>
                    @foreach($news->take(5) as $item)
                    <li>
                        <a href="{{ route('news.show',$item->slug) }}">
                            {{ $item->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>

            </div>

        </div>

    </div>

</section>
@endif

<!-- OFFERS -->

@if($offers->count())
<section class="offers-pro">

    <div class="offers-pro-wrapper">

        <h2 class="offers-pro-title">🔥 Exclusive Offers</h2>

        <div class="offers-pro-grid">

            @foreach($offers as $offer)
            <div class="offers-pro-card">

                <!-- IMAGE -->
                <div class="offers-pro-image">

                    <!-- BADGE -->
                    <span class="offers-pro-badge">
                        {{ $offer->type == 'percent'
                            ? $offer->value.'% OFF'
                            : '₹'.$offer->value.' OFF' }}
                    </span>

                    <img src="{{ $offer->image_url }}" alt="{{ $offer->title }}">

                   

                </div>

                <!-- CONTENT -->
                <div class="offers-pro-content">
                    <h4>{{ $offer->title }}</h4>

                    <p class="offers-pro-desc">
                        Save more on your order today!
                    </p>
                </div>

            </div>
            @endforeach

        </div>

    </div>

</section>
@endif

@endsection