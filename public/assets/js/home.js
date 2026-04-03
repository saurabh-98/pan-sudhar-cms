/* =========================
   SAFE INIT
========================= */

let cartRequestLock = false;
let itemLocks = {};
let cartLoading = false;

$(document).ready(function () {

    /* ✅ SweetAlert fallback */
    if (typeof Swal === "undefined") {
        window.Swal = {
            fire: function (title, text) {
                alert((title || '') + "\n" + (text || ''));
            }
        };
        console.warn("SweetAlert not loaded → fallback alert used");
    }

    /* ❌ Prevent invalid GET */
    $(document).on('click', 'a[href="/cart/add"]', function(e){
        e.preventDefault();
    });

    /* =========================
       ADD TO CART (FIXED 🔥)
    ========================= */

    $(document).off('click', '.tv2-add').on('click', '.tv2-add', function (e) {

        e.preventDefault();

        let btn = $(this);
        let menuId = btn.data('id');
        let originalText = btn.html();

        if (cartRequestLock || itemLocks[menuId]) return;

        if (!window.isLoggedIn) {
            Swal.fire('Login Required', 'Please login first', 'warning')
            .then(() => window.location.href = "/login");
            return;
        }

        cartRequestLock = true;
        itemLocks[menuId] = true;

        btn.prop('disabled', true).html("Adding...");

        $.post('/cart/add', {
            _token: getCSRF(),
            menu_id: menuId
        })

        .done(function (res) {

            if (!res.success) {
                Swal.fire('Error', res.message || 'Failed', 'error');
                return;
            }

            btn.html("✔ Added");
            showToast("Added to cart");

            toggleCart(); // open sidebar

        })

        .fail(function () {
            Swal.fire('Error', 'Add failed', 'error');
        })

        .always(function(){
            cartRequestLock = false;
            itemLocks[menuId] = false;

            setTimeout(() => {
                btn.html(originalText).prop('disabled', false);
            }, 700);
        });

    });

    /* FILTER */
    $('#searchInput').on('keyup', filterMenu);
    $('#typeFilter').on('change', filterMenu);
    $('#priceFilter').on('change', filterMenu);

    updateCartCount();
});


/* =========================
   HELPERS
========================= */

function getCSRF(){
    return $('meta[name="csrf-token"]').attr('content') || '';
}


/* =========================
   CART SIDEBAR
========================= */

function toggleCart() {
    $('#cartSidebar').toggleClass('active');
    loadCart();
}

document.querySelectorAll('.apply-btn').forEach(btn => {
    btn.addEventListener('click', function() {

        Swal.fire({
            icon: 'success',
            title: 'Offer Applied!',
            text: 'Your discount has been successfully applied 🎉',
            confirmButtonColor: '#ff5722',
            confirmButtonText: 'Awesome!'
        });

    });
});

setTimeout(() => {
    document.getElementById('offerPopup').style.display = 'flex';
}, 1500);

document.addEventListener("DOMContentLoaded", function () {

    if (!localStorage.getItem("popup_shown")) {

        let popup = document.getElementById("offerPopup");

        if (popup) {
            setTimeout(() => {
                popup.style.display = "flex";
            }, 1200);
        }

        localStorage.setItem("popup_shown", true);
    }

});

function closePopup(){
    document.getElementById('offerPopup').style.display = 'none';
}

/* =========================
   LOAD CART (FIXED)
========================= */

