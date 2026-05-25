@extends('layout.admin')

@section('content')

<div class="container-fluid admin-pan-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                New PAN Applications

            </h2>

            <p class="admin-pan-subtitle">

                Manage retailer PAN applications and assign them professionally.

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
    | ALERT
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
    | CARD
    ====================================================== --}}
    <div class="card admin-pan-card">

        <div class="card-body">

            {{-- =====================================================
            | TABLE
            ====================================================== --}}
            <div class="table-responsive">

                <table
                    class="table admin-pan-table align-middle"
                    id="panApplicationTable"
                    width="100%"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Application</th>

                            <th>Retailer</th>

                            <th>Applicant</th>

                            <th>Mobile</th>

                            <th>Status</th>

                            <th>Payment</th>

                            <th>Assigned</th>

                            <th>Date</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>

$(function () {

    $('#panApplicationTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: "{{ route('admin.pan.index') }}",

        columns: [

            {
                data: 'id',
                name: 'id'
            },

            {
                data: 'application_no',
                name: 'application_no'
            },

            {
                data: 'retailer',
                name: 'retailer'
            },

            {
                data: 'applicant',
                name: 'applicant'
            },

            {
                data: 'mobile_no',
                name: 'mobile_no'
            },

            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },

            {
                data: 'payment',
                name: 'payment',
                orderable: false,
                searchable: false
            },

            {
                data: 'assigned_to',
                name: 'assigned_to',
                orderable: false
            },

            {
                data: 'created_at',
                name: 'created_at'
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
                "Search PAN Application...",

            lengthMenu:
                "_MENU_ Records Per Page",

        }

    });

});

</script>
