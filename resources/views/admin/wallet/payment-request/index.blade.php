@extends('layout.admin')

@section('title','Payment Requests')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                <i class="fas fa-qrcode me-2"></i>

                Payment Requests

            </h4>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            @if(session('error'))

                <div class="alert alert-danger">

                    {{ session('error') }}

                </div>

            @endif

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped align-middle"
                    id="paymentRequestTable">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Retailer</th>

                            <th>Amount</th>

                            <th>UTR</th>

                            <th>Status</th>

                            <th>Submitted</th>

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

    $('#paymentRequestTable').DataTable({

        processing:true,

        serverSide:true,

        responsive:true,

        ajax:"{{ route('admin.wallet.payment-requests') }}",

        columns:[

            {

                data:'DT_RowIndex',

                name:'DT_RowIndex',

                orderable:false,

                searchable:false

            },

            {

                data:'retailer',

                name:'retailer',

                orderable:false

            },

            {

                data:'amount',

                name:'amount'

            },

            {

                data:'utr',

                name:'utr',

                defaultContent:'-'

            },

            {

                data:'status',

                name:'status',

                orderable:false,

                searchable:false

            },

            {

                data:'created_at',

                name:'created_at'

            },

            {

                data:'action',

                name:'action',

                orderable:false,

                searchable:false

            }

        ],

        pageLength:10,

        order:[[5,'desc']]

    });

});

</script>

@endsection