@extends('layout.retailer')

@section('content')

<div class="container-fluid pan-history-page">

    {{-- =========================================================
    | HEADER
    ========================================================== --}}
    <div class="history-header">

        <div>

            <h2 class="history-title">

                <i class="fa fa-id-card me-2 text-primary"></i>

                PAN Application History

            </h2>

            <p class="history-subtitle">

                View all submitted PAN applications with status,
                payment and uploaded receipt details.

            </p>

        </div>

        <a
            href="{{ route('retailer.pan.apply') }}"
            class="btn history-add-btn"
        >

            <i class="fas fa-plus-circle me-2"></i>

            New PAN Application

        </a>

    </div>


    {{-- =========================================================
    | TABLE CARD
    ========================================================== --}}
    <div class="card history-card border-0 shadow-lg">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="panHistoryTable"
                    class="table history-table align-middle nowrap w-100"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Shop Name</th>

                            <th>Applicant</th>

                            <th>State</th>

                            <th>District</th>

                            <th>Payment</th>

                            <th>Amount</th>

                            <th>Status</th>

                            <th>Receipt</th>

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

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    $('#panHistoryTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: "{{ route('retailer.pan.history') }}",

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },

            {
                data: 'shop_name',
                name: 'shop_name'
            },

            {
                data: 'applicant_name',
                name: 'applicant_name'
            },

            {
                data: 'state_name',
                name: 'state_name'
            },

            {
                data: 'district_name',
                name: 'district_name'
            },

            {
                data: 'payment',
                name: 'payment_status'
            },

            {
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'status',
                name: 'status'
            },

            /*
            |--------------------------------------------------------------------------
            | RECEIPT COLUMN
            |--------------------------------------------------------------------------
            */

            {
                data: 'document_status',
                name: 'document_status',
                searchable: false,
                orderable: false
            },

            {
                data: 'created_at',
                name: 'created_at'
            },

            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            }

        ],

        /*
        |--------------------------------------------------------------------------
        | LANGUAGE
        |--------------------------------------------------------------------------
        */

        language: {

            search: "_INPUT_",

            searchPlaceholder:

                "Search PAN applications...",

            paginate: {

                previous:

                    '<i class="fa fa-angle-left"></i>',

                next:

                    '<i class="fa fa-angle-right"></i>'

            }

        },

        /*
        |--------------------------------------------------------------------------
        | DRAW CALLBACK
        |--------------------------------------------------------------------------
        */

        drawCallback: function(){

            $('[data-bs-toggle="tooltip"]').tooltip();

        }

    });

});

</script>

@endsection