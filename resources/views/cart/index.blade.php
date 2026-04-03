@extends('layout.app')

@section('content')

<style>
/* =========================
   MODERN CART UI
========================= */
.cart-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px;
    border-radius: 14px;
    background: #fff;
    margin-bottom: 15px;
    transition: 0.3s;
}

.cart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.qty-box {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    border: none;
    background: #f1f1f1;
    font-size: 18px;
    cursor: pointer;
}

.qty-btn:hover {
    background: #ff5a00;
    color: #fff;
}

.remove-btn {
    background: #ff4d4f;
    border: none;
    color: #fff;
    padding: 6px 10px;
    border-radius: 8px;
}

.cart-summary {
    background: #fff;
}
</style>

<div class="container mt-4">

    <h3 class="mb-4 fw-bold">🛒 Your Cart</h3>

    <div class="row">

        <!-- CART ITEMS -->
        <div class="col-md-8">
            <div id="cart-page-items-container">
                <p>Loading...</p>
            </div>
        </div>

        <!-- SUMMARY -->
        <div class="col-md-4">
            <div class="cart-summary sticky-top p-4 shadow-sm rounded-4" style="top: 20px;">

                <h5 class="fw-bold mb-4">Order Summary</h5>

                <div class="d-flex justify-content-between">
                    <span>Total Items</span>
                    <strong id="cart-page-item-count">0</strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span>Total</span>
                    <strong id="cart-page-grand-total">₹0</strong>
                </div>

                <hr>

                <a href="/checkout" id="cart-page-checkout-btn"
                   class="btn btn-warning w-100 mb-2 fw-bold">
                   Proceed to Checkout
                </a>


            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let cartData = [];

/* =========================
   INIT
========================= */
$(document).ready(function(){
    cartPageLoad();
});


/* =========================
   LOAD CART
========================= */
function cartPageLoad() {

    $.get('/cart/list')

    .done(function(data){
        cartData = data || [];
        cartPageRender();
    });
}


/* =========================
   RENDER (🔥 INSTANT UI)
========================= */
function cartPageRender() {

    let html = '';
    let total = 0;
    let totalQty = 0;

    if (!cartData.length) {
        html = `<p class="text-center">Cart is empty 🛒</p>`;
    }

    cartData.forEach(item => {

        let price = parseFloat(item.menu.price);
        let qty = parseInt(item.qty);
        let itemTotal = price * qty;

        total += itemTotal;
        totalQty += qty;

        html += `
        <div class="cart-card">

            <div>
                <h6>${item.menu.name}</h6>
                <small>₹${price}</small>
            </div>

            <div class="qty-box">
                <button class="qty-btn"
                    onclick="cartPageUpdateQty(${item.id}, ${qty-1})"
                    ${qty<=1?'disabled':''}>−</button>

                <span>${qty}</span>

                <button class="qty-btn"
                    onclick="cartPageUpdateQty(${item.id}, ${qty+1})">+</button>
            </div>

            <div>
                <strong>₹${itemTotal.toFixed(2)}</strong>
            </div>

            <button class="remove-btn"
                onclick="cartPageRemove(${item.id})">✕</button>

        </div>
        `;
    });

    $('#cart-page-items-container').html(html);
    $('#cart-page-item-count').text(totalQty);
    $('#cart-page-grand-total').text('₹' + total.toFixed(2));
}


/* =========================
   UPDATE QTY (⚡ INSTANT)
========================= */
function cartPageUpdateQty(id, qty) {

    if (qty < 1) return;

    let item = cartData.find(i => i.id == id);
    if (item) item.qty = qty;

    cartPageRender(); // instant update

    $.post('/cart/update', {
        _token: $('meta[name="csrf-token"]').attr('content'),
        id: id,
        qty: qty
    });
}


/* =========================
   REMOVE (⚡ INSTANT)
========================= */
function cartPageRemove(id) {

    Swal.fire({
        title: 'Remove item?',
        icon: 'warning',
        showCancelButton: true
    }).then((result) => {

        if (result.isConfirmed) {

            cartData = cartData.filter(i => i.id !== id);
            cartPageRender();

            $.post('/cart/remove/' + id, {
                _token: $('meta[name="csrf-token"]').attr('content')
            });
        }
    });
}


</script>

@endsection