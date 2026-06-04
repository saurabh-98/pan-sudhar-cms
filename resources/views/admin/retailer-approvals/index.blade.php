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

{{-- =====================================================
| APPROVE RETAILER MODAL
====================================================== --}}

<div
    class="modal fade"
    id="approveRetailerModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-xl">

        <form
            method="POST"
            id="approveRetailerForm"
        >

            @csrf

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        Assign Modules & Approve Retailer

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">

                        Select modules that should be accessible to this retailer.

                    </div>

                    <div class="row">

                        @foreach($modules as $module)

                            <div class="col-md-4 mb-3">

                                <div class="form-check">

                                    <input
                                        class="form-check-input module-checkbox"
                                        type="checkbox"
                                        name="modules[]"
                                        value="{{ $module->id }}"
                                        id="module_{{ $module->id }}"
                                    >

                                    <label
                                        class="form-check-label"
                                        for="module_{{ $module->id }}"
                                    >

                                        {{ $module->name }}

                                    </label>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa fa-check"></i>

                        Approve Retailer

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<div
    class="modal fade"
    id="viewModulesModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Assigned Modules

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            <div
                class="modal-body"
                id="assignedModulesContainer"
            >

            </div>

        </div>

    </div>

</div>


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

    $(document).on(
        'click',
        '.approve-btn',
        function () {

            let retailerId =
                $(this).data('id');

            $('.module-checkbox').prop(
                'checked',
                false
            );

            let approveUrl =
                "{{ route('admin.retailer-approvals.approve', ['id' => '__ID__']) }}";

            approveUrl =
                approveUrl.replace(
                    '__ID__',
                    retailerId
                );

            $('#approveRetailerForm')
                .attr(
                    'action',
                    approveUrl
                );

            let modal =
                new bootstrap.Modal(
                    document.getElementById(
                        'approveRetailerModal'
                    )
                );

            modal.show();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | VALIDATE MODULE SELECTION
    |--------------------------------------------------------------------------
    */

    $('#approveRetailerForm').on(
        'submit',
        function (e) {

            let totalSelected =
                $('.module-checkbox:checked')
                .length;

            if (
                totalSelected === 0
            ) {

                e.preventDefault();

                alert(
                    'Please select at least one module before approving retailer.'
                );

                return false;
            }
        }
    );

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