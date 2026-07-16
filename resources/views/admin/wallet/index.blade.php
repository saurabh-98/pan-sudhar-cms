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
                            role="tab"
                            aria-controls="retailer"
                            aria-selected="true">
                        Retailer Recharge
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                            id="executive-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#executive"
                            type="button"
                            role="tab"
                            aria-controls="executive"
                            aria-selected="false">
                        Executive Recharge
                    </button>
                </li>
            </ul>

            {{-- ===================== TAB CONTENT ===================== --}}
            <div class="tab-content pt-3" id="walletTabContent">

                {{-- ---------- RETAILER TAB ---------- --}}
                <div class="tab-pane fade show active"
                     id="retailer"
                     role="tabpanel"
                     aria-labelledby="retailer-tab">

                    <table class="table table-bordered table-striped w-100" id="retailerTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Wallet Balance</th>
                                <th style="min-width:320px;">Recharge / Pull Back</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>

                {{-- ---------- EXECUTIVE TAB ---------- --}}
                <div class="tab-pane fade"
                     id="executive"
                     role="tabpanel"
                     aria-labelledby="executive-tab">

                    <table class="table table-bordered table-striped w-100" id="executiveTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Wallet Balance</th>
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
    | INIT DATATABLES
    |--------------------------------------------------------------------------
    */

    const retailerTable = $('#retailerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.wallet.retailers.list') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'wallet_balance', name: 'wallet_balance' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    const executiveTable = $('#executiveTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.wallet.executives.list') }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'wallet_balance', name: 'wallet_balance' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Fix DataTables width glitch when initialized inside a hidden Bootstrap tab
    $('#executive-tab').on('shown.bs.tab', function () {
        executiveTable.columns.adjust();
    });

    /*
    |--------------------------------------------------------------------------
    | RECHARGE / DEDUCT — EVENT DELEGATION
    |--------------------------------------------------------------------------
    | Buttons are rendered dynamically by DataTables, so we bind on a static
    | parent (document) rather than the buttons themselves.
    */

    $(document).on('click', '.btn-recharge', function () {

        const $btn      = $(this);
        const id        = $btn.data('id');
        const name      = $btn.data('name');
        const action    = $btn.data('action'); // 'add' or 'deduct'
        const url       = $btn.data('url');
        const balance   = parseFloat($btn.data('balance'));
        const $row      = $btn.closest('tr');
        const $input    = $row.find('.amount-input');
        const amount    = parseFloat($input.val());

        if (!amount || amount <= 0) {
            Swal.fire('Invalid Amount', 'Please enter a valid amount greater than 0.', 'warning');
            return;
        }

        if (action === 'deduct' && amount > balance) {
            Swal.fire(
                'Amount Too High',
                'You cannot pull back more than the current balance (₹' + balance.toFixed(2) + ').',
                'error'
            );
            return;
        }

        const isDeduct   = action === 'deduct';
        const actionText = isDeduct ? 'pull back' : 'add';

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to <b>${actionText}</b> <b>₹${amount.toFixed(2)}</b> ${isDeduct ? 'from' : 'to'} <b>${name}</b>'s wallet.`,
            icon: isDeduct ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: `Yes, ${isDeduct ? 'Pull Back' : 'Add'}`,
            cancelButtonText: 'Cancel',
            confirmButtonColor: isDeduct ? '#dc3545' : '#0d6efd',
        }).then(function (result) {

            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Processing...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    amount: amount,
                    _token: CSRF_TOKEN
                },
                dataType: 'json'
            })
            .done(function (data) {

                if (!data.success) {
                    Swal.fire('Failed', data.message || 'Something went wrong.', 'error');
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    timer: 1800,
                    showConfirmButton: false
                });

                // Reload the correct table without resetting page/search state
                if ($row.closest('table').attr('id') === 'retailerTable') {
                    retailerTable.ajax.reload(null, false);
                } else {
                    executiveTable.ajax.reload(null, false);
                }
            })
            .fail(function (xhr) {
                const msg = xhr.responseJSON?.message || 'Something went wrong.';
                Swal.fire('Failed', msg, 'error');
            });
        });
    });

});
</script>
@endsection