@extends('layout.app')

@section('content')

<!-- =========================================================
| ADMIN LOGIN
========================================================= -->

<div class="sg-auth-wrapper">

    <!-- BACKGROUND GLOW -->
    <div class="sg-auth-glow sg-auth-glow-1"></div>
    <div class="sg-auth-glow sg-auth-glow-2"></div>

    <!-- THEME TOGGLE -->
    <button class="sg-theme-toggle"
            onclick="toggleTheme()">

        <i class="fa-solid fa-moon"></i>

    </button>

    <!-- PARTICLES -->
    <div id="particles-js"></div>

    <div class="sg-auth-container">

        <!-- =====================================================
        | LEFT SIDE
        ===================================================== -->

        <div class="sg-auth-left">

            <div class="sg-auth-overlay">

                <!-- BADGE -->
                <span class="sg-auth-badge">

                    <i class="fa-solid fa-user-shield"></i>

                    Administrator Panel

                </span>

                <!-- TITLE -->
                <h1>

                    Welcome To

                    <span>Admin Control Center</span>

                </h1>

                <!-- SUBTITLE -->
                <p>

                    Securely manage retailers, distributors,
                    executives, PAN applications, Aadhaar services,
                    wallet transactions, reports, user permissions,
                    and system settings from one centralized
                    administration dashboard.

                </p>

                <!-- STATS -->
                <div class="sg-auth-stats">

                    <div class="sg-auth-stat">

                        <h3>

                            15K+

                        </h3>

                        <span>

                            Applications

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            2K+

                        </h3>

                        <span>

                            Retailers

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            99.9%

                        </h3>

                        <span>

                            Uptime

                        </span>

                    </div>

                </div>

                <!-- FEATURES -->

                <div class="mt-4">

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            User & Role Management

                        </span>

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Retailer & Distributor Monitoring

                        </span>

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Wallet & Transaction Management

                        </span>

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Reports & Analytics Dashboard

                        </span>

                    </div>

                    <div class="d-flex align-items-center">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            System Configuration & Security

                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- =====================================================
        | RIGHT SIDE
        ===================================================== -->

        <div class="sg-auth-right">

            <div class="sg-login-card">

                <!-- LOGO -->

                <div class="sg-login-logo">

                    <i class="fa-solid fa-user-shield"></i>

                </div>

                <!-- TITLE -->

                <h2 class="sg-login-title">

                    Administrator Login

                </h2>

                <p class="sg-login-subtitle">

                    Sign in to access the Administration Dashboard

                </p>
                                <!-- =====================================================
                | LOGIN FORM
                ===================================================== -->

                <form method="POST"
                      action="{{ route('login.post') }}">

                    @csrf

                    <!-- ROLE -->
                    <input type="hidden"
                           name="role"
                           value="admin">

                    <!-- EMAIL -->
                    <div class="sg-input-group">

                        <span class="sg-input-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </span>

                        <input type="email"
                               name="email"
                               class="sg-input-field @error('email') is-invalid @enderror"
                               placeholder=" "
                               value="{{ old('email') }}"
                               autocomplete="username"
                               required
                               autofocus>

                        <label>

                            Administrator Email Address

                        </label>

                        @error('email')

                            <small class="text-danger d-block mt-2">

                                {{ $message }}

                            </small>

                        @enderror

                    </div>

                    <!-- PASSWORD -->
                    <div class="sg-input-group">

                        <span class="sg-input-icon">

                            <i class="fa-solid fa-lock"></i>

                        </span>

                        <input type="password"
                               id="password"
                               name="password"
                               class="sg-input-field @error('password') is-invalid @enderror"
                               placeholder=" "
                               autocomplete="current-password"
                               required>

                        <label>

                            Administrator Password

                        </label>

                        <!-- PASSWORD TOGGLE -->

                        <button type="button"
                                class="sg-password-toggle">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                        @error('password')

                            <small class="text-danger d-block mt-2">

                                {{ $message }}

                            </small>

                        @enderror

                    </div>

                    <!-- REMEMBER / FORGOT -->

                    <div class="sg-login-options">

                        <label class="sg-remember">

                            <input type="checkbox"
                                   name="remember"
                                   {{ old('remember') ? 'checked' : '' }}>

                            <span>

                                Remember Me

                            </span>

                        </label>

                        @if (Route::has('password.request'))

                            <a href="{{ route('password.request') }}"
                               class="sg-forgot-link">

                                Forgot Password?

                            </a>

                        @endif

                    </div>

                    <!-- LOGIN BUTTON -->

                    <button type="submit"
                            class="sg-login-btn">

                        <i class="fa-solid fa-right-to-bracket me-2"></i>

                        Login to Admin Dashboard

                    </button>

                </form>

                <!-- =====================================================
                | FOOTER
                ===================================================== -->

                <div class="sg-login-footer">

                    <p>

                        <i class="fa-solid fa-shield-halved me-2"></i>

                        Secure Administration Portal

                    </p>

                    <small>

                        Authorized administrators only.
                        All login activities are monitored for
                        security and auditing purposes.

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<!-- =========================================================
| PARTICLES JS
========================================================= -->

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<!-- SWEET ALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function () {

    /* =====================================================
    | PARTICLES
    ===================================================== */

    particlesJS("particles-js", {

        particles: {

            number: {
                value: 45
            },

            color: {
                value: "#ffffff"
            },

            shape: {
                type: "circle"
            },

            opacity: {
                value: .35
            },

            size: {
                value: 3
            },

            move: {
                enable: true,
                speed: 2
            },

            line_linked: {

                enable: true,

                color: "#ffffff",

                opacity: .25,

                distance: 150

            }

        }

    });

    /* =====================================================
    | PASSWORD SHOW/HIDE
    ===================================================== */

    $(".sg-password-toggle").click(function () {

        let input = $("#password");

        let icon = $(this).find("i");

        if (input.attr("type") === "password") {

            input.attr("type", "text");

            icon.removeClass("fa-eye");

            icon.addClass("fa-eye-slash");

        } else {

            input.attr("type", "password");

            icon.removeClass("fa-eye-slash");

            icon.addClass("fa-eye");

        }

    });

    /* =====================================================
    | LOGIN BUTTON LOADING
    ===================================================== */

    $("form").submit(function(e){

        e.preventDefault();

        let form = this;

        Swal.fire({

            title: "Administrator Login",

            text: "Do you want to login to the Admin Dashboard?",

            icon: "question",

            showCancelButton: true,

            confirmButtonText: "Yes, Login",

            cancelButtonText: "Cancel",

            confirmButtonColor: "#0d6efd",

            cancelButtonColor: "#6c757d"

        }).then((result)=>{

            if(result.isConfirmed){

                $(".sg-login-btn")
                    .prop("disabled", true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Authenticating...');

                form.submit();

            }

        });

    });
});



