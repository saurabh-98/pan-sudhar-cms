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

                            {{ $application->created_at->format('d M Y') }}

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

                            {{ $application->dob }}

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
<div class="card show-card mb-4">

    <div class="card-header show-card-header">
        Uploaded Documents
    </div>

    <div class="card-body">

        <div class="row g-4">


            {{-- PHOTO --}}
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="document-card">

                    <div class="document-title">
                        Photo
                    </div>

                    <div class="document-preview">

                        @if(!empty($application->photo))

                            <img
                                src="{{ file_url($application->photo) }}"
                                class="img-fluid"
                                alt="Photo"
                            >

                        @else

                            <span class="text-danger">
                                Not Uploaded
                            </span>

                        @endif

                    </div>


                    @if(!empty($application->photo))

                    <div class="document-footer">

                        <a
                            href="{{ file_url($application->photo) }}"
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



            {{-- SIGNATURE --}}
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="document-card">

                    <div class="document-title">
                        Signature
                    </div>

                    <div class="document-preview">

                        @if(!empty($application->signature))

                            <img
                                src="{{ file_url($application->signature) }}"
                                class="img-fluid"
                                alt="Signature"
                            >

                        @else

                            <span class="text-danger">
                                Not Uploaded
                            </span>

                        @endif

                    </div>


                    @if(!empty($application->signature))

                    <div class="document-footer">

                        <a
                            href="{{ file_url($application->signature) }}"
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




            {{-- AADHAAR CARD --}}
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="document-card">

                    <div class="document-title">
                        Aadhaar Card
                    </div>


                    <div class="document-preview">


                        @if(!empty($application->aadhaar_card))


                            @if(Str::contains(
                                strtolower($application->aadhaar_card),
                                '.pdf'
                            ))

                                <div class="pdf-preview">

                                    <i class="fas fa-file-pdf"></i>

                                    <span>
                                        PDF Document
                                    </span>

                                </div>

                            @else

                                <img
                                    src="{{ file_url($application->aadhaar_card) }}"
                                    class="img-fluid"
                                    alt="Aadhaar"
                                >

                            @endif


                        @else


                            <span class="text-danger">
                                Not Uploaded
                            </span>


                        @endif


                    </div>


                    @if(!empty($application->aadhaar_card))

                    <div class="document-footer">

                        <a
                            href="{{ file_url($application->aadhaar_card) }}"
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





            {{-- DOB PROOF --}}
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="document-card">


                    <div class="document-title">
                        DOB Proof
                    </div>


                    <div class="document-preview">


                        @if(!empty($application->dob_proof_file))


                            @if(Str::contains(
                                strtolower($application->dob_proof_file),
                                '.pdf'
                            ))

                                <div class="pdf-preview">

                                    <i class="fas fa-file-pdf"></i>

                                    <span>
                                        PDF Document
                                    </span>

                                </div>

                            @else


                                <img
                                    src="{{ file_url($application->dob_proof_file) }}"
                                    class="img-fluid"
                                    alt="DOB Proof"
                                >

                            @endif


                        @else

                            <span class="text-danger">
                                Not Uploaded
                            </span>

                        @endif

                    </div>



                    @if(!empty($application->dob_proof_file))

                    <div class="document-footer">

                        <a
                            href="{{ file_url($application->dob_proof_file) }}"
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






            {{-- SUPPORTING DOCUMENT --}}
            <div class="col-xl-3 col-lg-4 col-md-6">

                <div class="document-card">


                    <div class="document-title">
                        Supporting Document
                    </div>


                    <div class="document-preview">


                        @if(!empty($application->supporting_document))


                            @if(Str::contains(
                                strtolower($application->supporting_document),
                                '.pdf'
                            ))

                                <div class="pdf-preview">

                                    <i class="fas fa-file-pdf"></i>

                                    <span>
                                        PDF Document
                                    </span>

                                </div>


                            @else

                                <img
                                    src="{{ file_url($application->supporting_document) }}"
                                    class="img-fluid"
                                    alt="Supporting Document"
                                >

                            @endif


                        @else


                            <span class="text-danger">
                                Not Uploaded
                            </span>


                        @endif


                    </div>



                    @if(!empty($application->supporting_document))

                    <div class="document-footer">

                        <a
                            href="{{ file_url($application->supporting_document) }}"
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



        </div>

    </div>

</div>
</div>

@endsection