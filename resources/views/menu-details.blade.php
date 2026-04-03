@extends('layout.app')

@section('content')

<div class="pd-page">

<div class="pd-container">

    <!-- LEFT IMAGE SLIDER -->
    <div class="pd-left">
        <div class="pd-image-slider">

            <div class="slider-wrapper" id="sliderWrapper">

                <!-- MAIN IMAGE -->
                <img src="{{ asset('storage/'.$product->image) }}" class="slide">

                <!-- EXTRA (duplicate or future gallery) -->
                <img src="{{ asset('storage/'.$product->image) }}" class="slide">
                <img src="{{ asset('storage/'.$product->image) }}" class="slide">

            </div>

            <!-- ARROWS -->
            <button class="slide-btn left" onclick="moveSlide(-1)">‹</button>
            <button class="slide-btn right" onclick="moveSlide(1)">›</button>

            <!-- BADGE -->
            <span class="pd-badge {{ $product->type == 'veg' ? 'veg':'nonveg' }}">
                {{ strtoupper($product->type) }}
            </span>

        </div>
    </div>

    <!-- MODAL -->
    <div id="imageModal" class="img-modal">
        <span class="close-btn">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>

    <!-- RIGHT SIDE -->
    <div class="pd-right">

        <div class="pd-card-box">

            <h1 class="pd-title">{{ $product->name }}</h1>

            <div class="pd-rating">⭐ {{ $product->rating ?? '4.5' }}</div>

            <div class="pd-price">₹{{ $product->price }}</div>

            <!-- TABS -->
            <div class="pd-tabs">
                <button class="pd-tab active" onclick="openTab(event, 'desc')">Description</button>
                <button class="pd-tab" onclick="openTab(event, 'spec')">Specifications</button>
            </div>

            <!-- DESCRIPTION -->
            <div id="desc" class="pd-tab-content active">
                @if($product->description)
                    <p class="pd-desc-text">{!! $product->description !!}</p>
                @else
                    <p class="text-muted">No description available.</p>
                @endif
            </div>

            <!-- SPECIFICATIONS -->
            <div id="spec" class="pd-tab-content">
                @if($product->specifications)
                    <div class="pd-spec-list">
                        @foreach(explode('|', $product->specifications) as $spec)
                            <div class="pd-spec-item">🔹 {{ trim($spec) }}</div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No specifications available.</p>
                @endif
                <hr>
            </div>

            <!-- QTY -->
            <div class="pd-qty">
                <button onclick="changeQty(-1)">−</button>
                <input type="text" id="qty" value="1">
                <button onclick="changeQty(1)">+</button>
            </div>

            <!-- ADD TO CART -->
            <button class="pd-add addToCartBtn"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}">
                <span class="btn-text">Add to Cart</span>
            </button>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<style>

/* 🔥 SLIDER */
.pd-image-slider {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
}

.slider-wrapper {
    display: flex;
    transition: transform 0.5s ease;
}

.slide {
    min-width: 100%;
    cursor: pointer;
}

/* arrows */
.slide-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: #fff;
    border: none;
    font-size: 24px;
    padding: 5px 10px;
    cursor: pointer;
}
.slide-btn.left { left: 10px; }
.slide-btn.right { right: 10px; }

/* modal */
.img-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    background: rgba(0,0,0,0.9);
    width: 100%;
    height: 100%;
}
.modal-content {
    margin: auto;
    display: block;
    max-width: 80%;
    margin-top: 5%;
}
.close-btn {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 30px;
    cursor: pointer;
}

</style>

<script>

// TAB
function openTab(evt, tabId){
    document.querySelectorAll('.pd-tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.pd-tab').forEach(el => el.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    evt.currentTarget.classList.add('active');
}

// QTY
function changeQty(val){
    let qty = document.getElementById('qty');
    let newVal = parseInt(qty.value) + val;
    if(newVal >= 1) qty.value = newVal;
}

// 🔥 SLIDER LOGIC
let currentSlide = 0;
const wrapper = document.getElementById('sliderWrapper');
const slides = document.querySelectorAll('.slide');

function moveSlide(direction){
    currentSlide += direction;

    if(currentSlide < 0) currentSlide = slides.length - 1;
    if(currentSlide >= slides.length) currentSlide = 0;

    wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
}

// AUTO PLAY
setInterval(() => {
    moveSlide(1);
}, 3000);

// CLICK FULLSCREEN
$('.slide').click(function(){
    $('#imageModal').show();
    $('#modalImg').attr('src', $(this).attr('src'));
});

$('.close-btn').click(function(){
    $('#imageModal').hide();
});

$(document).on('click', '.addToCartBtn', function () {

    let btn = $(this);
    let textEl = btn.find('.btn-text');

    if (!window.isLoggedIn) {
        Swal.fire('Login Required', 'Please login first', 'warning')
        .then(() => window.location.href = "/login");
        return;
    }

    let originalText = textEl.html();

    btn.prop('disabled', true);
    textEl.html("Adding...");

    $.post('/cart/add', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        menu_id: btn.data('id')
    })

    .done(function () {
        textEl.html("✔ Added");

        if(typeof toggleCart === "function") toggleCart();

        setTimeout(() => {
            textEl.html(originalText);
            btn.prop('disabled', false);
        }, 1200);
    })

    .fail(function () {
        textEl.html("❌ Error");

        setTimeout(() => {
            textEl.html(originalText);
            btn.prop('disabled', false);
        }, 1200);
    });

});


</script>

@endsection