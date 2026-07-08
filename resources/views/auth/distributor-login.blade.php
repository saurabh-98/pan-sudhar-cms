@extends('layout.app')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="dist-wrapper">

    <div class="dist-shape dist-shape-1"></div>
    <div class="dist-shape dist-shape-2"></div>

    <div class="container-fluid">

        <div class="dist-card">

            <div class="row g-0 align-items-stretch">

                <!-- LEFT SIDE -->
                <div class="col-lg-5">

                    <div class="dist-left">

                        <div class="dist-overlay"></div>

                        <div class="dist-left-content">

                            <div class="dist-logo-wrap">
                                <img src="{{ asset('logo.png') }}"
                                     class="dist-logo"
                                     alt="Logo">
                            </div>

                            <h1 class="dist-left-title">
                                Distributor Portal
                            </h1>

                            <p class="dist-left-desc">
                                Manage retailers, service requests,
                                wallet transactions and monitor
                                business operations from one place.
                            </p>

                            <div class="dist-feature-list">

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">
                                        <i class="fa fa-users"></i>
                                    </div>

                                    <div>
                                        <h5>Retailer Management</h5>
                                        <span>Manage retailer accounts</span>
                                    </div>

                                </div>

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">
                                        <i class="fa fa-wallet"></i>
                                    </div>

                                    <div>
                                        <h5>Wallet Control</h5>
                                        <span>Track wallet transactions</span>
                                    </div>

                                </div>

                                <div class="dist-feature">

                                    <div class="dist-feature-icon">
                                        <i class="fa fa-chart-line"></i>
                                    </div>

                                    <div>
                                        <h5>Business Reports</h5>
                                        <span>View service performance</span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="col-lg-7">

                    <div class="dist-right">

                        <div class="dist-header">

                            <div class="dist-login-icon">
                                <i class="fa fa-user-shield"></i>
                            </div>

                            <h2 class="dist-title">
                                Distributor Login
                            </h2>

                            <p class="dist-subtitle">
                                Access your distributor dashboard securely
                            </p>

                        </div>

                        <form id="distributorLoginForm">

                            @csrf

                            <div class="dist-group">

                                <label class="dist-label">
                                    Email Address
                                </label>

                                <div class="dist-input-wrap">

                                    <i class="fa fa-envelope dist-input-icon"></i>

                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="dist-input"
                                           placeholder="Enter Email Address">

                                </div>

                                <span class="dist-error error-email"></span>

                            </div>

                            <div class="dist-group">

                                <label class="dist-label">
                                    Password
                                </label>

                                <div class="dist-input-wrap">

                                    <i class="fa fa-lock dist-input-icon"></i>

                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="dist-input"
                                           placeholder="Enter Password">

                                    <span class="toggle-password">
                                        <i class="fa fa-eye"></i>
                                    </span>

                                </div>

                                <span class="dist-error error-password"></span>

                            </div>

                            <div class="dist-options">

                                <div class="form-check">

                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="remember">

                                    <label class="form-check-label"
                                           for="remember">
                                        Remember Me
                                    </label>

                                </div>

                                <a href="#" class="dist-forgot">
                                    Forgot Password?
                                </a>

                            </div>

                            <div class="dist-captcha">

                                <label class="dist-label">
                                    Verify You Are Human
                                </label>

                                <div class="g-recaptcha"
                                     data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                </div>

                                <span class="dist-error error-captcha"></span>

                            </div>

                            <button type="submit"
                                    id="loginBtn"
                                    class="dist-btn">

                                <i class="fa fa-right-to-bracket me-2"></i>

                                Login To Distributor Panel

                            </button>

                        </form>

                        <div class="dist-footer">

                            © {{ date('Y') }}
                            Distributor Management Portal

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

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

$(document).ready(function () {

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
        let icon  = $(this).find('i');

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
    | DISTRIBUTOR LOGIN
    |--------------------------------------------------------------------------
    */

    $('#distributorLoginForm').on('submit', function(e){

        e.preventDefault();

        $('.dist-error').html('');

        let captcha = grecaptcha.getResponse();

        if(captcha.length === 0){

            $('.error-captcha').html('Please verify captcha.');

            toastr.error('Please verify you are human.');

            return false;
        }

        toastr.info(
            'Authenticating distributor credentials...',
            'Please Wait'
        );

        let btn = $('#loginBtn');

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Logging in...
        `);

        $.ajax({

            url: "{{ route('distributor.login.submit') }}",

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
                    response.message ||
                    'Distributor Login Successful'
                );

                btn.html(`
                    <i class="fa-solid fa-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function(){

                    window.location.href =
                    response.redirect ||
                    "{{ route('admin.dashboard') }}";

                }, 1500);

            },

            error: function(xhr){

                btn.prop('disabled', false);

                btn.html(`
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login To Distributor Panel
                `);

                grecaptcha.reset();

                if(xhr.status === 422){

                    let errors = xhr.responseJSON.errors;

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
                        xhr.responseJSON.message ||
                        'Invalid distributor credentials'
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