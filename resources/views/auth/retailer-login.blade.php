@extends('layout.app')

@php

$popup = getActivePopup('login');

@endphp

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<div class="retailer-login-page">

    <!--======================================================
    BACKGROUND
    =======================================================-->

    <div id="particles-js"></div>

    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>

    <div class="login-container">

        <!--==================================================
        LEFT PANEL
        ===================================================-->

        <div class="login-left">

            <div class="left-content">

                <span class="portal-badge">

                    <i class="fa-solid fa-store"></i>

                    Retailer Portal

                </span>

                <h1>

                    Welcome Back,

                    <span>Retailer</span>

                </h1>

                <p>

                    Access your retailer dashboard securely to manage
                    PAN Services, Aadhaar Services, Wallet Recharge,
                    Commission Reports, Transactions and Customer
                    Applications anytime from anywhere.

                </p>

                <!--==========================
                STATS
                ==========================-->

                <div class="stats-wrapper">

                    <div class="stat-card">

                        <i class="fa-solid fa-users"></i>

                        <h3>15K+</h3>

                        <span>Retailers</span>

                    </div>

                    <div class="stat-card">

                        <i class="fa-solid fa-wallet"></i>

                        <h3>₹5Cr+</h3>

                        <span>Transactions</span>

                    </div>

                    <div class="stat-card">

                        <i class="fa-solid fa-shield-halved"></i>

                        <h3>99.99%</h3>

                        <span>Secure</span>

                    </div>

                </div>

                <!--==========================
                FEATURES
                ==========================-->

                <div class="feature-list">

                    <div class="feature-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>

                            Instant Wallet Recharge

                        </span>

                    </div>

                    <div class="feature-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>

                            PAN & Aadhaar Services

                        </span>

                    </div>

                    <div class="feature-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>

                            Live Application Tracking

                        </span>

                    </div>

                    <div class="feature-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>

                            Daily Commission Reports

                        </span>

                    </div>

                    <div class="feature-item">

                        <i class="fa-solid fa-circle-check"></i>

                        <span>

                            24×7 Retailer Dashboard

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!--==================================================
        RIGHT PANEL
        ===================================================-->

        <div class="login-right">

            <div class="login-card">

                <!--==========================
                LOGO
                ==========================-->

                <div class="login-logo">

                    <div class="logo-circle">

                        <i class="fa-solid fa-store"></i>

                    </div>

                </div>

                <h2>

                    Retailer Login

                </h2>

                <p class="login-subtitle">

                    Sign in to access your Retailer Dashboard

                </p>

                                <!--=========================================
                LOGIN FORM
                ==========================================-->

                <form id="retailerLoginForm">

                    @csrf

                    <!--=========================
                    EMAIL / MOBILE
                    =========================-->

                    <div class="form-group-modern">

                        <div class="input-box">

                            <span class="input-icon">

                                <i class="fa-solid fa-user"></i>

                            </span>

                            <input
                                type="text"
                                id="email"
                                name="email"
                                placeholder=" "
                                autocomplete="username"
                                required
                            >

                            <label>

                                Email Address / Mobile Number

                            </label>

                        </div>

                        <small class="text-danger error-email"></small>

                    </div>

                    <!--=========================
                    PASSWORD
                    =========================-->

                    <div class="form-group-modern">

                        <div class="input-box">

                            <span class="input-icon">

                                <i class="fa-solid fa-lock"></i>

                            </span>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder=" "
                                autocomplete="current-password"
                                required
                            >

                            <label>

                                Password

                            </label>

                            <button
                                type="button"
                                class="toggle-password">

                                <i class="fa-solid fa-eye"></i>

                            </button>

                        </div>

                        <small class="text-danger error-password"></small>

                    </div>

                    <!--=========================
                    OPTIONS
                    =========================-->

                    <div class="login-options">

                        <label class="remember-box">

                            <input
                                type="checkbox"
                                id="remember"
                                name="remember"
                            >

                            <span>

                                Remember Me

                            </span>

                        </label>

                        @if(Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="forgot-link">

                                Forgot Password?

                            </a>

                        @endif

                    </div>

                   

                    <!--=========================
                    LOGIN BUTTON
                    =========================-->

                    <button
                        type="submit"
                        class="btn-login"
                        id="loginBtn">

                        <span class="btn-text">

                            <i class="fa-solid fa-right-to-bracket me-2"></i>

                            Login To Dashboard

                        </span>

                    </button>

                </form>

                <!--=========================================
                QUICK FEATURES
                ==========================================-->

                <div class="quick-info">

                    <div class="quick-item">

                        <i class="fa-solid fa-wallet"></i>

                        <span>

                            Wallet

                        </span>

                    </div>

                    <div class="quick-item">

                        <i class="fa-solid fa-id-card"></i>

                        <span>

                            PAN

                        </span>

                    </div>

                    <div class="quick-item">

                        <i class="fa-solid fa-address-card"></i>

                        <span>

                            Aadhaar

                        </span>

                    </div>

                    <div class="quick-item">

                        <i class="fa-solid fa-chart-line"></i>

                        <span>

                            Reports

                        </span>

                    </div>

                </div>

                <!--=========================================
                FOOTER
                ==========================================-->

                <div class="login-footer">

                    <div class="footer-security">

                        <i class="fa-solid fa-shield-halved"></i>

                        SSL Secured Login

                    </div>

                    <p>

                        © {{ date('Y') }}

                        PAN & Aadhaar Suvidha Portal

                    </p>

                    <small>

                        Secure • Reliable • Fast

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@include('components.popup',[
    'popup' => $popup
])

@endsection

@section('scripts')

<!-- =======================================================
| JQUERY
======================================================= -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- =======================================================
| PARTICLES
======================================================= -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<!-- =======================================================
| SWEET ALERT
======================================================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- =======================================================
| TOASTR
======================================================= -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- =======================================================
| GOOGLE CAPTCHA
======================================================= -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>

$(function(){

    /*======================================================
    | TOASTR
    ======================================================*/

    toastr.options = {

        closeButton:true,

        progressBar:true,

        newestOnTop:true,

        preventDuplicates:true,

        positionClass:"toast-top-right",

        timeOut:3000

    };

    /*======================================================
    | PARTICLES
    ======================================================*/

    particlesJS("particles-js",{

        particles:{

            number:{ value:55 },

            color:{ value:"#ffffff" },

            shape:{ type:"circle" },

            opacity:{ value:.30 },

            size:{ value:3 },

            line_linked:{

                enable:true,

                distance:150,

                color:"#ffffff",

                opacity:.20,

                width:1

            },

            move:{

                enable:true,

                speed:2

            }

        }

    });

    /*======================================================
    | PASSWORD TOGGLE
    ======================================================*/

    $('.toggle-password').click(function(){

        let input=$("#password");

        let icon=$(this).find("i");

        if(input.attr("type")=="password"){

            input.attr("type","text");

            icon.removeClass("fa-eye")
                .addClass("fa-eye-slash");

        }else{

            input.attr("type","password");

            icon.removeClass("fa-eye-slash")
                .addClass("fa-eye");

        }

    });

    /*======================================================
    | AJAX LOGIN
    ======================================================*/

    $("#retailerLoginForm").submit(function(e){

        e.preventDefault();


        Swal.fire({

            title:"Retailer Login",

            text:"Proceed to Retailer Dashboard?",

            icon:"question",

            showCancelButton:true,

            confirmButtonText:"Login",

            cancelButtonText:"Cancel",

            confirmButtonColor:"#2563eb",

            cancelButtonColor:"#ef4444"

        }).then((result)=>{

            if(result.isConfirmed){

                loginRetailer();

            }

        });

    });

    /*======================================================
    | LOGIN FUNCTION
    ======================================================*/

    function loginRetailer(){

        let btn=$("#loginBtn");

        btn.prop("disabled",true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Authenticating...
        `);

        Swal.fire({

            title:"Please Wait",

            html:"Verifying credentials...",

            allowOutsideClick:false,

            didOpen:()=>{

                Swal.showLoading();

            }

        });

        $.ajax({

            url:"{{ route('retailer.login.submit') }}",

            method:"POST",

            data:{

                _token:"{{ csrf_token() }}",

                email:$("#email").val(),

                password:$("#password").val(),

                remember:$("#remember").is(":checked")?1:0

               

            },

            success:function(response){

                Swal.fire({

                    icon:"success",

                    title:"Welcome!",

                    text:response.message || "Login Successful",

                    timer:1800,

                    showConfirmButton:true

                });

                btn.html(`
                    <i class="fa-solid fa-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function(){

                    window.location.href=response.redirect
                    || "{{ route('retailer.dashboard') }}";

                },1800);

            },

            error:function(xhr){

                btn.prop("disabled",false);

                btn.html(`
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login To Dashboard
                `);

                

                Swal.close();

                if(xhr.status==422){

                    $.each(xhr.responseJSON.errors,function(key,value){

                        $(".error-"+key).html(value[0]);

                    });

                    toastr.error("Please correct the highlighted fields.");

                }

                else if(xhr.status==401){

                    toastr.error(xhr.responseJSON.message);

                }

                else{

                    toastr.error("Unexpected server error.");

                }

            }

        });

    }

});

/*==========================================
| POPUP
==========================================*/

@if($popup)

setTimeout(function(){

    let popupKey = "popup_{{ $popup->id }}";

    let today = new Date().toDateString();

    @if($popup->show_once_per_day)

        if(localStorage.getItem(popupKey) != today){

            let popup = new bootstrap.Modal(

                document.getElementById('popupModal')

            );

            popup.show();

            localStorage.setItem(
                popupKey,
                today
            );

        }

    @else

        new bootstrap.Modal(

            document.getElementById('popupModal')

        ).show();

    @endif

},1000);

@endif

</script>



@endsection