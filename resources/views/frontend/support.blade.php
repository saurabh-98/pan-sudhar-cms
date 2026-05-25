@extends('layout.app')

@section('content')

<section class="support-wrapper">

    <div class="support-container">

        <div class="support-card">

            <!-- HEADER -->

            <div class="support-header">

                <span class="support-badge">

                    <i class="fa-solid fa-headset"></i>

                    PUBLIC SUPPORT CENTER

                </span>

                <h1>

                    Raise Support Ticket

                </h1>

                <p>

                    Submit your issue or query and our support
                    team will assist you shortly.

                </p>

            </div>

            <!-- FORM -->

            <form id="supportForm"
                  enctype="multipart/form-data">

                @csrf

                <!-- GRID -->

                <div class="grid">

                    <!-- NAME -->

                    <div class="group">

                        <label>

                            Full Name

                        </label>

                        <input type="text"
                               name="name"
                               id="name"
                               placeholder="Enter Full Name">

                        <small class="error"
                               id="error-name"></small>

                    </div>

                    <!-- EMAIL -->

                    <div class="group">

                        <label>

                            Email Address

                        </label>

                        <input type="email"
                               name="email"
                               id="email"
                               placeholder="Enter Email Address">

                        <small class="error"
                               id="error-email"></small>

                    </div>

                    <!-- MOBILE -->

                    <div class="group">

                        <label>

                            Mobile Number

                        </label>

                        <input type="text"
                               name="mobile"
                               id="mobile"
                               maxlength="10"
                               placeholder="Enter Mobile Number">

                        <small class="error"
                               id="error-mobile"></small>

                    </div>

                    <!-- PRIORITY -->

                    <div class="group">

                        <label>

                            Priority

                        </label>

                        <select name="priority"
                                id="priority">

                            <option value="low">

                                Low

                            </option>

                            <option value="medium"
                                    selected>

                                Medium

                            </option>

                            <option value="high">

                                High

                            </option>

                        </select>

                    </div>

                </div>

                <!-- SUBJECT -->

                <div class="group">

                    <label>

                        Subject

                    </label>

                    <input type="text"
                           name="subject"
                           id="subject"
                           placeholder="Enter Subject">

                    <small class="error"
                           id="error-subject"></small>

                </div>

                <!-- MESSAGE -->

                <div class="group">

                    <div class="message-top">

                        <label>

                            Message

                        </label>

                        <span id="charCount">

                            0 / 1000

                        </span>

                    </div>

                    <textarea name="message"
                              id="message"
                              rows="6"
                              maxlength="1000"
                              placeholder="Describe your issue..."></textarea>

                    <small class="error"
                           id="error-message"></small>

                </div>

                <!-- FILE -->

                <div class="group">

                    <label>

                        Attachment (Optional)

                    </label>

                    <input type="file"
                           name="attachment"
                           id="attachment"
                           accept=".jpg,.jpeg,.png,.pdf">

                    <small class="file-note">

                        Allowed:
                        JPG, PNG, PDF (Max 2MB)

                    </small>

                    <small class="error"
                           id="error-attachment"></small>

                </div>

                <!-- CAPTCHA -->

                <div class="group">

                    <div class="g-recaptcha"
                         data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}">
                    </div>

                    <small class="error"
                           id="error-captcha"></small>

                </div>

                <!-- BUTTON -->

                <button type="submit"
                        class="submit-btn"
                        id="submitBtn">

                    <span id="btnText">

                        Submit Ticket

                    </span>

                    <span id="btnLoader"
                          style="display:none;">

                        <i class="fa-solid fa-spinner fa-spin"></i>

                        Submitting...

                    </span>

                </button>

            </form>

        </div>

    </div>

</section>

@endsection

@section('scripts')

<!-- SWEET ALERT -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- RECAPTCHA -->

<script src="https://www.google.com/recaptcha/api.js"
        async
        defer></script>

<script>