/* =====================================================
| DARK MODE
===================================================== */

function toggleTheme() {

    document.body.classList.toggle("dark-mode");

    const icon = document.querySelector(".sg-theme-toggle i");

    if(document.body.classList.contains("dark-mode")){

        icon.classList.remove("fa-moon");

        icon.classList.add("fa-sun");

    }else{

        icon.classList.remove("fa-sun");

        icon.classList.add("fa-moon");

    }

}

</script>

<!-- =========================================================
| SWEET ALERT SUCCESS
========================================================= -->

@if(session('success'))

<script>

Swal.fire({

    icon: 'success',

    title: 'Success',

    text: '{{ session("success") }}',

    confirmButtonColor: '#0d6efd',

    timer: 2500,

    timerProgressBar: true

});

</script>

@endif

<!-- =========================================================
| SWEET ALERT ERROR
========================================================= -->

@if(session('error'))

<script>

Swal.fire({

    icon: 'error',

    title: 'Login Failed',

    text: '{{ session("error") }}',

    confirmButtonColor: '#dc3545'

});

</script>

@endif

<!-- =========================================================
| VALIDATION ERROR
========================================================= -->

@if($errors->any())

<script>

Swal.fire({

    icon: 'warning',

    title: 'Validation Error',

    html: `
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    `,

    confirmButtonColor: '#f39c12'

});

</script>

@endif

@endsection