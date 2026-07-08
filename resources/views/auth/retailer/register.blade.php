@extends('layout.app')

@section('content')


<div class="parent-login-wrapper retailer-register-page">

    {{-- BACKGROUND EFFECTS --}}
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="container-fluid">

        <div class="parent-login-card">

            <div class="row g-0 h-100">

                <!-- =====================================================
                | LEFT PANEL
                ====================================================== -->

                <div class="col-lg-5">

                    <div class="parent-left-panel">

                        <div class="overlay-layer"></div>

                        <div class="left-content">

                            {{-- TITLE --}}
                            <h1>

                                Retailer Registration

                            </h1>

                            <p>

                                Join PAN & Aadhaar Suvidha Portal
                                and start offering PAN Card,
                                Aadhaar, and online documentation
                                services to customers.

                            </p>

                            {{-- FEATURES --}}
                            <div class="feature-list">

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        🪪

                                    </div>

                                    <div>

                                        <h5>

                                            PAN Services

                                        </h5>

                                        <span>

                                            New PAN & corrections

                                        </span>

                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        🔐

                                    </div>

                                    <div>

                                        <h5>

                                            Aadhaar Services

                                        </h5>

                                        <span>

                                            Aadhaar updates & support

                                        </span>

                                    </div>

                                </div>

                                <div class="feature-item">

                                    <div class="feature-icon">

                                        📂

                                    </div>

                                    <div>

                                        <h5>

                                            Retailer Dashboard

                                        </h5>

                                        <span>

                                            Manage customer requests

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- =====================================================
                | RIGHT PANEL
                ====================================================== -->

                <div class="col-lg-7">

                    <div class="parent-right-panel">

                        {{-- LOGIN HEADER --}}
                        <div class="login-header">

                            <div class="login-icon">

                                🏪

                            </div>

                            <h2>

                                Create Retailer Account

                            </h2>

                            <p>

                                Register securely to continue

                            </p>

                        </div>

                        <!-- =====================================================
                        | REGISTER FORM
                        ====================================================== -->

                        <form method="POST"
                            action="{{ route('retailer.register.submit') }}"
                            id="retailerRegisterForm">

                            @csrf

                            <div class="row">

                                <!-- SHOP NAME -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            Shop Name

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-shop"></i>

                                            <input
                                                type="text"
                                                name="shop_name"
                                                class="form-control @error('shop_name') is-invalid @enderror"
                                                placeholder="Enter Shop Name"
                                                value="{{ old('shop_name') }}"
                                                autocomplete="off"
                                            >

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('shop_name')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>

                                <!-- NAME -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            Name As Per Aadhaar

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-user"></i>

                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter Aadhaar Name"
                                                value="{{ old('name') }}"
                                                autocomplete="off"
                                            >

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('name')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>

                                <!-- MOBILE -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            Mobile Number

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-phone"></i>

                                            <input
                                                type="text"
                                                name="mobile"
                                                maxlength="10"
                                                class="form-control @error('mobile') is-invalid @enderror"
                                                placeholder="Enter Mobile Number"
                                                value="{{ old('mobile') }}"
                                                autocomplete="off"
                                            >

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('mobile')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>

                                <!-- EMAIL -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            Email ID

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-envelope"></i>

                                            <input
                                                type="email"
                                                name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Enter Email ID"
                                                value="{{ old('email') }}"
                                                autocomplete="off"
                                            >

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('email')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>


                                <!-- DISTRIBUTOR -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group-modern">
                                        <label>
                                            Distributor
                                        </label>

                                        <div class="input-box">
                                            <i class="fa-solid fa-user-tie"></i>

                                            <select
                                                name="distributor_id"
                                                id="distributor_id"
                                                class="form-control @error('distributor_id') is-invalid @enderror">

                                                <option value="">Select Distributor</option>

                                                @foreach($distributors as $distributor)
                                                    <option
                                                        value="{{ $distributor->id }}"
                                                        {{ old('distributor_id') == $distributor->id ? 'selected' : '' }}>
                                                        {{ $distributor->name }} 
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('distributor_id')
                                            <small class="text-danger d-block mt-1">
                                                {{ $message }}
                                            </small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- STATE -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            State

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-location-dot"></i>

                                            <select
                                                name="state_id"
                                                id="state_id"
                                                class="form-control @error('state_id') is-invalid @enderror">

                                                <option value="">

                                                    Select State

                                                </option>

                                                @foreach($states as $state)

                                                    <option
                                                        value="{{ $state->id }}"
                                                        {{ old('state_id') == $state->id ? 'selected' : '' }}>

                                                        {{ $state->name }}

                                                    </option>

                                                @endforeach

                                            </select>

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('state_id')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>

                                <!-- DISTRICT -->
                                <div class="col-md-6 mb-4">

                                    <div class="form-group-modern">

                                        <label>

                                            District

                                        </label>

                                        <div class="input-box">

                                            <i class="fa-solid fa-map-location-dot"></i>

                                            <select
                                                name="district_id"
                                                id="district_id"
                                                class="form-control @error('district_id') is-invalid @enderror">

                                                <option value="">

                                                    Select District

                                                </option>

                                            </select>

                                        </div>

                                        <small class="validation-error text-danger"></small>

                                        @error('district_id')

                                            <small class="text-danger d-block mt-1">

                                                {{ $message }}

                                            </small>

                                        @enderror

                                    </div>

                                </div>

                            </div>

                           <!-- CAPTCHA -->
                            <div class="form-group-modern mb-4">

                                <label>

                                    Verify You Are Human

                                </label>

                                @if(app()->environment('production'))

                                    <div class="cf-turnstile"
                                        id="turnstileWidget"
                                        data-sitekey="{{ config('services.turnstile.site_key') }}"
                                        data-expired-callback="onTurnstileExpired"
                                        data-error-callback="onTurnstileError">
                                    </div>

                                    <small class="error-captcha text-danger d-block mt-2"></small>

                                    @error('cf-turnstile-response')

                                        <small class="text-danger d-block mt-1">

                                            {{ $message }}

                                        </small>

                                    @enderror

                                @else

                                    <div class="alert alert-success mb-0">

                                        <i class="fa-solid fa-circle-check me-2"></i>

                                        Security verification is disabled in the local environment.

                                    </div>

                                @endif

                            </div>

                            <!-- BUTTON -->
                            <button
                                type="submit"
                                class="btn-login-parent">

                                <i class="fa-solid fa-user-plus me-2"></i>

                                Register Now

                            </button>

                            <!-- LOGIN -->
                            <div class="text-center mt-4">

                                Already have an account?

                                <a href="{{ route('retailer.login') }}">

                                    Login Here

                                </a>

                            </div>

                        </form>

                        <!-- FOOTER -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>

