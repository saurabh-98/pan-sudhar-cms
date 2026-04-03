<!-- 🔙 FLOATING BACK BUTTON -->
<button onclick="handleBack()" class="btn-back-floating">
    ⬅ Back
</button>

<style>
.btn-back-floating {
    position: fixed !important;
    top: 100px;   /* adjust if header exists */
    right: 30px;
    z-index: 9999;

    background: linear-gradient(135deg, #ff5a00, #ff8c42);
    color: #fff;

    border: none;
    border-radius: 50px;

    padding: 10px 18px;
    font-weight: 600;

    box-shadow: 0 8px 20px rgba(0,0,0,0.15);

    transition: all 0.3s ease;
}

.btn-back-floating:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}

/* MOBILE FIX */
@media(max-width:768px){
    .btn-back-floating{
        top:80px;
        right:15px;
        padding:8px 14px;
        font-size:14px;
    }
}
</style>

<script>
function handleBack() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.history.back();
    } else {
        window.location.href = "{{ route('customer.dashboard') }}";
    }
}
</script>