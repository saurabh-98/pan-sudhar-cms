@extends('layout.retailer')

@section('title', 'ITR / TDS Document Upload')

@section('styles')

<link
    rel="stylesheet"
    href="{{ asset('assets/retailer/css/itr-upload.css') }}"
>

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
/>

<style>

    .pan-header-right {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pan-guideline-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #d0d5dd;
        color: #344054;
        font-weight: 600;
        font-size: 14px;
        padding: 10px 18px;
        border-radius: 10px;
        white-space: nowrap;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
        transition:
            background-color .2s ease,
            border-color .2s ease,
            box-shadow .2s ease,
            transform .15s ease;
    }

    .pan-guideline-btn:hover {
        background: #f9fafb;
        border-color: #98a2b3;
        box-shadow: 0 2px 6px rgba(16, 24, 40, 0.08);
    }

    .pan-guideline-btn:active {
        transform: scale(0.97);
        background: #f2f4f7;
    }

    .pan-guideline-btn:focus-visible {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
    }

    .pan-guideline-btn i {
        color: #0d6efd;
        font-size: 15px;
        transition: transform .2s ease;
    }

    .pan-guideline-btn:hover i {
        transform: scale(1.15);
    }

    /* ATTENTION PULSE — shown until the guideline is opened once */
    .pan-guideline-btn-icon {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .pan-guideline-ping {
        position: absolute;
        top: -3px;
        right: -3px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #0d6efd;
        pointer-events: none;
    }

    .pan-guideline-btn:not(.viewed) .pan-guideline-ping::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: #0d6efd;
        animation: pan-ping 1.8s cubic-bezier(0, 0, 0.2, 1) infinite;
    }

    @keyframes pan-ping {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }
        75%, 100% {
            transform: scale(2.6);
            opacity: 0;
        }
    }

    .pan-guideline-btn.viewed .pan-guideline-ping {
        display: none;
    }

    .pan-guideline-btn.viewed {
        border-color: #b7e4c7;
        background: #f4fbf7;
    }

    .pan-guideline-viewed-tick {
        display: none;
        color: #12b76a;
        font-size: 12px !important;
    }

    .pan-guideline-btn.viewed .pan-guideline-viewed-tick {
        display: inline-block;
    }

    .pan-charge-card {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    /* HALF-WINDOW PANEL */
    .pan-guideline-offcanvas {
        width: 50% !important;
        transition: width .25s ease;
    }

    .pan-guideline-offcanvas.pan-expanded {
        width: 90% !important;
    }

    .pan-guideline-offcanvas .offcanvas-body {
        padding: 0;
        height: 100%;
    }

    .pan-offcanvas-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pan-expand-btn {
        border: 1px solid #d0d5dd;
        background: #fff;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #344054;
        transition: background-color .2s ease, border-color .2s ease;
    }

    .pan-expand-btn:hover {
        background: #f9fafb;
        border-color: #98a2b3;
    }

    #pdfLoadingState {
        flex: 0 0 auto;
        padding: 28px;
    }

    .pan-skeleton-line {
        height: 14px;
        border-radius: 6px;
        margin-bottom: 12px;
        background: linear-gradient(90deg, #eef0f2 25%, #f7f8f9 37%, #eef0f2 63%);
        background-size: 400% 100%;
        animation: pan-shimmer 1.4s ease infinite;
    }

    .pan-skeleton-line.w-75 { width: 75%; }
    .pan-skeleton-line.w-50 { width: 50%; }
    .pan-skeleton-line.w-100 { width: 100%; }

    @keyframes pan-shimmer {
        0% { background-position: 100% 50%; }
        100% { background-position: 0 50%; }
    }

    #pdfErrorState {
        flex: 0 0 auto;
    }

    #guidelinePdfFrame {
        flex: 1 1 auto;
        width: 100%;
        border: 0;
    }

    @media (max-width: 991px) {

        .pan-guideline-offcanvas,
        .pan-guideline-offcanvas.pan-expanded {
            width: 100% !important;
        }

    }

