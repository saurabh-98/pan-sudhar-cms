@extends('layout.admin')

@section('content')

<div class="container-fluid admin-pan-show-page">

    {{-- =====================================================
    | HEADER
    ====================================================== --}}
    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                ITR Application Details

            </h2>

            <p class="admin-pan-subtitle">

                Verify, review and assign ITR application.

            </p>

        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('admin.itr.index') }}"
                class="btn admin-dark-btn"
            >

                <i class="fa fa-arrow-left me-2"></i>

                Back

            </a>

        </div>

    </div>

    {{-- =====================================================
    | MAIN ROW
    ====================================================== --}}
    <div class="row g-4">

        {{-- =====================================================
        | LEFT SECTION
        ====================================================== --}}
        <div class="col-xl-8">

            {{-- =====================================================
            | APPLICATION OVERVIEW
            ====================================================== --}}
            <div class="card admin-pan-card mb-4">

                <div class="card-header pan-card-header">

                    ITR Overview

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    ITR No

                                </label>

                                <h6>

                                    {{ $application->application_no }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Status

                                </label>

                                <div>

                                    @if($application->status == 'approved')

                                        <span class="badge bg-success">

                                            Approved

                                        </span>

                                    @elseif($application->status == 'pending')

                                        <span class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    @elseif($application->status == 'Processing')

                                        <span class="badge bg-info">

                                            Processing

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Rejected

                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Applicant Name

                                </label>

                                <h6>

                                    {{ $application->name }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Email Address

                                </label>

                                <h6>

                                    {{ $application->email }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Charge Amount

                                </label>

                                <h6>

                                    ₹{{ number_format($application->charge, 2) }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Submitted Date

                                </label>

                                <h6>

                                    {{ $application->created_at->format('d M Y') }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | REMARKS
            ====================================================== --}}
            <div class="card admin-pan-card mb-4">

                <div class="card-header pan-card-header">

                    Remarks Details

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    User Remarks

                                </label>

                                <h6>

                                    {{ $application->remarks ?? 'N/A' }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Admin Remarks

                                </label>

                                <h6>

                                    {{ $application->admin_remarks ?? 'N/A' }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

           {{-- =====================================================
            | DOCUMENTS
            ====================================================== --}}
            <div class="card admin-pan-card">

                <div class="card-header pan-card-header d-flex justify-content-between align-items-center">

                    <span>
                        Uploaded Documents
                    </span>

                    <button
                        type="button"
                        class="btn assign-btn"
                        id="downloadAllDocumentsBtn"
                        data-url="{{ route('admin.itr.download.documents', $application->id) }}"
                    >

                        <span class="download-btn-text">

                            <i class="fa fa-download me-2"></i>

                            Download All

                        </span>

                        <span class="download-btn-loader d-none">

                            <i class="fa fa-spinner fa-spin me-2"></i>

                            Downloading...

                        </span>

                    </button>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- AADHAAR FRONT --}}
                        <div class="col-xl-4 col-lg-6">

                            <div class="document-card">

                                <div class="document-image-wrapper">

                                    <div class="document-title">
                                        Aadhaar Front
                                    </div>

                                    @if($application->aadhaar_front)

                                        <img
                                            src="{{ file_url($application->aadhaar_front) }}"
                                            alt="Aadhaar Front"
                                            loading="lazy"
                                        >

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->aadhaar_front) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    @else

                                        <div class="document-not-found">

                                            <i class="fa fa-file-circle-xmark"></i>

                                            <span>File Not Available</span>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                        {{-- AADHAAR BACK --}}
                        <div class="col-xl-4 col-lg-6">

                            <div class="document-card">

                                <div class="document-image-wrapper">

                                    <div class="document-title">
                                        Aadhaar Back
                                    </div>

                                    @if($application->aadhaar_back)

                                        <img
                                            src="{{ file_url($application->aadhaar_back) }}"
                                            alt="Aadhaar Back"
                                            loading="lazy"
                                        >

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->aadhaar_back) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    @else

                                        <div class="document-not-found">

                                            <i class="fa fa-file-circle-xmark"></i>

                                            <span>File Not Available</span>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                        {{-- PAN CARD --}}
                        <div class="col-xl-4 col-lg-6">

                            <div class="document-card">

                                <div class="document-image-wrapper">

                                    <div class="document-title">
                                        PAN Card
                                    </div>

                                    @if($application->pan_card)

                                        <img
                                            src="{{ file_url($application->pan_card) }}"
                                            alt="PAN Card"
                                            loading="lazy"
                                        >

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->pan_card) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    @else

                                        <div class="document-not-found">

                                            <i class="fa fa-file-circle-xmark"></i>

                                            <span>File Not Available</span>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

          {{-- =====================================================
            | RIGHT SECTION
            ===================================================== --}}
            <div class="col-xl-4">

                {{-- =====================================================
                | ADMIN PANEL
                ====================================================== --}}
                @if(auth()->user()->hasRole('admin'))

                    <div class="card admin-pan-card sticky-top action-panel-card">

                        <div class="card-header pan-card-header">

                            Action Panel

                        </div>

                        <div class="card-body">

                            {{-- RETAILER --}}
                            <div class="action-info-box mb-4">

                                <label>

                                    Retailer

                                </label>

                                <h6>

                                    {{ $application->user->name ?? 'N/A' }}

                                </h6>

                            </div>

                            {{-- ASSIGNED USER --}}
                            <div class="action-info-box mb-4">

                                <label>

                                    Assigned Employee

                                </label>

                                <h6>

                                    {{ $application->assignedEmployee->name ?? 'Not Assigned' }}

                                </h6>

                            </div>

                            {{-- ASSIGN FORM --}}
                            <form
                                id="assignItrForm"
                                action="{{ route('admin.itr.assign', $application->id) }}"
                                method="POST"
                            >

                                @csrf

                                {{-- ASSIGN USER --}}
                                <div class="mb-4">

                                    <label class="form-label fw-semibold">

                                        Assign To Employee

                                    </label>

                                    <select
                                        name="assigned_to"
                                        class="form-select admin-select"
                                        required
                                    >

                                        <option value="">

                                            Select Employee

                                        </option>

                                        @foreach($users as $user)

                                            @if($user->hasRole('Executive'))

                                                <option
                                                    value="{{ $user->id }}"
                                                    {{ $application->assigned_to == $user->id ? 'selected' : '' }}
                                                >

                                                    {{ $user->name }}

                                                </option>

                                            @endif

                                        @endforeach

                                    </select>

                                    <small
                                        class="text-danger error-assigned_to"
                                    ></small>

                                </div>

                                {{-- REMARKS --}}
                                <div class="mb-4">

                                    <label class="form-label fw-semibold">

                                        Remarks / Instructions

                                    </label>

                                    <textarea
                                        name="remarks"
                                        rows="5"
                                        class="form-control admin-textarea"
                                        placeholder="Write instructions for executive..."
                                    >{{ old('remarks', $application->remarks) }}</textarea>

                                    <small
                                        class="text-danger error-remarks"
                                    ></small>

                                </div>

                                {{-- BUTTON --}}
                                <button
                                    type="submit"
                                    class="btn assign-btn w-100"
                                    id="assignItrBtn"
                                >

                                    <span class="btn-text">

                                        <i class="fa fa-user-check me-2"></i>

                                        Assign ITR

                                    </span>

                                    <span
                                        class="btn-loader d-none"
                                    >

                                        <i class="fa fa-spinner fa-spin me-2"></i>

                                        Processing...

                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- =====================================================
                | EXECUTIVE DOCUMENT UPLOAD
                ====================================================== --}}
                @if(
                    auth()->user()->hasRole('Executive')
                    &&
                    $application->documents->count() == 0
                )

                    <div class="card border-0 shadow-lg mt-4">

                        <div class="card-header bg-primary text-white">

                            <h5 class="mb-0 fw-bold">

                                <i class="fa fa-upload me-2"></i>

                                Upload ITR Receipt

                            </h5>

                        </div>

                        <div class="card-body">

                            <form
                                id="documentUploadForm"
                                action="{{ route('admin.itr.document.upload', $application->id) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >

                                @csrf

                                {{-- FILE --}}
                                <div class="mb-3">

                                    <label class="form-label fw-semibold">

                                        Select File

                                    </label>

                                    <input
                                        type="file"
                                        name="support_file"
                                        id="support_file"
                                        class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                        required
                                    >

                                    <small class="text-muted">

                                        PDF, JPG, PNG, DOC, DOCX allowed

                                    </small>

                                    <small
                                        class="text-danger error-support_file"
                                    ></small>

                                </div>

                                {{-- REMARKS --}}
                                <div
                                    id="uploadRemarksBox"
                                    class="mb-3 d-none"
                                >

                                    <label class="form-label fw-semibold">

                                        Upload Remarks

                                    </label>

                                    <textarea
                                        name="upload_remarks"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Write remarks related to uploaded document..."
                                    ></textarea>

                                    <small
                                        class="text-danger error-upload_remarks"
                                    ></small>

                                </div>

                                {{-- BUTTON --}}
                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                    id="uploadBtn"
                                >

                                    <span class="btn-text">

                                        <i class="fa fa-cloud-upload-alt me-2"></i>

                                        Upload Receipt

                                    </span>

                                    <span
                                        class="btn-loader d-none"
                                    >

                                        <i class="fa fa-spinner fa-spin me-2"></i>

                                        Uploading...

                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>

                @endif


                {{-- =====================================================
                | DOCUMENT VISIBLE FOR ALL
                ====================================================== --}}
                @php

                    $doc = $application->documents->first();

                @endphp

                @if($doc)

                    <div class="card border-0 shadow-lg mt-4">

                        <div class="card-header bg-light">

                            <h5 class="mb-0 fw-bold">

                                <i class="fa fa-folder-open me-2"></i>

                                Uploaded Receipt

                            </h5>

                        </div>

                        <div class="card-body">

                            <div class="uploaded-doc-card p-3 border rounded shadow-sm">

                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                                    <div>


                                        @if($doc->remarks)

                                            <div class="mb-2">

                                                <span class="fw-semibold text-dark">

                                                    Remarks:

                                                </span>

                                                <p class="text-muted small mb-0">

                                                    {{ $doc->remarks }}

                                                </p>

                                            </div>

                                        @endif

                                        <div class="small text-secondary">

                                            <div class="mb-1">

                                                <i class="fa fa-user me-1"></i>

                                                Uploaded By:
                                                {{ $doc->user->name ?? 'N/A' }}

                                            </div>

                                            <div>

                                                <i class="fa fa-clock me-1"></i>

                                                Uploaded On:
                                                {{ $doc->created_at->format('d M Y h:i A') }}

                                            </div>

                                        </div>

                                    </div>

                                    <div>

                                        <a
                                            href="{{ asset('storage/' . $doc->file_path) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-primary"
                                        >

                                            <i class="fa fa-eye me-1"></i>

                                            View

                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            </div>
    </div>

