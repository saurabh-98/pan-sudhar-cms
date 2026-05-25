@extends('layout.app')

@section('content')

{{-- =========================================================
| TOASTR CSS
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

{{-- =========================================================
| FONT AWESOME
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="parent-login-wrapper">

    {{-- BACKGROUND EFFECTS --}}
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container-fluid">

        <div class="parent-login-card">

            <div class="row g-0 h-100">

                {{-- =========================================================
                | LEFT PANEL
                ========================================================= --}}
                <div class="col-lg-5">

                    <div class="parent-left-panel">

                        <div class="overlay-layer"></div>

                        <div class="left-content">

                            {{-- LOGO --}}
                            <div class="school-logo">

                                <img src="{{ asset('logo.png') }}"
                                     alt="Logo">

                            </div>

                            {{-- TITLE --}}
                            <h1>

                                Retailer Portal

                            </h1>

                            <p>

                                Manage PAN Card, Aadhaar services,
                                customer applications, corrections,
                                document uploads, and service tracking
                                from one secure digital dashboard.

                            </p>

                            {{-- FEATURES --}}
                            <div class="feature-list">

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        🪪

                                    </div>

                                    <div>

                                        <h5>

                                            PAN Card Services

                                        </h5>

                                        <span>

                                            New PAN & correction services

                                        </span>

                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        🔐

                                    </div>

                                    <div>

                                        <h5>

                                            Aadhaar Assistance

                                        </h5>

                                        <span>

                                            Aadhaar update & verification

                                        </span>

                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        📂

                                    </div>

                                    <div>

                                        <h5>

                                            Application Tracking

                                        </h5>

                                        <span>

                                            Track customer applications

                                        </span>

                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        ⚡

                                    </div>

                                    <div>

                                        <h5>

                                            Fast Processing

                                        </h5>

                                        <span>

                                            Quick and secure services

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- =========================================================
                | RIGHT PANEL
                ========================================================= --}}
                <div class="col-lg-7">

                    <div class="parent-right-panel">

                        {{-- LOGIN HEADER --}}
                        <div class="login-header">

                            <div class="login-icon">

                                🏪

                            </div>

                            <h2>

                                Retailer Login

                            </h2>

                            <p>

                                Access your retailer dashboard securely

                            </p>

                        </div>

                        {{-- =========================================================
                        | LOGIN FORM
                        ========================================================= --}}
                        <form id="retailerLoginForm">

                            @csrf

                            {{-- EMAIL --}}
                            <div class="form-group-modern">

                                <label>

                                    Retailer Email / Mobile Number

                                </label>

                                <div class="input-box">

                                    <i class="fa-solid fa-user"></i>

                                    <input
                                        type="text"
                                        name="email"
                                        id="email"
                                        placeholder="Enter Retailer Email or Mobile"
                                        autocomplete="username"
                                        required
                                    >

                                </div>

                                <span class="text-danger error-email"></span>

                            </div>

                            {{-- PASSWORD --}}
                            <div class="form-group-modern">

                                <label>

                                    Password

                                </label>

                                <div class="input-box">

                                    <i class="fa-solid fa-lock"></i>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        placeholder="Enter Password"
                                        autocomplete="current-password"
                                        required
                                    >

                                    <span class="toggle-password">

                                        <i class="fa-solid fa-eye"></i>

                                    </span>

                                </div>

                                <span class="text-danger error-password"></span>

                            </div>

                            {{-- EXTRA --}}
                            <div class="extra-options">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember">

                                    <label
                                        class="form-check-label"
                                        for="remember">

                                        Remember Me

                                    </label>

                                </div>

                                <a href="">

                                    Forgot Password?

                                </a>

                            </div>

                            {{-- GOOGLE CAPTCHA --}}
                            <div class="form-group-modern mb-4">

                                <label>

                                    Verify You Are Human

                                    <span class="text-danger">*</span>

                                </label>

                                <div class="g-recaptcha"
                                     data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                                </div>

                                <span class="text-danger error-captcha"></span>

                            </div>

                            {{-- BUTTON --}}
                            <button
                                type="submit"
                                class="btn-login-parent"
                                id="loginBtn">

                                <i class="fa-solid fa-right-to-bracket me-2"></i>

                                Login To Dashboard

                            </button>

                        </form>

                        {{-- FOOTER --}}
                        <div class="login-footer">

                            © {{ date('Y') }} PAN & Aadhaar Suvidha Portal

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

{{-- =========================================================
| JQUERY
========================================================= --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

{{-- =========================================================
| TOASTR
========================================================= --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | TOASTR SETTINGS
    |--------------------------------------------------------------------------
    */

    toastr.options = {

        closeButton: true,

        progressBar: true,

        newestOnTop: true,

        positionClass: "toast-top-right",

        preventDuplicates: true,

        timeOut: "3000"
    };

    /*
    |--------------------------------------------------------------------------
    | PASSWORD TOGGLE
    |--------------------------------------------------------------------------
    */

    $('.toggle-password').click(function(){

        let input = $('#password');

        let icon = $(this).find('i');

        if(input.attr('type') === 'password'){

            input.attr('type', 'text');

            icon.removeClass('fa-eye')
                .addClass('fa-eye-slash');

        }else{

            input.attr('type', 'password');

            icon.removeClass('fa-eye-slash')
                .addClass('fa-eye');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#retailerLoginForm').on('submit', function(e){

        e.preventDefault();

        $('.text-danger').html('');

        let captcha = grecaptcha.getResponse();

        if(captcha.length === 0){

            $('.error-captcha').html(
                'Please verify captcha.'
            );

            toastr.error(
                'Please verify you are human.'
            );

            return false;
        }

        toastr.info(
            'Authenticating your credentials...',
            'Please Wait'
        );

        let btn = $('#loginBtn');

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Logging in...
        `);

        $.ajax({

            url: "{{ route('retailer.login.submit') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                email: $('#email').val(),

                password: $('#password').val(),

                remember: $('#remember').is(':checked') ? 1 : 0,

                'g-recaptcha-response': captcha
            },

            success: function(response){

                toastr.success(
                    response.message || 'Login Successful'
                );

                btn.html(`
                    <i class="fa-solid fa-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function(){

                    window.location.href =
                    response.redirect
                    || "{{ route('retailer.dashboard') }}";

                }, 1500);
            },

            error: function(xhr){

                btn.prop('disabled', false);

                btn.html(`
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login To Dashboard
                `);

                grecaptcha.reset();

                if(xhr.status === 422){

                    let errors =
                    xhr.responseJSON.errors;

                    $.each(errors, function(key, value){

                        $('.error-' + key)
                        .html(value[0]);

                    });

                    toastr.error(
                        'Please fill all required fields'
                    );
                }

                else if(xhr.status === 401){

                    toastr.error(
                        xhr.responseJSON.message
                        || 'Invalid credentials'
                    );
                }

                else{

                    toastr.error(
                        'Something went wrong'
                    );
                }
            }
        });

    });
});

</script>

@endsection