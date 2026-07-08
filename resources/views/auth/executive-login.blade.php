@extends('layout.app')

@section('content')

<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="exl-wrapper">


<div class="exl-shape exl-shape-1"></div>
<div class="exl-shape exl-shape-2"></div>

<div class="container-fluid">

    <div class="exl-card">

        <div class="row g-0 align-items-stretch">

            <!-- LEFT PANEL -->

            <div class="col-lg-5">

                <div class="exl-left">

                    <div class="exl-overlay"></div>

                    <div class="exl-left-content">

                        <div class="exl-logo-wrap">

                            <img
                                src="{{ asset('logo.png') }}"
                                alt="Logo"
                                class="exl-logo">

                        </div>

                        <h1 class="exl-left-title">

                            Executive Portal

                        </h1>

                        <p class="exl-left-desc">

                            Manage assigned PAN correction applications,
                            verify documents, upload receipts and
                            complete approval workflow securely.

                        </p>

                        <div class="exl-feature-list">

                            <div class="exl-feature">

                                <div class="exl-feature-icon">

                                    <i class="fa fa-clipboard-list"></i>

                                </div>

                                <div>

                                    <h5>Assigned Applications</h5>

                                    <span>View assigned cases</span>

                                </div>

                            </div>

                            <div class="exl-feature">

                                <div class="exl-feature-icon">

                                    <i class="fa fa-file-circle-check"></i>

                                </div>

                                <div>

                                    <h5>Document Verification</h5>

                                    <span>Verify uploaded documents</span>

                                </div>

                            </div>

                            <div class="exl-feature">

                                <div class="exl-feature-icon">

                                    <i class="fa fa-cloud-arrow-up"></i>

                                </div>

                                <div>

                                    <h5>Receipt Upload</h5>

                                    <span>Upload processing receipts</span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <!-- RIGHT PANEL -->

            <div class="col-lg-7">

                <div class="exl-right">

                    <div class="exl-header">

                        <div class="exl-login-icon">

                            <i class="fa fa-user-tie"></i>

                        </div>

                        <h2 class="exl-title">

                            Executive Login

                        </h2>

                        <p class="exl-subtitle">

                            Access your dashboard securely

                        </p>

                    </div>

                    <form
                        id="executiveLoginForm"
                        class="exl-form">

                        @csrf

                        <div class="exl-group">

                            <label class="exl-label">

                                Executive Email Address

                            </label>

                            <div class="exl-input-wrap">

                                <i class="fa fa-envelope exl-input-icon"></i>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="exl-input"
                                    placeholder="Enter Email Address">

                            </div>

                            <span class="exl-error error-email"></span>

                        </div>

                        <div class="exl-group">

                            <label class="exl-label">

                                Password

                            </label>

                            <div class="exl-input-wrap">

                                <i class="fa fa-lock exl-input-icon"></i>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="exl-input"
                                    placeholder="Enter Password">

                                <span class="exl-password-toggle">

                                    <i class="fa fa-eye"></i>

                                </span>

                            </div>

                            <span class="exl-error error-password"></span>

                        </div>

                        <div class="exl-options">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">

                                    Remember Me

                                </label>

                            </div>

                            <a href="#" class="exl-forgot">

                                Forgot Password?

                            </a>

                        </div>

                        <div class="exl-captcha">

                            <label class="exl-label">

                                Verify You Are Human

                            </label>

                          @if(app()->environment('production'))
                                <div class="cf-turnstile"
                                    data-sitekey="{{ config('services.turnstile.site_key') }}">
                                </div>
                            @endif

                            <span class="exl-error error-captcha"></span>

                        </div>

                        <button
                            type="submit"
                            id="loginBtn"
                            class="exl-btn">

                            <i class="fa fa-right-to-bracket me-2"></i>

                            Login To Executive Panel

                        </button>

                    </form>

                    <div class="exl-footer">

                        © {{ date('Y') }}
                        PAN Sudhar Executive Portal

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

<script
src="https://challenges.cloudflare.com/turnstile/v0/api.js"
async
defer></script>


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

        timeOut: 3000

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

            input.attr('type','text');

            icon.removeClass('fa-eye')
                .addClass('fa-eye-slash');

        }else{

            input.attr('type','password');

            icon.removeClass('fa-eye-slash')
                .addClass('fa-eye');

        }

    });

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE LOGIN
    |--------------------------------------------------------------------------
    */

    $('#executiveLoginForm').on('submit',function(e){

        e.preventDefault();

        $('.text-danger').html('');

        @if(app()->environment('production'))

        let captcha = document.querySelector(
            '[name="cf-turnstile-response"]'
        )?.value;

        if(!captcha){

            $('.error-captcha').html(
                'Please complete verification.'
            );

            toastr.error(
                'Verification required.'
            );

            return false;

        }

        @endif

        let btn = $('#loginBtn');

        btn.prop('disabled',true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Logging in...
        `);

        let captcha = "";

        @if(app()->environment('production'))

        captcha = document.querySelector(
            '[name="cf-turnstile-response"]'
        )?.value;

        @endif

        $.ajax({

            url:"{{ route('executive.login.submit') }}",

            type:"POST",

            data:{

                _token:"{{ csrf_token() }}",

                email:$('#email').val(),

                password:$('#password').val(),

                remember:$('#remember').is(':checked') ? 1 : 0,

                "cf-turnstile-response":captcha

            },

            success:function(response){

                toastr.success(

                    response.message ||

                    'Executive Login Successful'

                );

                btn.html(`
                    <i class="fa-solid fa-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function(){

                    window.location.href =

                        response.redirect ||

                        "{{ route('admin.dashboard') }}";

                },1500);

            },

            error:function(xhr){

                btn.prop('disabled',false);

                btn.html(`
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login To Executive Panel
                `);

                if(xhr.status===422){

                    $.each(xhr.responseJSON.errors,function(key,value){

                        $('.error-'+key).html(value[0]);

                    });

                    toastr.error(
                        'Please fill all required fields.'
                    );

                }
                else if(xhr.status===401){

                    toastr.error(

                        xhr.responseJSON.message ||

                        'Invalid executive credentials.'

                    );

                }
                else if(xhr.status===403){

                    toastr.error(

                        xhr.responseJSON.message ||

                        'Unauthorized access.'

                    );

                }
                else{

                    toastr.error(
                        'Something went wrong.'
                    );

                }

            }

        });

    });

});

</script>
@endsection