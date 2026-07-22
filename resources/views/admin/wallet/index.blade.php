@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">
            <h4>Wallet Recharge Management</h4>
        </div>

        <div class="card-body">

            {{-- ===================== TAB NAV ===================== --}}
            <ul class="nav nav-tabs" id="walletTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                            id="retailer-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#retailer"
                            type="button"
                            role="tab">
                        Retailer Recharge
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="executive-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#executive"
                            type="button"
                            role="tab">
                        Executive Recharge
                    </button>
                </li>
            </ul>

            {{-- ===================== TAB CONTENT ===================== --}}
            <div class="tab-content pt-3">

                {{-- ================= RETAILER ================= --}}
                <div class="tab-pane fade show active" id="retailer">

                    <table class="table table-bordered table-striped w-100" id="retailerTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Wallet Balance</th>
                            <th>Due Amount</th>
                            <th style="min-width:320px;">Recharge / Pull Back</th>
                        </tr>
                        </thead>

                        <tbody></tbody>
                    </table>

                </div>

                {{-- ================= EXECUTIVE ================= --}}
                <div class="tab-pane fade" id="executive">

                    <table class="table table-bordered table-striped w-100" id="executiveTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Wallet Balance</th>
                            <th>Due Amount</th>
                            <th style="min-width:320px;">Recharge / Pull Back</th>
                        </tr>
                        </thead>

                        <tbody></tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>
$(function () {

    const CSRF_TOKEN = '{{ csrf_token() }}';

    /*
    |--------------------------------------------------------------------------
    | RETAILER DATATABLE
    |--------------------------------------------------------------------------
    */

    const retailerTable = $('#retailerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.wallet.retailers.list") }}',
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'id', name:'id' },
            { data: 'name', name:'name' },
            { data: 'email', name:'email' },
            { data: 'wallet_balance', name:'wallet_balance' },
            { data: 'wallet_due', name:'wallet_due', orderable:false, searchable:false },
            { data: 'action', orderable:false, searchable:false }
        ]
    });

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVE DATATABLE
    |--------------------------------------------------------------------------
    */

    const executiveTable = $('#executiveTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.wallet.executives.list") }}',
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'id', name:'id' },
            { data: 'name', name:'name' },
            { data: 'email', name:'email' },
            { data: 'wallet_balance', name:'wallet_balance' },
            { data: 'wallet_due', name:'wallet_due', orderable:false, searchable:false },
            { data: 'action', orderable:false, searchable:false }
        ]
    });

    /*
    |--------------------------------------------------------------------------
    | Fix DataTable Width
    |--------------------------------------------------------------------------
    */

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function () {
        $.fn.dataTable.tables({visible:true, api:true}).columns.adjust();
    });

    /*
    |--------------------------------------------------------------------------
    | Recharge / Deduct
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.btn-recharge', function () {

    let btn = $(this);

    let id      = btn.data('id');
    let name    = btn.data('name');
    let action  = btn.data('action'); // add / deduct
    let url     = btn.data('url');
    let balance = parseFloat(btn.data('balance'));

    let row    = btn.closest('tr');
    let input  = row.find('.amount-input');
    let amount = parseFloat(input.val());

    if (!amount || amount <= 0) {
        Swal.fire(
            'Invalid Amount',
            'Please enter a valid amount.',
            'warning'
        );
        return;
    }

    if (action === 'deduct' && amount > balance) {
        Swal.fire(
            'Amount Too High',
            'Cannot deduct more than wallet balance.',
            'error'
        );
        return;
    }

    // ===========================
    // PULL BACK
    // ===========================

    if (action === 'deduct') {

        Swal.fire({

            title: 'Confirm Pull Back',

            html: `
                <div class="text-start">
                    <p><strong>User :</strong> ${name}</p>
                    <p><strong>Amount :</strong> ₹${amount.toFixed(2)}</p>
                </div>
            `,

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText: 'Yes, Pull Back',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (!result.isConfirmed) return;

            processRecharge(url, amount, 'deduct');

        });

        return;
    }

    // ===========================
    // RECHARGE
    // ===========================

    Swal.fire({

        title: 'Wallet Recharge',

        html: `

            <div class="text-start">

                <p><strong>User :</strong> ${name}</p>

                <p><strong>Recharge Amount :</strong> ₹${amount.toFixed(2)}</p>

                <hr>

                <label class="mb-2">
                    <strong>Select Recharge Type</strong>
                </label>

                <div class="form-check mb-2">

                    <input
                        class="form-check-input"
                        type="radio"
                        name="recharge_type"
                        id="payment_received"
                        value="payment"
                        checked>

                    <label class="form-check-label" for="payment_received">
                        💰 Payment Received
                    </label>

                </div>

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="radio"
                        name="recharge_type"
                        id="credit"
                        value="credit">

                    <label class="form-check-label" for="credit">
                        📝 Credit / Pay Later
                    </label>

                </div>

                <div class="alert alert-warning mt-3 mb-0">
                    <small>
                        <strong>Payment Received</strong> →
                        Wallet balance will increase only.
                        <br><br>

                        <strong>Credit / Pay Later</strong> →
                        Wallet balance and Due Amount both increase.
                    </small>
                </div>

            </div>

        `,

        icon: 'question',

        width: 550,

        showCancelButton: true,

        confirmButtonText: 'Continue',

        cancelButtonText: 'Cancel',

        preConfirm: () => {

            return {
                recharge_type: document.querySelector(
                    'input[name="recharge_type"]:checked'
                ).value
            };

        }

    }).then((result) => {

        if (!result.isConfirmed) return;

        processRecharge(
            url,
            amount,
            result.value.recharge_type
        );

    });

});


/*
|--------------------------------------------------------------------------
| AJAX Function
|--------------------------------------------------------------------------
*/

function processRecharge(url, amount, rechargeType)
{

    Swal.fire({

        title:'Processing...',

        allowOutsideClick:false,

        didOpen:()=>{
            Swal.showLoading();
        }

    });

    $.ajax({

        url:url,

        type:'POST',

        data:{

            amount:amount,

            recharge_type:rechargeType,

            _token:CSRF_TOKEN

        },

        success:function(response){

            Swal.close();

            if(response.success){

                Swal.fire({

                    icon:'success',

                    title:'Success',

                    text:response.message

                });

                retailerTable.ajax.reload(null,false);

                executiveTable.ajax.reload(null,false);

            }else{

                Swal.fire(

                    'Error',

                    response.message,

                    'error'

                );

            }

        },

        error:function(xhr){

            Swal.close();

            Swal.fire(

                'Error',

                xhr.responseJSON?.message ?? 'Something went wrong.',

                'error'

            );

        }

    });

}
});
</script>

@endsection