</div>

@endsection

@section('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | REDIRECT URL
    |--------------------------------------------------------------------------
    */

    const itrAssignRedirectUrl =
        "{{ route('admin.itr.index') }}";

    /*
    |--------------------------------------------------------------------------
    | ASSIGN ITR APPLICATION
    |--------------------------------------------------------------------------
    */

    $('#assignItrForm').on('submit', function (e) {

        e.preventDefault();

        let form = $(this);

        Swal.fire({

            title: 'Are you sure?',

            text: 'Do you want to assign this ITR application?',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes, Assign',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if(result.isConfirmed)
            {
                $('.text-danger').html('');

                let button = $('#assignItrBtn');

                button.prop('disabled', true);

                button.find('.btn-text').addClass('d-none');

                button.find('.btn-loader').removeClass('d-none');

                $.ajax({

                    url: form.attr('action'),

                    type: 'POST',

                    data: form.serialize(),

                    success: function (response) {

                        button.prop('disabled', false);

                        button.find('.btn-text').removeClass('d-none');

                        button.find('.btn-loader').addClass('d-none');

                        Swal.fire({

                            icon: 'success',

                            title: 'Assigned Successfully',

                            text: response.message,

                            confirmButtonColor: '#3085d6'

                        }).then(() => {

                            window.location.href =
                                itrAssignRedirectUrl;

                        });

                    },

                    error: function (xhr) {

                        button.prop('disabled', false);

                        button.find('.btn-text').removeClass('d-none');

                        button.find('.btn-loader').addClass('d-none');

                        if(xhr.status === 422)
                        {
                            let errors =
                                xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {

                                $('.error-' + key)

                                    .html(value[0]);

                            });
                        }
                        else
                        {
                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text:
                                    xhr.responseJSON.message ??
                                    'Something went wrong.'

                            });
                        }

                    }

                });
            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD ALL DOCUMENTS
    |--------------------------------------------------------------------------
    */

    $('#downloadAllDocumentsBtn').on('click', function () {

        let button = $(this);

        let url = button.data('url');

        Swal.fire({

            title: 'Download Documents?',

            text: 'All uploaded documents will be downloaded as ZIP.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: 'Download',

            cancelButtonText: 'Cancel',

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33'

        }).then((result) => {

            if(result.isConfirmed)
            {
                button.prop('disabled', true);

                button.find('.download-btn-text')

                    .addClass('d-none');

                button.find('.download-btn-loader')

                    .removeClass('d-none');

                setTimeout(function () {

                    window.location.href = url;

                    button.prop('disabled', false);

                    button.find('.download-btn-text')

                        .removeClass('d-none');

                    button.find('.download-btn-loader')

                        .addClass('d-none');

                }, 800);
            }

        });

    });

});

</script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SHOW REMARKS BOX
    |--------------------------------------------------------------------------
    */

    const fileInput = document.getElementById('support_file');

    const remarksBox = document.getElementById('uploadRemarksBox');

    if(fileInput){

        fileInput.addEventListener('change', function () {

            if(this.files.length > 0){

                remarksBox.classList.remove('d-none');

            }else{

                remarksBox.classList.add('d-none');

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT UPLOAD AJAX
    |--------------------------------------------------------------------------
    */

    $('#documentUploadForm').on('submit', function(e){

        e.preventDefault();

        let form = this;

        let formData = new FormData(form);

        /*
        |--------------------------------------------------------------------------
        | SWEET ALERT CONFIRM
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: 'Upload Receipt?',

            text: 'Are you sure you want to upload this receipt?',

            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes, Upload'

        }).then((result) => {

            if(result.isConfirmed){

                /*
                |--------------------------------------------------------------------------
                | BUTTON LOADER
                |--------------------------------------------------------------------------
                */

                $('#uploadBtn').prop('disabled', true);

                $('#uploadBtn .btn-text').addClass('d-none');

                $('#uploadBtn .btn-loader').removeClass('d-none');

                /*
                |--------------------------------------------------------------------------
                | CLEAR ERRORS
                |--------------------------------------------------------------------------
                */

                $('.text-danger').text('');

                /*
                |--------------------------------------------------------------------------
                | AJAX REQUEST
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url: $(form).attr('action'),

                    type: 'POST',

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(response){

                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS ALERT
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon: 'success',

                            title: 'Success',

                            text: response.message ||

                                'Receipt uploaded successfully.',

                            timer: 2500,

                            showConfirmButton: false

                        }).then(() => {

                            location.reload();

                        });

                    },

                    error: function(xhr){

                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION ERROR
                        |--------------------------------------------------------------------------
                        */

                        if(xhr.status === 422){

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value){

                                $('.error-' + key)

                                    .text(value[0]);

                            });

                        }

                        /*
                        |--------------------------------------------------------------------------
                        | SERVER ERROR
                        |--------------------------------------------------------------------------
                        */

                        else{

                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text:

                                    xhr.responseJSON?.message ||

                                    'Something went wrong.'

                            });

                        }

                    },

                    complete: function(){

                        /*
                        |--------------------------------------------------------------------------
                        | BUTTON RESET
                        |--------------------------------------------------------------------------
                        */

                        $('#uploadBtn').prop('disabled', false);

                        $('#uploadBtn .btn-text')

                            .removeClass('d-none');

                        $('#uploadBtn .btn-loader')

                            .addClass('d-none');

                    }

                });

            }

        });

    });

});

</script>

@endsection