function loadCart() {

    if (cartLoading) return;
    cartLoading = true;

    $('#cartItems').html("Loading...");

    $.get('/cart/list')

    .done(function (data) {

        console.log("Cart Data:", data);

        let html = '';
        let total = 0;

        if (!data || !data.length) {
            html = `<p class="text-center">Cart is empty 🛒</p>`;
        }

        data.forEach(item => {

            let name  = item.menu?.name ?? 'Item';
            let price = parseFloat(item.menu?.price ?? 0);
            let qty   = item.qty ?? 1;

            let itemTotal = price * qty;
            total += itemTotal;

           html += `
                <div class="cart-card">

                    <div class="cart-left">
                        <h6 class="cart-title">${name}</h6>
                        <p class="cart-price">₹${price.toFixed(2)}</p>
                    </div>

                    <div class="cart-center">
                        <div class="qty-box">
                            <button onclick="updateQty(${item.id}, ${qty - 1})">−</button>
                            <span>${qty}</span>
                            <button onclick="updateQty(${item.id}, ${qty + 1})">+</button>
                        </div>
                    </div>

                    <div class="cart-right">
                        <button onclick="removeItem(${item.id})" class="remove-btn">✕</button>
                    </div>

                </div>
            `;
        });

        $('#cartItems').html(html);
        $('#cartTotal').text('₹' + total.toFixed(2));

        updateCartCount(data);

        // ✅ Disable checkout if empty
        if (!data || data.length === 0) {
            $('.checkout-btn').prop('disabled', true);
        } else {
            $('.checkout-btn').prop('disabled', false);
        }

    })

    .always(function(){
        cartLoading = false;
    });
}


/* =========================
   UPDATE QTY
========================= */

function updateQty(id, qty) {

    $.post('/cart/update', {
        _token: getCSRF(),
        id: id,
        qty: qty   // ✅ send actual quantity
    })
    .done(function () {
        loadCart();
        updateCartCount();
    });
}

/* =========================
   REMOVE ITEM
========================= */

function removeItem(id) {

    $.post(`/cart/remove/${id}`, {
        _token: getCSRF()
    })

    .done(function () {
        showToast("Removed");
        loadCart();
    });
}


/* =========================
   PLACE ORDER (QUICK ORDER)
========================= */

function placeOrder() {

    if (!window.isLoggedIn) {
        window.location.href = "/login";
        return;
    }

    $.post('/cart/place-order', {
        _token: getCSRF()
    })

    .done(function (res) {

        if (res.success) {
            Swal.fire('Success', 'Order Placed 🎉', 'success');

            setTimeout(() => {
                window.location.href = res.redirect;
            }, 1200);
        }
    });
}


/* =========================
   GO TO CHECKOUT ✅ NEW
========================= */


function goToCheckout() {

    $.get('/cart/list')

    .done(function (data) {

        if (!data || data.length === 0) {
            Swal.fire('Cart Empty', 'Please add items first 🛒', 'warning');
            return;
        }

        // Close sidebar
        $('#cartSidebar').removeClass('active');

        setTimeout(() => {
            window.location.href = "/checkout";
        }, 200);
    });
}


/* =========================
   CART COUNT
========================= */

function updateCartCount(data = null) {

    if (data) {
        $('#cartCount').text(data.length || 0);
        return;
    }

    $.get('/cart/list')
    .done(res => $('#cartCount').text(res.length || 0));
}


/* =========================
   FILTER
========================= */

function filterMenu() {

    let search = $('#searchInput').val().toLowerCase();
    let type = $('#typeFilter').val();
    let price = $('#priceFilter').val();

    let visible = 0;

    $('.food-item').each(function () {

        let item = $(this);
        let name = item.data('name');
        let itemType = item.data('type');
        let itemPrice = parseFloat(item.data('price'));

        let show = true;

        if (search && !name.includes(search)) show = false;
        if (type && itemType !== type) show = false;

        if (price === 'low' && itemPrice >= 200) show = false;
        if (price === 'mid' && (itemPrice < 200 || itemPrice > 400)) show = false;
        if (price === 'high' && itemPrice <= 400) show = false;

        item.toggle(show);

        if (show) visible++;
    });

    $('.no-result').toggle(visible === 0);
}



//  HERO SEARCH FUNCTION

function heroSearchTrigger(){

    let value = document.getElementById('heroSearch').value.toLowerCase();

    // 🟢 Redirect to menu page with query
    window.location.href = "/menu?search=" + encodeURIComponent(value);
}




/* =========================
   TOAST
========================= */

function showToast(msg) {

    let toast = $('#toast');

    if (!toast.length) {
        $('body').append('<div id="toast" class="toast-msg"></div>');
        toast = $('#toast');
    }

    toast.text(msg).fadeIn();

    setTimeout(() => toast.fadeOut(), 1500);
}