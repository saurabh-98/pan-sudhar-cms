@extends('layout.retailer')

@section('content')

<div class="container-fluid aadhaar-page-wrapper">

    <div class="card aadhaar-form-card">

        {{-- HEADER --}}
        <div class="card-header aadhaar-form-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h4 class="aadhaar-title">

                        <i class="fa-solid fa-id-card me-2"></i>

                        {{ $serviceName }}

                    </h4>

                    <h5 class="aadhaar-subtitle">

                        Complete the form and generate preview before final submission.

                    </h5>

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
                                Bank Service Guidelines
                            </button>

                        @endif

                        <div class="pan-charge-card">

                            <span class="pan-charge-label">
                                Service Charge
                            </span>

                            <span class="pan-charge-amount">
                                ₹{{ number_format($bankAccountCharge, 2) }}
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
                                CSC — Service Guidelines
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


                </div>

            </div>

            </div>

        </div>

        {{-- BODY --}}
        <div class="card-body aadhaar-form-body">

            <form
                id="aadhaarForm"
                enctype="multipart/form-data"
            >

                @csrf

                <input
                    type="hidden"
                    name="service_name"
                    value="{{ $serviceName }}"
                >

                <input
                    type="hidden"
                    name="service_slug"
                    value="{{ $serviceSlug }}"
                >

                {{-- DYNAMIC FIELDS --}}
                <div class="row g-4">

                    @foreach($fields as $field)

                        <div class="{{ ($field['type'] ?? 'text') === 'textarea'
                            ? 'col-12'
                            : 'col-xl-6 col-lg-6 col-md-6'
                        }}">

                            <div class="aadhaar-field-card">

                                <label class="aadhaar-label">

                                    {{ $field['label'] }}

                                    @if(!empty($field['required']))
                                        <span class="required">*</span>
                                    @endif

                                </label>

                                {{-- TEXTAREA --}}
                                @if(($field['type'] ?? '') === 'textarea')

                                    <textarea
                                        name="{{ $field['name'] }}"
                                        rows="5"
                                        class="form-control aadhaar-input"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                    >{{ old(
                                        $field['name'],
                                        $data['form_data'][$field['name']] ?? ''
                                    ) }}</textarea>

                                {{-- FILE --}}
                                @elseif(($field['type'] ?? '') === 'file')

                                    <div class="aadhaar-upload-box">

                                        <input
                                            type="file"
                                            name="{{ $field['name'] }}"
                                            class="form-control aadhaar-input"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                            {{ !empty($field['required']) ? 'required' : '' }}
                                        >

                                    </div>

                                    @if(!empty($files[$field['name']]))

                                        <div class="uploaded-file-box">

                                            <a
                                                href="{{ file_url($files[$field['name']]) }}"
                                                target="_blank"
                                                class="aadhaar-file-btn"
                                            >

                                                <i class="fa fa-file-pdf me-2"></i>

                                                View Uploaded File

                                            </a>

                                        </div>

                                    @endif

                                {{-- SELECT --}}
                                @elseif(($field['type'] ?? '') === 'select')

                                    <select
                                        name="{{ $field['name'] }}"
                                        class="form-select aadhaar-input"
                                        {{ !empty($field['required']) ? 'required' : '' }}
                                    >

                                        <option value="">

                                            Select Option

                                        </option>

                                        @foreach($field['options'] ?? [] as $option)

                                            <option
                                                value="{{ $option }}"
                                                {{
                                                    old(
                                                        $field['name'],
                                                        $data['form_data'][$field['name']] ?? ''
                                                    ) == $option
                                                    ? 'selected'
                                                    : ''
                                                }}
                                            >

                                                {{ $option }}

                                            </option>

                                        @endforeach

                                    </select>

                                {{-- DATE --}}
                                @elseif(($field['type'] ?? '') === 'date')

                                    <input
                                        type="date"
                                        name="{{ $field['name'] }}"
                                        class="form-control aadhaar-input"
                                        value="{{ old(
                                            $field['name'],
                                            $data['form_data'][$field['name']] ?? ''
                                        ) }}"
                                    >

                                {{-- EMAIL --}}
                                @elseif(($field['type'] ?? '') === 'email')

                                    <input
                                        type="email"
                                        name="{{ $field['name'] }}"
                                        class="form-control aadhaar-input"
                                        value="{{ old(
                                            $field['name'],
                                            $data['form_data'][$field['name']] ?? ''
                                        ) }}"
                                    >

                                {{-- NUMBER --}}
                                @elseif(($field['type'] ?? '') === 'number')

                                    <input
                                        type="number"
                                        name="{{ $field['name'] }}"
                                        class="form-control aadhaar-input"
                                        value="{{ old(
                                            $field['name'],
                                            $data['form_data'][$field['name']] ?? ''
                                        ) }}"
                                    >

                                {{-- DEFAULT --}}
                                @else

                                    <input
                                        type="text"
                                        name="{{ $field['name'] }}"
                                        class="form-control aadhaar-input"
                                        value="{{ old(
                                            $field['name'],
                                            $data['form_data'][$field['name']] ?? ''
                                        ) }}"
                                    >

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- REMARKS --}}
                <div class="aadhaar-field-card mt-4">

                    <label class="aadhaar-label">

                        Remarks

                    </label>

                    <textarea
                        name="remarks"
                        rows="5"
                        class="form-control aadhaar-input"
                    >{{ old(
                        'remarks',
                        $data['form_data']['remarks'] ?? ''
                    ) }}</textarea>

                </div>


                {{-- ACTION --}}
                <div class="aadhaar-action-area">

                    <button
                        type="submit"
                        class="aadhaar-submit-btn"
                    >

                        <i class="fa fa-eye me-2"></i>

                        Generate Preview

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
@section('scripts')

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


<script>

$(document).ready(function(){


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


    $('#aadhaarForm').on('submit', function(e){

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: "{{ route('retailer.bank-account.preview') }}",

            type: "POST",

            data: formData,

            processData: false,

            contentType: false,

            beforeSend: function(){

                Swal.fire({

                    title: 'Please Wait...',

                    text: 'Generating Preview',

                    allowOutsideClick: false,

                    didOpen: () => {

                        Swal.showLoading();

                    }

                });

            },

            success: function(response){

                Swal.close();

                if(response.status){

                    window.location.href =
                        response.redirect_url;

                }

            },

            error: function(xhr){

                Swal.close();

                let message =
                    'Something went wrong.';

                if(xhr.responseJSON?.errors){

                    message = '';

                    $.each(

                        xhr.responseJSON.errors,

                        function(key,value){

                            message +=
                                value[0] + '<br>';

                        }

                    );

                }
                else if(xhr.responseJSON?.message){

                    message =
                        xhr.responseJSON.message;

                }

                Swal.fire({

                    icon: 'error',

                    title: 'Validation Error',

                    html: message

                });

            }

        });

    });

});

</script>

@endsection
