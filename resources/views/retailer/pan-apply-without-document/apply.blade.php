@extends('layout.retailer')

@section('title', 'Apply Without Document PAN')


@section('content')

<div class="container-fluid py-4">

    {{-- PAGE HEADER --}}

    <div class="pan-page-header">

        <div class="pan-header-left">

            <div class="pan-header-icon">

                <i class="fa fa-id-card"></i>

            </div>

            <div>

                <h1>

                    Apply Without Document PAN Card

                </h1>

                <p>

                    Fill all applicant details carefully before preview submission

                </p>

            </div>

        </div>

        <div class="pan-header-right d-flex align-items-center gap-3">

            {{-- SERVICE GUIDELINE BUTTON --}}
            @if($guideline && $guideline->pdf)

                <button
                    type="button"
                    class="pan-guideline-btn"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#serviceGuidelineOffcanvas"
                    aria-controls="serviceGuidelineOffcanvas"
                >
                    <i class="fa fa-circle-info me-2"></i>
                    Service Guidelines
                </button>

            @endif

            <div class="pan-charge-card">

                <span class="pan-charge-label">
                    Service Charge
                </span>

                <span class="pan-charge-amount">
                    ₹{{ number_format($panCharge, 2) }}
                </span>

            </div>

        </div>

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
                    PAN Without Document — Service Guidelines
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"
                ></button>

            </div>

            <div class="offcanvas-body p-0 d-flex flex-column">

                <div id="pdfLoadingState" class="text-center text-muted py-5">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3"></i>
                    <p>Loading guideline document...</p>
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

    {{-- FORM --}}

    <form

        id="panApplicationForm"

        method="POST"

        action="{{ route('retailer.pan-apply-without-document.preview') }}"

        enctype="multipart/form-data"
        novalidate >   

        @csrf

        <div class="pan-form-wrapper">

            {{-- STEP 1 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            1
                        </div>

                        <div>

                            <h4>
                                Applicant Personal Information
                            </h4>

                            <p>
                                Applicant personal information
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="pan-label ">
                            Applicant First Name
                        </label>

                        <input
                            type="text"
                            name="first_name"
                            class="form-control"
                            value="{{ old('first_name', request('first_name')) }}"
                          
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label">
                            Applicant Middle Name
                        </label>

                        <input
                            type="text"
                            name="middle_name"
                            class="form-control"
                            value="{{ old('middle_name', request('middle_name')) }}"
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Applicant Last Name
                        </label>

                        <input
                            type="text"
                            name="last_name"
                            class="form-control"
                            value="{{ old('last_name', request('last_name')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">

                            Old PAN Number

                        </label>

                        <input
                            type="text"
                            name="old_pan_number"
                            maxlength="10"
                            class="form-control text-uppercase"
                            value="{{ old('old_pan_number', request('old_pan_number')) }}"
                            placeholder="ABCDE1234F"
                           
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Gender
                        </label>

                        <select
                            name="gender"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select Gender
                            </option>

                            <option
                                value="Male"
                                {{ old('gender', request('gender')) == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>

                            <option
                                value="Female"
                                {{ old('gender', request('gender')) == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>

                            <option
                                value="Transgender"
                                {{ old('gender', request('gender')) == 'Transgender' ? 'selected' : '' }}>
                                Transgender
                            </option>

                        </select>

                    </div>

                </div>

            </div>

           {{-- STEP 2 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            2
                        </div>

                        <div>

                            <h4>
                                Parents Details Information
                            </h4>

                            <p>
                                Parents information
                            </p>

                        </div>

                    </div>

                </div>

                {{-- FATHER DETAILS --}}

                <div class="parent-box father-box">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <label class="pan-label">
                                Father's First Name
                            </label>

                            <input
                                type="text"
                                name="father_first_name"
                                class="form-control"
                                value="{{ old('father_first_name', request('father_first_name')) }}"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="pan-label">
                                Father's Middle Name
                            </label>

                            <input
                                type="text"
                                name="father_middle_name"
                                class="form-control"
                                value="{{ old('father_middle_name', request('father_middle_name')) }}"
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="pan-label required">
                                Father's Last Name
                            </label>

                            <input
                                type="text"
                                name="father_last_name"
                                class="form-control"
                                value="{{ old('father_last_name', request('father_last_name')) }}"
                                required
                            >

                        </div>

                    </div>

                </div>

                {{-- MOTHER DETAILS --}}

                <div class="parent-box mother-box mt-4">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <label class="pan-label">
                                Mother's First Name
                            </label>

                            <input
                                type="text"
                                name="mother_first_name"
                                class="form-control"
                                value="{{ old('mother_first_name', request('mother_first_name')) }}"
                                required
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="pan-label">
                                Mother's Middle Name
                            </label>

                            <input
                                type="text"
                                name="mother_middle_name"
                                class="form-control"
                                value="{{ old('mother_middle_name', request('mother_middle_name')) }}"
                            >

                        </div>

                        <div class="col-md-4">

                            <label class="pan-label required">
                                Mother's Last Name
                            </label>

                            <input
                                type="text"
                                name="mother_last_name"
                                class="form-control"
                                value="{{ old('mother_last_name', request('mother_last_name')) }}"
                                required
                            >

                        </div>

                    </div>

                </div>

                {{-- PAN CARD NAME --}}

                <div class="parent-box mt-4">

                    <div class="row">

                        <div class="col-md-12">

                            <label class="pan-label required">
                                Name to be printed on PAN card
                            </label>

                            <select
                                name="pan_print_name"
                                class="form-select"
                                required
                            >

                                <option
                                    value="Father"
                                    {{ old('pan_print_name', request('pan_print_name')) == 'Father' ? 'selected' : '' }}>
                                    Father
                                </option>

                                <option
                                    value="Mother"
                                    {{ old('pan_print_name', request('pan_print_name')) == 'Mother' ? 'selected' : '' }}>
                                    Mother
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

            {{-- STEP 3 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            3
                        </div>

                        <div>

                            <h4>
                                Contact & Email Details
                            </h4>

                            <p>
                                Contact and email information
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Country Code
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            value="INDIA (91)"
                            readonly
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Mobile Number
                        </label>

                        <input
                            type="text"
                            name="mobile_no"
                            maxlength="10"
                            class="form-control"
                            value="{{ old('mobile_no', request('mobile_no')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Email ID
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', request('email')) }}"
                            required
                        >

                    </div>

                </div>

            </div>

           {{-- STEP 4 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            4
                        </div>

                        <div>

                            <h4>
                                Address Details Information
                            </h4>

                            <p>
                                Address information
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="pan-label required">
                            House No. / Street / Mohalla
                        </label>

                        <input
                            type="text"
                            name="house_no"
                            class="form-control"
                            value="{{ old('house_no', request('house_no')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Village / Colony / Ward
                        </label>

                        <input
                            type="text"
                            name="village"
                            class="form-control"
                            value="{{ old('village', request('village')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Post Office / Police Station
                        </label>

                        <input
                            type="text"
                            name="post_office"
                            class="form-control"
                            value="{{ old('post_office', request('post_office')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Area / Locality / Tehsil
                        </label>

                        <input
                            type="text"
                            name="area"
                            class="form-control"
                            value="{{ old('area', request('area')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            State
                        </label>

                        <select
                            name="state"
                            id="state"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Select State
                            </option>

                            @foreach($states as $state)

                                <option
                                    value="{{ $state->id }}"
                                    {{ old('state', request('state')) == $state->id ? 'selected' : '' }}>

                                    {{ $state->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            District
                        </label>

                        <select
                            name="district"
                            id="district"
                            class="form-control"
                            required
                        >

                            <option value="">
                                Select District
                            </option>

                            @if(old('district', request('district_name')))

                                <option
                                    value="{{ old('district', request('district')) }}"
                                    selected>

                                    {{ old('district_name', request('district_name')) }}

                                </option>

                            @endif

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            PIN Code
                        </label>

                        <input
                            type="text"
                            name="pincode"
                            maxlength="6"
                            class="form-control"
                            value="{{ old('pincode', request('pincode')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Proof of Identity
                        </label>

                        <select
                            name="identity_proof"
                            class="form-select"
                            required
                        >

                            <option
                                value="AADHAAR"
                                {{ old('identity_proof', request('identity_proof')) == 'AADHAAR' ? 'selected' : '' }}>

                                Aadhaar Card issued by UIDAI

                            </option>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Proof of Address
                        </label>

                        <select
                            name="address_proof"
                            class="form-select"
                            required
                        >

                            <option
                                value="AADHAAR"
                                {{ old('address_proof', request('address_proof')) == 'AADHAAR' ? 'selected' : '' }}>

                                Aadhaar Card issued by UIDAI

                            </option>

                        </select>

                    </div>

                </div>

            </div>


           {{-- STEP 5 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            5
                        </div>

                        <div>

                            <h4>
                                Date of Birth & Proof of DOB
                            </h4>

                            <p>
                                DOB verification details
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Date of Birth
                        </label>

                        <input
                            type="text"
                            name="dob"
                            id="dob"
                            class="form-control"
                            placeholder="DD/MM/YYYY"
                            value="{{ old('dob', request('dob')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Re-enter DOB
                        </label>

                       <input
                            type="text"
                            name="confirm_dob"
                            id="confirm_dob"
                            class="form-control"
                            placeholder="DD/MM/YYYY"
                            value="{{ old('confirm_dob', request('confirm_dob')) }}"
                            required
                        >
                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Proof of DOB
                        </label>

                        <select
                            name="dob_proof"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Please Select
                            </option>

                            <option
                                value="AADHAAR"
                                {{ old('dob_proof', request('dob_proof')) == 'AADHAAR' ? 'selected' : '' }}>

                                Aadhaar Card

                            </option>

                            <option
                                value="Birth Certificate"
                                {{ old('dob_proof', request('dob_proof')) == 'Birth Certificate' ? 'selected' : '' }}>

                                Birth Certificate

                            </option>

                        </select>

                    </div>

                </div>


            </div>

           {{-- STEP 6 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            7
                        </div>

                        <div>

                            <h4>
                                Aadhaar Details
                            </h4>

                            <p>
                                Aadhaar information
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Aadhaar Number
                        </label>

                        <input
                            type="text"
                            name="aadhaar_no"
                            maxlength="12"
                            class="form-control"
                            value="{{ old('aadhaar_no', request('aadhaar_no')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-6">

                        <label class="pan-label required">
                            Customer Name (as per Aadhaar Card)
                        </label>

                        <input
                            type="text"
                            name="aadhaar_name"
                            class="form-control"
                            value="{{ old('aadhaar_name', request('aadhaar_name')) }}"
                            required
                        >

                    </div>

                </div>

            </div>

           {{-- STEP 7 --}}
            <div class="pan-step-card">

                <div class="step-heading">

                    <div class="step-left">

                        <div class="step-number">
                            7
                        </div>

                        <div>

                            <h4>
                                Signature Details
                            </h4>

                            <p>
                                Signature / Thumb impression
                            </p>

                        </div>

                    </div>

                </div>

                <div class="signature-wrapper">

                    <label class="signature-option">

                        <input
                            type="radio"
                            name="signature_type"
                            value="Signature"
                            {{ old('signature_type', request('signature_type')) == 'Signature' ? 'checked' : '' }}
                            required
                        >

                        <div>

                            <h5>
                                Signature
                            </h5>

                            <p>
                                If applicant is literate
                            </p>

                        </div>

                    </label>

                    <label class="signature-option">

                        <input
                            type="radio"
                            name="signature_type"
                            value="Thumb Impression"
                            {{ old('signature_type', request('signature_type')) == 'Thumb Impression' ? 'checked' : '' }}
                        >

                        <div>

                            <h5>
                                Thumb Impression
                            </h5>

                            <p>
                                If applicant is illiterate
                            </p>

                        </div>

                    </label>

                </div>

            </div>


            {{-- STEP 8 DOCUMENTS --}}

                    @php

                        function cloudFileExtension($file)
                        {
                            if (!$file) {
                                return null;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | LOCAL FILE
                            |--------------------------------------------------------------------------
                            */

                            $ext = strtolower(
                                pathinfo(
                                    $file,
                                    PATHINFO_EXTENSION
                                )
                            );

                            if (!empty($ext)) {
                                return $ext;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | CLOUDINARY URL
                            |--------------------------------------------------------------------------
                            */

                            $url = file_url($file);

                            if (!$url) {
                                return null;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | PDF UPLOADED AS RAW
                            |--------------------------------------------------------------------------
                            */

                            if (
                                str_contains(
                                    strtolower($url),
                                    '/raw/upload/'
                                )
                            ) {
                                return 'pdf';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | IMAGE
                            |--------------------------------------------------------------------------
                            */

                            return 'jpg';
                        }


                      $documents = [

                            [
                                'title' => 'Applicant Photo',
                                'name'  => 'photo',
                                'icon'  => 'fa-camera',
                                'text'  => 'JPG / PNG • Max 5 MB',
                                'accept'=> '.jpg,.jpeg,.png'
                            ],

                            [
                                'title' => 'Signature',
                                'name'  => 'signature',
                                'icon'  => 'fa-signature',
                                'text'  => 'JPG / PNG • Max 5 MB',
                                'accept'=> '.jpg,.jpeg,.png'
                            ],

                            [
                                'title' => 'Aadhaar Card',
                                'name'  => 'aadhaar_card',
                                'icon'  => 'fa-id-card',
                                'text'  => 'JPG / PNG / PDF • Max 5 MB',
                                'accept'=> '.jpg,.jpeg,.png,.pdf'
                            ],

                            [
                                'title' => 'DOB Proof',
                                'name'  => 'dob_proof_file',
                                'icon'  => 'fa-calendar-days',
                                'text'  => 'JPG / PNG / PDF • Max 5 MB',
                                'accept'=> '.jpg,.jpeg,.png,.pdf'
                            ],

                            [
                                'title' => 'Supporting Document',
                                'name'  => 'supporting_document',
                                'icon'  => 'fa-file-circle-plus',
                                'text'  => 'JPG / PNG / PDF • Max 5 MB',
                                'accept'=> '.jpg,.jpeg,.png,.pdf'
                            ]

                        ];

                    @endphp


                    <div class="pan-step-card">

                        <div class="step-heading">

                            <div class="step-left">

                                <div class="step-number">
                                    8
                                </div>

                                <div>

                                    <h4>
                                        Upload Documents
                                    </h4>

                                    <p>
                                        Upload all mandatory PAN application documents
                                    </p>

                                </div>

                            </div>

                        </div>


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

                                    $ext =
                                        $exists
                                        ?
                                        cloudFileExtension($file)
                                        :
                                        null;

                                    $isPdf =
                                        $ext === 'pdf';

                                @endphp


                                <div class="col-lg-3 col-md-6">

                                    <div class="upload-wrapper">

                                        <label class="upload-box">

                                            <input
                                                type="file"
                                                name="{{ $doc['name'] }}"
                                                class="document-input d-none"
                                                accept="{{ $doc['accept'] }}"
                                                data-max-size="5242880"
                                            >

                                            <div class="upload-preview">

                                                @if($exists)

                                                    @if($isPdf)

                                                        
                                                            href="{{ file_url($file) }}"
                                                            target="_blank"
                                                        >

                                                            <img
                                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                                class="preview-image"
                                                                alt="PDF Document"
                                                            >
                                                        </a>

                                                    @else

                                                        <img
                                                            src="{{ file_url($file) }}"
                                                            class="preview-image"
                                                            alt="{{ $doc['title'] }}"
                                                            loading="lazy"
                                                            onerror="
                                                                this.onerror=null;
                                                                this.src='{{ asset('assets/images/no-image.png') }}';
                                                            "
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

                                            <span class="file-size text-primary fw-bold small"></span>

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



                            {{-- EXISTING FILES --}}

                            @foreach($documents as $doc)

                                @php
                                    $field = $doc['name'];
                                @endphp

                                @if(
                                    !empty($files[$field])
                                    &&
                                    file_exists_custom($files[$field])
                                )

                                    <input
                                        type="hidden"
                                        name="existing_files[{{ $field }}]"
                                        value="{{ $files[$field] }}"
                                    >

                                @endif

                            @endforeach

                        </div>

                    </div>
            
            {{-- PREVIEW BUTTON --}}

            <div class="submit-wrapper">

                <button
                    type="button"
                    id="previewBtn"
                    class="submit-pan-btn"
                >

                    <i class="fa fa-eye me-2"></i>

                    Preview Application

                </button>

            </div>

        </div>

    </form>

</div>

@endsection

@section('styles')

<style>

    .pan-header-right {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pan-guideline-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
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

    /* HALF-WINDOW PANEL */
    .pan-guideline-offcanvas {
        width: 50% !important;
    }

    .pan-guideline-offcanvas .offcanvas-body {
        padding: 0;
        height: 100%;
    }

    #pdfLoadingState {
        flex: 0 0 auto;
    }

    #guidelinePdfFrame {
        flex: 1 1 auto;
        width: 100%;
        border: 0;
    }

    @media (max-width: 991px) {

        .pan-guideline-offcanvas {
            width: 100% !important;
        }

    }

</style>

@endsection

@section('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | SERVICE GUIDELINE PDF (LAZY LOAD ON OPEN)
    |--------------------------------------------------------------------------
    */

    let guidelineOffcanvasEl =
        document.getElementById('serviceGuidelineOffcanvas');

    if (guidelineOffcanvasEl) {

        guidelineOffcanvasEl.addEventListener(
            'show.bs.offcanvas',
            function () {

                let iframe =
                    document.getElementById('guidelinePdfFrame');

                if (iframe && !iframe.dataset.loaded) {

                    iframe.addEventListener('load', function () {

                        document.getElementById('pdfLoadingState')
                            .style.display = 'none';

                        iframe.style.display = 'block';

                    });

                    iframe.src = iframe.dataset.src;

                    iframe.dataset.loaded = 'true';

                }

            }
        );

    }

    /*
    |--------------------------------------------------------------------------
    | DISTRICT LOAD
    |--------------------------------------------------------------------------
    */

    function loadDistricts(stateId, selectedDistrict = null) {

        if (stateId === '') {

            $('#district').html(
                '<option value="">Select District</option>'
            );

            return;
        }

        $('#district').html(
            '<option value="">Loading...</option>'
        );

        $.ajax({

            url:
            `{{ route('retailer.get.districts', ':id') }}`
            .replace(':id', stateId),

            type: "GET",

            dataType: "json",

            success: function (districts) {

                $('#district').html(
                    '<option value="">Select District</option>'
                );

                $.each(districts, function (key, district) {

                    let selected = '';

                    if (
                        selectedDistrict &&
                        selectedDistrict == district.id
                    ) {

                        selected = 'selected';

                    }

                    $('#district').append(`

                        <option
                            value="${district.id}"
                            ${selected}>

                            ${district.name}

                        </option>

                    `);

                });

            },

            error: function () {

                $('#district').html(
                    '<option value="">District not found</option>'
                );

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | STATE CHANGE
    |--------------------------------------------------------------------------
    */

    $('#state').change(function () {

        let stateId = $(this).val();

        loadDistricts(stateId);

    });

    /*
    |--------------------------------------------------------------------------
    | RESTORE DISTRICT
    |--------------------------------------------------------------------------
    */

    let selectedState =
    "{{ old('state', $data['state'] ?? '') }}";

    let selectedDistrict =
    "{{ old('district', $data['district'] ?? '') }}";

    if (
        selectedState &&
        selectedDistrict
    ) {

        loadDistricts(
            selectedState,
            selectedDistrict
        );

    }

    /*
    |--------------------------------------------------------------------------
    | TEXT VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[type="text"]').on('input', function () {

        let fieldName = $(this).attr('name');

        let skipFields = [
            'mobile_no',
            'aadhaar_no',
            'pincode',
            'old_pan_number',
            'house_no',
            'dob',
            'confirm_dob'
        ];

        if (!skipFields.includes(fieldName)) {

            this.value = this.value.replace(
                /[^a-zA-Z\s]/g,
                ''
            );

        }

    });

    /*
    |--------------------------------------------------------------------------
    | MOBILE VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[name="mobile_no"]').on('input', function () {

        this.value = this.value.replace(/\D/g, '');

        if (this.value.length > 10) {

            this.value =
            this.value.slice(0, 10);

        }

    });


    /*
    |--------------------------------------------------------------------------
    | OLD PAN VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[name="old_pan_number"]').on('input', function () {

        this.value = this.value.toUpperCase();

        this.value = this.value.replace(

            /[^A-Z0-9]/g,

            ''

        );

    });

    /*
    |--------------------------------------------------------------------------
    | PINCODE VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[name="pincode"]').on('input', function () {

        this.value = this.value.replace(/\D/g, '');

        if (this.value.length > 6) {

            this.value =
            this.value.slice(0, 6);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | AADHAAR VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[name="aadhaar_no"]').on('input', function () {

        this.value = this.value.replace(/\D/g, '');

        if (this.value.length > 12) {

            this.value =
            this.value.slice(0, 12);

        }

    });

    /*
    |--------------------------------------------------------------------------
    | EMAIL VALIDATION
    |--------------------------------------------------------------------------
    */

    $('input[name="email"]').on('blur', function () {

        let email = $(this).val();

        let regex =
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!regex.test(email)) {

            $(this).addClass('is-invalid');

        } else {

            $(this).removeClass('is-invalid');

        }

    });

    /*
    |--------------------------------------------------------------------------
    | DOB MATCH
    |--------------------------------------------------------------------------
    */

    $('#dob, #confirm_dob').on('change', function () {

    let dob = $('#dob').val().trim();
    let confirmDob = $('#confirm_dob').val().trim();

    if (
        confirmDob !== '' &&
        dob !== confirmDob
    ) {
        $('#confirm_dob').addClass('is-invalid');
    } else {
        $('#confirm_dob').removeClass('is-invalid');
        }
    });


   $('.document-input').change(function () {


        let file = this.files[0];

        if (!file) {
            return;
        }

        let wrapper =
            $(this).closest('.upload-wrapper');

        let previewImage =
            wrapper.find('.preview-image');

        let fileDetails =
            wrapper.find('.file-details');

        let fileName =
            wrapper.find('.file-name');

        let fileSize =
            wrapper.find('.file-size');

        let defaultUpload =
            wrapper.find('.default-upload');

        let errorBox =
            wrapper.find('.file-error');

        /*
        |--------------------------------------------------------------------------
        | CLEAR OLD ERROR
        |--------------------------------------------------------------------------
        */

        errorBox.html('');

        /*
        |--------------------------------------------------------------------------
        | VALID FILE TYPES
        |--------------------------------------------------------------------------
        */

        let allowedTypes = [

            'image/jpeg',
            'image/png',
            'application/pdf'

        ];

        if (!allowedTypes.includes(file.type)) {

            errorBox.html(
                'Only JPG, PNG and PDF files are allowed.'
            );

            $(this).val('');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FILE SIZE VALIDATION (5 MB)
        |--------------------------------------------------------------------------
        */

        if (file.size > 5242880) {

            errorBox.html(
                'File size cannot exceed 5 MB.'
            );

            $(this).val('');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FILE DETAILS
        |--------------------------------------------------------------------------
        */

        fileName.text(file.name);

        fileSize.text(
            '(' +
            (file.size / 1024 / 1024).toFixed(2) +
            ' MB)'
        );

        fileDetails.removeClass('d-none');

        /*
        |--------------------------------------------------------------------------
        | PREVIEW
        |--------------------------------------------------------------------------
        */

        if (file.type !== 'application/pdf') {

            let reader =
                new FileReader();

            reader.onload =
            function (e) {

                previewImage

                .attr(
                    'src',
                    e.target.result
                )

                .removeClass('d-none');

                defaultUpload
                .addClass('d-none');
            };

            reader.readAsDataURL(file);

        } else {

            previewImage

            .attr(
                'src',
                'https://cdn-icons-png.flaticon.com/512/337/337946.png'
            )

            .removeClass('d-none');

            defaultUpload
            .addClass('d-none');
        }
        

        });

    /*
    |--------------------------------------------------------------------------
    | RESTORE EXISTING PREVIEW
    |--------------------------------------------------------------------------
    */

    $('.upload-wrapper').each(function () {

        let wrapper =
        $(this);

        let previewImage =
        wrapper.find('.preview-image');

        let fileDetails =
        wrapper.find('.file-details');

        let defaultUpload =
        wrapper.find('.default-upload');

        if (

            previewImage.attr('src') &&
            previewImage.attr('src') !== ''

        ) {

            previewImage.removeClass('d-none');

            fileDetails.removeClass('d-none');

            defaultUpload.addClass('d-none');

        }

    });

    /*
    |--------------------------------------------------------------------------
    | REMOVE FILE
    |--------------------------------------------------------------------------
    */

    $('.remove-file-btn').click(function () {

        let wrapper =
        $(this).closest('.upload-wrapper');

        let fileInput =
        wrapper.find('.document-input');

        let inputName =
        fileInput.attr('name');

        /*
        |--------------------------------------------------------------------------
        | CLEAR INPUT
        |--------------------------------------------------------------------------
        */

        fileInput.val('');

        /*
        |--------------------------------------------------------------------------
        | CLEAR IMAGE
        |--------------------------------------------------------------------------
        */

        wrapper.find('.preview-image')

        .attr('src', '')

        .addClass('d-none');

        /*
        |--------------------------------------------------------------------------
        | SHOW DEFAULT
        |--------------------------------------------------------------------------
        */

        wrapper.find('.default-upload')

        .removeClass('d-none');

        /*
        |--------------------------------------------------------------------------
        | HIDE DETAILS
        |--------------------------------------------------------------------------
        */

        wrapper.find('.file-details')

        .addClass('d-none');

        /*
        |--------------------------------------------------------------------------
        | REMOVE EXISTING FILE
        |--------------------------------------------------------------------------
        */

        $(
            'input[name="existing_files['
            + inputName +
            ']"]'
        ).remove();

    });

    /*
    |--------------------------------------------------------------------------
    | CHECK FILE EXIST
    |--------------------------------------------------------------------------
    */

    function hasFile(inputName) {

    let input = $(
        'input[name="' + inputName + '"]'
    );

    if (input.length === 0) {
        return true;
    }

    let newFile = 0;

    if (
        input[0] &&
        input[0].files
    ) {
        newFile =
        input[0].files.length;
    }

    let existingFile = $(
        'input[name="existing_files['
        + inputName +
        ']"]'
    ).val();

    return (
        newFile > 0 ||
        !!existingFile
    );
}
    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#previewBtn').on('click', function (e) {

    e.preventDefault();

    let form =
    document.getElementById(
        'panApplicationForm'
    );

    if (!form) {

        console.error(
            'Form not found'
        );

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | HTML VALIDATION
    |--------------------------------------------------------------------------
    */

    if (!form.checkValidity()) {

        form.reportValidity();

        return;
    }

    /*
    |--------------------------------------------------------------------------
    | FILE VALIDATION
    |--------------------------------------------------------------------------
    */

    if (!hasFile('photo')) {

        Swal.fire({
            icon: 'warning',
            title: 'Photo Required',
            text: 'Please upload applicant photo.'
        });

        return;
    }

    if (!hasFile('signature')) {

        Swal.fire({
            icon: 'warning',
            title: 'Signature Required',
            text: 'Please upload signature.'
        });

        return;
    }

    if (!hasFile('aadhaar_card')) {

        Swal.fire({
            icon: 'warning',
            title: 'Aadhaar Required',
            text: 'Please upload Aadhaar card.'
        });

        return;
    }


    Swal.fire({

        title: 'Continue To Preview?',

        text:
        'Please verify all entered details carefully.',

        icon: 'question',

        showCancelButton: true,

        confirmButtonText:
        'Yes Continue',

        cancelButtonText:
        'Cancel'

    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        let formData =
        new FormData(form);

        $.ajax({

            url: form.action,

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            headers: {

                'X-CSRF-TOKEN':
                $('meta[name="csrf-token"]')
                .attr('content')

            },

            beforeSend: function () {

                Swal.fire({

                    title:
                    'Preparing Preview',

                    text:
                    'Please wait...',

                    allowOutsideClick:
                    false,

                    didOpen: () => {

                        Swal.showLoading();

                    }

                });

            },

            success: function (response) {

                Swal.close();

                console.log(
                    'Response:',
                    response
                );

                if (
                    response &&
                    response.status &&
                    response.redirect_url
                ) {

                    window.location.href =
                    response.redirect_url;

                    return;
                }

                Swal.fire({

                    icon: 'error',

                    title:
                    'Invalid Response',

                    text:
                    'Redirect URL not found.'

                });

            },

           error: function(xhr)
            {
                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    Swal.fire({
                        icon:'error',
                        title:'Validation Error',
                        text:'Please fix highlighted fields.'
                    });

                    return;
                }

                Swal.fire({
                    icon:'error',
                    title:'Error',
                    text:
                        xhr.responseJSON?.message
                        ??
                        'Something went wrong'
                });
            }

        });

    });

});

});

</script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    flatpickr("#dob", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });

    flatpickr("#confirm_dob", {
        dateFormat: "d/m/Y",
        allowInput: true,
        maxDate: "today"
    });

});
</script>

@endsection