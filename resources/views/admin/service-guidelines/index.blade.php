@extends('layout.admin')

@section('content')

<div class="container-fluid">

<h3 class="dashboard-title mb-4">

    Service Guideline Management

</h3>

<div class="row g-4">

    {{-- FORM --}}
    <div class="col-lg-4">

        <div class="modern-card">

            <h5 class="card-title">

                Add / Edit Service Guideline

            </h5>

            <form
                id="serviceGuidelineForm"
                enctype="multipart/form-data"
            >

                @csrf

                <input
                    type="hidden"
                    id="guideline_id"
                    name="id"
                >

                <div class="mb-3">

                    <label>

                        Service 

                    </label>

                    <select
                        name="service_code"
                        id="service_code"
                        class="form-control modern-input"
                    >

                        <option value="">

                            Select Service

                        </option>

                        @foreach($services as $groupName => $items)

                            <optgroup label="{{ $groupName }}">

                                @foreach($items as $item)

                                    <option value="{{ $item->slug }}">
                                        {{ $item->name }}
                                    </option>
                                @endforeach

                            </optgroup>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label>

                        Title

                    </label>

                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="form-control modern-input"
                        placeholder="e.g. Birth Certificate Guideline"
                    >

                </div>

                <div class="mb-3">

                    <label>

                        Description

                    </label>

                    <textarea
                        name="description"
                        id="description"
                        class="form-control modern-input"
                        rows="3"
                    ></textarea>

                </div>

                <div class="mb-3">

                    <label>

                        PDF File

                    </label>

                    <input
                        type="file"
                        name="pdf"
                        id="pdf"
                        class="form-control modern-input"
                        accept="application/pdf"
                    >

                    <small class="text-muted">
                        Required when creating a new guideline. Leave empty on edit to keep the existing file.
                    </small>

                    <br>

                    <small
                        class="text-muted"
                        id="existingPdfNote"
                    ></small>

                </div>

                <div class="mb-3">

                    <label>

                        Status

                    </label>

                    <select
                        name="is_active"
                        id="is_active"
                        class="form-control modern-input"
                    >

                        <option value="1">

                            Active

                        </option>

                        <option value="0">

                            Inactive

                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-gradient w-100"
                >

                    Save Service Guideline

                </button>

                <button
                    type="button"
                    id="resetFormBtn"
                    class="btn btn-outline-secondary w-100 mt-2"
                >

                    Clear Form

                </button>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="col-lg-8">

        <div class="modern-card">

            <div class="table-header">

                <h5>

                    Service Guideline List

                </h5>

            </div>

            <div class="table-responsive">

                <table
                    id="serviceGuidelineTable"
                    class="modern-table"
                    style="width:100%"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Service</th>

                            <th>PDF</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

</div>

@endsection

@section('scripts')

<script>

$(document).ready(function(){

    let table = $('#serviceGuidelineTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('admin.service-guidelines.list') }}",

        columns: [

            { data: 'DT_RowIndex', orderable: false, searchable: false },

            { data: 'service_code' },

            { data: 'pdf', orderable: false, searchable: false },

            { data: 'is_active', orderable: false, searchable: false },

            { data: 'action', orderable: false, searchable: false }

        ]

    });

    function clearValidationErrors() {

        $('#serviceGuidelineForm .is-invalid').removeClass('is-invalid');

        $('#serviceGuidelineForm .invalid-feedback').remove();

    }

    function showValidationErrors(errors) {

        $.each(errors, function(field, messages){

            let input = $('#serviceGuidelineForm [name="' + field + '"]');

            input.addClass('is-invalid');

            input.after(
                '<div class="invalid-feedback d-block">' + messages[0] + '</div>'
            );

        });

    }

    function resetForm() {

        $('#serviceGuidelineForm')[0].reset();

        $('#guideline_id').val('');

        $('#existingPdfNote').text('');

        clearValidationErrors();

    }

    $('#resetFormBtn').on('click', function(){

        resetForm();

    });

    $(document).on('click', '.editBtn', function(){

        let id = $(this).data('id');

        $.ajax({

            url: "{{ route('admin.service-guidelines.edit', ':id') }}"
                    .replace(':id', id),

            method: 'GET',

            success: function(res){

                if (res.status) {

                    let data = res.data;

                    $('#guideline_id').val(data.id);

                    $('#service_code').val(data.service_code);

                    $('#title').val(data.title);

                    $('#description').val(data.description);

                    $('#is_active').val(data.is_active ? '1' : '0');

                    $('#existingPdfNote').text(
                        data.pdf ? 'Current file: ' + data.pdf : ''
                    );

                }

            },

            error: function(){

                Swal.fire(
                    'Error',
                    'Unable to load record.',
                    'error'
                );

            }

        });

    });

    $('#serviceGuidelineForm').on('submit', function(e){

        e.preventDefault();

        clearValidationErrors();

        let id = $('#guideline_id').val();

        let url = id

            ? "{{ route('admin.service-guidelines.update', ':id') }}"
                .replace(':id', id)

            : "{{ route('admin.service-guidelines.store') }}";

        let formData = new FormData(this);

        if (id) {

            formData.append('_method', 'POST');

        }

        $.ajax({

            url: url,

            method: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            success: function(res){

                Swal.fire(
                    'Success',
                    res.message || 'Saved successfully',
                    'success'
                );

                resetForm();

                table.ajax.reload();

            },

            error: function(xhr){

                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    showValidationErrors(xhr.responseJSON.errors);

                    Swal.fire(
                        'Error',
                        'Please fix the highlighted fields.',
                        'error'
                    );

                    return;

                }

                let msg = xhr.responseJSON?.message
                    || 'Something went wrong.';

                Swal.fire(
                    'Error',
                    msg,
                    'error'
                );

            }

        });

    });

    $(document).on('click', '.deleteBtn', function(){

        let id = $(this).data('id');

        Swal.fire({

            title: 'Delete Service Guideline?',

            icon: 'warning',

            showCancelButton: true

        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({

                    url: "{{ route('admin.service-guidelines.destroy', ':id') }}"
                            .replace(':id', id),

                    method: 'POST',

                    data: {

                        _token: "{{ csrf_token() }}",

                        _method: 'DELETE'

                    },

                    success: function(res){

                        Swal.fire(
                            'Deleted',
                            res.message || '',
                            'success'
                        );

                        table.ajax.reload();

                    },

                    error: function(){

                        Swal.fire(
                            'Error',
                            'Unable to delete record.',
                            'error'
                        );

                    }

                });

            }

        });

    });

});

</script>

@endsection