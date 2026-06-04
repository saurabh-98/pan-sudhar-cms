
@extends('layout.admin')

@section('styles')

@endsection

@section('content')

<div class="container-fluid chx-wrapper">

    {{-- PAGE HEADER --}}

    <div class="chx-page-header">

        <div>

            <h2>

                Charges Management

            </h2>

            <p>

                Manage PAN, ITR, Wallet and Service Charges

            </p>

        </div>

    </div>

    <div class="row g-4 align-items-start">

        {{-- FORM SECTION --}}

        <div class="col-xl-4 col-lg-5">

            <div class="card chx-card chx-form-card">

                <div class="card-header chx-card-header">

                    <h5>

                        Charge Management

                    </h5>

                </div>

                <div class="card-body">

                    <form id="chargeForm">

                        @csrf

                        <input
                            type="hidden"
                            id="charge_id"
                            name="charge_id"
                        >

                        <div class="mb-3">

                            <label class="chx-label">

                                Charge Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control chx-input"
                                placeholder="Enter charge name"
                                required
                            >

                            <small class="text-danger error-name"></small>

                        </div>

                        <div class="mb-3">

                            <label class="chx-label">

                                Charge Code

                            </label>

                            <input
                                type="text"
                                name="code"
                                class="form-control chx-input"
                                placeholder="e.g. new_pan_apply"
                                required
                            >

                            <small class="text-danger error-code"></small>

                        </div>

                        <div class="mb-3">

                            <label class="chx-label">

                                Charge Type

                            </label>

                            <select
                                name="type"
                                class="form-control chx-input"
                            >

                                <option value="fixed">

                                    Fixed

                                </option>

                                <option value="percentage">

                                    Percentage

                                </option>

                            </select>

                            <small class="text-danger error-type"></small>

                        </div>

                        <div class="mb-3">

                            <label class="chx-label">

                                Amount / Value

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="amount"
                                class="form-control chx-input"
                                placeholder="0.00"
                                required
                            >

                            <small class="text-danger error-amount"></small>

                        </div>

                        <div class="mb-3">

                            <label class="chx-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                class="form-control chx-input"
                                rows="4"
                                placeholder="Enter description"
                            ></textarea>

                            <small class="text-danger error-description"></small>

                        </div>

                        <div class="mb-4">

                            <label class="chx-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-control chx-input"
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
                            id="saveBtn"
                            class="btn chx-btn-primary w-100"
                        >

                            Save Charge

                        </button>

                    </form>

                </div>

            </div>

        </div>

        {{-- TABLE SECTION --}}

        <div class="col-xl-8 col-lg-7">

            <div class="card chx-card">

                <div class="card-header chx-card-header">

                    <h5>

                        Charges List

                    </h5>

                </div>

                <div class="card-body">

                    <table
                        id="chargesTable"
                        class="table chx-table"
                        width="100%"
                    >

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Name</th>

                                <th>Code</th>

                                <th>Type</th>

                                <th>Value</th>

                                <th>Status</th>

                                <th width="150">

                                    Action

                                </th>

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

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css"
/>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>

$(function () {

   $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN':
                '{{ csrf_token() }}'

        }

    });
    
    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#chargesTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        ajax: "{{ route('admin.charges.list') }}",

        columns: [

            {
                data: 'id',
                name: 'id'
            },

            {
                data: 'name',
                name: 'name'
            },

            {
                data: 'code',
                name: 'code'
            },

            {
                data: 'type',
                name: 'type'
            },

            {
                data: 'value',
                name: 'value'
            },

            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }

        ],

        pageLength: 10,

        order: [[0, 'desc']]

    });

    /*
    |--------------------------------------------------------------------------
    | STORE / UPDATE
    |--------------------------------------------------------------------------
    */

    $('#chargeForm').on(

        'submit',

        function (e) {

            e.preventDefault();

            $('.text-danger').html('');

            let id = $('#charge_id').val();

            let url = id

                ? "{{ url('admin/charges/update') }}/" + id

                : "{{ route('admin.charges.store') }}";

            $('#saveBtn')

                .prop('disabled', true)

                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Processing...'
                );

            $.ajax({

                url: url,

                type: 'POST',

                data: $(this).serialize(),

                success: function (response) {

                    $('#chargeForm')[0].reset();

                    $('#charge_id').val('');

                    $('.text-danger').html('');

                    $('#saveBtn')

                        .prop('disabled', false)

                        .html('Save Charge');

                    table.ajax.reload(
                        null,
                        false
                    );

                    Swal.fire({

                        icon: 'success',

                        title: 'Success',

                        text: response.message,

                        timer: 2000,

                        showConfirmButton: false

                    });

                },

                error: function (xhr) {

                    $('#saveBtn')

                        .prop('disabled', false)

                        .html('Save Charge');

                    if (
                        xhr.status === 422
                    ) {

                        $.each(

                            xhr.responseJSON.errors,

                            function (
                                key,
                                value
                            ) {

                                $('.error-' + key)

                                    .html(
                                        value[0]
                                    );

                            }

                        );

                    } else {

                        Swal.fire({

                            icon: 'error',

                            title: 'Error',

                            text: 'Something went wrong.'

                        });

                    }

                }

            });

        }

    );

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(

        'click',

        '.editCharge',

        function () {

            let id =
                $(this).data('id');

            $.ajax({

                url:
                    "{{ url('admin/charges/edit') }}/" + id,

                type: 'GET',

                success: function (res) {

                    $('#charge_id')
                        .val(res.id);

                    $('[name="name"]')
                        .val(res.name);

                    $('[name="code"]')
                        .val(res.code);

                    $('[name="type"]')
                        .val(res.type);

                    $('[name="amount"]')
                        .val(res.amount);

                    $('[name="description"]')
                        .val(res.description);

                    $('[name="status"]')
                        .val(res.status);

                    $('#saveBtn')
                        .html(
                            'Update Charge'
                        );

                    $('html, body')

                        .animate({

                            scrollTop: 0

                        }, 400);

                }

            });

        }

    );

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on(

        'click',

        '.deleteCharge',

        function () {

            let id =
                $(this).data('id');

            Swal.fire({

                title:
                    'Delete Charge?',

                text:
                    'This action cannot be undone.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonColor:
                    '#dc3545',

                cancelButtonColor:
                    '#6c757d',

                confirmButtonText:
                    'Yes, Delete'

            }).then(

                (
                    result
                ) => {

                    if (
                        result.isConfirmed
                    ) {

                        $.ajax({

                        url: "{{ url('admin/charges/delete') }}/" + id,

                        type: 'DELETE',

                        data: {

                            _token: '{{ csrf_token() }}'

                        },

                        success: function(response){

                            table.ajax.reload(null,false);

                            Swal.fire({

                                icon: 'success',

                                title: 'Deleted',

                                text: response.message

                            });

                        }

                    });

                    }

                }

            );

        }

    );

});

</script>



@endsection

