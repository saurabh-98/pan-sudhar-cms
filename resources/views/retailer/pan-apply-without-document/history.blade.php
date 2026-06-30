
@extends('layout.retailer')

@section('content')

<div class="container-fluid pan-history-page">

    {{-- =====================================================
    | HEADER
    ====================================================== --}}
    <div class="history-header">

        <div>

            <h2 class="history-title">

                <i class="fa fa-pen me-2 text-primary"></i>

                PAN Without Docs History

            </h2>

            <p class="history-subtitle">

                View all submitted PAN without docs applications.

            </p>

        </div>

        <a
            href="{{ route('retailer.pan-apply-without-document.apply') }}"
            class="btn history-add-btn"
        >

            <i class="fas fa-plus-circle me-2"></i>

             PAN Without Docs

        </a>

    </div>

    {{-- =====================================================
    | TABLE CARD
    ====================================================== --}}
    <div class="card history-card border-0 shadow-lg">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    id="panCorrectionTable"
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

    $('#panCorrectionTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        order: [[0, 'desc']],

        ajax: "{{ route('retailer.pan-apply-without-document.history') }}",

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
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'status',
                name: 'status'
            },

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

        language: {

            search: "_INPUT_",

            searchPlaceholder:

                "Search PAN correction applications...",

            paginate: {

                previous:

                    '<i class="fa fa-angle-left"></i>',

                next:

                    '<i class="fa fa-angle-right"></i>'

            }

        }

    });

});

</script>

@endsection