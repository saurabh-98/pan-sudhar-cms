@extends('layout.app')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="dist-wrapper">

    <!-- Decorative Shapes -->

    <div class="dist-shape dist-shape-1"></div>
    <div class="dist-shape dist-shape-2"></div>

    <div class="container-fluid">

        <div class="dist-card">

            <div class="row g-0 align-items-stretch">

                <!-- ==========================================================
                | LEFT PANEL
                =========================================================== -->

                <div class="col-lg-5">

                    <div class="dist-left">

                        <div class="dist-overlay"></div>

                        <div class="dist-left-content">

                            <!-- Logo -->

                            <div class="dist-logo-wrap">

                                <img src="{{ asset('logo.png') }}"
                                     class="dist-logo"
                                     alt="Logo">

                            </div>

                            <!-- Heading -->

                            <h1 class="dist-left-title">

                                Super Distributor Portal

                            </h1>

                            <!-- Description -->

                            <p class="dist-left-desc">

                                Manage distributors, retailers,
                                wallet transactions, commissions,
                                service requests and monitor your
                                entire business network from one
                                secure dashboard.

                            </p>

                            <!-- Features -->

                            <div class="dist-feature-list">

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">

                                        <i class="fa-solid fa-users-gear"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Distributor Network
                                        </h5>

                                        <span>
                                            Manage distributors &
                                            retailers efficiently
                                        </span>

                                    </div>

                                </div>

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">

                                        <i class="fa-solid fa-wallet"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Commission Wallet
                                        </h5>

                                        <span>
                                            Wallet, commission &
                                            settlement management
                                        </span>

                                    </div>

                                </div>

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">

                                        <i class="fa-solid fa-chart-line"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Business Analytics
                                        </h5>

                                        <span>
                                            Monitor revenue,
                                            retailers and services
                                        </span>

                                    </div>

                                </div>

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">

                                        <i class="fa-solid fa-shield-halved"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Secure Access
                                        </h5>

                                        <span>
                                            Enterprise-grade
                                            authentication &
                                            monitoring
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==========================================================
                | RIGHT PANEL
                =========================================================== -->

                <div class="col-lg-7">

                    <div class="dist-right">

                        <div class="dist-header">

                            <div class="dist-login-icon">

                                <i class="fa-solid fa-crown"></i>

                            </div>

                            <h2 class="dist-title">

                                Super Distributor Login

                            </h2>

                            <p class="dist-subtitle">

                                Securely access your Super Distributor
                                Dashboard to manage distributors,
                                retailers, wallet, commissions and
                                service operations.

                            </p>

                        </div>

                        <form id="superDistributorLoginForm">

                            @csrf

                            <!-- Email Starts -->

                                                        <!-- =====================================
                            | EMAIL
                            ====================================== -->

                            <div class="dist-group">

                                <label class="dist-label">

                                    Email Address

                                </label>

                                <div class="dist-input-wrap">

                                    <i class="fa-solid fa-envelope dist-input-icon"></i>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="dist-input"
                                           placeholder="Enter Registered Email Address"
                                           autocomplete="email">

                                </div>

                                <span class="dist-error error-email"></span>

                            </div>

                            <!-- =====================================
                            | PASSWORD
                            ====================================== -->

                            <div class="dist-group">

                                <label class="dist-label">

                                    Password

                                </label>

                                <div class="dist-input-wrap">

                                    <i class="fa-solid fa-lock dist-input-icon"></i>

                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="dist-input"
                                           placeholder="Enter Your Password"
                                           autocomplete="current-password">

                                    <span class="toggle-password">

                                        <i class="fa-solid fa-eye"></i>

                                    </span>

                                </div>

                                <span class="dist-error error-password"></span>

                            </div>

                            <!-- =====================================
                            | OPTIONS
                            ====================================== -->

                            <div class="dist-options">

                                <div class="form-check">

                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="remember"
                                           name="remember">

                                    <label class="form-check-label"
                                           for="remember">

                                        Remember Me

                                    </label>

                                </div>

                                <a href="#"
                                   class="dist-forgot">

                                    Forgot Password?

                                </a>

                            </div>

                            <!-- =====================================
                            | CAPTCHA
                            ====================================== -->

                            <div class="dist-captcha">

                                <label class="dist-label">

                                    Security Verification

                                </label>

                                @if(app()->environment('production'))
                                    <div class="cf-turnstile"
                                        data-sitekey="{{ config('services.turnstile.site_key') }}">
                                    </div>
                                @endif

                                <span class="dist-error error-captcha"></span>

                            </div>

                            <!-- =====================================
                            | LOGIN BUTTON
                            ====================================== -->

                            <button type="submit"
                                    id="loginBtn"
                                    class="dist-btn">

                                <i class="fa-solid fa-crown me-2"></i>

                                Login To Super Distributor Panel

                            </button>

                        </form>

                        <!-- =====================================
                        | FOOTER
                        ====================================== -->

                        <div class="dist-footer">

                            © {{ date('Y') }}

                            Super Distributor Management Portal

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script
src="https://challenges.cloudflare.com/turnstile/v0/api.js"
async
defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

