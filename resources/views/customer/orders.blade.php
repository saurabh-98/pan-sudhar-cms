@extends('layout.customer')

@section('content')

<style>

/* ===== HEADER ===== */
.orders-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.orders-header h3 {
    font-weight:bold;
}

/* ===== FILTER TABS ===== */
.filter-tabs {
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

.filter-btn {
    padding:6px 16px;
    border-radius:25px;
    border:none;
    background:#f1f1f1;
    cursor:pointer;
    transition:0.3s;
    font-weight:500;
}

.filter-btn.active,
.filter-btn:hover {
    background:#ff5a00;
    color:#fff;
    transform: scale(1.05);
}

/* ===== TABLE BOX ===== */
.table-box {
    background:#fff;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

/* ===== STATUS ===== */
.status-badge {
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    text-transform:capitalize;
}

.status-pending { background:#fff3cd; color:#856404; }
.status-delivered { background:#d4edda; color:#155724; }
.status-cancelled { background:#f8d7da; color:#721c24; }

/* ===== ORDER TYPE ===== */
.type-badge {
    padding:5px 10px;
    border-radius:15px;
    font-size:12px;
    font-weight:600;
}

.type-inside { background:#e3f2fd; color:#0d47a1; }
.type-outside { background:#fff3cd; color:#856404; }

/* ===== AMOUNT ===== */
.amount {
    color:#ff5a00;
    font-weight:600;
    font-size:15px;
}

/* ===== VIEW BUTTON ===== */
.btn-view {
    background:#111;
    color:#fff;
    border-radius:12px;
    padding:6px 14px;
    transition:0.3s;
    text-decoration:none;
}

.btn-view:hover {
    background:#ff5a00;
}

/* ===== HOVER EFFECT ===== */
#ordersTable tbody tr {
    transition:0.2s;
}

#ordersTable tbody tr:hover {
    transform: scale(1.01);
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align:center;
    padding:40px;
    color:#999;
    font-size:15px;
}

</style>

<div class="container mt-4">

    <div class="orders-header">
        <h3>🛒 My Orders</h3>
        <span class="text-muted">Track your recent orders 🍽️</span>
    </div>

    <!-- FILTER -->
    <div class="filter-tabs">
        <button class="filter-btn active" onclick="filterStatus('', this)">All</button>
        <button class="filter-btn" onclick="filterStatus('pending', this)">Pending</button>
        <button class="filter-btn" onclick="filterStatus('delivered', this)">Delivered</button>
        <button class="filter-btn" onclick="filterStatus('cancelled', this)">Cancelled</button>
    </div>

    <div class="table-box">

        <!-- STATS -->
        <div class="d-flex justify-content-between mb-3">
            <div><strong id="totalOrders">0</strong> Orders</div>
            <div class="text-muted">Live updates 🔄</div>
        </div>

        <table id="ordersTable" class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Mobile</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>

</div>

@endsection


@section('scripts')

<script>

let table;
window.currentFilter = '';

$(function () {

    table = $('#ordersTable').DataTable({

        ajax: {
            url: "{{ route('customer.orders.list') }}",
            data: function (d) {
                d.status = window.currentFilter;
            }
        },

        pageLength: 5,
        order: [[0, 'desc']],

        columns: [

            { data: 'id' },

            { data: 'id', render: id => `<strong>#ORD-${id}</strong>` },

            { data: 'mobile', defaultContent: '-' },

            {
                data: 'order_type',
                render: type => type === 'inside'
                    ? `<span class="type-badge type-inside">🍽 Dine-in</span>`
                    : `<span class="type-badge type-outside">🚚 Delivery</span>`
            },

            {
                data: 'final_total',
                render: amt => `<span class="amount">₹${amt}</span>`
            },

            {
                data: 'status',
                render: s => `<span class="status-badge status-${s}">${s}</span>`
            },

             {
                data: 'payment_status',
                render: s => `<span class="status-badge status-${s}">${s}</span>`
            },

            {
                data: 'created_at',
                render: d => {
                    let date = new Date(d);
                    return date.toLocaleDateString('en-IN') +
                        '<br><small>' + date.toLocaleTimeString('en-IN') + '</small>';
                }
            },

            {
                data: 'id',
                render: id => `<a href="/customer/orders/${id}" class="btn-view">👁 View</a>`
            }
        ],

        // 🔥 FIXED DRAW CALLBACK
        drawCallback: function(settings) {
            let total = settings.json?.data?.length || 0;
            $('#totalOrders').text(total);

            if(total === 0){
                $('#ordersTable tbody').html(`
                    <tr>
                        <td colspan="8" class="empty-state">
                            🚫 No orders found
                        </td>
                    </tr>
                `);
            }
        },

        // 🔥 LOADING UX
        processing: true,
        language: {
            processing: "⏳ Loading orders..."
        },

        // 🔥 HIGHLIGHT FIRST ROW
        rowCallback: function(row, data, index) {
            if(index === 0){
                $(row).css('background','#fff7f3');
            }
        }

    });

});


/* FILTER FUNCTION */
function filterStatus(status, btn) {

    window.currentFilter = status;

    $('.filter-btn').removeClass('active');
    $(btn).addClass('active');

    // 🔥 LOADING STATE
    $('#ordersTable tbody').html(`
        <tr>
            <td colspan="8" class="text-center p-3">
                ⏳ Filtering...
            </td>
        </tr>
    `);

    table.ajax.reload();
}

</script>

@endsection