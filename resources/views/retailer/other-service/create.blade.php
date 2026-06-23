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

                <div>

                    <span class="aadhaar-charge-badge">

                        <i class="fa-solid fa-wallet"></i>

                        Charge ₹{{ number_format($otherServiceCharge,2) }}

                    </span>

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

<script>

$(document).ready(function(){

    $('#aadhaarForm').on('submit', function(e){

        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({

            url: "{{ route('retailer.other-service.preview') }}",

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
