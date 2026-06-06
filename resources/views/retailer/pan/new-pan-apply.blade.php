@extends('layout.retailer')

@section('title', 'Apply New PAN')


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

                    Apply New PAN Card

                </h1>

                <p>

                    Fill all applicant details carefully before preview submission

                </p>

            </div>

        </div>

    </div>

    {{-- FORM --}}

    <form

        id="panApplicationForm"

        method="POST"

        action="{{ route('retailer.pan.preview') }}"

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

                        <label class="pan-label required">
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
                            id="house_no"
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
                            type="date"
                            name="dob"
                            class="form-control"
                            value="{{ old('dob', request('dob')) }}"
                            required
                        >

                    </div>

                    <div class="col-md-4">

                        <label class="pan-label required">
                            Re-enter DOB
                        </label>

                        <input
                            type="date"
                            name="confirm_dob"
                            class="form-control"
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

                $documents = [

                [
                    'title' => 'Applicant Photo',
                    'name' => 'photo',
                    'icon' => 'fa-camera',
                    'text' => 'JPG / PNG (Max 5 MB)',
                    'accept' => '.jpg,.jpeg,.png',
                    'max_size' => 5120
                ],

                [
                    'title' => 'Signature',
                    'name' => 'signature',
                    'icon' => 'fa-signature',
                    'text' => 'JPG / PNG (Max 5 MB)',
                    'accept' => '.jpg,.jpeg,.png',
                    'max_size' => 5120
                ],

                [
                    'title' => 'Aadhaar Card',
                    'name' => 'aadhaar_card',
                    'icon' => 'fa-id-card',
                    'text' => 'JPG / PNG / PDF (Max 5 MB)',
                    'accept' => '.jpg,.jpeg,.png,.pdf',
                    'max_size' => 5120
                ],

                [
                    'title' => 'DOB Proof',
                    'name' => 'dob_proof_file',
                    'icon' => 'fa-calendar-days',
                    'text' => 'JPG / PNG / PDF (Max 5 MB)',
                    'accept' => '.jpg,.jpeg,.png,.pdf',
                    'max_size' => 5120
                ],

                [
                    'title' => 'Supporting Document',
                    'name' => 'supporting_document',
                    'icon' => 'fa-file-circle-plus',
                    'text' => 'JPG / PNG / PDF (Max 5 MB)',
                    'accept' => '.jpg,.jpeg,.png,.pdf',
                    'max_size' => 5120
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

                            @endphp

                            <div class="col-lg-3 col-md-6">

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
@section('scripts')

<script>

$(document).ready(function () {

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

        let allowNumericFields = [

            'mobile_no',
            'aadhaar_no',
            'pincode',
            'house_no'

        ];

        if (!allowNumericFields.includes(fieldName)) {

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

    $('input[name="confirm_dob"]').change(function () {

        let dob =
        $('input[name="dob"]').val();

        let confirmDob =
        $(this).val();

        if (dob !== confirmDob) {

            $(this).addClass('is-invalid');

        } else {

            $(this).removeClass('is-invalid');

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

        errorBox.html('');

        /*
        |--------------------------------------------------------------------------
        | FILE TYPE VALIDATION
        |--------------------------------------------------------------------------
        */

        let allowedTypes = [

            'image/jpeg',
            'image/png',
            'application/pdf'

        ];

        if (!allowedTypes.includes(file.type)) {

            errorBox.html(
                'Only JPG, PNG and PDF files are allowed'
            );

            $(this).val('');

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | FILE SIZE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (file.size > 5242880) {

            errorBox.html(
                'File size must not exceed 5 MB'
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

        if (file.type === 'application/pdf') {

            previewImage

            .attr(
                'src',
                'https://cdn-icons-png.flaticon.com/512/337/337946.png'
            )

            .removeClass('d-none');

            defaultUpload.addClass('d-none');

        } else {

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

        let newFile =

        $(
            'input[name="' + inputName + '"]'
        )[0].files.length;

        let existingFile =

        $(
            'input[name="existing_files['
            + inputName +
            ']"]'
        ).val();

        return newFile || existingFile;

    }

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#previewBtn').click(function () {

        let form =
        document.getElementById(
            'panApplicationForm'
        );

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

                text:
                'Please upload applicant photo.'

            });

            return;
        }

        if (!hasFile('signature')) {

            Swal.fire({

                icon: 'warning',

                title: 'Signature Required',

                text:
                'Please upload signature.'

            });

            return;
        }

        if (!hasFile('aadhaar_card')) {

            Swal.fire({

                icon: 'warning',

                title: 'Aadhaar Required',

                text:
                'Please upload Aadhaar card.'

            });

            return;
        }

      
        /*
        |--------------------------------------------------------------------------
        | CONFIRMATION
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title:
            'Continue To Preview?',

            text:
            'Please verify all entered details carefully.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText:
            'Yes Continue',

            cancelButtonText:
            'Cancel'

        }).then((result) => {

            if (result.isConfirmed) {

                console.log('============== FILES ==============');

                let totalSize = 0;

                $('.document-input').each(function () {

                    if (this.files.length > 0) {

                        let file = this.files[0];

                        totalSize += file.size;

                        console.log(
                            this.name,
                            file.name,
                            (file.size / 1024 / 1024).toFixed(2) + ' MB'
                        );
                    }
                });

                console.log(
                    'TOTAL SIZE:',
                    
                    (totalSize / 1024 / 1024).toFixed(2) + ' MB'
                );

                let formData =
                new FormData(

                    $('#panApplicationForm')[0]

                );

                /*
                |--------------------------------------------------------------------------
                | AJAX REQUEST
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url:
                    $('#panApplicationForm')
                    .attr('action'),

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    beforeSend: function () {

                        Swal.fire({

                            title:
                            'Preparing Preview',

                            text:
                            'Please wait...',

                            allowOutsideClick: false,

                            didOpen: () => {

                                Swal.showLoading();

                            }

                        });

                    },

                    success: function (response) {

                        Swal.close();

                        if (response.status) {

                            window.location.href =
                            response.redirect_url;

                        }

                    },

                    error: function (xhr) {

                        Swal.close();

                        if (xhr.status === 422) {

                            Swal.fire({

                                icon: 'error',

                                title:
                                'Validation Error',

                                text:
                                'Please fix highlighted fields.'

                            });

                        } else {

                            Swal.fire({

                                icon: 'error',

                                title:
                                'Server Error',

                                text:
                                'Something went wrong.'

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