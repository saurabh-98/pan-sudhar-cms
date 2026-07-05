@extends('layout.retailer')

@section('content')

<div class="container-fluid aadhaar-page-wrapper">

    <div class="card aadhaar-form-card">

     {{-- HEADER --}}
    <div class="card-header aadhaar-form-header">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            {{-- Left Side --}}
            <div>

                <h4 class="aadhaar-title">
                    <i class="fa-solid fa-id-card me-2"></i>
                    {{ $serviceName }}
                </h4>

                <p class="aadhaar-subtitle mb-0">
                    Complete the form and generate preview before final submission.
                </p>

            </div>

            {{-- Right Side --}}
            <div class="pan-header-right">

                {{-- Bank Service Guidelines --}}
                @if($guideline && $guideline->pdf)

                    <button
                        type="button"
                        class="pan-guideline-btn"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#serviceGuidelineOffcanvas"
                        aria-controls="serviceGuidelineOffcanvas">

                        <i class="fa-solid fa-circle-info"></i>
                        <span>Bank Service Guidelines</span>

                    </button>

                @endif

                {{-- View Bank Form --}}
                @if(isset($bankForm) && $bankForm && $bankForm->pdf)

                    <a
                        href="{{ file_url($bankForm->pdf) }}"
                        target="_blank"
                        class="bank-form-btn">

                        <i class="fa-solid fa-eye"></i>
                        <span>View Bank Form</span>

                    </a>

                    {{-- Download Bank Form --}}
                    <a
                        href="{{ file_url($bankForm->pdf) }}"
                        download
                        class="bank-download-btn">

                        <i class="fa-solid fa-download"></i>
                        <span>Download Form</span>

                    </a>

                @endif

                {{-- Service Charge --}}
                <div class="pan-charge-card">

                    <span class="pan-charge-label">
                        Service Charge
                    </span>

                    <span class="pan-charge-amount">
                        ₹{{ number_format($bankAccountCharge, 2) }}
                    </span>

                </div>

            </div>

        </div>

    </div>

    {{-- SERVICE GUIDELINE OFFCANVAS --}}
    @if($guideline && $guideline->pdf)

    <div
        class="offcanvas offcanvas-end pan-guideline-offcanvas"
        tabindex="-1"
        id="serviceGuidelineOffcanvas"
        aria-labelledby="serviceGuidelineLabel">

        <div class="offcanvas-header">

            <h5 id="serviceGuidelineLabel">
                <i class="fa fa-file-pdf text-danger me-2"></i>
                Bank Service Guidelines
            </h5>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close">
            </button>

        </div>

        <div class="offcanvas-body p-0 d-flex flex-column">

            <div id="pdfLoadingState" class="text-center text-muted py-5">

                <i class="fa fa-spinner fa-spin fa-2x mb-3"></i>

                <p>Loading guideline document...</p>

            </div>

            <iframe
                id="guidelinePdfFrame"
                data-src="{{ file_url($guideline->pdf) }}"
                style="display:none;">
            </iframe>

            <div class="text-center py-3 border-top">

                <a href="{{ file_url($guideline->pdf) }}"
                target="_blank"
                class="btn btn-outline-primary btn-sm">

                    <i class="fa-solid fa-up-right-from-square me-1"></i>
                    Open PDF in New Tab

                </a>

            </div>

        </div>

    </div>

    @endif

        {{-- closes .card-header --}}

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
        {{-- closes .card-body --}}

    </div>
    {{-- closes .card --}}

</div>
{{-- closes .container-fluid --}}

@endsection


@section('styles')

<style>

/*==================================================
=                 HEADER
==================================================*/

