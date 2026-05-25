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

                    <i class="fa-solid fa-graduation-cap"></i>

                    Smart School ERP Platform

                </span>

                <!-- TITLE -->
                <h1>

                    Welcome To
                    <span>School ERP</span>

                </h1>

                <!-- SUBTITLE -->
                <p>

                    Manage admissions, students, departments,
                    teachers, academics, reports, attendance,
                    and digital learning in one modern platform.

                </p>

                <!-- STATS -->
                <div class="sg-auth-stats">

                    <div class="sg-auth-stat">

                        <h3>

                            5000+

                        </h3>

                        <span>

                            Students

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            120+

                        </h3>

                        <span>

                            Faculty

                        </span>

                    </div>

                    <div class="sg-auth-stat">

                        <h3>

                            15+

                        </h3>

                        <span>

                            Departments

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

                    🎓

                </div>

                <!-- TITLE -->
                <h2 class="sg-login-title">

                    Department Login

                </h2>

                <p class="sg-login-subtitle">

                    Access your school management dashboard

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
                               placeholder=" ">

                        <label>

                            Email Address

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
                               placeholder=" ">

                        <label>

                            Password

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

                        Login To Dashboard

                    </button>

                </form>

                <!-- FOOTER -->
                <div class="sg-login-footer">

                    <p>

                        Secure School ERP Login System

                    </p>

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