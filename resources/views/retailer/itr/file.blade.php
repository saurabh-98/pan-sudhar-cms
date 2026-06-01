@extends('layout.retailer')

@section('title', 'ITR Document Upload')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('assets/retailer/css/itr-upload.css') }}"
>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
/>

@endsection



@section('content')

<div class="itr-page-wrapper">

    <div class="container-fluid">

        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-11">

                <div class="itr-card">

                    <!-- =========================================================
                    | HEADER
                    ========================================================= -->

                    <div class="itr-header">

                        <div class="itr-header-left">

                            <div class="itr-header-icon">

                                <i class="fas fa-file-shield"></i>

                            </div>

                        </div>

                    </div>



                    <!-- =========================================================
                    | BODY
                    ========================================================= -->

                    <div class="itr-body">



                        


                        <!-- =========================================================
                        | FORM SECTION
                        ========================================================= -->

                        <div id="formSection">

                            <form
                                id="itrUploadForm"
                                enctype="multipart/form-data"
                            >

                                @csrf

                                <div class="row">



                                    <!-- =========================================================
                                    | WALLET
                                    ========================================================= -->

                                    <div class="col-md-12 mb-4">

                                        <div class="wallet-balance-box">

                                            <div>

                                                <h6>

                                                    Available Wallet Balance

                                                </h6>

                                                <p>

                                                    Current usable wallet amount

                                                </p>

                                            </div>

                                            <div class="wallet-balance">

                                                ₹{{ number_format(auth()->user()->wallet_balance, 2) }}

                                            </div>

                                        </div>

                                    </div>





                                    <!-- =========================================================
                                    | AADHAAR FRONT
                                    ========================================================= -->

                                    <div class="col-lg-6 mb-4">

                                        <label class="itr-label">

                                            Aadhaar Front

                                            <span>*</span>

                                        </label>

                                        <div class="upload-box">

                                            <input
                                                type="file"
                                                name="aadhaar_front"
                                                id="aadhaar_front"
                                                class="upload-input"
                                                accept="image/*,.pdf"
                                            >

                                            <label
                                                for="aadhaar_front"
                                                class="upload-label"
                                            >

                                                <i class="fas fa-id-card"></i>

                                                <h6>

                                                    Upload Aadhaar Front

                                                </h6>

                                                <p>

                                                    JPG, PNG, JPEG, PDF Supported

                                                </p>

                                            </label>

                                        </div>

                                        <div
                                            class="preview-box"
                                            id="aadhaar_front_preview"
                                        ></div>

                                    </div>




                                    <!-- =========================================================
                                    | AADHAAR BACK
                                    ========================================================= -->

                                    <div class="col-lg-6 mb-4">

                                        <label class="itr-label">

                                            Aadhaar Back

                                            <span>*</span>

                                        </label>

                                        <div class="upload-box">

                                            <input
                                                type="file"
                                                name="aadhaar_back"
                                                id="aadhaar_back"
                                                class="upload-input"
                                                accept="image/*,.pdf"
                                            >

                                            <label
                                                for="aadhaar_back"
                                                class="upload-label"
                                            >

                                                <i class="fas fa-address-card"></i>

                                                <h6>

                                                    Upload Aadhaar Back

                                                </h6>

                                                <p>

                                                    JPG, PNG, JPEG, PDF Supported

                                                </p>

                                            </label>

                                        </div>

                                        <div
                                            class="preview-box"
                                            id="aadhaar_back_preview"
                                        ></div>

                                    </div>




                                    <!-- =========================================================
                                    | PAN CARD
                                    ========================================================= -->

                                    <div class="col-lg-12 mb-4">

                                        <label class="itr-label">

                                            PAN Card

                                            <span>*</span>

                                        </label>

                                        <div class="upload-box">

                                            <input
                                                type="file"
                                                name="pan_card"
                                                id="pan_card"
                                                class="upload-input"
                                                accept="image/*,.pdf"
                                            >

                                            <label
                                                for="pan_card"
                                                class="upload-label"
                                            >

                                                <i class="fas fa-credit-card"></i>

                                                <h6>

                                                    Upload PAN Card

                                                </h6>

                                                <p>

                                                    JPG, PNG, JPEG, PDF Supported

                                                </p>

                                            </label>

                                        </div>

                                        <div
                                            class="preview-box"
                                            id="pan_card_preview"
                                        ></div>

                                    </div>




                                    <!-- =========================================================
                                    | NAME
                                    ========================================================= -->

                                    <div class="col-lg-4 mb-4">

                                        <label class="itr-label">

                                            Name As Per Aadhaar

                                            <span>*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="name"
                                            class="itr-input"
                                            placeholder="Enter Full Name"
                                        >

                                    </div>

                                    <!-- =========================================================
                                    | MOBILE
                                    ========================================================= -->

                                    <div class="col-lg-4 mb-4">

                                        <label class="itr-label">

                                            Mobile Number As Per Aadhaar

                                            <span>*</span>

                                        </label>

                                        <input
                                            type="text"
                                            name="mobile"
                                            class="itr-input"
                                            maxlength="10"
                                            placeholder="Enter 10 Digit Mobile Number"
                                        >

                                    </div>

                                    <!-- =========================================================
                                    | EMAIL
                                    ========================================================= -->

                                    <div class="col-lg-4 mb-4">

                                        <label class="itr-label">

                                            Email ID

                                            <span>*</span>

                                        </label>

                                        <input
                                            type="email"
                                            name="email"
                                            class="itr-input"
                                            placeholder="Enter Email Address"
                                        >

                                    </div>



                                    <!-- =========================================================
                                    | REMARKS
                                    ========================================================= -->

                                    <div class="col-lg-12 mb-4">

                                        <label class="itr-label">

                                            Remarks

                                        </label>

                                        <textarea
                                            name="remarks"
                                            class="itr-textarea"
                                            rows="5"
                                            placeholder="Write remarks here..."
                                        ></textarea>

                                    </div>




                                    <!-- =========================================================
                                    | PREVIEW BUTTON
                                    ========================================================= -->

                                    <div class="col-lg-12">

                                        <button
                                            type="button"
                                            class="submit-btn"
                                            id="submitBtn"
                                        >

                                            <i class="fas fa-eye"></i>

                                            Continue To Preview

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>





                        <!-- =========================================================
                        | PREVIEW SECTION
                        ========================================================= -->

                        <div
                            id="previewSection"
                            style="display:none;"
                        >

                            <div class="preview-main-card">



                                <!-- HEADER -->

                                <div class="preview-header">

                                    <div>

                                        <h3>

                                            Review Your Submission

                                        </h3>

                                        <p>

                                            Verify all uploaded information before final payment.

                                        </p>

                                    </div>

                                    <div class="preview-badge">

                                        <i class="fas fa-shield-check"></i>

                                        Secure Submission

                                    </div>

                                </div>




                                <!-- =========================================================
                                | USER DETAILS
                                ========================================================= -->

                                <div class="preview-user-box">

                                    <div class="preview-user-item">

                                        <span>
                                            Full Name
                                        </span>

                                        <strong
                                            id="preview_name"
                                        ></strong>

                                    </div>

                                    <div class="preview-user-item">

                                        <span>
                                            Mobile Number
                                        </span>

                                        <strong
                                            id="preview_mobile"
                                        ></strong>

                                    </div>

                                    <div class="preview-user-item">

                                        <span>
                                            Email Address
                                        </span>

                                        <strong
                                            id="preview_email"
                                        ></strong>

                                    </div>

                                    <div class="preview-user-item">

                                        <span>
                                            Remarks
                                        </span>

                                        <strong
                                            id="preview_remarks"
                                        ></strong>

                                    </div>

                                </div>




                                <!-- =========================================================
                                | DOCUMENTS
                                ========================================================= -->

                                <div class="row mt-4">



                                    <!-- AADHAAR FRONT -->

                                    <div class="col-lg-4 mb-4">

                                        <div class="preview-document-card">

                                            <div class="preview-title">

                                                Aadhaar Front

                                            </div>

                                            <div
                                                id="preview_aadhaar_front"
                                            ></div>

                                        </div>

                                    </div>




                                    <!-- AADHAAR BACK -->

                                    <div class="col-lg-4 mb-4">

                                        <div class="preview-document-card">

                                            <div class="preview-title">

                                                Aadhaar Back

                                            </div>

                                            <div
                                                id="preview_aadhaar_back"
                                            ></div>

                                        </div>

                                    </div>




                                    <!-- PAN -->

                                    <div class="col-lg-4 mb-4">

                                        <div class="preview-document-card">

                                            <div class="preview-title">

                                                PAN Card

                                            </div>

                                            <div
                                                id="preview_pan_card"
                                            ></div>

                                        </div>

                                    </div>

                                </div>




                                <!-- =========================================================
                                | PAYMENT SUMMARY
                                ========================================================= -->

                                <div class="payment-summary-box">

                                    <!-- LEFT -->

                                    <div class="payment-left">

                                        <div class="payment-icon">

                                            <i class="fas fa-credit-card"></i>

                                        </div>

                                        <div>

                                            <h5>

                                                ITR Filing Charges

                                            </h5>

                                            <p>

                                                Charges will deduct automatically after successful submission.

                                            </p>

                                        </div>

                                    </div>



                                    <!-- RIGHT -->

                                    <div class="payment-right">

                                        <div class="payment-item">

                                            <span>
                                                Filing Charge
                                            </span>

                                            <strong>
                                                ₹99
                                            </strong>

                                        </div>

                                        <div class="payment-item total">

                                            <span>
                                                Total Payable
                                            </span>

                                            <strong>
                                                ₹99
                                            </strong>

                                        </div>

                                    </div>

                                </div>



                                <!-- =========================================================
                                | ACTION BUTTONS
                                ========================================================= -->

                                <div class="preview-actions">

                                    <button
                                        type="button"
                                        class="back-btn"
                                        id="backBtn"
                                    >

                                        <i class="fas fa-arrow-left"></i>

                                        Back

                                    </button>



                                    <button
                                        type="button"
                                        class="submit-btn"
                                        id="finalSubmitBtn"
                                    >

                                        <span class="btn-text">

                                            Final Submit

                                        </span>

                                        <span
                                            class="spinner-border spinner-border-sm d-none"
                                            id="btnLoader"
                                        ></span>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection




