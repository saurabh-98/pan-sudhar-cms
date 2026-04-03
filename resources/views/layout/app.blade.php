<!DOCTYPE html>
<html>
<head>
    <title>Foodies</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/campaign.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/news.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/trending.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/product-details.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/offers.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/category.css') }}">

<style>

/* USER DROPDOWN */
.user-dropdown {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.user-name {
    font-weight: 600;
    color: #333;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 35px;
    right: 0;
    background: #fff;
    min-width: 180px;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    padding: 10px;
    z-index: 1000;
}

.dropdown-menu a,
.dropdown-menu button {
    display: block;
    width: 100%;
    padding: 8px 10px;
    border: none;
    background: none;
    text-align: left;
    color: #333;
}

.dropdown-menu a:hover,
.dropdown-menu button:hover {
    background: #f5f5f5;
    border-radius: 6px;
}

.user-dropdown:hover .dropdown-menu {
    display: block;
}

/* POPUP */
.popup-overlay {
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.6);
    display:flex;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

.popup-box {
    background:#fff;
    padding:20px;
    border-radius:16px;
    text-align:center;
    width:320px;
    position:relative;
}

.popup-img {
    width:100%;
    border-radius:10px;
    margin-bottom:10px;
}

.popup-btn {
    background:#ff5a00;
    color:#fff;
    border:none;
    padding:10px 15px;
    border-radius:8px;
}

.popup-close {
    position:absolute;
    top:10px;
    right:15px;
    font-size:20px;
    cursor:pointer;
}

/* USER DROPDOWN */
.user-dropdown {
    position: relative;
    display: inline-block;
}

.user-trigger {
    cursor: pointer;
    background: #ff5722;
    color: #fff;
    padding: 8px 14px;
    border-radius: 25px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.dropdown-menu-user {
    position: absolute;
    top: 120%;
    right: 0;
    background: #fff;
    min-width: 180px;
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    z-index: 9999;
}

.dropdown-menu-user a,
.dropdown-menu-user button {
    padding: 10px 14px;
    text-decoration: none;
    color: #333;
    border: none;
    background: none;
    text-align: left;
    width: 100%;
    cursor: pointer;
}

.dropdown-menu-user a:hover,
.dropdown-menu-user button:hover {
    background: #f5f5f5;
}

.dropdown-divider {
    height: 1px;
    background: #eee;
}

/* SHOW */
.user-dropdown.active .dropdown-menu-user {
    display: flex;
}

</style>

</head>

<body>

<!-- HEADER -->
@include('layout.header')

<div class="main-wrapper">
    @yield('content')
</div>

<!-- FOOTER -->
@include('layout.footer')


<!-- CART SIDEBAR -->
<div id="cartSidebar" class="cart-sidebar">

    <div class="cart-header">
        <h5>Your Cart</h5>
        <span onclick="toggleCart()" style="cursor:pointer;">✖</span>
    </div>

    <div id="cartItems"></div>

    <div class="cart-footer">
        <h5>Total: ₹<span id="cartTotal">0</span></h5>
        <button class="checkout-btn" onclick="goToCheckout()">Checkout</button>
    </div>

</div>


<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script src="{{ asset('assets/js/home.js') }}"></script>

<!-- SWIPER -->
<script>
document.addEventListener("DOMContentLoaded", function () {

    new Swiper(".heroSwiper", {
        loop: true,
        slidesPerView: 1,
        autoplay: { delay: 4000 },
        speed: 800,
        pagination: { el: ".swiper-pagination", clickable: true }
    });

    new Swiper(".campaignSwiper", {
        loop: true,
        slidesPerView: 1,
        autoplay: { delay: 3000 },
        pagination: { el: ".swiper-pagination", clickable: true }
    });

});

document.addEventListener("DOMContentLoaded", function(){

    const dropdown = document.querySelector(".user-dropdown");
    const trigger = document.querySelector(".user-trigger");

    if(trigger){
        trigger.addEventListener("click", function(e){
            e.stopPropagation();
            dropdown.classList.toggle("active");
        });
    }

    document.addEventListener("click", function(){
        dropdown?.classList.remove("active");
    });

});
</script>

@yield('scripts')


<!--  POPUP ONLY ON HOME -->
@if(request()->is('/') && $popup)
<div id="offerPopup" class="popup-overlay" style="display:none;">
    <div class="popup-box">

        <span class="popup-close" onclick="closePopup()">×</span>

        @if($popup->image)
            <img src="{{ asset($popup->image) }}" class="popup-img">
        @endif

        <h4>{{ $popup->title }}</h4>
        <p>{{ $popup->description }}</p>

        <button class="popup-btn">Grab Offer</button>

    </div>
</div>
@endif


<!-- POPUP SCRIPT -->
<script>
$(document).ready(function(){

    let popupShown = sessionStorage.getItem('popupShown');

    if(!popupShown && $('#offerPopup').length){
        setTimeout(()=>{
            $('#offerPopup').fadeIn();
            sessionStorage.setItem('popupShown', true);
        }, 1200);
    }

});

function closePopup(){
    $('#offerPopup').fadeOut();
}
</script>

</body>
</html>