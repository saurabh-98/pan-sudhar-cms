@extends('layout.retailer')

@section('title', 'ITR Filing Preview')

@section('content')

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                ITR Filing Preview
            </h2>

            <p class="text-muted mb-0">
                Verify details before final submission.
            </p>

        </div>

        <div>

            <span class="badge bg-warning text-dark fs-6 px-4 py-3">

                ITR Charge :
                ₹{{ number_format($itrCharge, 2) }}

            </span>

        </div>

    </div>

    <div id="response-message"></div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body p-4">

            {{-- APPLICANT DETAILS --}}
            <h5 class="fw-bold mb-4">

                Applicant Details

            </h5>

            <div class="row g-4">

                <div class="col-md-4">

                    <label class="fw-semibold mb-2">

                        Name

                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{ $data['name'] }}

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="fw-semibold mb-2">

                        Mobile

                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{ $data['mobile'] }}

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="fw-semibold mb-2">

                        Email

                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{ $data['email'] }}

                    </div>

                </div>

                <div class="col-md-12">

                    <label class="fw-semibold mb-2">

                        Remarks

                    </label>

                    <div class="border rounded p-3 bg-light">

                        {{ $data['remarks'] ?? 'N/A' }}

                    </div>

                </div>

            </div>

            <hr class="my-5">

            {{-- DOCUMENTS --}}
            <h5 class="fw-bold mb-4">

                Uploaded Documents

            </h5>

            @php

                $documents = [

                    'Aadhaar Front' =>
                        $files['aadhaar_front'] ?? null,

                    'Aadhaar Back' =>
                        $files['aadhaar_back'] ?? null,

                    'PAN Card' =>
                        $files['pan_card'] ?? null,

                ];

            @endphp

            <div class="row g-4">

                @foreach($documents as $title => $file)

                    @if(!empty($file))

                        @php

                            $url = file_url($file);

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
                                    '.pdf'
                                );

                        @endphp

                        <div class="col-md-4">

                            <div class="card border-0 shadow-sm h-100">

                                <div class="card-body text-center">

                                    <h6 class="fw-bold mb-3">

                                        {{ $title }}

                                    </h6>

                                    @if($isPdf)

                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                            class="btn btn-danger"
                                        >

                                            <i class="fa fa-file-pdf me-2"></i>

                                            View PDF

                                        </a>

                                    @else

                                        <a
                                            href="{{ $url }}"
                                            target="_blank"
                                        >

                                            <img
                                                src="{{ $url }}"
                                                class="img-fluid rounded border"
                                                style="max-height:200px;"
                                                alt="{{ $title }}"
                                            >

                                        </a>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

            {{-- WALLET SUMMARY --}}
            <div class="alert alert-warning mt-5 rounded-4">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <strong>

                            Wallet Balance :

                        </strong>

                        ₹{{ number_format(auth()->user()->wallet_balance, 2) }}

                    </div>

                    <div>

                        <strong>

                            ITR Charge :

                        </strong>

                        ₹{{ number_format($itrCharge, 2) }}

                    </div>

                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="d-flex justify-content-between mt-4">

                <a
                    href="{{ route('retailer.itr.index') }}"
                    class="btn btn-light btn-lg"
                >

                    <i class="fa fa-arrow-left me-2"></i>

                    Back

                </a>

                <button
                    type="button"
                    id="finalSubmitBtn"
                    class="btn btn-primary btn-lg"
                >

                    <span id="submitText">

                        Final Submit

                    </span>

                    <span
                        id="submitLoader"
                        class="spinner-border spinner-border-sm ms-2 d-none"
                    ></span>

                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

$('#finalSubmitBtn').click(function(){

    let button = $(this);

    $('#submitText').text(
        'Submitting...'
    );

    $('#submitLoader')
        .removeClass('d-none');

    button.prop(
        'disabled',
        true
    );

    $.ajax({

        url:
        "{{ route('retailer.itr.final-submit') }}",

        type: 'POST',

        data: {

            _token:
            "{{ csrf_token() }}"

        },

        success: function(response){

            if(response.status){

                window.location.href =
                    response.redirect_url;

            }

        },

        error: function(xhr){

            button.prop(
                'disabled',
                false
            );

            $('#submitText').text(
                'Final Submit'
            );

            $('#submitLoader')
                .addClass('d-none');

            Swal.fire({

                icon: 'error',

                title: 'Error',

                text:

                    xhr.responseJSON?.message

                    ??

                    'Something went wrong.'

            });

        }

    });

});

</script>

@endsection