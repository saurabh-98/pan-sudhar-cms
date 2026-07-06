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


            {{-- ================= EXECUTIVE UPLOADED RECEIPT ================= --}}

            <div class="card mb-4">
                <div class="card-header">
                    <strong>Executive Receipt</strong>
                </div>
                <div class="card-body">

                    @forelse($application->serviceDocuments as $doc)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 mb-2">
                            <div>
                                <div class="fw-semibold">
                                    {{ ucfirst($doc->document_type) }}
                                    <span class="text-muted small">
                                        uploaded by {{ $doc->user->name ?? 'N/A' }}
                                    </span>
                                </div>
                                @if($doc->remarks)
                                    <div class="small text-muted">{{ $doc->remarks }}</div>
                                @endif
                            </div>
                            <a href="{{ str_starts_with($doc->file_path, 'http') ? $doc->file_path : asset($doc->file_path) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fa fa-eye"></i> View
                            </a>
                        </div>
                    @empty

                        {{-- Only the assigned Executive can upload the receipt --}}
                        @if(auth()->user()->hasRole('Executive') && $application->assigned_to == auth()->id())
                            <form id="upload-document-form" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Support File</label>
                                    <input type="file"
                                           name="support_file"
                                           class="form-control"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           required>
                                    <div class="form-text">PDF, JPG, PNG, DOC, DOCX up to 5 MB.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Remarks</label>
                                    <textarea name="upload_remarks" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-upload"></i> Upload Receipt
                                </button>
                            </form>
                        @else
                            <p class="text-muted mb-0">No receipt uploaded yet.</p>
                        @endif

                    @endforelse

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

@push('scripts')
<script>
    $(function () {

        /* ================= ASSIGN ================= */

        $('#assign-form').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.pan-find.assign', $application->id) }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        alert(res.message);
                        location.reload();
                    }
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert(res?.message || 'Something went wrong.');
                }
            });
        });

        /* ================= UPLOAD DOCUMENT ================= */

        $('#upload-document-form').on('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.pan-find.document.upload', $application->id) }}",
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.status) {
                        alert(res.message);
                        location.reload();
                    }
                },
                error: function (xhr) {
                    const res = xhr.responseJSON;
                    alert(res?.message || 'Upload failed.');
                }
            });
        });

    });
</script>
@endpush