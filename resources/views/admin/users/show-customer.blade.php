@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">👤 Customer Profile</h3>

        <!-- 🔥 COMMON BACK BUTTON -->
        <x-back-button fallback="admin.users.customers" />
    </div>

    <!-- CUSTOMER INFO -->
    <div class="modern-card p-4 mb-4">

        <div class="row">

            <div class="col-md-6">
                <h4 class="fw-bold">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>

                <p>
                    Status:
                    <span class="badge {{ ($user->status ?? 'active') == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </p>

                <p>Joined: {{ $user->created_at->format('d M Y') }}</p>
            </div>

            <div class="col-md-6 text-end">
                <h6>Total Orders</h6>
                <h4 class="badge bg-info p-2">{{ $totalOrders }}</h4>

                <h6 class="mt-3">Total Spent</h6>
                <h4 class="text-success fw-bold">
                    ₹{{ number_format($totalSpent,2) }}
                </h4>
            </div>

        </div>

    </div>

    <!-- ANALYTICS -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="modern-card p-3 text-center">
                <h6>Average Order</h6>
                <h4 class="text-primary">
                    ₹{{ $totalOrders ? number_format($totalSpent / $totalOrders,2) : 0 }}
                </h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card p-3 text-center">
                <h6>Customer Type</h6>
                <h5 class="text-success">
                    {{ $totalSpent > 1000 ? 'Premium' : 'Regular' }}
                </h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="modern-card p-3 text-center">
                <h6>Loyalty</h6>
                <h5 class="text-warning">
                    {{ $totalOrders > 5 ? 'Loyal' : 'New' }}
                </h5>
            </div>
        </div>

    </div>

    <!-- ORDER TABLE -->
    <div class="modern-card p-4">

        <div class="d-flex justify-content-between mb-3">
            <h5>🛒 Order History</h5>

            <select id="statusFilter" class="form-control w-25">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>

        <div class="table-responsive">

            <table id="ordersTable" class="modern-table w-100 text-center">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Invoice</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>
$(document).ready(function(){

    let table = $('#ordersTable').DataTable({
        processing: true,
        ajax: "{{ route('admin.users.customer.orders', $user->id) }}",

        columns: [
            {
                className: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '➕'
            },
            {
                data: null,
                render: (data, type, row, meta) => meta.row + 1
            },
            {
                data: 'id',
                render: data => `<strong>#${data}</strong>`
            },
            {
                data: 'total',
                render: data => `<span class="text-success fw-bold">${data}</span>`
            },
            {
                data: 'status',
                render: data => getStatusTimeline(data)
            },
            {
                data: 'payment',
                render: data => {
                    let cls = data === 'paid' ? 'bg-success' : 'bg-warning';
                    return `<span class="badge ${cls}">${data}</span>`;
                }
            },
            { data: 'date' },
            { data: 'actions', orderable: false }
        ]
    });

    $('#statusFilter').on('change', function(){
        table.column(4).search(this.value).draw();
    });

    $('#ordersTable tbody').on('click', 'td.details-control', function () {

        let tr = $(this).closest('tr');
        let row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {

            let orderId = row.data().id;

            $.get("/admin/orders/items/" + orderId, function(res){

                let html = `<table class="table table-sm">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>`;

                res.data.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.name}</td>
                            <td>${item.quantity}</td>
                            <td>₹${item.price}</td>
                        </tr>
                    `;
                });

                html += `</table>`;

                row.child(html).show();
                tr.addClass('shown');
            });
        }

    });

});

function getStatusTimeline(status){

    let steps = ['pending','preparing','delivered'];

    let html = '<div style="display:flex;gap:5px">';

    steps.forEach(step => {

        let active = steps.indexOf(step) <= steps.indexOf(status);

        html += `<span class="badge ${active ? 'bg-success':'bg-secondary'}">
                    ${step}
                 </span>`;
    });

    html += '</div>';

    return html;
}
</script>

@endsection