$(document).ready(function () {

    toastr.options = {
        closeButton: true,
        progressBar: true,
        newestOnTop: true,
        positionClass: "toast-top-right",
        preventDuplicates: true,
        timeOut: 3000
    };

    /*=========================================
    | PASSWORD SHOW / HIDE
    =========================================*/

    $('.toggle-password').click(function () {

        let input = $('#password');
        let icon  = $(this).find('i');

        if (input.attr('type') === 'password') {

            input.attr('type', 'text');

            icon.removeClass('fa-eye')
                .addClass('fa-eye-slash');

        } else {

            input.attr('type', 'password');

            icon.removeClass('fa-eye-slash')
                .addClass('fa-eye');

        }

    });

    /*=========================================
    | SUPER DISTRIBUTOR LOGIN
    =========================================*/

    $('#superDistributorLoginForm').on('submit', function (e) {

        e.preventDefault();

        $('.text-danger').html('');

        @if(app()->environment('production'))

        let captcha = document.querySelector(
            '[name="cf-turnstile-response"]'
        )?.value;

        if (!captcha) {

            $('.error-captcha').html(
                'Please complete verification.'
            );

            toastr.error(
                'Verification required.'
            );

            return false;

        }

        @endif

        toastr.info(
            'Authenticating Super Distributor...',
            'Please Wait'
        );

        let btn = $('#loginBtn');

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Logging In...
        `);

        let captcha = "";

        @if(app()->environment('production'))

        captcha = document.querySelector(
            '[name="cf-turnstile-response"]'
        )?.value;

        @endif

        $.ajax({

            url: "{{ route('super-distributor.login.submit') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                email: $('#email').val(),

                password: $('#password').val(),

                remember: $('#remember').is(':checked') ? 1 : 0,

                "cf-turnstile-response": captcha

            },

            success: function (response) {

                toastr.success(

                    response.message ||

                    'Super Distributor Login Successful'

                );

                btn.html(`
                    <i class="fa-solid fa-circle-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function () {

                    window.location.href =

                        response.redirect ||

                        "{{ route('admin.dashboard') }}";

                }, 1500);

            },

            error: function (xhr) {

                btn.prop('disabled', false);

                btn.html(`
                    <i class="fa-solid fa-crown me-2"></i>
                    Login To Super Distributor Panel
                `);

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        $('.error-' + key).html(value[0]);

                    });

                    toastr.error(
                        'Please fill all required fields.'
                    );

                }
                else if (xhr.status === 401) {

                    toastr.error(

                        xhr.responseJSON.message ||

                        'Invalid Super Distributor credentials.'

                    );

                }
                else if (xhr.status === 403) {

                    toastr.error(

                        xhr.responseJSON.message ||

                        'Unauthorized access.'

                    );

                }
                else {

                    toastr.error(

                        xhr.responseJSON.message ||

                        'Something went wrong. Please try again.'

                    );

                }

            }

        });

    });

});

</script>

<script>

    /*
    |--------------------------------------------------------------------------
    | EXTRA PREMIUM FEATURES
    |--------------------------------------------------------------------------
    */

    $(function () {

        /*
        |--------------------------------------------------------------------------
        | AUTO FOCUS EMAIL
        |--------------------------------------------------------------------------
        */

        $('#email').trigger('focus');


        /*
        |--------------------------------------------------------------------------
        | ENTER KEY LOGIN
        |--------------------------------------------------------------------------
        */

        $('#email, #password').keypress(function (e) {

            if (e.which === 13) {

                $('#loginBtn').trigger('click');

            }

        });


        /*
        |--------------------------------------------------------------------------
        | BUTTON RIPPLE EFFECT
        |--------------------------------------------------------------------------
        */

        $('.dist-btn').on('click', function () {

            $(this).addClass('clicked');

            let btn = $(this);

            setTimeout(function () {

                btn.removeClass('clicked');

            }, 400);

        });


        /*
        |--------------------------------------------------------------------------
        | INPUT ANIMATION
        |--------------------------------------------------------------------------
        */

        $('.dist-input').on('focus', function () {

            $(this)
                .closest('.dist-input-wrap')
                .addClass('active');

        });

        $('.dist-input').on('blur', function () {

            $(this)
                .closest('.dist-input-wrap')
                .removeClass('active');

        });


        /*
        |--------------------------------------------------------------------------
        | DISABLE DOUBLE SUBMIT
        |--------------------------------------------------------------------------
        */

        $('#superDistributorLoginForm').on('submit', function () {

            $('#loginBtn').prop('disabled', true);

        });

    });

</script>

@endsection