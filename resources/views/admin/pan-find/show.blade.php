@extends('layout.admin')

@section('title', 'PAN Find Application - ' . $application->application_no)

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            Application #{{ $application->application_no }}
        </h4>

        <a href="{{ route('admin.pan-find.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="row">

        {{-- ================= APPLICATION DETAILS ================= --}}

        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Application Details</strong>
                    {!! $application->status_badge !!}
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Retailer</label>
                            <div class="fw-semibold">
                                {{ $application->user->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Service</label>
                            <div class="fw-semibold">
                                {{ $application->service_name }}
                            </div>
                        </div>

                
                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Aadhaar Number</label>
                            <div class="fw-semibold">
                                {{ $application->aadhaar_number }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Payment</label>
                            <div class="fw-semibold">
                                {!! $application->payment_badge !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Applied On</label>
                            <div class="fw-semibold">
                                {{ $application->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        @if($application->remarks)
                        <div class="col-md-12">
                            <label class="text-muted small mb-0">Remarks</label>
                            <div class="fw-semibold">
                                {{ $application->remarks }}
                            </div>
                        </div>
                        @endif

                        @if($application->admin_remark)
                        <div class="col-md-12">
                            <label class="text-muted small mb-0">Admin Remark</label>
                            <div class="fw-semibold text-danger">
                                {{ $application->admin_remark }}
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>


         {{-- ================= EXECUTIVE PAN DETAILS ================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <strong>PAN Details</strong>
            </div>

            <div class="card-body">

                @if($application->full_name)

                    <div class="border rounded p-3">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    Full Name
                                </label>

                                <div class="fw-semibold">
                                    {{ $application->full_name }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    PAN Number
                                </label>

                                <div class="fw-semibold">
                                    {{ strtoupper($application->pan_number) }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    Date of Birth
                                </label>

                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($application->dob)->format('d M Y') }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    Gender
                                </label>

                                <div class="fw-semibold">
                                    {{ $application->gender }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    Submitted By
                                </label>

                                <div class="fw-semibold">
                                    {{ optional($application->assignedUser)->name ?? 'Executive' }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-0">
                                    Submitted On
                                </label>

                                <div class="fw-semibold">
                                    {{ optional($application->updated_at)->format('d M Y h:i A') }}
                                </div>
                            </div>

                        </div>

                    </div>

                @elseif(auth()->user()->hasRole('Executive') && $application->assigned_to == auth()->id())

                    <form id="upload-document-form">

                        @csrf

                        <div class="row">

                            <div class="col-md-12 mb-3">

                                <label class="form-label">
                                    Full Name
                                </label>

                                <input type="text"
                                    name="full_name"
                                    class="form-control"
                                    placeholder="Enter Full Name"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    PAN Number
                                </label>

                                <input type="text"
                                    name="pan_number"
                                    class="form-control text-uppercase"
                                    maxlength="10"
                                    placeholder="ABCDE1234F"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Date of Birth
                                </label>

                                <input type="date"
                                    name="dob"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Gender
                                </label>

                                <select name="gender"
                                        class="form-select"
                                        required>

                                    <option value="">
                                        Select Gender
                                    </option>

                                    <option value="Male">
                                        Male
                                    </option>

                                    <option value="Female">
                                        Female
                                    </option>

                                    <option value="Other">
                                        Other
                                    </option>

                                </select>

                            </div>

                        </div>

                        <button type="submit"
                                class="btn btn-success">

                            <i class="fa fa-save me-2"></i>

                            Save PAN Details

                        </button>

                    </form>

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="fa fa-clock me-2"></i>

                        PAN details have not been submitted yet.

                    </div>

                @endif

            </div>

        </div>

        </div>

        {{-- ================= ACTIONS SIDEBAR ================= --}}

        <div class="col-lg-4">

            {{-- ================= ASSIGN TO EXECUTIVE ================= --}}

            @unless(in_array(strtolower($application->status), ['approved', 'completed', 'rejected']))
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Assign to Executive</strong>
                </div>
                <div class="card-body">
                    <form id="assign-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Executive</label>
                            <select name="assigned_to" class="form-select" required>
                                <option value="">-- Select Executive --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        @selected($application->assigned_to == $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="2">{{ $application->remarks }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fa fa-user-check"></i> Assign
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Assigned Executive</strong>
                </div>
                <div class="card-body">
                    {{ $application->assignedUser->name ?? 'Not Assigned' }}
                </div>
            </div>
            @endunless

            {{-- ================= REJECT ================= --}}

            @unless(in_array(strtolower($application->status), ['approved', 'completed', 'rejected']))
            <div class="card mb-4 border-danger">
                <div class="card-header text-danger">
                    <strong>Reject Application</strong>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Rejecting will refund any deducted wallet amount back to the retailer.
                    </p>
                    <form action="{{ route('admin.pan-find.reject', $application->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to reject this application?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fa fa-times"></i> Reject Application
                        </button>
                    </form>
                </div>
            </div>
            @endunless

        </div>

    </div>

</div>
@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function () {

    /* ================= ASSIGN ================= */

    $('#assign-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);

        Swal.fire({
            title: 'Assign Application?',
            text: 'Are you sure you want to assign this application?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Assign'
        }).then((result) => {

            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Please wait...',
                    text: 'Assigning application...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('admin.pan-find.assign', $application->id) }}",
                    method: 'POST',
                    data: form.serialize(),

                    success: function (res) {

                        Swal.close();

                        if (res.status) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.message,
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                location.reload();
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: res.message
                            });

                        }
                    },

                    error: function (xhr) {

                        Swal.close();

                        const res = xhr.responseJSON;

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res?.message || 'Something went wrong.'
                        });

                    }

                });

            }

        });

    });

   /* ================= SAVE PAN DETAILS ================= */

    $('#upload-document-form').on('submit', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        Swal.fire({
            title: 'Save PAN Details?',
            text: 'Are you sure you want to submit these PAN details?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Save'
        }).then((result) => {

            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while PAN details are being saved.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({

                    url: "{{ route('admin.pan-find.document.upload', $application->id) }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (res) {

                        Swal.close();

                        if (res.status) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Saved Successfully',
                                text: res.message,
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                location.reload();
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Failed',
                                text: res.message
                            });

                        }

                    },

                    error: function (xhr) {

                        Swal.close();

                        let message = 'Something went wrong.';

                        if (xhr.responseJSON) {

                            if (xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }

                            if (xhr.responseJSON.errors) {

                                message = Object.values(xhr.responseJSON.errors)
                                    .map(error => error[0])
                                    .join('<br>');

                            }

                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            html: message
                        });

                    }

                });

            }

        });

    });
    });
</script>
@endsection