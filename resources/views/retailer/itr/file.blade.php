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

                        <div class="itr-header-content">

                            <div class="itr-header-left">

                                <div class="itr-header-icon">
                                    <i class="fas fa-file-shield"></i>
                                </div>

                                <div>

                                    <h2>Income Tax Return Filing</h2>

                                    <p>
                                        Securely upload documents and submit your ITR application online
                                    </p>

                                </div>

                            </div>

                            <div class="itr-charge-card">

                                <span class="charge-label">
                                    Service Charge
                                </span>

                                <span class="charge-price">
                                    ₹{{ number_format($itrCharge ?? 99,2) }}
                                </span>

                            </div>

                        </div>

                    </div>




                    <!-- =========================================================
                    | BODY
                    ========================================================= -->

                    <div class="itr-body">



                        


                       @php

                        $documents = [


                        [
                            'title' => 'Aadhaar Front',
                            'name'  => 'aadhaar_front',
                            'icon'  => 'fa-id-card',
                            'text'  => 'JPG / PNG / PDF (Max 5 MB)',
                            'accept'=> '.jpg,.jpeg,.png,.pdf',
                            'max_size' => 5120
                        ],

                        [
                            'title' => 'Aadhaar Back',
                            'name'  => 'aadhaar_back',
                            'icon'  => 'fa-address-card',
                            'text'  => 'JPG / PNG / PDF (Max 5 MB)',
                            'accept'=> '.jpg,.jpeg,.png,.pdf',
                            'max_size' => 5120
                        ],

                        [
                            'title' => 'PAN Card',
                            'name'  => 'pan_card',
                            'icon'  => 'fa-credit-card',
                            'text'  => 'JPG / PNG / PDF (Max 5 MB)',
                            'accept'=> '.jpg,.jpeg,.png,.pdf',
                            'max_size' => 5120
                        ]
                        

                        ];

                        @endphp

                        <div id="formSection">

                        <form
                            id="itrUploadForm"
                            enctype="multipart/form-data"
                        >


                        @csrf

                        <div class="row g-4">

                            @foreach($documents as $doc)

                                @php

                                    $file =
                                        $files[$doc['name']]
                                        ??
                                        null;

                                    $exists =
                                        !empty($file)
                                        &&
                                        file_exists_custom($file);

                                @endphp

                                <div class="col-lg-4">

                                    <div class="upload-wrapper">

                                        <label class="upload-box">

                                            <input
                                                type="file"
                                                name="{{ $doc['name'] }}"
                                                class="document-input d-none"
                                                accept="{{ $doc['accept'] }}"
                                                data-max-size="{{ $doc['max_size'] * 1024 }}"
                                            >

                                            <div class="upload-preview">

                                                @if($exists)

                                                    @if(
                                                        str_contains(
                                                            strtolower(file_url($file)),
                                                            '.pdf'
                                                        )
                                                    )

                                                        <a
                                                            href="{{ file_url($file) }}"
                                                            target="_blank"
                                                        >

                                                            <img
                                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                                class="preview-image"
                                                                alt="PDF"
                                                            >

                                                        </a>

                                                    @else

                                                        <img
                                                            src="{{ file_url($file) }}"
                                                            class="preview-image"
                                                            alt="{{ $doc['title'] }}"
                                                        >

                                                    @endif

                                                @else

                                                    <img
                                                        class="preview-image d-none"
                                                        alt="{{ $doc['title'] }}"
                                                    >

                                                @endif

                                                <div class="default-upload {{ $exists ? 'd-none' : '' }}">

                                                    <div class="upload-icon">

                                                        <i class="fa {{ $doc['icon'] }}"></i>

                                                    </div>

                                                    <h5>
                                                        {{ $doc['title'] }}
                                                    </h5>

                                                    <p>
                                                        {{ $doc['text'] }}
                                                    </p>

                                                </div>

                                            </div>

                                        </label>

                                        <div class="file-details {{ $exists ? '' : 'd-none' }}">

                                            <span class="file-name">

                                                {{ $exists ? basename(parse_url(file_url($file), PHP_URL_PATH)) : '' }}

                                            </span>

                                            <span class="file-size text-muted small"></span>

                                            <button
                                                type="button"
                                                class="remove-file-btn"
                                            >
                                                <i class="fa fa-times"></i>
                                            </button>

                                        </div>

                                        <div class="file-error text-danger small mt-2"></div>

                                    </div>

                                </div>

                            @endforeach

                            @foreach($documents as $doc)

                                @if(
                                    !empty($files[$doc['name']])
                                    &&
                                    file_exists_custom($files[$doc['name']])
                                )

                                    <input
                                        type="hidden"
                                        name="existing_files[{{ $doc['name'] }}]"
                                        value="{{ $files[$doc['name']] }}"
                                    >

                                @endif

                            @endforeach

                            <div class="col-lg-4">

                                <label class="itr-label">
                                    Name As Per Aadhaar
                                    <span>*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="itr-input"
                                    placeholder="Enter Full Name"
                                    value="{{ old('name', $data['name'] ?? '') }}"
                                >

                            </div>

                            <div class="col-lg-4">

                                <label class="itr-label">
                                    Mobile Number As Per Aadhaar
                                    <span>*</span>
                                </label>

                                <input
                                    type="text"
                                    name="mobile"
                                    class="itr-input"
                                    maxlength="10"
                                    placeholder="Enter Mobile Number"
                                    value="{{ old('mobile', $data['mobile'] ?? '') }}"
                                >

                            </div>

                            <div class="col-lg-4">

                                <label class="itr-label">
                                    Email ID
                                    <span>*</span>
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="itr-input"
                                    placeholder="Enter Email Address"
                                    value="{{ old('email', $data['email'] ?? '') }}"
                                >

                            </div>

                            <div class="col-lg-12">

                                <label class="itr-label">
                                    Remarks
                                </label>

                                <textarea
                                    name="remarks"
                                    class="itr-textarea"
                                    rows="4"
                                    placeholder="Write remarks here..."
                                >{{ old('remarks', $data['remarks'] ?? '') }}</textarea>

                            </div>

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

