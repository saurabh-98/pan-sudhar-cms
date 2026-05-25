
@extends('layout.app')

@section('title', 'Reset Password')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-4">

                        <h2 class="fw-bold">
                            Reset Password
                        </h2>

                    </div>

                    <form id="resetPasswordForm">

                        @csrf

                        <input type="hidden"
                               name="token"
                               value="{{ $token }}">

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control rounded-4">

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                New Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control rounded-4">

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Confirm Password
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control rounded-4">

                        </div>

                        <button type="submit"
                                class="btn btn-success w-100 rounded-pill py-3"
                                id="resetBtn">

                            Reset Password

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

$('#resetPasswordForm').on('submit', function(e){

    e.preventDefault();

    let btn = $('#resetBtn');

    btn.prop('disabled', true);

    btn.html(`
        <span class="spinner-border spinner-border-sm me-2"></span>
        Updating...
    `);

    $.ajax({

        url:"{{ route('parent.password.update') }}",

        type:"POST",

        data:$(this).serialize(),

        success:function(response){

            Swal.fire({

                icon:'success',
                title:'Success',
                text:response.message,
                confirmButtonColor:'#16a34a'

            }).then(() => {

                window.location.href =
                response.redirect;
            });
        },

        error:function(xhr){

            let message =
            xhr.responseJSON?.message
            || 'Something went wrong';

            Swal.fire({

                icon:'error',
                title:'Error',
                text:message,
                confirmButtonColor:'#dc2626'
            });
        },

        complete:function(){

            btn.prop('disabled', false);

            btn.html('Reset Password');
        }
    });
});

</script>

@endsection