$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | CHARACTER COUNT
    |--------------------------------------------------------------------------
    */

    $('#message').on('keyup', function(){

        let count = $(this).val().length;

        $('#charCount').text(

            count + ' / 1000'
        );
    });

    /*
    |--------------------------------------------------------------------------
    | SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#supportForm').on('submit', function(e){

        e.preventDefault();

        /*
        |--------------------------------------------------------------------------
        | RESET ERRORS
        |--------------------------------------------------------------------------
        */

        $('.error').text('');

        /*
        |--------------------------------------------------------------------------
        | FRONTEND VALIDATION
        |--------------------------------------------------------------------------
        */

        let valid = true;

        let name =
            $('#name').val().trim();

        let email =
            $('#email').val().trim();

        let mobile =
            $('#mobile').val().trim();

        let subject =
            $('#subject').val().trim();

        let message =
            $('#message').val().trim();

        let captcha =
            grecaptcha.getResponse();

        /*
        |--------------------------------------------------------------------------
        | NAME
        |--------------------------------------------------------------------------
        */

        if(name === ''){

            valid = false;

            $('#error-name').text(
                'Name is required'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        let emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(email === ''){

            valid = false;

            $('#error-email').text(
                'Email is required'
            );

        }else if(!emailPattern.test(email)){

            valid = false;

            $('#error-email').text(
                'Invalid email format'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        if(!/^[0-9]{10}$/.test(mobile)){

            valid = false;

            $('#error-mobile').text(
                'Enter valid 10 digit mobile number'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SUBJECT
        |--------------------------------------------------------------------------
        */

        if(subject === ''){

            valid = false;

            $('#error-subject').text(
                'Subject is required'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MESSAGE
        |--------------------------------------------------------------------------
        */

        if(message.length < 10){

            valid = false;

            $('#error-message').text(
                'Message must be at least 10 characters'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CAPTCHA
        |--------------------------------------------------------------------------
        */

        if(captcha === ''){

            valid = false;

            $('#error-captcha').text(
                'Please verify captcha'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILE VALIDATION
        |--------------------------------------------------------------------------
        */

        let file =
            $('#attachment')[0].files[0];

        if(file){

            let allowed = [

                'image/jpeg',
                'image/png',
                'application/pdf'
            ];

            if(!allowed.includes(file.type)){

                valid = false;

                $('#error-attachment').text(
                    'Invalid file type'
                );
            }

            if(file.size > 2097152){

                valid = false;

                $('#error-attachment').text(
                    'File size must be less than 2MB'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STOP
        |--------------------------------------------------------------------------
        */

        if(!valid){

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | LOADING
        |--------------------------------------------------------------------------
        */

        $('#submitBtn').prop(
            'disabled',
            true
        );

        $('#btnText').hide();

        $('#btnLoader').show();

        /*
        |--------------------------------------------------------------------------
        | FORM DATA
        |--------------------------------------------------------------------------
        */

        let formData =
            new FormData(this);

        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url:
            "{{ route('support.store') }}",

            type:'POST',

            data:formData,

            processData:false,

            contentType:false,

            headers:{
                'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')
            },

            success:function(response){

                $('#submitBtn').prop(
                    'disabled',
                    false
                );

                $('#btnText').show();

                $('#btnLoader').hide();

                /*
                |--------------------------------------------------------------------------
                | RESET
                |--------------------------------------------------------------------------
                */

                $('#supportForm')[0].reset();

                grecaptcha.reset();

                $('#charCount').text(
                    '0 / 1000'
                );

                /*
                |--------------------------------------------------------------------------
                | SUCCESS ALERT
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    icon:'success',

                    title:'Ticket Submitted',

                    html:
                    `
                    <div style="font-size:15px;">
                        ${response.message}
                    </div>
                    `,

                    confirmButtonColor:'#2563eb'
                });
            },

            error:function(xhr){

                $('#submitBtn').prop(
                    'disabled',
                    false
                );

                $('#btnText').show();

                $('#btnLoader').hide();

                /*
                |--------------------------------------------------------------------------
                | VALIDATION ERRORS
                |--------------------------------------------------------------------------
                */

                if(xhr.status === 422){

                    let errors =
                        xhr.responseJSON.errors;

                    $.each(errors,function(key,value){

                        $('#error-' + key)
                        .text(value[0]);
                    });

                }else{

                    Swal.fire({

                        icon:'error',

                        title:'Submission Failed',

                        text:
                        xhr.responseJSON.message
                        ||
                        'Something went wrong',

                        confirmButtonColor:'#dc2626'
                    });
                }
            }
        });

    });

});
</script>

@endsection