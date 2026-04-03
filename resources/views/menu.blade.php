@extends('layout.app')

@php use Illuminate\Support\Str; @endphp

@section('content')

<!-- HERO -->
<section class="menu-v2-banner">
    <h1>Our Menu</h1>
</section>

<!-- FILTER -->
<div class="menu-v2-filter container mt-3">
    <input type="text" id="searchInput" placeholder="Search food..." class="form-control">

    <select id="typeFilter" class="form-select">
        <option value="">All</option>
        <option value="veg">Veg</option>
        <option value="non-veg">Non-Veg</option>
    </select>

    <select id="priceFilter" class="form-select">
        <option value="">All Price</option>
        <option value="low">Below ₹200</option>
        <option value="mid">₹200 - ₹400</option>
        <option value="high">Above ₹400</option>
    </select>
</div>

<!-- CATEGORY -->
<div class="menu-v2-tabs">
    @foreach($categories as $key => $category)
        <button class="menu-v2-tab {{ $key == 0 ? 'active' : '' }}"
            onclick="showCategory({{ $category->id }}, this)">
            {{ $category->name }}
        </button>
    @endforeach
</div>

<!-- MENU -->
<section class="menu-v2-section">
<div class="container">

@foreach($categories as $key => $category)

<div class="menu-v2-category"
     id="cat-{{ $category->id }}"
     style="{{ $key != 0 ? 'display:none;' : '' }}">

    <h2 class="menu-v2-title">{{ $category->name }}</h2>

    <div class="row g-4">

    @foreach($category->menus as $item)

    <div class="col-lg-6 menu-v2-item"
         data-name="{{ strtolower($item->name) }}"
         data-type="{{ strtolower($item->type) }}"
         data-price="{{ $item->price }}">

        <div class="menu-v2-card">

            <!-- IMAGE -->
            <div class="menu-v2-img">
                <img 
                src="{{ Str::startsWith($item->image, 'http') 
                        ? $item->image 
                        : asset('storage/'.$item->image) }}"
                alt="{{ $item->name }}">

                <span class="menu-v2-badge {{ strtolower($item->type ?? 'veg') == 'veg' ? 'veg' : 'nonveg' }}">
                    {{ strtoupper($item->type ?? 'veg') }}
                </span>
            </div>

            <!-- CONTENT -->
            <div class="menu-v2-info">

                <h4>{{ $item->name }}</h4>

                <p class="menu-v2-price">₹{{ $item->price }}</p>

                <div class="menu-v2-actions">

                    <a href="{{ route('menu.detail', $item->id) }}" class="menu-v2-details">
                        Details
                    </a>

                    <button 
                        class="menu-v2-add addToCartBtn"
                        data-id="{{ $item->id }}"
                        data-name="{{ $item->name }}">
                        
                        <span class="btn-text">🛒 Add</span>
                    </button>

                </div>

            </div>

        </div>

    </div>

    @endforeach

    </div>
</div>

@endforeach

<div class="no-result text-center mt-4" style="display:none;">
    <h5>No items found 😔</h5>
</div>

</div>
</section>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// ================= ADD TO CART =================
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


// ================= CATEGORY SWITCH =================
function showCategory(id, el){

    document.querySelectorAll('.menu-v2-category')
        .forEach(cat => cat.style.display = 'none');

    document.getElementById('cat-' + id).style.display = 'block';

    document.querySelectorAll('.menu-v2-tab')
        .forEach(btn => btn.classList.remove('active'));

    if(el){
        el.classList.add('active');
    }

    applyFilters(); // 🔥 important
}


// ================= MAIN FILTER FUNCTION =================
function applyFilters(){

    let search = document.getElementById('searchInput').value.toLowerCase();
    let type = document.getElementById('typeFilter').value;
    let price = document.getElementById('priceFilter').value;

    let items = document.querySelectorAll('.menu-v2-item');
    let found = false;

    items.forEach(item => {

        let name = item.dataset.name;
        let itemType = item.dataset.type;
        let itemPrice = parseInt(item.dataset.price);

        let match = true;

        // 🔍 SEARCH
        if(search && !name.includes(search)){
            match = false;
        }

        // 🥗 TYPE
        if(type && itemType !== type){
            match = false;
        }

        // 💰 PRICE
        if(price === 'low' && itemPrice >= 200) match = false;
        if(price === 'mid' && (itemPrice < 200 || itemPrice > 400)) match = false;
        if(price === 'high' && itemPrice <= 400) match = false;

        // APPLY
        if(match){
            item.style.display = 'block';
            found = true;
        } else {
            item.style.display = 'none';
        }

    });

    // ❌ NO RESULT
    document.querySelector('.no-result').style.display = found ? "none" : "block";
}


// ================= EVENTS =================

// SEARCH
document.getElementById('searchInput').addEventListener('keyup', applyFilters);

// TYPE FILTER
document.getElementById('typeFilter').addEventListener('change', applyFilters);

// PRICE FILTER
document.getElementById('priceFilter').addEventListener('change', applyFilters);


// ================= AUTO LOAD =================
document.addEventListener("DOMContentLoaded", function(){

    let params = new URLSearchParams(window.location.search);

    let search = params.get("search");
    let category = params.get("category");

    if(search){
        document.getElementById('searchInput').value = search;
    }

    if(category){
        let btn = document.querySelector(`.menu-v2-tab[onclick*="${category}"]`);
        showCategory(category, btn);
    }

    applyFilters(); // 🔥 IMPORTANT

});

</script>

@endsection