$(function(){

    /* =========================================================
    | REMOVE ERROR
    ========================================================= */

    function removeError(input){

        $(input).removeClass('input-error');

        $(input)
        .closest('.col-lg-4, .col-lg-12, .upload-wrapper')
        .find('.error-message')
        .remove();
    }

    /* =========================================================
    | SHOW ERROR
    ========================================================= */

    function showError(input,message){

        removeError(input);

        $(input).addClass('input-error');

        $(input)
        .closest('.col-lg-4, .col-lg-12, .upload-wrapper')
        .append(`
            <div class="error-message">
                <i class="fas fa-circle-exclamation"></i>
                ${message}
            </div>
        `);
    }

    /* =========================================================
    | FILE EXISTS
    | FOR RETURN FROM PREVIEW PAGE
    ========================================================= */

    function hasFile(name){

        let input =
        $('input[name="'+name+'"]');

        if(
            input.length &&
            input[0].files &&
            input[0].files.length > 0
        ){
            return true;
        }

        let existingFile =
        $('input[name="existing_files['+name+']"]');

        return existingFile.length > 0;
    }

    /* =========================================================
    | NAME VALIDATION
    ========================================================= */

    function validateName(){

        let input =
        $('input[name="name"]');

        let value =
        input.val().trim();

        if(value === ''){

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

    /* =========================================================
    | MOBILE VALIDATION
    ========================================================= */

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

        if(value === ''){

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
    | DOCUMENT UPLOAD PREVIEW
    ========================================================= */

    $('.document-input').on(
        'change',
        function(){

            let file =
            this.files[0];

            if(!file){
                return;
            }

            let wrapper =
            $(this).closest(
                '.upload-wrapper'
            );

            let previewImage =
            wrapper.find(
                '.preview-image'
            );

            let defaultUpload =
            wrapper.find(
                '.default-upload'
            );

            let fileDetails =
            wrapper.find(
                '.file-details'
            );

            let fileName =
            wrapper.find(
                '.file-name'
            );

            let fileSize =
            wrapper.find(
                '.file-size'
            );

            let errorBox =
            wrapper.find(
                '.file-error'
            );

            errorBox.html('');

            let allowedTypes = [

                'image/jpeg',
                'image/jpg',
                'image/png',
                'application/pdf'

            ];

            if(
                !allowedTypes.includes(
                    file.type
                )
            ){

                errorBox.html(
                    'Only JPG, PNG and PDF allowed.'
                );

                $(this).val('');

                return;
            }

            if(
                file.size >
                (5 * 1024 * 1024)
            ){

                errorBox.html(
                    'Maximum file size is 5 MB.'
                );

                $(this).val('');

                return;
            }

            fileName.text(
                file.name
            );

            fileSize.text(
                '(' +
                (
                    file.size /
                    1024 /
                    1024
                ).toFixed(2)
                +
                ' MB)'
            );

            fileDetails.removeClass(
                'd-none'
            );

            if(
                file.type ===
                'application/pdf'
            ){

                previewImage
                .attr(
                    'src',
                    'https://cdn-icons-png.flaticon.com/512/337/337946.png'
                )
                .removeClass(
                    'd-none'
                );

                defaultUpload
                .addClass(
                    'd-none'
                );

            }else{

                let reader =
                new FileReader();

                reader.onload =
                function(e){

                    previewImage
                    .attr(
                        'src',
                        e.target.result
                    )
                    .removeClass(
                        'd-none'
                    );

                    defaultUpload
                    .addClass(
                        'd-none'
                    );
                };

                reader.readAsDataURL(
                    file
                );
            }
        }
    );

    /* =========================================================
    | REMOVE FILE
    ========================================================= */

    $(document).on(
        'click',
        '.remove-file-btn',
        function(){

            let wrapper =
            $(this).closest(
                '.upload-wrapper'
            );

            wrapper
            .find('.document-input')
            .val('');

            wrapper
            .find('.preview-image')
            .attr('src','')
            .addClass('d-none');

            wrapper
            .find('.default-upload')
            .removeClass('d-none');

            wrapper
            .find('.file-details')
            .addClass('d-none');

            Swal.fire({

                toast:true,
                position:'top-end',
                icon:'success',
                title:'File removed successfully',
                timer:1800,
                showConfirmButton:false

            });
        }
    );

    /* =========================================================
    | LIVE VALIDATION
    ========================================================= */

    $('input[name="name"]')
    .on(
        'keyup blur',
        validateName
    );

    $('input[name="mobile"]')
    .on(
        'keyup blur',
        validateMobile
    );

    $('input[name="email"]')
    .on(
        'keyup blur',
        validateEmail
    );

    $('input[name="mobile"]')
    .on(
        'input',
        function(){

            this.value =
            this.value
            .replace(/\D/g,'')
            .slice(0,10);
        }
    );

    /* =========================================================
    | SUBMIT
    ========================================================= */

    $('#submitBtn').click(function(e){

        e.preventDefault();

        let valid = true;

        if(!hasFile('aadhaar_front')){
            valid = false;
        }

        if(!hasFile('aadhaar_back')){
            valid = false;
        }

        if(!hasFile('pan_card')){
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

        if(!valid){

            Swal.fire({

                icon:'error',

                title:'Validation Error',

                text:'Please complete all required fields.'

            });

            return;
        }

        let button = $(this);

        let formData =
        new FormData(
            $('#itrUploadForm')[0]
        );

        button.prop(
            'disabled',
            true
        );

        $.ajax({

            url:
            "{{ route('retailer.itr.preview') }}",

            type:'POST',

            data:formData,

            processData:false,

            contentType:false,

            success:function(response){

                if(response.status){

                    window.location.href =
                    response.redirect_url;
                }
            },

            error:function(xhr){

                button.prop(
                    'disabled',
                    false
                );

                Swal.fire({

                    icon:'error',

                    title:'Error',

                    text:
                    xhr.responseJSON?.message
                    ??
                    'Something went wrong.'

                });
            }
        });
    });

});

</script>
@endsection