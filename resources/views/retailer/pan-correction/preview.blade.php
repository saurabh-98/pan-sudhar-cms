@extends('layout.retailer')

@section('content')

<div class="container-fluid py-4 pan-preview-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <div>

            <h2 class="fw-bold mb-1 text-primary">

                <i class="fa fa-pen me-2"></i>

                PAN Correction Preview

            </h2>

            <p class="text-muted mb-0">

                Verify PAN correction details before final submission.

            </p>

        </div>

        <div>

            <span class="badge bg-warning text-dark fs-6 px-4 py-3 rounded-pill shadow-sm">

                PAN Correction Charge :
                ₹170

            </span>

        </div>

    </div>

    {{-- RESPONSE --}}
    <div id="response-message"></div>

    {{-- =====================================================
    | MAIN CARD
    ====================================================== --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <div class="card-body p-4 p-lg-5">

            {{-- =====================================================
            | PERSONAL DETAILS
            ====================================================== --}}
            <div class="preview-section">

                <div class="preview-heading">

                    <i class="fa fa-user"></i>

                    Personal Details

                </div>

                <div class="row g-4">

                    <div class="col-md-3">

                        <label class="preview-label">

                            First Name

                        </label>

                        <div class="preview-box">

                            {{ $data['first_name'] }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <label class="preview-label">

                            Middle Name

                        </label>

                        <div class="preview-box">

                            {{ $data['middle_name'] ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="col-md-3">

                        <label class="preview-label">

                            Last Name

                        </label>

                        <div class="preview-box">

                            {{ $data['last_name'] }}

                        </div>

                    </div>

                    {{-- OLD PAN --}}
                    <div class="col-md-3">

                        <label class="preview-label">

                            Old PAN Number

                        </label>

                        <div class="preview-box text-uppercase fw-bold text-primary">

                            {{ $data['old_pan_number'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="preview-label">

                            Gender

                        </label>

                        <div class="preview-box">

                            {{ $data['gender'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="preview-label">

                            Date Of Birth

                        </label>

                        <div class="preview-box">

                            {{ $data['dob'] }}

                        </div>

                    </div>

                    <div class="col-md-4">

                        <label class="preview-label">

                            Confirm DOB

                        </label>

                        <div class="preview-box">

                            {{ $data['confirm_dob'] }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | PARENT DETAILS
            ====================================================== --}}
            <div class="preview-section">

                <div class="preview-heading">

                    <i class="fa fa-users"></i>

                    Parent Details

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3 text-primary">

                                    Father Details

                                </h6>

                                <div class="preview-box mb-2">

                                    {{ $data['father_first_name'] }}
                                    {{ $data['father_middle_name'] ?? '' }}
                                    {{ $data['father_last_name'] }}

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card border-0 shadow-sm h-100">

                            <div class="card-body">

                                <h6 class="fw-bold mb-3 text-danger">

                                    Mother Details

                                </h6>

                                <div class="preview-box mb-2">

                                    {{ $data['mother_first_name'] }}
                                    {{ $data['mother_middle_name'] ?? '' }}
                                    {{ $data['mother_last_name'] }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | CONTACT DETAILS
            ====================================================== --}}
            <div class="preview-section">

                <div class="preview-heading">

                    <i class="fa fa-phone"></i>

                    Contact Details

                </div>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="preview-label">

                            Mobile Number

                        </label>

                        <div class="preview-box">

                            {{ $data['mobile_no'] }}

                        </div>

                    </div>

                    <div class="col-md-6">

                        <label class="preview-label">

                            Email Address

                        </label>

                        <div class="preview-box">

                            {{ $data['email'] }}

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

                @php

                    $documents = [

                        'Photo' =>
                            $files['photo'] ?? null,

                        'Signature' =>
                            $files['signature'] ?? null,

                        'Aadhaar Card' =>
                            $files['aadhaar_card'] ?? null,

                        'DOB Proof' =>
                            $files['dob_proof_file'] ?? null,

                        'Supporting Document' =>
                            $files['supporting_document'] ?? null,

                    ];

                @endphp

                <div class="row g-4">

                    @foreach($documents as $title => $file)

                        @if(
                            !empty($file)
                            &&
                            file_exists_custom($file)
                        )

                            @php

                                $url = file_url($file);

                                /*
                                |--------------------------------------------------------------------------
                                | DETECT PDF FOR BOTH:
                                | LOCAL STORAGE
                                | CLOUDINARY RAW FILES
                                |--------------------------------------------------------------------------
                                */

                                $extension = strtolower(
                                    pathinfo(
                                        $file,
                                        PATHINFO_EXTENSION
                                    )
                                );

                                $isPdf =
                                    $extension === 'pdf'
                                    ||
                                    str_contains(
                                        strtolower($url),
                                        '/raw/upload/'
                                    )
                                    ||
                                    str_contains(
                                        strtolower($url),
                                        '.pdf'
                                    );

                            @endphp

                            <div class="col-lg-3 col-md-6">

                                <div class="document-preview-card">

                                    <div class="document-title">

                                        {{ $title }}

                                    </div>

                                    @if($isPdf)

                                        <div class="text-center p-3">

                                            <a
                                                href="{{ $url }}"
                                                target="_blank"
                                                class="btn btn-danger w-100"
                                            >
                                                View PDF
                                            </a>

                                            <div class="mt-2 small text-muted">
                                                PDF Document
                                            </div>

                                        </div>

                                    @else

                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                        >

                                            <img
                                                src="{{ $url }}"
                                                class="img-fluid rounded"
                                                alt="{{ $title }}"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/no-image.png') }}';"
                                            >

                                        </a>

                                    @endif

                                </div>

                            </div>

                        @endif

                    @endforeach

                </div>

            </div>

            {{-- =====================================================
            | WALLET
            ====================================================== --}}
            <div class="alert alert-warning border-0 shadow-sm rounded-4 mt-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div>

                        <strong>

                            Wallet Balance :

                        </strong>

                        ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}

                    </div>

                    <div>

                        <strong>

                            Service Charge :

                        </strong>

                        ₹170

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | ACTIONS
            ====================================================== --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-5">

                <form
                    action="{{ route('retailer.pan-correction.apply') }}"
                    method="GET"
                >

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
                        class="btn btn-light btn-lg px-5 shadow-sm"
                    >

                        <i class="fa fa-arrow-left me-2"></i>

                        Back

                    </button>

                </form>

                <button
                    type="button"
                    class="btn btn-primary btn-lg px-5 shadow-sm"
                    id="final-submit-btn"
                >

                    <i class="fa fa-check-circle me-2"></i>

                    Final Submit

                </button>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$('#final-submit-btn').on('click', function () {

    let button = $(this);

    Swal.fire({

        title: 'Submit PAN Correction?',

        text: 'Please verify all details before final submission.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Yes Submit',

        cancelButtonText: 'Cancel',

        confirmButtonColor: '#0d6efd'

    }).then((result) => {

        if(result.isConfirmed)
        {

            button.prop('disabled', true);

            button.html(`
                <span class="spinner-border spinner-border-sm me-2"></span>
                Submitting...
            `);

            $.ajax({

                url: "{{ route('retailer.pan-correction.store') }}",

                type: "POST",

                data: {

                    _token: "{{ csrf_token() }}"

                },

                success: function(response){

                    if(response.status)
                    {

                        Swal.fire({

                            icon: 'success',

                            title: 'Success',

                            text: response.message,

                            timer: 2000,

                            showConfirmButton: false

                        });

                        setTimeout(function(){

                            window.location.href =
                                response.redirect_url;

                        }, 2000);

                    }

                },

                error: function(xhr){

                    button.prop('disabled', false);

                    button.html(`
                        <i class="fa fa-check-circle me-2"></i>
                        Final Submit
                    `);

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: xhr.responseJSON?.message
                            ?? 'Something went wrong.'

                    });

                }

            });

        }

    });

});

</script>

@endsection