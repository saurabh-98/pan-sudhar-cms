@extends('layout.admin')

@section('content')

<div class="container-fluid admin-pan-find-page">

    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                PAN Find Service Applications

            </h2>

            <p class="admin-pan-subtitle">

                Manage retailer PAN Find service applications and assign them professionally.

            </p>

        </div>

        <div class="header-right">

            <div class="live-status">

                <span class="live-dot"></span>

                Live Data

            </div>

        </div>

    </div>

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

    <div class="card admin-pan-card">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table admin-pan-table align-middle"
                    id="panFindApplicationTable"
                    width="100%"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Application No</th>

                            <th>Retailer</th>

                            <th>Aadhaar Number</th>

                            <th>Service</th>

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

@section('scripts')

<script>

$(function () {

    $('#panFindApplicationTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: "{{ route('admin.pan-find.index') }}",

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
                name: 'retailer',
                orderable:false,
                searchable:false
            },

           

            {
                data: 'aadhaar_number',
                name: 'aadhaar_number',
                orderable:false,
                searchable:false
            },

            {
                data: 'service',
                name: 'service_name'
            },

            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },

            {
                data: 'payment',
                name: 'payment_status',
                orderable: false,
                searchable: false
            },

            {
                data: 'assigned_to',
                name: 'assigned_to',
                orderable: false,
                searchable: false
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
                "Search PAN Find Application...",

            lengthMenu:
                "_MENU_ Records Per Page",

        }

    });

});

</script>

@endsection