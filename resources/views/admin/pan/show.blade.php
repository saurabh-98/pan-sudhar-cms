@extends('layout.admin')

@section('content')

@php
    use Illuminate\Support\Str;
@endphp


<div class="container-fluid admin-pan-show-page">

    {{-- =====================================================
    | HEADER
    ====================================================== --}}
    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                PAN Application Details

            </h2>

            <p class="admin-pan-subtitle">

                Verify, review and assign PAN application.

            </p>

        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('admin.pan.index') }}"
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

                    Application Overview

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Application No

                                </label>

                                <h6>

                                    {{ $application->application_no }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    PAN Type

                                </label>

                                <h6>

                                    {{ $application->pan_type }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Status

                                </label>

                                <div>

                                    {!! $application->status_badge !!}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Payment Status

                                </label>

                                <div>

                                    {!! $application->payment_badge !!}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | PERSONAL DETAILS
            ====================================================== --}}
            <div class="card admin-pan-card mb-4">

                <div class="card-header pan-card-header">

                    Personal Details

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    First Name

                                </label>

                                <h6>

                                    {{ $application->first_name }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    Middle Name

                                </label>

                                <h6>

                                    {{ $application->middle_name ?? 'N/A' }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    Last Name

                                </label>

                                <h6>

                                    {{ $application->last_name }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    Gender

                                </label>

                                <h6>

                                    {{ $application->gender }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    Date Of Birth

                                </label>

                                <h6>

                                    {{ $application->dob }}

                                </h6>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="pan-info-box">

                                <label>

                                    Mobile Number

                                </label>

                                <h6>

                                    {{ $application->mobile_no }}

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

                                    Aadhaar Number

                                </label>

                                <h6>

                                    {{ $application->masked_aadhaar }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | FAMILY DETAILS
            ====================================================== --}}
            <div class="card admin-pan-card mb-4">

                <div class="card-header pan-card-header">

                    Family Details

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- FATHER --}}
                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Father Name

                                </label>

                                <h6>

                                    {{ $application->father_full_name }}

                                </h6>

                            </div>

                        </div>

                        {{-- MOTHER --}}
                        <div class="col-md-6">

                            <div class="pan-info-box">

                                <label>

                                    Mother Name

                                </label>

                                <h6>

                                    {{ $application->mother_full_name }}

                                </h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | ADDRESS
            ====================================================== --}}
            <div class="card admin-pan-card mb-4">

                <div class="card-header pan-card-header">

                    Address Details

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-12">

                            <div class="pan-info-box">

                                <label>

                                    Full Address

                                </label>

                                <h6>

                                    {{ $application->full_address }}

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

                    <div class="card-header pan-card-header">

                        Uploaded Documents

                        <button
                            type="button"
                            class="btn assign-btn"
                            id="downloadAllDocumentsBtn"
                            data-url="{{ route('admin.pan.new.download.documents', $application->id) }}"
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

                            {{-- PHOTO --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="document-card">

                                    <div class="document-image-wrapper">

                                        <div class="document-title">

                                            Photo

                                        </div>

                                        @php
                                            $photoExt = strtolower(
                                                pathinfo(
                                                    $application->photo ?? '',
                                                    PATHINFO_EXTENSION
                                                )
                                            );
                                        @endphp

                                        @if($photoExt == 'pdf')

                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                alt="PDF"
                                            >

                                        @else

                                            <img
                                                src="{{ file_url($application->photo) }}"
                                                alt="Photo"
                                            >

                                        @endif

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->photo) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- SIGNATURE --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="document-card">

                                    <div class="document-image-wrapper">

                                        <div class="document-title">

                                            Signature

                                        </div>

                                        @php
                                            $signatureExt = strtolower(
                                                pathinfo(
                                                    $application->signature ?? '',
                                                    PATHINFO_EXTENSION
                                                )
                                            );
                                        @endphp

                                        @if($signatureExt == 'pdf')

                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                alt="PDF"
                                            >

                                        @else

                                            <img
                                                src="{{ file_url($application->signature) }}"
                                                alt="Signature"
                                            >

                                        @endif

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->signature) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- AADHAAR CARD --}}
                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="document-card">

                                    <div class="document-image-wrapper">

                                        <div class="document-title">

                                            Aadhaar Card

                                        </div>

                                        @php
                                            $aadhaarExt = strtolower(
                                                pathinfo(
                                                    $application->aadhaar_card ?? '',
                                                    PATHINFO_EXTENSION
                                                )
                                            );
                                        @endphp

                                        @if($aadhaarExt == 'pdf')

                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                alt="PDF"
                                            >

                                        @else

                                            <img
                                                src="{{ file_url($application->aadhaar_card) }}"
                                                alt="Aadhaar Card"
                                            >

                                        @endif

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->aadhaar_card) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- DOB PROOF --}}
                            @if($application->dob_proof_file)

                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="document-card">

                                    <div class="document-image-wrapper">

                                        <div class="document-title">

                                            DOB Proof

                                        </div>

                                        @php
                                            $dobExt = strtolower(
                                                pathinfo(
                                                    $application->dob_proof_file ?? '',
                                                    PATHINFO_EXTENSION
                                                )
                                            );
                                        @endphp

                                        @if($dobExt == 'pdf')

                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                alt="PDF"
                                            >

                                        @else

                                            <img
                                                src="{{ file_url($application->dob_proof_file) }}"
                                                alt="DOB Proof"
                                            >

                                        @endif

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->dob_proof_file) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endif

                            {{-- SUPPORTING DOCUMENT --}}
                            @if($application->supporting_document)

                            <div class="col-xl-3 col-lg-4 col-md-6">

                                <div class="document-card">

                                    <div class="document-image-wrapper">

                                        <div class="document-title">

                                            Supporting Document

                                        </div>

                                        @php
                                            $supportingExt = strtolower(
                                                pathinfo(
                                                    $application->supporting_document ?? '',
                                                    PATHINFO_EXTENSION
                                                )
                                            );
                                        @endphp

                                        @if($supportingExt == 'pdf')

                                            <img
                                                src="https://cdn-icons-png.flaticon.com/512/337/337946.png"
                                                alt="PDF"
                                            >

                                        @else

                                            <img
                                                src="{{ file_url($application->supporting_document) }}"
                                                alt="Supporting Document"
                                            >

                                        @endif

                                        <div class="document-overlay">

                                            <a
                                                href="{{ file_url($application->supporting_document) }}"
                                                target="_blank"
                                                class="document-view-btn"
                                            >

                                                <i class="fa fa-eye me-2"></i>

                                                View

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            @endif

                        </div>

                    </div>

                </div>
        </div>

      {{-- ======================================================
| RIGHT SECTION
====================================================== --}}
<div class="col-xl-4">

    {{-- ======================================================
    | ADMIN PANEL
    ====================================================== --}}
    @if(auth()->user()->hasRole('admin'))

        <div class="card admin-pan-card sticky-top action-panel-card border-0 shadow-lg mb-4">

            {{-- HEADER --}}
            <div class="card-header pan-card-header d-flex align-items-center justify-content-between">

                <div>

                    <h5 class="mb-0 fw-bold">

                        <i class="fa fa-user-shield me-2"></i>

                        Action Panel

                    </h5>

                </div>

                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">

                    Admin Control

                </span>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- RETAILER INFO --}}
                <div class="action-info-card mb-4">

                    <div class="d-flex align-items-center gap-3">

                        <div class="action-avatar">

                            {{ strtoupper(substr($application->user->name ?? 'N',0,1)) }}

                        </div>

                        <div>

                            <label class="info-label">

                                Retailer

                            </label>

                            <h6 class="info-value mb-0">

                                {{ $application->user->name ?? 'N/A' }}

                            </h6>

                        </div>

                    </div>

                </div>

                {{-- ASSIGNED EXECUTIVE --}}
                <div class="action-info-card mb-4">

                    <label class="info-label d-block mb-2">

                        Assigned Executive

                    </label>

                    @if($application->assignedUser)

                        <div class="assigned-user-box">

                            <i class="fa fa-user-check me-2"></i>

                            {{ $application->assignedUser->name }}

                        </div>

                    @else

                        <div class="not-assigned-box">

                            <i class="fa fa-user-clock me-2"></i>

                            Not Assigned Yet

                        </div>

                    @endif

                </div>

                {{-- ASSIGN FORM --}}
                <form
                    id="assignApplicationForm"
                    action="{{ route('admin.pan.assign', $application->id) }}"
                    method="POST"
                >

                    @csrf

                    {{-- EXECUTIVE SELECT --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            <i class="fa fa-users me-2"></i>

                            Assign To Executive

                        </label>

                        <select
                            name="assigned_to"
                            class="form-select admin-select"
                            required
                        >

                            <option value="">

                                Select Executive

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

                        <small class="text-danger error-assigned_to"></small>

                    </div>

                    {{-- REMARKS --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">

                            <i class="fa fa-comment-dots me-2"></i>

                            Remarks / Instructions

                        </label>

                        <textarea
                            name="remarks"
                            rows="5"
                            class="form-control admin-textarea"
                            placeholder="Write instructions, verification remarks or notes for executive..."
                        >{{ old('remarks', $application->remarks) }}</textarea>

                        <small class="text-danger error-remarks"></small>

                    </div>

                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        class="btn assign-btn w-100 py-3"
                        id="assignBtn"
                    >

                        <span class="btn-text">

                            <i class="fa fa-paper-plane me-2"></i>

                            Assign Application

                        </span>

                        <span class="btn-loader d-none">

                            <i class="fa fa-spinner fa-spin me-2"></i>

                            Processing...

                        </span>

                    </button>

                </form>

            </div>

        </div>

    @endif


    {{-- ======================================================
    | EXECUTIVE DOCUMENT UPLOAD PANEL
    ====================================================== --}}
    @if(
        auth()->user()->hasRole('Executive')
        &&
        $application->documents->count() == 0
    )

        <div class="card border-0 shadow-lg mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0 fw-bold">

                    <i class="fa fa-upload me-2"></i>

                    Upload Documents

                </h5>

            </div>

            <div class="card-body">

                <form
                    id="documentUploadForm"
                    action="{{ route('admin.pan.document.upload', $application->id) }}"
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

                            Upload Document

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


   {{-- ======================================================
    | DOCUMENTS VISIBLE FOR ALL
    ====================================================== --}}
    @php

        $doc = $application->documents->first();

    @endphp

    @if($doc)

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-light">

                <h5 class="mb-0 fw-bold">

                    <i class="fa fa-folder-open me-2"></i>

                    Uploaded Document

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

{{-- ======================================================
| SHOW REMARKS AFTER FILE SELECT
====================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const fileInput = document.getElementById('support_file');

    const remarksBox = document.getElementById('uploadRemarksBox');

    if(fileInput){

        fileInput.addEventListener('change', function () {

            if (this.files.length > 0) {

                remarksBox.classList.remove('d-none');

            } else {

                remarksBox.classList.add('d-none');

            }

        });

    }

});

</script>


<script>

$(document).ready(function () {

    $('#assignApplicationForm').on('submit', function (e) {

        e.preventDefault();

        let form = $(this);

        /*
        |--------------------------------------------------------------------------
        | CONFIRMATION ALERT
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: 'Are you sure?',

            text: 'Do you want to assign this PAN application?',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#3085d6',

            cancelButtonColor: '#d33',

            confirmButtonText: 'Yes, Assign',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            /*
            |--------------------------------------------------------------------------
            | IF CONFIRMED
            |--------------------------------------------------------------------------
            */

            if(result.isConfirmed)
            {
                $('.text-danger').html('');

                let button = $('#assignBtn');

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

                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS MESSAGE
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon: 'success',

                            title: 'Assigned Successfully',

                            text: response.message,

                            confirmButtonColor: '#3085d6'

                        }).then(() => {

                            /*
                            |--------------------------------------------------------------------------
                            | REDIRECT
                            |--------------------------------------------------------------------------
                            */

                            window.location.href =
                                "{{ route('admin.pan.index') }}";

                        });

                    },

                    error: function (xhr) {

                        button.prop('disabled', false);

                        button.find('.btn-text').removeClass('d-none');

                        button.find('.btn-loader').addClass('d-none');

                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION ERROR
                        |--------------------------------------------------------------------------
                        */

                        if(xhr.status === 422)
                        {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function (key, value) {

                                $('.error-' + key).html(value[0]);

                            });
                        }
                        else
                        {
                            /*
                            |--------------------------------------------------------------------------
                            | GENERAL ERROR
                            |--------------------------------------------------------------------------
                            */

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
    | SHOW REMARK BOX
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
    | AJAX FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#documentUploadForm').on('submit', function(e){

        e.preventDefault();

        let form = this;

        let formData = new FormData(form);

        /*
        |--------------------------------------------------------------------------
        | SWEET ALERT CONFIRMATION
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: 'Upload Document?',

            text: "Are you sure you want to upload this document?",

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

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(response){

                        /*
                        |--------------------------------------------------------------------------
                        | RESET FORM
                        |--------------------------------------------------------------------------
                        */

                        form.reset();

                        remarksBox.classList.add('d-none');

                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS ALERT
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon: 'success',

                            title: 'Success',

                            text: response.message ??

                                'Document uploaded successfully.',

                            timer: 2000,

                            showConfirmButton: false

                        }).then(() => {

                            location.reload();

                        });

                    },

                    error: function(xhr){

                        /*
                        |--------------------------------------------------------------------------
                        | VALIDATION ERRORS
                        |--------------------------------------------------------------------------
                        */

                        if(xhr.status === 422){

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value){

                                $('.error-' + key).text(value[0]);

                            });

                        }else{

                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text: 'Something went wrong.'

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

                        $('#uploadBtn .btn-text').removeClass('d-none');

                        $('#uploadBtn .btn-loader').addClass('d-none');

                    }

                });

            }

        });

    });

});

</script>
@endsection