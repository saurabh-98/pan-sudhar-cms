@if($popup)

@php

$user = auth()->user();

$retailer = null;

$referralLink = '';

if($user){

    $retailer = DB::table('retailers')
        ->where('user_id',$user->id)
        ->first();

    if($retailer){

        $referralLink = route('retailer.register',[
            'ref'=>$retailer->referral_code
        ]);

    }

}

@endphp

<link rel="stylesheet"
      href="{{ asset('assets/css/referral-popup.css') }}">

<div class="modal fade"
     id="popupModal"
     tabindex="-1"
     data-bs-backdrop="static">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content referral-popup">

            {{-- ===========================
                Header
            ============================ --}}

            <div class="popup-header">

                <button
                    type="button"
                    class="popup-close-btn"
                    id="popupCloseBtn">

                    ✕

                </button>

                <div class="d-flex align-items-center">

                    <div
                        class="gift-icon"
                        id="giftIcon">

                        🎁

                    </div>

                    <div>

                        <span class="offer-badge">

                            REFER & EARN

                        </span>

                        <h3 class="popup-title">

                            {{ $popup->title }}

                        </h3>

                        <p class="popup-subtitle">

                            Invite retailers and earn wallet rewards instantly.

                        </p>

                    </div>

                </div>

            </div>

            <div class="popup-body">

         


            @if(auth()->check() && $retailer)

                {{-- Referral Code --}}

                <div class="referral-code-card">

                    <span>Your Referral Code</span>

                    <h2>{{ $retailer->referral_code }}</h2>

                    <small>
                        Share this code or your referral link with new retailers.
                    </small>

                </div>

                {{-- Countdown --}}

                <div class="countdown-strip" id="countdownStrip">

                    ⏳ Offer Ends In

                    <span class="unit" id="cdHours">24</span>h

                    <span class="unit" id="cdMinutes">00</span>m

                    <span class="unit" id="cdSeconds">00</span>s

                </div>

                {{-- Referral Link --}}

                <div class="copy-link-row">

                    <input
                        type="text"
                        id="referralLinkInput"
                        value="{{ $referralLink }}"
                        readonly>

                    <button
                        type="button"
                        class="copy-link-btn"
                        id="copyLinkBtn">

                        📋 Copy

                    </button>

                </div>

                {{-- QR Code --}}

                <div class="text-center mt-4">

                    <img
                        src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($referralLink) }}"
                        class="img-fluid rounded"
                        width="180">

                </div>

                {{-- Share Buttons --}}

                <div class="share-buttons mt-4">

                    <a
                        href="https://wa.me/?text={{ urlencode('Join PAN Sudhar Portal using my referral link: '.$referralLink) }}"
                        target="_blank"
                        class="btn btn-success">

                        <i class="fab fa-whatsapp"></i>

                        WhatsApp

                    </a>

                    <a
                        href="https://t.me/share/url?url={{ urlencode($referralLink) }}"
                        target="_blank"
                        class="btn btn-info">

                        <i class="fab fa-telegram"></i>

                        Telegram

                    </a>

                    <a
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}"
                        target="_blank"
                        class="btn btn-primary">

                        <i class="fab fa-facebook"></i>

                        Facebook

                    </a>

                </div>

            @else

                <div class="alert alert-primary text-center">

                    <h4 class="mb-3">

                        🎉 Refer a Retailer & Earn ₹100

                    </h4>

                    <p>

                        Login to your retailer account to get your personal referral
                        link and start earning rewards.

                    </p>

                    <a
                        href="{{ route('retailer.login') }}"
                        class="btn btn-primary">

                        Login Now

                    </a>

                </div>

            @endif

   

    {!! $popup->description !!}

</div>

{{-- ===========================================
    Popup Footer
============================================ --}}

<div class="popup-footer">

    <button
        type="button"
        class="btn btn-popup btn-popup-secondary"
        id="popupMaybeLaterBtn">

        Maybe Later

    </button>

    <div class="d-flex gap-2 flex-wrap">

       
        <button
            type="button"
            id="copyReferralBtn"
            class="btn btn-warning">

            <i class="fas fa-copy"></i>

            Copy Link

        </button>

        <a
            href="https://wa.me/?text={{ urlencode('Join PAN Sudhar Portal using my referral link: '.$referralLink) }}"
            target="_blank"
            class="btn btn-success">

            <i class="fab fa-whatsapp"></i>

            Share

        </a>

    </div>

</div>

</div>

</div>

</div>

<div id="confettiLayer"></div>

<script src="{{ asset('assets/js/referral-popup.js') }}"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

    const popupKey = "popup_{{ $popup->id }}";

    const today = new Date().toDateString();

    const modalEl = document.getElementById('popupModal');

    if(modalEl){

        const modal = new bootstrap.Modal(modalEl);

        @if($popup->show_once_per_day)

            if(localStorage.getItem(popupKey) == today){

                modal.show();

                localStorage.setItem(
                    popupKey,
                    today
                );

            }

        @else

            modal.show();

        @endif

    }

});
</script>

@endif