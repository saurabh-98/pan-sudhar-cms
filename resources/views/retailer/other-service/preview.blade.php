@extends('layout.retailer')

@section('content')

<div class="container-fluid py-4">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-1">

           {{ $data['service_name'] }}
        </h2>

        <p class="text-muted mb-0">

            Verify details before final submission

        </p>

    </div>

    <div>

        <span class="badge bg-warning text-dark fs-6 px-4 py-3 rounded-pill">

            Service Charge :
            ₹{{ number_format($otherServiceCharge,2) }}

        </span>

    </div>

</div>

<div id="response-message"></div>

<div class="card shadow-lg border-0 rounded-4">

    <div class="card-body p-4">

        {{-- APPLICATION DETAILS --}}

        <div class="mb-5">

            <h4 class="border-bottom pb-3 mb-4 fw-bold">

                Application Details

            </h4>

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="fw-semibold mb-2">

                        Service Name

                    </label>

                    <div class="preview-box">

                        {{ $data['service_name'] }}

                    </div>

                </div>

                @foreach(($data['form_data'] ?? []) as $field => $value)

                    @if($field !== 'remarks')

                        <div class="col-md-6">

                            <label class="fw-semibold mb-2">

                                {{ ucwords(str_replace('_',' ', $field)) }}

                            </label>

                            <div class="preview-box">

                                {{ $value ?: 'N/A' }}

                            </div>

                        </div>

                    @endif

                @endforeach

            </div>

        </div>

        {{-- REMARKS --}}

        @if(!empty($data['form_data']['remarks']))

            <div class="mb-5">

                <h4 class="border-bottom pb-3 mb-4 fw-bold">

                    Remarks

                </h4>

                <div class="preview-box">

                    {{ $data['form_data']['remarks'] }}

                </div>

            </div>

        @endif

        {{-- DOCUMENTS --}}

        <div class="mb-5">

            <h4 class="border-bottom pb-3 mb-4 fw-bold">

                Uploaded Documents

            </h4>

            <div class="row">

                @forelse($files as $name => $path)

                    <div class="col-md-4 mb-3">

                        <div class="document-preview-card p-3 border rounded">

                            <div class="document-title mb-2 fw-semibold">

                                {{ ucwords(str_replace('_',' ', $name)) }}

                            </div>

                            @php

                                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                            @endphp

                            @if(in_array($extension,['jpg','jpeg','png','webp']))

                                <a
                                    href="{{ file_url($path) }}"
                                    target="_blank"
                                >

                                    <img
                                        src="{{ file_url($path) }}"
                                        class="img-fluid rounded"
                                        alt="{{ $name }}"
                                    >

                                </a>

                            @else

                                <a
                                    href="{{ file_url($path) }}"
                                    target="_blank"
                                    class="btn btn-danger btn-sm"
                                >

                                    <i class="fa fa-file-pdf me-1"></i>

                                    View PDF

                                </a>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="col-12">

                        <div class="alert alert-light border">

                            No documents uploaded.

                        </div>

                    </div>

                @endforelse

            </div>

        </div>

        {{-- WALLET SUMMARY --}}

        <div class="alert alert-warning border-0 rounded-4">

            <div class="d-flex justify-content-between flex-wrap gap-3">

                <div>

                    <strong>

                        Wallet Balance :

                    </strong>

                    ₹{{ number_format(auth()->user()->wallet_balance,2) }}

                </div>

                <div>

                    <strong>

                        Service Charge :

                    </strong>

                    ₹{{ number_format($otherServiceCharge,2) }}

                </div>

                <div>

                    <strong>

                        Balance After Deduction :

                    </strong>

                    ₹{{ number_format(
                        auth()->user()->wallet_balance - $otherServiceCharge,
                        2
                    ) }}

                </div>

            </div>

        </div>

        {{-- BUTTONS --}}

        <div class="d-flex justify-content-between mt-4">

            <a
                href="{{ route(
                    'retailer.other-service.service',
                    $data['service_slug'] ?? ''
                ) }}"
                class="btn btn-light btn-lg px-5"
            >

                Back

            </a>

            <button
                type="button"
                id="final-submit-btn"
                class="btn btn-primary btn-lg px-5"
            >

                Final Submit

            </button>

        </div>

    </div>

</div>


</div>

@endsection

@section('scripts')

<script>

document
.getElementById(
    'final-submit-btn'
)
.addEventListener(
    'click',
    function(){

        let button = this;

        button.disabled = true;

        button.innerHTML =
            '<i class="fa fa-spinner fa-spin me-2"></i>Submitting...';

        fetch(

            "{{ route('retailer.other-service.final-submit') }}",

            {

                method:'POST',

                headers:{

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Accept':
                        'application/json'

                }

            }

        )
        .then(
            response =>
                response.json()
        )

        .then(
            data => {

                if(data.status){

                    window.location.href =
                        data.redirect_url;

                }else{

                    button.disabled = false;

                    button.innerHTML =
                        'Final Submit';

                    Swal.fire({

                        icon:'error',

                        title:'Error',

                        text:data.message

                    });

                }

            }
        )
        .catch(function(){

            button.disabled = false;

            button.innerHTML =
                'Final Submit';

            Swal.fire({

                icon:'error',

                title:'Error',

                text:'Something went wrong.'

            });

        });

    }
);

</script>

@endsection