</style>

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

                                   <h2>Income Tax Return (ITR) / TDS Refund</h2>

                                    <p>
                                       Securely upload documents and submit your ITR / TDS Refund application online.
                                    </p>

                                </div>

                            </div>

                              <div class="pan-header-right d-flex align-items-center gap-3">

                        {{-- SERVICE GUIDELINE BUTTON --}}
                        @if($guideline && $guideline->pdf)

                            <button
                                type="button"
                                class="pan-guideline-btn"
                                id="serviceGuidelineBtn"
                                data-bs-toggle="offcanvas"
                                data-bs-target="#serviceGuidelineOffcanvas"
                                aria-controls="serviceGuidelineOffcanvas"
                                data-guideline-key="guideline-viewed-{{ $guideline->id ?? 'itr' }}"
                            >
                                <span class="pan-guideline-btn-icon">
                                    <i class="fa fa-circle-info"></i>
                                    <span class="pan-guideline-ping"></span>
                                </span>
                                Service Guidelines
                                <i class="fa fa-circle-check pan-guideline-viewed-tick" aria-hidden="true"></i>
                            </button>

                        @endif

                        <div class="pan-charge-card">

                            <span class="pan-charge-label">
                                Service Charge
                            </span>

                            <span class="pan-charge-amount">
                                ₹{{ number_format($itrCharge, 2) }}
                            </span>

                        </div>

                        {{-- SERVICE GUIDELINE OFFCANVAS (HALF-WINDOW PDF VIEWER) --}}

                @if($guideline && $guideline->pdf)

                    <div
                        class="offcanvas offcanvas-end pan-guideline-offcanvas"
                        tabindex="-1"
                        id="serviceGuidelineOffcanvas"
                        aria-labelledby="serviceGuidelineLabel"
                    >
                        <div class="offcanvas-header">

                            <h5 id="serviceGuidelineLabel">
                                <i class="fa fa-file-pdf me-2 text-danger"></i>
                                ITR — Service Guidelines
                            </h5>

                            <div class="pan-offcanvas-header-actions">

                                <button
                                    type="button"
                                    class="pan-expand-btn"
                                    id="guidelineExpandBtn"
                                    title="Expand view"
                                    aria-label="Expand view"
                                >
                                    <i class="fa fa-expand"></i>
                                </button>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="offcanvas"
                                    aria-label="Close"
                                ></button>

                            </div>

                        </div>

                        <div class="offcanvas-body p-0 d-flex flex-column">

                            <div id="pdfLoadingState">
                                <div class="pan-skeleton-line w-75"></div>
                                <div class="pan-skeleton-line w-100"></div>
                                <div class="pan-skeleton-line w-100"></div>
                                <div class="pan-skeleton-line w-50"></div>
                                <p class="text-muted mt-3 mb-0">
                                    <i class="fa fa-spinner fa-spin me-2"></i>
                                    Loading guideline document...
                                </p>
                            </div>

                            <div id="pdfErrorState" class="text-center text-muted py-5 d-none">
                                <i class="fa fa-triangle-exclamation fa-2x mb-3 text-warning"></i>
                                <p class="mb-3">Couldn't preview the document here.</p>
                                <a href="{{ file_url($guideline->pdf) }}" target="_blank" class="btn btn-sm btn-primary">
                                    Open PDF in New Tab
                                </a>
                            </div>

                            <iframe
                                id="guidelinePdfFrame"
                                data-src="{{ file_url($guideline->pdf) }}"
                                style="display:none;"
                            ></iframe>

                            <div class="text-center py-3 border-top">

                                <a href="{{ file_url($guideline->pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-up-right-from-square me-1"></i>
                                    Open PDF in New Tab
                                </a>

                            </div>

                        </div>

                    </div>

                @endif


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
    | SERVICE GUIDELINE PANEL
    | pulse-until-viewed + skeleton loader + timeout fallback
    | + expand/collapse toggle
    ========================================================= */

    let guidelineBtn =
        document.getElementById('serviceGuidelineBtn');

    let guidelineOffcanvasEl =
        document.getElementById('serviceGuidelineOffcanvas');

    let expandBtn =
        document.getElementById('guidelineExpandBtn');

    if (guidelineBtn) {

        let storedKey = guidelineBtn.dataset.guidelineKey;

        if (storedKey && localStorage.getItem(storedKey)) {
            guidelineBtn.classList.add('viewed');
        }

    }

    if (guidelineOffcanvasEl) {

        guidelineOffcanvasEl.addEventListener(
            'show.bs.offcanvas',
            function () {

                let iframe =
                    document.getElementById('guidelinePdfFrame');

                let loadingState =
                    document.getElementById('pdfLoadingState');

                let errorState =
                    document.getElementById('pdfErrorState');

                if (guidelineBtn) {

                    guidelineBtn.classList.add('viewed');

                    let storedKey = guidelineBtn.dataset.guidelineKey;

                    if (storedKey) {
                        localStorage.setItem(storedKey, '1');
                    }

                }

                if (iframe && !iframe.dataset.loaded) {

                    let settled = false;

                    iframe.addEventListener('load', function () {

                        settled = true;

                        loadingState.style.display = 'none';

                        iframe.style.display = 'block';

                    });

                    setTimeout(function () {

                        if (!settled) {

                            loadingState.style.display = 'none';

                            if (errorState) {
                                errorState.classList.remove('d-none');
                            }

                        }

                    }, 8000);

                    iframe.src = iframe.dataset.src;

                    iframe.dataset.loaded = 'true';

                }

            }
        );

        guidelineOffcanvasEl.addEventListener(
            'hidden.bs.offcanvas',
            function () {

                guidelineOffcanvasEl.classList.remove('pan-expanded');

                if (expandBtn) {

                    expandBtn.innerHTML = '<i class="fa fa-expand"></i>';

                    expandBtn.setAttribute('title', 'Expand view');

                }

            }
        );

    }

    if (expandBtn && guidelineOffcanvasEl) {

        expandBtn.addEventListener('click', function () {

            guidelineOffcanvasEl.classList.toggle('pan-expanded');

            let expanded =
                guidelineOffcanvasEl.classList.contains('pan-expanded');

            expandBtn.innerHTML = expanded
                ? '<i class="fa fa-compress"></i>'
                : '<i class="fa fa-expand"></i>';

            expandBtn.setAttribute(
                'title',
                expanded ? 'Collapse view' : 'Expand view'
            );

        });

    }

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