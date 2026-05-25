@extends('layout.admin')

@section('content')

<div class="container-fluid admin-pan-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="admin-pan-header">

        <div>

            <h2 class="admin-pan-title">

                ITR Applications

            </h2>

            <p class="admin-pan-subtitle">

                Manage retailer ITR applications and assign them professionally.

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
                    id="itrApplicationTable"
                    width="100%"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>ITR No</th>

                            <th>Retailer</th>

                            <th>Applicant</th>

                            <th>Email</th>

                            <th>Charge</th>

                            <th>Status</th>

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

    $('#itrApplicationTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: "{{ route('admin.itr.index') }}",

        columns: [

            {
                data: 'id',
                name: 'id'
            },

            {
                data: 'itr_no',
                name: 'itr_no'
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
                data: 'email',
                name: 'email'
            },

            {
                data: 'charge',
                name: 'charge'
            },

            {
                data: 'status',
                name: 'status',
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
                "Search ITR Application...",

            lengthMenu:
                "_MENU_ Records Per Page",

        }

    });

});

</script>

@endsection