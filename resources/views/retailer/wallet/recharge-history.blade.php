@extends('layout.retailer')

@section('title', 'Recharge History')

@section('styles')

<link rel="stylesheet" href="{{ asset('assets/retailer/css/recharge-history.css') }}">

@endsection

@section('content')

<div class="container-fluid">

    <div class="card recharge-history-card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="recharge-history-title">

                <i class="fas fa-history"></i>

                Recharge History

            </h4>

            <a href="{{ route('retailer.wallet.recharge') }}"
               class="btn recharge-btn">

                <i class="fas fa-plus-circle me-2"></i>

                Recharge Wallet

            </a>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success alert-dismissible fade show">

                    <i class="fas fa-check-circle me-2"></i>

                    {{ session('success') }}

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

            @endif

            <div class="table-responsive">

                <table class="table"
                       id="rechargeTable">

                    <thead>

                        <tr>

                            <th width="5%">

                                #

                            </th>

                            <th width="20%">

                                Amount

                            </th>

                            <th width="20%">

                                Transaction ID (UTR)

                            </th>

                            <th width="15%">

                                Status

                            </th>

                            <th width="25%">

                                Submitted Date

                            </th>

                            <th width="15%">

                                Action

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

$(function(){

    $('#rechargeTable').DataTable({

        processing:true,

        serverSide:true,

        responsive:true,

        autoWidth:false,

        ajax:"{{ route('retailer.wallet.recharge-history') }}",

        columns:[

            {
                data:'DT_RowIndex',
                name:'DT_RowIndex',
                orderable:false,
                searchable:false
            },

            {
                data:'amount',
                name:'amount'
            },

            {
                data:'utr',
                name:'utr'
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

        language:{

            search:"",

            searchPlaceholder:"Search Recharge History...",

            processing:'<i class="fa fa-spinner fa-spin"></i> Loading...',

            emptyTable:'No Recharge History Found.'

        },

        pageLength:10,

        order:[[4,'desc']]

    });

});

</script>

@endsection