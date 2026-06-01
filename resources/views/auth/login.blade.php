@extends('layout.auth')

@section('content')

<!-- =========================================================
| ADVANCED SCHOOL LOGIN
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

                    <i class="fa-solid fa-id-card"></i>

                    PAN Sudhar Portal

                </span>

                <!-- TITLE -->
                <h1>

                    Welcome To
                    <span>PAN Sudhar Portal</span>

                </h1>

                <!-- SUBTITLE -->
                <p>

                    Manage PAN Correction Applications,
                    Document Verification, Executive Assignment,
                    Application Tracking, Approval Workflow,
                    Department Operations and Status Monitoring
                    through one secure digital platform.

                </p>

                <!-- STATS -->
                <div class="sg-auth-stats">

                    <div class="sg-auth-stat">

                        <h3>

                            10,000+

                        </h3>

                        <span>

                            Applications

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            250+

                        </h3>

                        <span>

                            Executives

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            24×7

                        </h3>

                        <span>

                            Tracking

                        </span>

                    </div>

                </div>

                <!-- FEATURES -->
                <div class="mt-4">

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            PAN Correction Application Management

                        </span>

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Document Verification & Approval Workflow

                        </span>

                    </div>

                    <div class="d-flex align-items-center mb-3">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Executive Assignment & Tracking

                        </span>

                    </div>

                    <div class="d-flex align-items-center">

                        <i class="fa-solid fa-circle-check me-2"></i>

                        <span>

                            Real-Time Status Monitoring

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

                    🪪

                </div>

                <!-- TITLE -->
                <h2 class="sg-login-title">

                    PAN Correction Department Login

                </h2>

                <p class="sg-login-subtitle">

                    Access PAN Sudhar Department Dashboard

                </p>

                <!-- FORM -->
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
                            required
                            class="sg-input-field"
                            placeholder=" "
                            autocomplete="username">

                        <label>

                            Department Email Address

                        </label>

                    </div>

                    <!-- PASSWORD -->
                    <div class="sg-input-group">

                        <span class="sg-input-icon">

                            <i class="fa-solid fa-lock"></i>

                        </span>

                        <input type="password"
                            id="password"
                            name="password"
                            required
                            class="sg-input-field"
                            placeholder=" "
                            autocomplete="current-password">

                        <label>

                            Department Password

                        </label>

                        <button type="button"
                                class="sg-password-toggle">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                    <!-- OPTIONS -->
                    <div class="sg-login-options">

                        <label class="sg-remember">

                            <input type="checkbox"
                                name="remember">

                            <span>

                                Remember Me

                            </span>

                        </label>

                        <a href="{{ route('password.request') }}"
                        class="sg-forgot-link">

                            Forgot Password?

                        </a>

                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                            class="sg-login-btn">

                        <i class="fa-solid fa-right-to-bracket"></i>

                        Login To Department Panel

                    </button>

                </form>

                <!-- FOOTER -->
                <div class="sg-login-footer">

                    <p>

                        Secure PAN Sudhar Department Portal

                    </p>

                    <small>

                        PAN Correction Application Management &
                        Verification System

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<!-- PARTICLES -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>

/* =========================================================
| PARTICLES
========================================================= */

particlesJS("particles-js", {

    particles: {

        number: {

            value: 45
        },

        size: {

            value: 3
        },

        move: {

            speed: 2
        },

        line_linked: {

            enable: true
        }
    }
});

/* =========================================================
| PASSWORD TOGGLE
========================================================= */

document
.querySelector(".sg-password-toggle")
?.addEventListener("click", function(){

    let input =
    document.getElementById("password");

    const icon =
    this.querySelector("i");

    if(input.type === "password"){

        input.type = "text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

    }else{

        input.type = "password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");
    }
});

/* =========================================================
| DARK MODE
========================================================= */

function toggleTheme(){

    document.body.classList.toggle("dark-mode");
}

</script>

@endsection