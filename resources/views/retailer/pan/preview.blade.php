@extends('layout.retailer')

@section('content')

<div class="container-fluid py-4">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>

            <h2 class="fw-bold mb-1">

                PAN Application Preview

            </h2>

            <p class="text-muted mb-0">

                Please verify all details before final submission.

            </p>

        </div>

        <div>

            <span class="badge bg-warning text-dark fs-6 px-4 py-3 rounded-pill">

                Service Charge :
                ₹107

            </span>

        </div>

    </div>

    {{-- =====================================================
    | ALERTS
    ====================================================== --}}
    <div id="response-message"></div>

    {{-- =====================================================
    | MAIN CARD
    ====================================================== --}}
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

        <div class="card-body p-4">

            {{-- =====================================================
            | PERSONAL DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Personal Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            First Name

                        </label>

                        <div class="preview-box">

                            {{ $data['first_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Middle Name

                        </label>

                        <div class="preview-box">

                            {{ $data['middle_name'] ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Last Name

                        </label>

                        <div class="preview-box">

                            {{ $data['last_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Gender

                        </label>

                        <div class="preview-box">

                            {{ $data['gender'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Date Of Birth

                        </label>

                        <div class="preview-box">

                            {{ $data['dob'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Confirm DOB

                        </label>

                        <div class="preview-box">

                            {{ $data['confirm_dob'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | FATHER DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Father Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            First Name

                        </label>

                        <div class="preview-box">

                            {{ $data['father_first_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Middle Name

                        </label>

                        <div class="preview-box">

                            {{ $data['father_middle_name'] ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Last Name

                        </label>

                        <div class="preview-box">

                            {{ $data['father_last_name'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | MOTHER DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Mother Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            First Name

                        </label>

                        <div class="preview-box">

                            {{ $data['mother_first_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Middle Name

                        </label>

                        <div class="preview-box">

                            {{ $data['mother_middle_name'] ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Last Name

                        </label>

                        <div class="preview-box">

                            {{ $data['mother_last_name'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | CONTACT DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Contact Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            Mobile Number

                        </label>

                        <div class="preview-box">

                            {{ $data['mobile_no'] }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            Email Address

                        </label>

                        <div class="preview-box">

                            {{ $data['email'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | PAN DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    PAN Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            PAN Print Name

                        </label>

                        <div class="preview-box">

                            {{ $data['pan_print_name'] }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            Signature Type

                        </label>

                        <div class="preview-box">

                            {{ $data['signature_type'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | AADHAAR DETAILS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Aadhaar Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            Aadhaar Number

                        </label>

                        <div class="preview-box">

                            {{ $data['aadhaar_no'] }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="fw-semibold mb-2">

                            Aadhaar Name

                        </label>

                        <div class="preview-box">

                            {{ $data['aadhaar_name'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | ADDRESS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Address Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-3">

                        <label class="fw-semibold mb-2">

                            House No

                        </label>

                        <div class="preview-box">

                            {{ $data['house_no'] }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <label class="fw-semibold mb-2">

                            Village

                        </label>

                        <div class="preview-box">

                            {{ $data['village'] }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <label class="fw-semibold mb-2">

                            Post Office

                        </label>

                        <div class="preview-box">

                            {{ $data['post_office'] }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <label class="fw-semibold mb-2">

                            Area

                        </label>

                        <div class="preview-box">

                            {{ $data['area'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            State

                        </label>

                        <div class="preview-box">

                            {{ $data['state_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            District

                        </label>

                        <div class="preview-box">

                            {{ $data['district_name'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Pincode

                        </label>

                        <div class="preview-box">

                            {{ $data['pincode'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | PROOFS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Proof Details

                </h4>

                <div class="row g-4">

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Identity Proof

                        </label>

                        <div class="preview-box">

                            {{ $data['identity_proof'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            Address Proof

                        </label>

                        <div class="preview-box">

                            {{ $data['address_proof'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="fw-semibold mb-2">

                            DOB Proof

                        </label>

                        <div class="preview-box">

                            {{ $data['dob_proof'] }}

                        </div>

                    </div>

                </div>

            </div>

          {{-- =====================================================
            | DOCUMENTS
            ====================================================== --}}
            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Uploaded Documents

                </h4>

                <div class="row g-4">

                    {{-- PHOTO --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="document-preview-card">

                            <div class="document-title">

                                Photo

                            </div>

                            @php
                                $photoExt = strtolower(
                                    pathinfo(
                                        $files['photo'] ?? '',
                                        PATHINFO_EXTENSION
                                    )
                                );
                            @endphp

                            @if($photoExt == 'pdf')

                                <a
                                    href="{{ file_url($files['photo']) }}"
                                    target="_blank"
                                    class="btn btn-danger w-100"
                                >
                                    View PDF
                                </a>

                            @else

                                <img
                                    src="{{ file_url($files['photo']) }}"
                                    class="img-fluid rounded"
                                    alt="Photo"
                                >

                            @endif

                        </div>

                    </div>

                    {{-- SIGNATURE --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="document-preview-card">

                            <div class="document-title">

                                Signature

                            </div>

                            @php
                                $signatureExt = strtolower(
                                    pathinfo(
                                        $files['signature'] ?? '',
                                        PATHINFO_EXTENSION
                                    )
                                );
                            @endphp

                            @if($signatureExt == 'pdf')

                                <a
                                    href="{{ file_url($files['signature']) }}"
                                    target="_blank"
                                    class="btn btn-danger w-100"
                                >
                                    View PDF
                                </a>

                            @else

                                <img
                                    src="{{ file_url($files['signature']) }}"
                                    class="img-fluid rounded"
                                    alt="Signature"
                                >

                            @endif

                        </div>

                    </div>

                    {{-- AADHAAR CARD --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="document-preview-card">

                            <div class="document-title">

                                Aadhaar Card

                            </div>

                            @php
                                $aadhaarExt = strtolower(
                                    pathinfo(
                                        $files['aadhaar_card'] ?? '',
                                        PATHINFO_EXTENSION
                                    )
                                );
                            @endphp

                            @if($aadhaarExt == 'pdf')

                                <a
                                    href="{{ file_url($files['aadhaar_card']) }}"
                                    target="_blank"
                                    class="btn btn-danger w-100"
                                >
                                    View PDF
                                </a>

                            @else

                                <img
                                    src="{{ file_url($files['aadhaar_card']) }}"
                                    class="img-fluid rounded"
                                    alt="Aadhaar Card"
                                >

                            @endif

                        </div>

                    </div>


                    {{-- DOB PROOF --}}
                    @if(!empty($files['dob_proof_file']))

                    <div class="col-lg-3 col-md-6">

                        <div class="document-preview-card">

                            <div class="document-title">

                                DOB Proof

                            </div>

                            @php
                                $dobExt = strtolower(
                                    pathinfo(
                                        $files['dob_proof_file'] ?? '',
                                        PATHINFO_EXTENSION
                                    )
                                );
                            @endphp

                            @if($dobExt == 'pdf')

                                <a
                                    href="{{ file_url($files['dob_proof_file']) }}"
                                    target="_blank"
                                    class="btn btn-danger w-100"
                                >
                                    View PDF
                                </a>

                            @else

                                <img
                                    src="{{ file_url($files['dob_proof_file']) }}"
                                    class="img-fluid rounded"
                                    alt="DOB Proof"
                                >

                            @endif

                        </div>

                    </div>

                    @endif

                    {{-- SUPPORTING DOCUMENT --}}
                    @if(!empty($files['supporting_document']))

                    <div class="col-lg-3 col-md-6">

                        <div class="document-preview-card">

                            <div class="document-title">

                                Supporting Document

                            </div>

                            @php
                                $supportingExt = strtolower(
                                    pathinfo(
                                        $files['supporting_document'] ?? '',
                                        PATHINFO_EXTENSION
                                    )
                                );
                            @endphp

                            @if($supportingExt == 'pdf')

                                <a
                                    href="{{ file_url($files['supporting_document']) }}"
                                    target="_blank"
                                    class="btn btn-danger w-100"
                                >
                                    View PDF
                                </a>

                            @else

                                <img
                                    src="{{ file_url($files['supporting_document']) }}"
                                    class="img-fluid rounded"
                                    alt="Supporting Document"
                                >

                            @endif

                        </div>

                    </div>

                    @endif

                </div>

            </div>

            {{-- =====================================================
            | WALLET INFO
            ====================================================== --}}
            <div class="alert alert-warning border-0 rounded-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <strong>

                            Wallet Balance :

                        </strong>

                        ₹{{ number_format(auth()->user()->wallet_balance,2) }}

                    </div>

                    <div>

                        <strong>

                            PAN Charge :

                        </strong>

                        ₹107

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | ACTION BUTTONS
            ====================================================== --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-3">

                <form
                    action="{{ route('retailer.pan.apply') }}"
                    method="GET"
                    class="d-inline">

                    @foreach($data as $key => $value)

                        @if(!is_array($value))

                            <input
                                type="hidden"
                                name="{{ $key }}"
                                value="{{ $value }}"
                            >

                        @endif

                    @endforeach

                    <button
                        type="submit"
                        class="btn btn-light btn-lg px-5">

                        Back

                    </button>

                </form>

                <button
                    type="button"
                    class="btn btn-primary btn-lg px-5"
                    id="final-submit-btn">

                    Final Submit

                </button>

            </div>

        </div>

    </div>

</div>

{{-- =====================================================
| AJAX FINAL SUBMIT
====================================================== --}}
<script>

document
    .getElementById('final-submit-btn')

    .addEventListener(

        'click',

        function(){

            let button = this;

            button.disabled = true;

            button.innerHTML =
                'Submitting...';

            fetch(

                "{{ route('retailer.pan.final.submit') }}",

                {
                    method : 'POST',

                    headers : {

                        'X-CSRF-TOKEN':
                            '{{ csrf_token() }}',

                        'Accept':
                            'application/json'

                    }
                }

            )

            .then(response => response.json())

            .then(data => {

                if(data.status)
                {
                    window.location.href =
                        data.redirect_url;
                }
                else
                {
                    button.disabled = false;

                    button.innerHTML =
                        'Final Submit';

                    document
                        .getElementById(
                            'response-message'
                        )

                        .innerHTML = `

                        <div class="alert alert-danger rounded-4 shadow-sm">

                            ${data.message}

                        </div>
                    `;
                }

            })

            .catch(error => {

                button.disabled = false;

                button.innerHTML =
                    'Final Submit';

                document
                    .getElementById(
                        'response-message'
                    )

                    .innerHTML = `

                    <div class="alert alert-danger rounded-4 shadow-sm">

                        Something went wrong.

                    </div>
                `;
            });

        }

    );

</script>

@endsection