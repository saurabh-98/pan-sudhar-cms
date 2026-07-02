@extends('layout.retailer')

@section('content')

<div class="container-fluid aadhaar-history-page">

<div class="history-header">

    <div>

        <h2 class="history-title">

            <i class="fa fa-id-card me-2 text-primary"></i>

            Bank Account Service History

        </h2>

        <p class="history-subtitle">

            View all submitted Bank Account service applications,
            status, payment and service details.

        </p>

    </div>


</div>

<div class="card history-card border-0 shadow-lg">

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="aadhaarHistoryTable"
                class="table history-table align-middle nowrap w-100"
            >

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Application No</th>

                        <th>Customer Name</th>

                        <th>Mobile</th>

                        <th>Service</th>

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

$(function(){

    $('#aadhaarHistoryTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0,'desc']],

        ajax: "{{ route('retailer.bank-account.history') }}",

        columns: [

            {
                data:'DT_RowIndex',
                name:'DT_RowIndex',
                searchable:false,
                orderable:false
            },

            {
                data:'application_no',
                name:'application_no'
            },

            {
                data:'customer_name',
                name:'customer_name',
                orderable:false
            },

            {
                data:'mobile',
                name:'mobile',
                orderable:false
            },

            {
                data:'service',
                name:'service_name'
            },

            {
                data:'payment',
                name:'payment_status'
            },

            {
                data:'amount',
                name:'amount'
            },

            {
                data:'status',
                name:'status'
            },

            {
                data:'created_at',
                name:'created_at'
            },

             {
                data: 'document_status',
                name: 'document_status',
                searchable: false,
                orderable: false
            },


            {
                data:'action',
                name:'action',
                searchable:false,
                orderable:false
            }

        ],

        language: {

            search: "_INPUT_",

            searchPlaceholder:
                "Search Aadhaar services...",

            paginate: {

                previous:
                    '<i class="fa fa-angle-left"></i>',

                next:
                    '<i class="fa fa-angle-right"></i>'
            }

        },

        drawCallback: function(){

            $('[data-bs-toggle="tooltip"]').tooltip();

        }

    });

});

</script>

@endsection
