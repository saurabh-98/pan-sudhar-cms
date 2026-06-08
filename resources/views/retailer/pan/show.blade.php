@extends('layout.retailer')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp

<div class="container-fluid pan-show-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="show-header">

        <div>

            <h2 class="show-title">

                PAN Application Details

            </h2>

            <p class="show-subtitle">

                View complete PAN application information.

            </p>

        </div>

        <div class="d-flex flex-wrap gap-2">

            {{-- BACK --}}
            <a
                href="{{ route('retailer.pan.history') }}"
                class="btn show-btn-dark"
            >

                <i class="fas fa-arrow-left me-2"></i>

                Back

            </a>

        </div>

    </div>

    {{-- =====================================================
    | STATUS CARD
    ====================================================== --}}
    <div class="card show-card mb-4">

        <div class="card-body">

            <div class="row g-4">

                {{-- APPLICATION NO --}}
                <div class="col-lg-3 col-md-6">

                    <div class="status-box">

                        <label>

                            Application No

                        </label>

                        <h5>

                            {{ $application->application_no }}

                        </h5>

                    </div>

                </div>

                {{-- STATUS --}}
                <div class="col-lg-3 col-md-6">

                    <div class="status-box">

                        <label>

                            Application Status

                        </label>

                        <div>

                            {!! $application->status_badge !!}

                        </div>

                    </div>

                </div>

                {{-- PAYMENT --}}
                <div class="col-lg-3 col-md-6">

                    <div class="status-box">

                        <label>

                            Payment Status

                        </label>

                        <div>

                            {!! $application->payment_badge !!}

                        </div>

                    </div>

                </div>

                {{-- DATE --}}
                <div class="col-lg-3 col-md-6">

                    <div class="status-box">

                        <label>

                            Submitted Date

                        </label>

                        <h5>

                            {{ $application->created_at }}

                        </h5>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | PERSONAL DETAILS
    ====================================================== --}}
    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Personal Details

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- FIRST NAME --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            First Name

                        </label>

                        <h6>

                            {{ $application->first_name }}

                        </h6>

                    </div>

                </div>

                {{-- MIDDLE NAME --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Middle Name

                        </label>

                        <h6>

                            {{ $application->middle_name ?? 'N/A' }}

                        </h6>

                    </div>

                </div>

                {{-- LAST NAME --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Last Name

                        </label>

                        <h6>

                            {{ $application->last_name }}

                        </h6>

                    </div>

                </div>

                {{-- GENDER --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Gender

                        </label>

                        <h6>

                            {{ $application->gender }}

                        </h6>

                    </div>

                </div>

                {{-- DOB --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Date Of Birth

                        </label>

                        <h6>

                            {{ $application->dob_formatted }}

                        </h6>

                    </div>

                </div>

                {{-- MOBILE --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Mobile Number

                        </label>

                        <h6>

                            {{ $application->mobile_no }}

                        </h6>

                    </div>

                </div>

                {{-- EMAIL --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Email Address

                        </label>

                        <h6>

                            {{ $application->email }}

                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | FATHER DETAILS
    ====================================================== --}}
    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Father Details

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Father First Name

                        </label>

                        <h6>

                            {{ $application->father_first_name }}

                        </h6>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Father Middle Name

                        </label>

                        <h6>

                            {{ $application->father_middle_name ?? 'N/A' }}

                        </h6>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Father Last Name

                        </label>

                        <h6>

                            {{ $application->father_last_name }}

                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | MOTHER DETAILS
    ====================================================== --}}
    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Mother Details

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Mother First Name

                        </label>

                        <h6>

                            {{ $application->mother_first_name }}

                        </h6>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Mother Middle Name

                        </label>

                        <h6>

                            {{ $application->mother_middle_name ?? 'N/A' }}

                        </h6>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Mother Last Name

                        </label>

                        <h6>

                            {{ $application->mother_last_name }}

                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | ADDRESS DETAILS
    ====================================================== --}}
    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Address Details

        </div>

        <div class="card-body">

            <div class="row g-4">

                {{-- HOUSE NO --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            House No

                        </label>

                        <h6>

                            {{ $application->house_no }}

                        </h6>

                    </div>

                </div>

                {{-- VILLAGE --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Village

                        </label>

                        <h6>

                            {{ $application->village }}

                        </h6>

                    </div>

                </div>

                {{-- POST OFFICE --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Post Office

                        </label>

                        <h6>

                            {{ $application->post_office }}

                        </h6>

                    </div>

                </div>

                {{-- AREA --}}
                <div class="col-md-3">

                    <div class="show-box">

                        <label>

                            Area

                        </label>

                        <h6>

                            {{ $application->area }}

                        </h6>

                    </div>

                </div>

                {{-- STATE --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            State

                        </label>

                        <h6>

                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>

                             {{ $application->stateData->name ?? 'N/A' }}

                        </h6>

                    </div>

                </div>

                {{-- DISTRICT --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            District

                        </label>

                        <h6>

                            <i class="fas fa-city me-2 text-primary"></i>

                             {{ $application->districtData->name ?? 'N/A' }}

                        </h6>

                    </div>

                </div>

                {{-- PINCODE --}}
                <div class="col-md-4">

                    <div class="show-box">

                        <label>

                            Pincode

                        </label>

                        <h6>

                            {{ $application->pincode }}

                        </h6>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | DOCUMENTS
    ====================================================== --}}

    @php

        function documentExtension($file)
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
            | CLOUDINARY RAW PDF
            |--------------------------------------------------------------------------
            */

            $url = file_url($file);

            if (
                $url &&
                str_contains(
                    strtolower($url),
                    '/raw/upload/'
                )
            ) {
                return 'pdf';
            }

            /*
            |--------------------------------------------------------------------------
            | DEFAULT IMAGE
            |--------------------------------------------------------------------------
            */

            return 'jpg';
        }


        $documents = [

            'Photo' =>
                $application->photo,

            'Signature' =>
                $application->signature,

            'Aadhaar Card' =>
                $application->aadhaar_card,

            'DOB Proof' =>
                $application->dob_proof_file,

            'Supporting Document' =>
                $application->supporting_document,

        ];

    @endphp

    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Uploaded Documents

        </div>

        <div class="card-body">

            <div class="row g-4">

                @foreach($documents as $title => $file)

                    <div class="col-xl-3 col-lg-4 col-md-6">

                        <div class="document-card">

                            <div class="document-title">

                                {{ $title }}

                            </div>

                            <div class="document-preview">

                                @if(!empty($file))

                                    @php

                                        $url = file_url($file);

                                        $extension =
                                            documentExtension($file);

                                        $isPdf =
                                            $extension === 'pdf';

                                    @endphp

                                    @if($isPdf)

                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                            class="text-decoration-none"
                                        >

                                            <div class="pdf-preview">

                                                <i class="fas fa-file-pdf fa-4x text-danger"></i>

                                                <div class="mt-2">

                                                    PDF Document

                                                </div>

                                            </div>

                                        </a>

                                    @else

                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                        >

                                            <img
                                                src="{{ $url }}"
                                                class="img-fluid"
                                                alt="{{ $title }}"
                                                loading="lazy"
                                                onerror="
                                                    this.onerror=null;
                                                    this.src='{{ asset('assets/images/no-image.png') }}';
                                                "
                                            >

                                        </a>

                                    @endif

                                @else

                                    <span class="text-danger">

                                        Not Uploaded

                                    </span>

                                @endif

                            </div>

                            @if(!empty($file))

                                <div class="document-footer">

                                    <a
                                        href="{{ $url }}"
                                        target="_blank"
                                        class="btn document-btn"
                                    >

                                        <i class="fas fa-eye me-2"></i>

                                        View Document

                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>
    
</div>

@endsection