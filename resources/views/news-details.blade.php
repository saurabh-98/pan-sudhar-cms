@extends('layout.app')

@section('content')
<style>

  /* CARD */
.news-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    padding: 15px;
    transition: 0.3s;
}

.news-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

/* READ MORE */
.read-more {
    color: #ff5a00;
    font-weight: 600;
    text-decoration: none;
}

/* RIGHT SIDE */
.news-link {
    display: block;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    color: #333;
    text-decoration: none;
}

/* DETAILS */
.news-detail {
    background: #fff;
    padding: 25px;
    border-radius: 15px;
}
</style>

<div class="container mt-4">

    <div class="news-detail">

        <h2>{{ $news->title }}</h2>

        <p class="text-muted">
            {{ $news->created_at->format('d M Y, h:i A') }}
        </p>

        <img src="{{ asset('storage/'.$news->image) }}"
             class="img-fluid rounded mb-3">

        <div class="news-content">
            {!! $news->description !!}
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-dark mt-3">
            ← Back
        </a>

    </div>

</div>

@endsection