@extends('layout.retailer')

@section('content')

<div class="container-fluid wallet-history-page">

    {{-- =====================================================
    | PAGE HEADER
    ====================================================== --}}
    <div class="wallet-history-header">

        <div>

            <h2 class="wallet-history-title">

                Wallet Transaction History

            </h2>

            <p class="wallet-history-subtitle">

                View all wallet credit and debit transactions.

            </p>

        </div>

        <div class="wallet-balance-card">

            <div class="wallet-balance-icon">

                <i class="fas fa-wallet"></i>

            </div>

            <div>

                <span class="wallet-balance-label">

                    Available Balance

                </span>

                <h4 class="wallet-balance-amount">

                    ₹{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}

                </h4>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | CARD
    ====================================================== --}}
    <div class="card wallet-history-card">

        <div class="card-body">

            {{-- =====================================================
            | TABLE
            ====================================================== --}}
            <div class="table-responsive wallet-history-table-wrapper">

                <table
                    id="walletHistoryTable"
                    class="table wallet-history-table align-middle w-100"
                >

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Transaction ID</th>

                            <th>Amount</th>

                            <th>Type</th>

                            <th>Remark</th>

                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody></tbody>

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

    $('#walletHistoryTable').DataTable({

        processing: true,

        serverSide: true,

        responsive: true,

        autoWidth: false,

        scrollX: true,

        pageLength: 10,

        lengthMenu: [

            [10, 25, 50, 100],

            [10, 25, 50, 100]

        ],

        ajax: {

            url: "{{ route('retailer.wallet.history') }}",

            type: "GET",

            error: function(xhr){

                console.log(xhr.responseText);
            }
        },

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },

            {
                data: 'transaction_id',
                name: 'id'
            },

            {
                data: 'amount',
                name: 'amount'
            },

            {
                data: 'type',
                name: 'type'
            },

            {
                data: 'remark',
                name: 'remark'
            },

            {
                data: 'created_at',
                name: 'created_at'
            }

        ],

        language: {

            search: "",

            searchPlaceholder:
                "Search transactions...",

            lengthMenu:
                "Show _MENU_ entries",

            info:
                "Showing _START_ to _END_ of _TOTAL_ transactions",

            processing:
                "Loading transactions...",

            emptyTable:
                "No Wallet Transactions Found",

            zeroRecords:
                "No matching transactions found",

            paginate: {

                previous:
                    '<i class="fas fa-angle-left"></i>',

                next:
                    '<i class="fas fa-angle-right"></i>'
            }

        },

        drawCallback: function () {

            $('.dataTables_paginate > .pagination')
                .addClass('pagination-rounded');
        }

    });

});

</script>

@endsection