.aadhaar-form-header{

    background:linear-gradient(135deg,#2563eb 0%,#4f46e5 45%,#7c3aed 100%);
    border-radius:20px;
    padding:28px 30px;
    position:relative;
    overflow:hidden;
    border:none;

}

.aadhaar-form-header::before{

    content:"";
    position:absolute;
    width:260px;
    height:260px;
    border-radius:50%;
    background:rgba(255,255,255,.08);
    top:-120px;
    right:-80px;

}

.aadhaar-form-header::after{

    content:"";
    position:absolute;
    width:180px;
    height:180px;
    border-radius:50%;
    background:rgba(255,255,255,.05);
    bottom:-80px;
    left:-60px;

}

.aadhaar-title{

    color:#fff;
    font-size:2rem;
    font-weight:700;
    margin-bottom:6px;
    position:relative;
    z-index:2;

}

.aadhaar-title i{

    color:#fff;

}

.aadhaar-subtitle{

    color:rgba(255,255,255,.85);
    font-size:.95rem;
    margin:0;
    position:relative;
    z-index:2;

}

/*==================================================
=              HEADER RIGHT
==================================================*/

.pan-header-right{

    display:flex;
    align-items:center;
    justify-content:flex-end;
    flex-wrap:wrap;
    gap:14px;
    position:relative;
    z-index:2;

}

/*==================================================
=                 BUTTONS
==================================================*/

.pan-guideline-btn,
.bank-form-btn,
.bank-download-btn{

    height:50px;
    padding:0 22px;

    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;

    border-radius:12px;

    font-size:15px;
    font-weight:600;

    text-decoration:none;
    white-space:nowrap;

    transition:all .25s ease;

}

/* Guideline */

.pan-guideline-btn{

    background:#fff;
    border:1px solid #e4e7ec;
    color:#344054;

    box-shadow:0 5px 15px rgba(0,0,0,.08);

}

.pan-guideline-btn i{

    color:#2563eb;

}

.pan-guideline-btn:hover{

    background:#f8fafc;
    border-color:#2563eb;
    transform:translateY(-3px);
    box-shadow:0 12px 25px rgba(0,0,0,.15);

}

/* View */

.bank-form-btn{

    background:linear-gradient(135deg,#1d4ed8,#2563eb);
    color:#fff;
    border:none;

    box-shadow:0 8px 20px rgba(37,99,235,.30);

}

.bank-form-btn:hover{

    color:#fff;
    transform:translateY(-3px);

    box-shadow:0 15px 30px rgba(37,99,235,.45);

}

/* Download */

.bank-download-btn{

    background:linear-gradient(135deg,#15803d,#16a34a);
    color:#fff;
    border:none;

    box-shadow:0 8px 20px rgba(22,163,74,.30);

}

.bank-download-btn:hover{

    color:#fff;
    transform:translateY(-3px);

    box-shadow:0 15px 30px rgba(22,163,74,.45);

}

.bank-form-btn i,
.bank-download-btn i{

    font-size:15px;

}

/*==================================================
=            SERVICE CHARGE
==================================================*/

.pan-charge-card{

    height:62px;
    min-width:180px;

    background:linear-gradient(135deg,#3b82f6,#60a5fa);

    color:#fff;

    border-radius:14px;

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;

    padding:0 18px;

    box-shadow:0 15px 35px rgba(0,0,0,.18);

}

.pan-charge-label{

    font-size:11px;
    font-weight:600;
    letter-spacing:1px;
    text-transform:uppercase;
    opacity:.9;

}

.pan-charge-amount{

    margin-top:2px;
    font-size:2rem;
    font-weight:700;
    line-height:1;

}

/*==================================================
=              OFFCANVAS
==================================================*/

.pan-guideline-offcanvas{

    width:50% !important;

}

.pan-guideline-offcanvas .offcanvas-header{

    border-bottom:1px solid #eaecf0;

}

.pan-guideline-offcanvas .offcanvas-body{

    padding:0;
    height:100%;

}

#pdfLoadingState{

    flex:0 0 auto;

}

#guidelinePdfFrame{

    flex:1;
    width:100%;
    border:none;
    min-height:80vh;

}

/*==================================================
=              RESPONSIVE
==================================================*/

@media(max-width:991px){

    .aadhaar-form-header{

        padding:22px;

    }

    .aadhaar-title{

        font-size:1.6rem;

    }

    .pan-header-right{

        width:100%;
        justify-content:flex-start;

    }

    .pan-guideline-btn,
    .bank-form-btn,
    .bank-download-btn,
    .pan-charge-card{

        width:100%;

    }

    .pan-guideline-offcanvas{

        width:100% !important;

    }

}

@media(max-width:576px){

    .aadhaar-title{

        font-size:1.35rem;

    }

    .aadhaar-subtitle{

        font-size:.85rem;

    }

    .pan-guideline-btn,
    .bank-form-btn,
    .bank-download-btn{

        height:46px;
        font-size:14px;

    }

    .pan-charge-card{

        height:60px;

    }

    .pan-charge-amount{

        font-size:1.6rem;

    }

}

</style>

@endsection


@section('scripts')

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