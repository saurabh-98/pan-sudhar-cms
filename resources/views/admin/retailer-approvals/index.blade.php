@extends('layout.admin')

@section('content')

<div class="container-fluid admin-pan-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                Retailer Approval Requests

            </h2>

            <p class="admin-pan-subtitle">

                Review, approve or reject retailer registration requests.

            </p>

        </div>

        <div class="header-right">

            <div class="live-status">

                <span class="live-dot"></span>

                Live Data

            </div>

        </div>

    </div>

    {{-- =====================================================
    | SUCCESS ALERT
    ====================================================== --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show admin-alert">

            <i class="fa fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- =====================================================
    | ERROR ALERT
    ====================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show admin-alert">

            <i class="fa fa-times-circle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- =====================================================
    | GENERATED CREDENTIALS
    ====================================================== --}}
    @if(session('credentials'))

        <div class="alert alert-info alert-dismissible fade show">

            <h5 class="mb-3">

                <i class="fa fa-key me-2"></i>

                Generated Retailer Credentials

            </h5>

            <div class="row">

                <div class="col-md-6">

                    <label class="fw-bold">

                        User ID (Email)

                    </label>

                    <div class="input-group">

                        <input
                            type="text"
                            readonly
                            class="form-control"
                            id="generatedUserId"
                            value="{{ session('credentials.username') }}"
                        >

                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="copyField('generatedUserId')"
                        >

                            Copy

                        </button>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="fw-bold">

                        Password (Mobile Number)

                    </label>

                    <div class="input-group">

                        <input
                            type="text"
                            readonly
                            class="form-control"
                            id="generatedPassword"
                            value="{{ session('credentials.password') }}"
                        >

                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="copyField('generatedPassword')"
                        >

                            Copy

                        </button>

                    </div>

                </div>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif

    {{-- =====================================================
    | CARD
    ====================================================== --}}
    <div class="card admin-pan-card">

        <div class="card-body">

            <div class="table-responsive retailer-scroll">

                <table
                    class="table admin-pan-table align-middle"
                    id="retailerApprovalTable"
                    width="100%"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Shop Name</th>

                            <th>Owner Name</th>

                            <th>Mobile</th>

                            <th>Email</th>

                            <th>State</th>

                            <th>District</th>

                            <th>Distributor</th>

                            <th>Status</th>
                            
                            <th>Applied On</th>

                            <th>Dashboard Access</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<style>

.retailer-scroll{

    width: 100%;

    overflow-x: auto;

    overflow-y: hidden;
}

#retailerApprovalTable{

    min-width: 1700px;
}

.dataTables_wrapper{

    width: 100%;
}

.dataTables_scrollBody{

    overflow-x: auto !important;
}

#retailerApprovalTable th:last-child,
#retailerApprovalTable td:last-child{

    position: sticky;

    right: 0;

    background: #fff;

    z-index: 10;
}

</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>

function copyField(id)
{
    let copyText = document.getElementById(id);

    copyText.select();

    copyText.setSelectionRange(
        0,
        99999
    );

    navigator.clipboard.writeText(
        copyText.value
    );

    alert(
        'Copied Successfully'
    );
}

$(function () {

    /*
    |--------------------------------------------------------------------------
    | RETAILER APPROVAL DATATABLE
    |--------------------------------------------------------------------------
    */

    let retailerTable =
        $('#retailerApprovalTable').DataTable({

            processing: true,

            serverSide: true,

            responsive: false,

            scrollX: true,

            scrollCollapse: true,

            autoWidth: false,

            pageLength: 10,

            order: [[0, 'desc']],

            ajax:
                "{{ route('admin.retailer-approvals.index') }}",

            columns: [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },

                {
                    data: 'shop_name',
                    name: 'shop_name'
                },

                {
                    data: 'owner_name',
                    name: 'owner_name'
                },

                {
                    data: 'mobile',
                    name: 'mobile'
                },

                {
                    data: 'email',
                    name: 'email'
                },

                {
                    data: 'state',
                    name: 'state'
                },

                {
                    data: 'district',
                    name: 'district'
                },

                {
                    data: 'distributor',
                    name: 'distributor',
                    orderable: false,
                    searchable: true
                },

                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                
                {
                    data: 'created_at',
                    name: 'created_at'
                },

                {
                    data: 'dashboard_access',
                    name: 'dashboard_access',
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

            language: {

                search: "",

                searchPlaceholder:
                    "Search Retailer Request...",

                lengthMenu:
                    "_MENU_ Records Per Page"

            }

        });

    /*
    |--------------------------------------------------------------------------
    | OPEN MODULE ASSIGNMENT MODAL
    |--------------------------------------------------------------------------
    */

   $(document).on('click', '.approve-btn', function () {

    let form = $(this).closest('form');

    Swal.fire({
        title: 'Approve Retailer?',
        text: 'Are you sure you want to approve this retailer?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, Approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            form.submit();

        }

    });

});

   
    
    /*
    |--------------------------------------------------------------------------
    | AUTO REFRESH TABLE AFTER MODAL CLOSE
    |--------------------------------------------------------------------------
    */

    $('#approveRetailerModal').on(
        'hidden.bs.modal',
        function () {

            retailerTable.ajax.reload(
                null,
                false
            );

        }
    );

});

</script>


@endsection