/*
|--------------------------------------------------------------------------
| TURNSTILE CALLBACKS (must be global, called by name from cloudflare script)
|--------------------------------------------------------------------------
*/

function onTurnstileExpired() {

    $('.error-captcha').text(
        'Security check expired, please verify again.'
    );
}

function onTurnstileError() {

    $('.error-captcha').text(
        'Security verification failed to load. Please refresh the page.'
    );
}

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | DISTRICT ROUTE
    |--------------------------------------------------------------------------
    */

    let districtRoute =
        "{{ url('retailer/get-districts') }}";

    /*
    |--------------------------------------------------------------------------
    | CSRF TOKEN
    |--------------------------------------------------------------------------
    */

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
            $('meta[name="csrf-token"]').attr('content')

        }

    });

    /*
    |--------------------------------------------------------------------------
    | SHOW ERROR
    |--------------------------------------------------------------------------
    */

    function showError(element, message) {

        $(element).addClass('is-invalid');

        $(element)
            .closest('.form-group-modern')
            .find('.validation-error')
            .text(message);

    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE ERROR
    |--------------------------------------------------------------------------
    */

    function removeError(element) {

        $(element).removeClass('is-invalid');

        $(element)
            .closest('.form-group-modern')
            .find('.validation-error')
            .text('');

    }

    /*
    |--------------------------------------------------------------------------
    | SHOP NAME
    |--------------------------------------------------------------------------
    */

    $('input[name="shop_name"]').on('keyup blur', function () {

        this.value = this.value
            .replace(/[^a-zA-Z0-9\s&.-]/g, '');

        let value = $(this).val().trim();

        if (value == '') {

            showError(this, 'Shop name is required');

        } else if (value.length < 3) {

            showError(this, 'Minimum 3 characters required');

        } else {

            removeError(this);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | NAME
    |--------------------------------------------------------------------------
    */

    $('input[name="name"]').on('keyup blur', function () {

        this.value = this.value
            .replace(/[^a-zA-Z\s]/g, '');

        let value = $(this).val().trim();

        let regex = /^[A-Za-z\s]+$/;

        if (value == '') {

            showError(this, 'Name is required');

        } else if (!regex.test(value)) {

            showError(this, 'Only alphabets allowed');

        } else if (value.length < 3) {

            showError(this, 'Minimum 3 characters required');

        } else {

            removeError(this);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    $('input[name="mobile"]').on('keyup blur', function () {

        this.value = this.value
            .replace(/\D/g, '')
            .slice(0, 10);

        let value = $(this).val();

        let regex = /^[6-9]\d{9}$/;

        if (value == '') {

            showError(this, 'Mobile number is required');

        } else if (!regex.test(value)) {

            showError(this, 'Enter valid mobile number');

        } else {

            removeError(this);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | EMAIL
    |--------------------------------------------------------------------------
    */

    $('input[name="email"]').on('keyup blur', function () {

        this.value = this.value.replace(/\s/g, '');

        let value = $(this).val().trim();

        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value == '') {

            showError(this, 'Email is required');

        } else if (!regex.test(value)) {

            showError(this, 'Enter valid email');

        } else {

            removeError(this);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | LOAD DISTRICTS
    |--------------------------------------------------------------------------
    */

    function loadDistricts(stateId, selectedDistrict = '') {

        if (stateId == '') {

            $('#district_id').html(
                '<option value="">Select District</option>'
            );

            return;
        }

        $('#district_id')
            .prop('disabled', true)
            .html(
                '<option value="">Loading District...</option>'
            );

        $.ajax({

            url: districtRoute + '/' + stateId,

            type: 'GET',

            dataType: 'json',

            success: function (response) {

                let options =
                    '<option value="">Select District</option>';

                $.each(response, function (key, district) {

                    let selected =
                        selectedDistrict == district.id
                        ? 'selected'
                        : '';

                    options += `
                        <option value="${district.id}" ${selected}>
                            ${district.name}
                        </option>
                    `;

                });

                $('#district_id')
                    .html(options)
                    .prop('disabled', false);

            },

            error: function () {

                $('#district_id')
                    .html(
                        '<option value="">District not found</option>'
                    )
                    .prop('disabled', false);

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | STATE CHANGE
    |--------------------------------------------------------------------------
    */

    $('#state_id').on('change', function () {

        let stateId = $(this).val();

        if (stateId == '') {

            showError(this, 'Please select state');

            $('#district_id').html(
                '<option value="">Select District</option>'
            );

            return;

        } else {

            removeError(this);

        }

        loadDistricts(stateId);

    });

    /*
    |--------------------------------------------------------------------------
    | DISTRICT VALIDATION
    |--------------------------------------------------------------------------
    */

    $('#district_id').on('change', function () {

        if ($(this).val() == '') {

            showError(this, 'Please select district');

        } else {

            removeError(this);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | OLD DATA LOAD
    |--------------------------------------------------------------------------
    */

    let oldState = "{{ old('state_id') }}";
    let oldDistrict = "{{ old('district_id') }}";

    if (oldState != '') {

        loadDistricts(oldState, oldDistrict);

    }

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#retailerRegisterForm').on('submit', function (e) {

        e.preventDefault();

        let isValid = true;

        /*
        |--------------------------------------------------------------------------
        | VALIDATE INPUTS
        |--------------------------------------------------------------------------
        */

        $('input').trigger('blur');

        /*
        |--------------------------------------------------------------------------
        | STATE VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($('#state_id').val() == '') {

            showError('#state_id', 'Please select state');

            isValid = false;

        }

        /*
        |--------------------------------------------------------------------------
        | DISTRICT VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($('#district_id').val() == '') {

            showError('#district_id', 'Please select district');

            isValid = false;

        }

        /*
        |--------------------------------------------------------------------------
        | TURNSTILE VALIDATION
        |--------------------------------------------------------------------------
        */

        $('.error-captcha').text('');

        @if(app()->environment('production'))

        let captcha = document.querySelector(
            '[name="cf-turnstile-response"]'
        )?.value;

        if (!captcha) {

            $('.error-captcha')
                .text('Please complete the security verification.');

            isValid = false;

        }

        @endif

        /*
        |--------------------------------------------------------------------------
        | INVALID CHECK
        |--------------------------------------------------------------------------
        */

        if ($('.is-invalid').length > 0) {

            isValid = false;

        }

        /*
        |--------------------------------------------------------------------------
        | STOP IF INVALID
        |--------------------------------------------------------------------------
        */

        if (!isValid) {

            $('html, body').animate({

                scrollTop:
                $('.is-invalid:first').offset().top - 100

            }, 500);

            return false;

        }

        /*
        |--------------------------------------------------------------------------
        | CONFIRM ALERT
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: 'Confirm Registration?',
            text: 'Please verify all details before submit.',
            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes, Submit'

        }).then((result) => {

            if (result.isConfirmed) {

                /*
                |--------------------------------------------------------------------------
                | BUTTON LOADING
                |--------------------------------------------------------------------------
                */

                $('.btn-login-parent')
                    .prop('disabled', true)
                    .html(`
                        <i class="fa fa-spinner fa-spin me-2"></i>
                        Please Wait...
                    `);

                /*
                |--------------------------------------------------------------------------
                | BUILD FORM DATA (must happen before the ajax call, not inside it)
                |--------------------------------------------------------------------------
                */

                let formData = $('#retailerRegisterForm').serialize();

                @if(app()->environment('production'))

                formData += '&cf-turnstile-response=' +
                    encodeURIComponent(
                        document.querySelector(
                            '[name="cf-turnstile-response"]'
                        )?.value || ''
                    );

                @endif

                /*
                |--------------------------------------------------------------------------
                | AJAX SUBMIT
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url: $('#retailerRegisterForm').attr('action'),

                    type: 'POST',

                    data: formData,

                    success: function (response) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Registration Submitted',

                            html: `

                                <div style="text-align:center">

                                    <p>

                                        Your retailer registration request has been submitted successfully.

                                    </p>

                                    <p>

                                        Please wait for department approval.

                                    </p>

                                    <p>

                                        Login credentials will be generated and shared
                                        after approval.

                                    </p>

                                </div>

                            `,

                            confirmButtonText: 'OK'

                        }).then(() => {

                            window.location.href =
                                response.redirect;

                        });

                    },

                    error: function (xhr) {

                        $('.btn-login-parent')
                            .prop('disabled', false)
                            .html(`
                                <i class="fa-solid fa-user-plus me-2"></i>
                                Register Now
                            `);

                        /*
                        |--------------------------------------------------------------------------
                        | RESET TURNSTILE SO A STALE TOKEN ISN'T REUSED ON RETRY
                        |--------------------------------------------------------------------------
                        */

                        @if(app()->environment('production'))

                        if (typeof turnstile !== "undefined") {

                            turnstile.reset('#turnstileWidget');

                        }

                        @endif

                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION ERRORS
                        |--------------------------------------------------------------------------
                        */

                        if (xhr.status === 422) {

                            let errors =
                                xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {

                                let field =
                                    $('[name="' + key + '"]');

                                showError(field, value[0]);

                            });

                            Swal.fire({

                                icon: 'error',

                                title: 'Validation Error',

                                text: 'Please fix required fields.'

                            });

                        } else {

                            Swal.fire({

                                icon: 'error',

                                title: 'Server Error',

                                text: 'Something went wrong.'

                            });

                        }

                    }

                });

            }

        });

    });

});

</script>

@endsection