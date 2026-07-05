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

        {{-- =====================================================
        | STATUS TABS
        ====================================================== --}}
        <div class="card-header p-0 border-0">
            <ul class="nav nav-tabs admin-pan-tabs" id="itrStatusTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-status="" type="button">
                        All
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-status="new" type="button">
                        New Application
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-status="assigned" type="button">
                        Assigned
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-status="approved" type="button">
                        Approved
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-status="rejected" type="button">
                        Rejected
                    </button>
                </li>
            </ul>
        </div>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>


<script>

$(function () {

    var currentStatus = '';

    var table = $('#itrApplicationTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: {
            url: "{{ route('admin.itr.index') }}",
            data: function (d) {
                d.status_tab = currentStatus;
            }
        },

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
                name: 'retailer',
                orderable: false,
                searchable: false
            },

            {
                data: 'applicant',
                name: 'applicant',
                orderable: false,
                searchable: false
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
                orderable: false,
                searchable: false
            },

           {
                data: 'created_at',
                name: 'created_at',
                render: function(data) {
                    return moment(data).format('DD MMM YYYY hh:mm A');
                }
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

    // Tab click handling
    $('#itrStatusTabs .nav-link').on('click', function () {

        $('#itrStatusTabs .nav-link').removeClass('active');
        $(this).addClass('active');

        currentStatus = $(this).data('status');

        table.ajax.reload();

    });

});

</script>

@endsection