@section('scripts')

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function(){



    /* =========================================================
    | REMOVE ERROR
    ========================================================= */

    function removeError(input){

        $(input).removeClass('input-error');

        $(input)
        .closest('.mb-4')
        .find('.error-message')
        .remove();

    }



    /* =========================================================
    | SHOW ERROR
    ========================================================= */

    function showError(input, message){

        removeError(input);

        $(input).addClass('input-error');

        $(input)
        .closest('.mb-4')
        .append(`

            <div class="error-message">

                <i class="fas fa-circle-exclamation"></i>

                ${message}

            </div>

        `);

    }




    /* =========================================================
    | FILE VALIDATION
    ========================================================= */

    function validateFile(input){

        const file = input.files[0];

        if(!file){

            showError(
                input,
                'This field is required.'
            );

            return false;

        }

        const allowedTypes = [

            'image/jpeg',
            'image/jpg',
            'image/png',
            'application/pdf'

        ];

        if(!allowedTypes.includes(file.type)){

            showError(
                input,
                'Only JPG, PNG and PDF allowed.'
            );

            input.value = '';

            return false;

        }

        if(file.size > 5 * 1024 * 1024){

            showError(
                input,
                'Maximum file size is 5MB.'
            );

            input.value = '';

            return false;

        }

        removeError(input);

        return true;

    }




    /* =========================================================
    | NAME VALIDATION
    ========================================================= */

    function validateName(){

        let input =
        $('input[name="name"]');

        let value =
        input.val().trim();

        if(value == ''){

            showError(
                input,
                'Name is required.'
            );

            return false;

        }

        if(value.length < 3){

            showError(
                input,
                'Minimum 3 characters required.'
            );

            return false;

        }

        removeError(input);

        return true;

    }


    function validateMobile(){

        let input =
        $('input[name="mobile"]');

        let value =
        input.val().trim();

        let regex =
        /^[6-9][0-9]{9}$/;

        if(value === ''){

            showError(
                input,
                'Mobile number is required.'
            );

            return false;
        }

        if(!regex.test(value)){

            showError(
                input,
                'Enter valid 10 digit mobile number.'
            );

            return false;
        }

        removeError(input);

        return true;
    }

    /* =========================================================
    | EMAIL VALIDATION
    ========================================================= */

    function validateEmail(){

        let input =
        $('input[name="email"]');

        let value =
        input.val().trim();

        let regex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(value == ''){

            showError(
                input,
                'Email is required.'
            );

            return false;

        }

        if(!regex.test(value)){

            showError(
                input,
                'Enter valid email address.'
            );

            return false;

        }

        removeError(input);

        return true;

    }




    /* =========================================================
    | FILE PREVIEW
    ========================================================= */

    function previewFile(input, previewBox){

        const file = input.files[0];

        if(file){

            const fileType = file.type;



            // IMAGE

            if(fileType.startsWith('image/')){

                const reader =
                new FileReader();

                reader.onload =
                function(e){

                    $(previewBox).html(`

                        <div class="file-preview-card">

                            <button
                                type="button"
                                class="remove-file-btn"
                            >

                                <i class="fas fa-times"></i>

                            </button>

                            <img
                                src="${e.target.result}"
                                alt="Preview"
                            >

                            <div class="file-name">

                                ${file.name}

                            </div>

                        </div>

                    `);

                }

                reader.readAsDataURL(file);

            }



            // PDF

            else{

                $(previewBox).html(`

                    <div class="pdf-preview-wrapper">

                        <button
                            type="button"
                            class="remove-file-btn"
                        >

                            <i class="fas fa-times"></i>

                        </button>

                        <div class="pdf-preview">

                            <i class="fas fa-file-pdf"></i>

                            <div class="pdf-info">

                                <h6>
                                    ${file.name}
                                </h6>

                                <p>
                                    PDF File Selected
                                </p>

                            </div>

                        </div>

                    </div>

                `);

            }



            // REMOVE FILE

            $(previewBox)
            .find('.remove-file-btn')
            .on('click', function(){

                $(input).val('');

                $(previewBox).html('');

                Swal.fire({

                    toast:true,

                    position:'top-end',

                    icon:'success',

                    title:'File removed successfully',

                    showConfirmButton:false,

                    timer:1800

                });

            });

        }

    }




    /* =========================================================
    | FILE EVENTS
    ========================================================= */

    $('#aadhaar_front').on('change', function(){

        if(validateFile(this)){

            previewFile(
                this,
                '#aadhaar_front_preview'
            );

        }

    });



    $('#aadhaar_back').on('change', function(){

        if(validateFile(this)){

            previewFile(
                this,
                '#aadhaar_back_preview'
            );

        }

    });



    $('#pan_card').on('change', function(){

        if(validateFile(this)){

            previewFile(
                this,
                '#pan_card_preview'
            );

        }

    });




    /* =========================================================
    | LIVE VALIDATION
    ========================================================= */

    $('input[name="name"]').on(
        'keyup blur',
        validateName
    );

    $('input[name="mobile"]').on(
        'keyup blur',
        validateMobile
    );

    $('input[name="mobile"]').on(
        'input',
        function(){

            this.value =
            this.value
            .replace(/\D/g, '')
            .slice(0,10);

        }
    );

    $('input[name="email"]').on(
        'keyup blur',
        validateEmail
    );




    /* =========================================================
    | PREVIEW BUTTON
    ========================================================= */

    $('#submitBtn').click(function(e){

        e.preventDefault();

        let valid = true;



        if(!validateFile(
            document.getElementById(
                'aadhaar_front'
            )
        )){
            valid = false;
        }

        if(!validateFile(
            document.getElementById(
                'aadhaar_back'
            )
        )){
            valid = false;
        }

        if(!validateFile(
            document.getElementById(
                'pan_card'
            )
        )){
            valid = false;
        }

        if(!validateName()){
            valid = false;
        }

        if(!validateMobile()){
            valid = false;
        }

        if(!validateEmail()){
            valid = false;
        }



        // STOP IF INVALID

        if(!valid){

            Swal.fire({

                icon:'error',

                title:'Validation Error',

                text:'Please fix all required fields.'

            });

            return false;

        }



        // STEP CHANGE

        $('#stepUpload')
        .removeClass('active');

        $('#stepPreview')
        .addClass('active');



        $('#formSection').hide();

        $('#previewSection').fadeIn();




        // USER DETAILS

        $('#preview_name').text(

            $('input[name="name"]').val()

        );


        $('#preview_mobile').text(
            $('input[name="mobile"]').val()
        );
        
        $('#preview_email').text(

            $('input[name="email"]').val()

        );



        $('#preview_remarks').text(

            $('textarea[name="remarks"]').val()
            || 'N/A'

        );




        // DOCUMENT PREVIEW

        generatePreview(
            '#aadhaar_front',
            '#preview_aadhaar_front'
        );



        generatePreview(
            '#aadhaar_back',
            '#preview_aadhaar_back'
        );



        generatePreview(
            '#pan_card',
            '#preview_pan_card'
        );

    });




    /* =========================================================
    | GENERATE PREVIEW
    ========================================================= */

    function generatePreview(inputId, target){

        const file =
        $(inputId)[0].files[0];

        if(!file) return;

        const type = file.type;



        // IMAGE

        if(type.startsWith('image/')){

            const reader =
            new FileReader();

            reader.onload =
            function(e){

                $(target).html(`

                    <img
                        src="${e.target.result}"
                        class="preview-image"
                    >

                `);

            }

            reader.readAsDataURL(file);

        }



        // PDF

        else{

            $(target).html(`

                <div class="pdf-preview-card">

                    <i class="fas fa-file-pdf"></i>

                    <span>
                        ${file.name}
                    </span>

                </div>

            `);

        }

    }




    /* =========================================================
    | BACK BUTTON
    ========================================================= */

    $('#backBtn').click(function(){

        $('#previewSection').hide();

        $('#formSection').fadeIn();

        $('#stepPreview')
        .removeClass('active');

        $('#stepUpload')
        .addClass('active');

    });




    /* =========================================================
    | FINAL SUBMIT
    ========================================================= */

    $('#finalSubmitBtn').click(function(){

        Swal.fire({

            title:'Confirm Final Submission?',

            html:
            `
                <div>

                    <p>
                        ₹99 will deduct from wallet.
                    </p>

                    <strong>
                        Continue submission?
                    </strong>

                </div>
            `,

            icon:'warning',

            showCancelButton:true,

            confirmButtonText:'Yes Submit',

            confirmButtonColor:'#2563eb',

            cancelButtonColor:'#ef4444'

        }).then((result)=>{

            if(result.isConfirmed){

                let formData =
                new FormData(
                    $('#itrUploadForm')[0]
                );

                $('#finalSubmitBtn')
                .prop('disabled', true);

                $('#btnLoader')
                .removeClass('d-none');



                $.ajax({

                    url:
                    "{{ route('retailer.itr.store') }}",

                    type:"POST",

                    data:formData,

                    processData:false,

                    contentType:false,



                    success:function(response){

                        $('#finalSubmitBtn')
                        .prop('disabled', false);

                        $('#btnLoader')
                        .addClass('d-none');



                        Swal.fire({

                            icon:'success',

                            title:'Success',

                            html:
                            `
                                <div>

                                    <p>
                                        ${response.message}
                                    </p>

                                    <strong>
                                        ₹99 deducted successfully
                                    </strong>

                                </div>
                            `,

                            confirmButtonColor:'#2563eb'

                        });



                        // RESET

                        $('#itrUploadForm')[0]
                        .reset();

                        $('.preview-box').html('');

                        $('#previewSection').hide();

                        $('#formSection').show();

                        $('#stepPreview')
                        .removeClass('active');

                        $('#stepUpload')
                        .addClass('active');

                    },



                    error:function(xhr){

                        $('#finalSubmitBtn')
                        .prop('disabled', false);

                        $('#btnLoader')
                        .addClass('d-none');



                        Swal.fire({

                            icon:'error',

                            title:'Submission Failed',

                            text:
                            xhr.responseJSON.message ??
                            'Something went wrong.'

                        });

                    }

                });

            }

        });

    });

});

</script>

@endsection