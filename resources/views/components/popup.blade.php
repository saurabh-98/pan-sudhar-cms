@if($popup)

<style>
    #popupModal .modal-content{
        border:none;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 20px 60px rgba(0,0,0,.25);
        animation:popupZoom .35s ease;
    }

    @keyframes popupZoom{
        from{
            transform:scale(.8);
            opacity:0;
        }
        to{
            transform:scale(1);
            opacity:1;
        }
    }

    .popup-header{
        background:linear-gradient(135deg,#0d6efd,#6610f2);
        color:#fff;
        padding:25px;
    }

    .popup-title{
        font-size:28px;
        font-weight:700;
        margin-bottom:5px;
    }

    .popup-subtitle{
        opacity:.9;
        font-size:14px;
    }

    .gift-icon{
        width:65px;
        height:65px;
        background:#fff;
        color:#0d6efd;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:32px;
        margin-right:18px;
    }

    .popup-banner{
        width:100%;
        border-radius:12px;
        margin-bottom:20px;
    }

    .reward-box{
        background:#fff8e6;
        border-left:5px solid #ffc107;
        border-radius:12px;
        padding:18px;
        margin-bottom:25px;
    }

    .reward-box h4{
        margin:0;
        color:#ff6600;
        font-weight:700;
    }

    .reward-box p{
        margin-top:6px;
        margin-bottom:0;
    }

    .popup-body{
        padding:30px;
    }

    .popup-body ol{
        padding-left:22px;
        margin-bottom:0;
    }

    .popup-body li{
        margin-bottom:14px;
        line-height:1.8;
        font-size:15px;
        color:#555;
    }

    .popup-body strong{
        color:#0d6efd;
    }

    .popup-footer{
        background:#f8f9fa;
        padding:18px 25px;
        display:flex;
        justify-content:space-between;
        align-items:center;
    }

    .btn-popup{
        border-radius:30px;
        padding:10px 28px;
        font-weight:600;
    }

    .btn-popup-primary{
        background:#0d6efd;
        color:#fff;
        transition:.3s;
    }

    .btn-popup-primary:hover{
        background:#0b5ed7;
        transform:translateY(-2px);
        color:#fff;
    }

    .btn-popup-secondary{
        border:1px solid #ccc;
        background:#fff;
    }

    .offer-badge{
        display:inline-block;
        background:#dc3545;
        color:#fff;
        padding:4px 12px;
        border-radius:50px;
        font-size:12px;
        margin-bottom:10px;
    }

    @media(max-width:768px){

        .popup-header{
            text-align:center;
        }

        .popup-header .d-flex{
            flex-direction:column;
        }

        .gift-icon{
            margin-right:0;
            margin-bottom:15px;
        }

        .popup-footer{
            flex-direction:column;
            gap:12px;
        }

        .btn-popup{
            width:100%;
        }

    }
</style>

<div class="modal fade"
     id="popupModal"
     tabindex="-1"
     data-bs-backdrop="static">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="popup-header">

                <div class="d-flex align-items-center">

                    <div class="gift-icon">

                        🎁

                    </div>

                    <div>

                        <span class="offer-badge">

                            LIMITED TIME OFFER

                        </span>

                        <div class="popup-title">

                            {{ $popup->title }}

                        </div>

                        <div class="popup-subtitle">

                            Invite retailers and earn exciting rewards.

                        </div>

                    </div>

                </div>

            </div>

            <div class="popup-body">

                @if($popup->image)

                    <img src="{{ file_url($popup->image) }}"
                         class="popup-banner">

                @endif

                <div class="reward-box">

                    <h4>

                        💰 Earn ₹100 on Every Successful Referral

                    </h4>

                    <p>

                        Refer your friends today and get your reward directly in your wallet after successful verification.

                    </p>

                </div>

                {!! $popup->description !!}

            </div>

            <div class="popup-footer">

                <button
                    class="btn btn-popup btn-popup-secondary"
                    data-bs-dismiss="modal">

                    Maybe Later

                </button>

                @if($popup->button_link)

                    <a href="{{ $popup->button_link }}"
                       class="btn btn-popup btn-popup-primary">

                        🚀 {{ $popup->button_text }}

                    </a>

                @endif

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    let popupKey = "popup_{{ $popup->id }}";

    let today = new Date().toDateString();

    const modal = new bootstrap.Modal(
        document.getElementById('popupModal')
    );

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

});

</script>

@endif