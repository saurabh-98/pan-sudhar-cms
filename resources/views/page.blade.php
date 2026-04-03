@extends('layout.app')

@section('content')

<style>

/* ===== BANNER ===== */
.page-banner {
    background: linear-gradient(135deg, #ff5a00, #ff8c42);
    padding: 60px 20px;
    text-align: center;
    color: #fff;
    position: relative;
}

.page-banner h1 {
    font-size: 32px;
    font-weight: 700;
}

/* ===== BREADCRUMB ===== */
.breadcrumb {
    font-size: 14px;
    margin-bottom: 20px;
    color: #888;
}

.breadcrumb a {
    color: #ff5a00;
    text-decoration: none;
    font-weight: 500;
}

.breadcrumb span {
    margin: 0 5px;
}

/* ===== CONTENT BOX ===== */
.content-box {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    line-height: 1.8;
    font-size: 15px;
}

/* TYPOGRAPHY */
.content-box h1,
.content-box h2,
.content-box h3 {
    margin-top: 20px;
    font-weight: 600;
}

.content-box p {
    margin-bottom: 15px;
    color: #555;
}

.content-box ul {
    padding-left: 20px;
}

.content-box img {
    max-width: 100%;
    border-radius: 10px;
    margin: 15px 0;
}

/* ===== SECTION ===== */
.page-content {
    padding: 40px 0;
    background: #f8f9fa;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .page-banner h1 {
        font-size: 24px;
    }

    .content-box {
        padding: 20px;
    }
}

</style>


<!-- BANNER -->
<section class="page-banner">
    <h1>{{ $page->title }}</h1>
</section>

<!-- CONTENT -->
<section class="page-content">
    <div class="container">

        <!-- BREADCRUMB -->
        <div class="breadcrumb">
            <a href="{{ route('home') }}">🏠 Home</a> 
            <span>/</span> 
            <span>{{ $page->title }}</span>
        </div>

        <!-- PAGE CONTENT -->
        <div class="content-box">

            {!! $page->content !!}

        </div>

    </div>
</section>

@endsection