@extends('layout.app')

@section('content')

<style>
.checkout-card {
    background:#fff;
    border-radius:14px;
    padding:18px;
    margin-bottom:15px;
}
.qty-box { display:flex; gap:10px; align-items:center; }
.qty-btn {
    width:35px;height:35px;border:none;border-radius:8px;
    background:#f1f1f1;cursor:pointer;
}
.qty-btn:hover { background:#ff5a00;color:#fff; }
.summary-card { background:#fff;border-radius:16px; }
.total-highlight { font-size:22px;font-weight:bold; }
.apply-btn {
    background:#ff5a00;color:#fff;border:none;
    padding:10px 18px;border-radius:10px;
}
.address-card { background:#fff;border-radius:14px; }
</style>

<div class="container mt-4">

    <h3 class="fw-bold mb-4">🧾 Checkout</h3>

    <div class="row">

        <!-- LEFT -->
        <div class="col-md-8">

            <div id="checkoutItems"></div>

            <!-- ORDER DETAILS -->
            <div class="card p-3 shadow-sm mt-3">
                <h5>📞 Order Details</h5>

                <label class="mt-2">Mobile Number</label>
                <input type="text" id="mobile"
                       value="{{ auth()->user()->mobile ?? '' }}"
                       class="form-control">

                <label class="mt-2">Order Type</label>
                <select id="order_type" class="form-control">
                    <option value="outside">🚚 Delivery</option>
                    <option value="inside">🍽 Dine-in</option>
                </select>

                <div id="table_wrap" class="d-none">
                    <label class="mt-2">Table Number</label>
                    <input type="text" id="table_number" class="form-control">
                </div>
            </div>

            <!-- ADDRESS -->
            <div class="card address-card p-3 shadow-sm mt-3" id="address_section">
                <h5>📍 Delivery Address</h5>
                <textarea id="address" class="form-control mt-2"
                          rows="2" placeholder="Enter full address"></textarea>
            </div>

            <!-- COUPON -->
            <div class="card p-3 shadow-sm mt-3">
                <h5>🎟 Apply Coupon</h5>
                <div class="d-flex gap-2 mt-2">
                    <input type="text" id="coupon" class="form-control">
                    <button id="applyCouponBtn" class="apply-btn">Apply</button>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-md-4">
            <div class="summary-card sticky-top p-4 shadow-sm" style="top:20px;">

                <h5 class="fw-bold mb-3">Order Summary</h5>

                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>₹<span id="subtotal">0</span></span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>GST</span>
                    <span>₹<span id="tax">0</span></span>
                </div>

                <div class="d-flex justify-content-between">
                    <span>Delivery</span>
                    <span>₹<span id="delivery">0</span></span>
                </div>

                <div class="d-flex justify-content-between text-success">
                    <span>Discount</span>
                    <span>₹<span id="discount">0</span></span>
                </div>

                <hr>

                <div class="d-flex justify-content-between total-highlight">
                    <span>Total</span>
                    <span>₹<span id="total">0</span></span>
                </div>

                <button id="placeOrder" class="btn btn-success w-100 mt-3 fw-bold">
                    🚀 Place Order
                </button>

            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let checkoutData = [];
let appliedCoupon = ''; // ✅ STORE COUPON GLOBALLY

/* INIT */
$(document).ready(function(){
    checkoutLoad();

    // Order type toggle
    $('#order_type').on('change', function(){
        if($(this).val() === 'inside'){
            $('#table_wrap').removeClass('d-none');
            $('#address_section').hide();
        } else {
            $('#table_wrap').addClass('d-none');
            $('#address_section').show();
        }
    });

    // ✅ APPLY COUPON (FIXED)
    $('#applyCouponBtn').click(function(){
        let code = $('#coupon').val().trim();

        if(!code){
            Swal.fire('Error','Enter coupon','error');
            return;
        }

        appliedCoupon = code; // 🔥 SAVE COUPON
        checkoutLoad();
    });

});

/* LOAD DATA */
function checkoutLoad() {

    $.post('/checkout/calculate', {
        _token: "{{ csrf_token() }}",
        coupon: appliedCoupon // ✅ ALWAYS USE STORED COUPON
    })
    .done(function(res){

        console.log('API:', res);

        if(!res.success){
            Swal.fire('Error', res.message || 'Something went wrong','error');
            return;
        }

        // ✅ KEEP INPUT SYNCED
        $('#coupon').val(appliedCoupon);

        checkoutData = res.items || [];
        renderItems(checkoutData);
        updateSummary(res);

        // ✅ OPTIONAL SUCCESS MESSAGE
        if(res.discount > 0 && appliedCoupon){
            console.log('Coupon Applied:', appliedCoupon);
        }

    });
}

/* RENDER ITEMS */
function renderItems(items) {
    let html = '';

    items.forEach(item => {
        let price = parseFloat(item.menu.price);
        let qty   = parseInt(item.qty);

        html += `
        <div class="checkout-card d-flex justify-content-between">
            <div>${item.menu.name}</div>

            <div class="qty-box">
                <button class="qty-btn" onclick="updateQty(${item.id}, ${qty-1})" ${qty<=1?'disabled':''}>−</button>
                <span>${qty}</span>
                <button class="qty-btn" onclick="updateQty(${item.id}, ${qty+1})">+</button>
            </div>

            <div>₹${(price*qty).toFixed(2)}</div>
        </div>`;
    });

    $('#checkoutItems').html(html);
}

/* UPDATE SUMMARY */
function updateSummary(res) {
    $('#subtotal').text(Number(res.subtotal).toFixed(2));
    $('#tax').text(Number(res.tax).toFixed(2));
    $('#delivery').text(Number(res.delivery).toFixed(2));
    $('#discount').text(Number(res.discount).toFixed(2));
    $('#total').text(Number(res.total).toFixed(2));
}

/* PLACE ORDER */
$('#placeOrder').click(function(){

    let mobile = $('#mobile').val().trim();
    let type = $('#order_type').val();
    let table = $('#table_number').val().trim();
    let address = $('#address').val().trim();

    if(!mobile){
        Swal.fire('Error','Enter mobile number','error');
        return;
    }

    if(type === 'inside' && !table){
        Swal.fire('Error','Enter table number','error');
        return;
    }

    if(type === 'outside' && !address){
        Swal.fire('Error','Enter address','error');
        return;
    }

    $.post('/cart/place-order', {
        _token: "{{ csrf_token() }}",
        mobile,
        order_type: type,
        table_number: table,
        address,
        coupon: appliedCoupon // ✅ USE SAME COUPON
    })
    .done(function(res){

        if(!res.success){
            Swal.fire('Error', res.message,'error');
            return;
        }

        Swal.fire('Success','Order placed 🎉','success');

        setTimeout(() => {
            window.location.href = res.redirect || '/';
        }, 1500);
    });

});

</script>

@endsection