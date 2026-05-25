@extends('layout.app')

@section('title', 'Forgot Password')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Forgot Password
                        </h2>

                        <p class="text-muted">
                            Enter your registered email
                        </p>

                    </div>

                    <div class="alert alert-danger d-none"
                         id="errorBox">
                    </div>

                    <form id="forgotPasswordForm">

                        @csrf

                        <!-- EMAIL -->
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control rounded-4">

                            <small class="text-danger"
                                   id="err_email">
                            </small>

                        </div>

                        <!-- BUTTON -->
                        <button type="submit"
                                class="btn btn-primary w-100 rounded-pill py-3"
                                id="submitBtn">

                            <i class="fa-solid fa-paper-plane me-2"></i>

                            Send Reset Link

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | FORGOT PASSWORD SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#forgotPasswordForm').on('submit', function(e){

        e.preventDefault();

        /*
        |--------------------------------------------------------------------------
        | RESET ERRORS
        |--------------------------------------------------------------------------
        */

        $('#err_email').html('');

        $('#errorBox')
        .addClass('d-none')
        .html('');

        /*
        |--------------------------------------------------------------------------
        | EMAIL VALIDATION
        |--------------------------------------------------------------------------
        */

        let email =
        $('#email').val().trim();

        let emailPattern =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(email === ''){

            $('#err_email').html(
                'Email is required.'
            );

            return false;
        }

        if(!emailPattern.test(email)){

            $('#err_email').html(
                'Enter valid email address.'
            );

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | BUTTON LOADING
        |--------------------------------------------------------------------------
        */

        let btn =
        $('#submitBtn');

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Sending...
        `);

        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url:
            "{{ route('parent.password.send') }}",

            type:"POST",

            data:$(this).serialize(),

            success:function(response){

                Swal.fire({

                    icon:'success',

                    title:'Success',

                    text:response.message,

                    confirmButtonColor:'#2563eb'
                });

                /*
                |--------------------------------------------------------------------------
                | RESET FORM
                |--------------------------------------------------------------------------
                */

                $('#forgotPasswordForm')[0].reset();

            },

            error:function(xhr){

                let message =
                'Something went wrong';

                /*
                |--------------------------------------------------------------------------
                | VALIDATION ERROR
                |--------------------------------------------------------------------------
                */

                if(xhr.responseJSON?.errors){

                    let errors =
                    xhr.responseJSON.errors;

                    if(errors.email){

                        $('#err_email')
                        .html(errors.email[0]);
                    }

                    message =
                    Object.values(errors)
                    .map(error => error[0])
                    .join('<br>');
                }

                /*
                |--------------------------------------------------------------------------
                | NORMAL ERROR
                |--------------------------------------------------------------------------
                */

                else if(xhr.responseJSON?.message){

                    message =
                    xhr.responseJSON.message;
                }

                Swal.fire({

                    icon:'error',

                    title:'Error',

                    html:message,

                    confirmButtonColor:'#dc2626'
                });

            },

            complete:function(){

                btn.prop('disabled', false);

                btn.html(`
                    <i class="fa-solid fa-paper-plane me-2"></i>
                    Send Reset Link
                `);

            }

        });

    });

});

</script>

@endsection