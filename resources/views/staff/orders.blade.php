@extends('layout.staff')

@section('content')

<div class="container">

<h3 class="mb-3">📦 Order Management</h3>

<!-- SEARCH + FILTER -->
<div class="d-flex justify-content-between mb-3">
    <input type="text" id="searchOrder" class="form-control w-50" placeholder="🔍 Search order...">

    <select id="filterStatus" class="form-select w-auto">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="preparing">Preparing</option>
        <option value="ready">Ready</option>
        <option value="delivered">Delivered</option>
    </select>
</div>

<div class="card shadow">
<div class="card-body table-responsive">

<table class="table table-bordered table-hover align-middle text-center">

<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Customer</th>
    <th>Total</th>
    <th>Status</th>
    <th width="200">Action</th>
</tr>
</thead>

<tbody id="orderTableBody">
    <tr>
        <td colspan="5">Loading...</td>
    </tr>
</tbody>

</table>

</div>
</div>

</div>

<!-- MODAL -->
<div class="modal fade" id="orderModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5>🧾 Order Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderDetailsContent"></div>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script>
$(document).ready(function(){

    let listRoute   = "{{ route('staff.orders.list') }}";
    let statusRoute = "{{ route('staff.orders.status', ':id') }}";
    let showRoute   = "{{ route('staff.orders.show', ':id') }}";

    // ===============================
    // LOAD ORDERS (FIXED)
    // ===============================
    function loadOrders(){

        $('#orderTableBody').html('<tr><td colspan="5">Loading...</td></tr>');

        $.get(listRoute, {
            search: $('#searchOrder').val(),
            status: $('#filterStatus').val()
        })
        .done(function(res){

            let html = '';

            if(!res.data || res.data.length === 0){
                html = '<tr><td colspan="5">No orders found</td></tr>';
            } else {

                res.data.forEach(order => {

                    html += `
                    <tr id="row-${order.id}">
                        <td>${order.id}</td>
                        <td>${order.user?.name ?? 'Guest'}</td>
                        <td>₹${order.final_total}</td>

                        <td>
                            <select class="form-select statusDropdown">
                                <option value="pending" ${order.status=='pending'?'selected':''}>Pending</option>
                                <option value="preparing" ${order.status=='preparing'?'selected':''}>Preparing</option>
                                <option value="ready" ${order.status=='ready'?'selected':''}>Ready</option>
                                <option value="delivered" ${order.status=='delivered'?'selected':''}>Delivered</option>
                            </select>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-primary viewOrder" data-id="${order.id}">
                                👁
                            </button>

                            <button class="btn btn-sm btn-success updateStatus" data-id="${order.id}">
                                ✔
                            </button>
                        </td>
                    </tr>
                    `;
                });

            }

            $('#orderTableBody').html(html);

        })
        .fail(function(){
            $('#orderTableBody').html('<tr><td colspan="5">Error loading data</td></tr>');
        });
    }

    loadOrders();

    // SEARCH
    $('#searchOrder').keyup(loadOrders);
    $('#filterStatus').change(loadOrders);

    // ===============================
    // UPDATE STATUS
    // ===============================
    $(document).on('click','.updateStatus',function(){

        let id = $(this).data('id');
        let status = $('#row-' + id).find('.statusDropdown').val();

        Swal.fire({
            title: "Update Status?",
            showCancelButton: true
        }).then((res)=>{

            if(res.isConfirmed){

                $.post(statusRoute.replace(':id', id), {
                    _token: "{{ csrf_token() }}",
                    status: status
                })
                .done(()=>{
                    Swal.fire("Updated","","success");
                    loadOrders();
                });

            }

        });

    });

    // ===============================
    // VIEW ORDER DETAILS (FIXED 🔥)
    // ===============================
    $(document).on('click','.viewOrder',function(){

        let id = $(this).data('id');

        let modal = new bootstrap.Modal(document.getElementById('orderModal'));
        modal.show();

        $('#orderDetailsContent').html('Loading...');

        $.get(showRoute.replace(':id', id))
        .done(function(order){

            let html = `
                <h6>Order #${order.id}</h6>
                <p><b>Customer:</b> ${order.user?.name ?? 'Guest'}</p>
                <p><b>Status:</b> ${order.status}</p>
                <hr>

                <table class="table table-bordered text-center">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
            `;

            let total = 0;

            order.items.forEach(item => {

                let t = item.price * item.quantity;
                total += t;

                html += `
                    <tr>
                        <td>${item.product?.name ?? 'Item'}</td>
                        <td>${item.quantity}</td>
                        <td>₹${item.price}</td>
                        <td>₹${t}</td>
                    </tr>
                `;
            });

            html += `
                </table>

                <hr>
                <h5 class="text-end">Final: ₹${order.final_total}</h5>
            `;

            $('#orderDetailsContent').html(html);

        })
        .fail(()=>{
            $('#orderDetailsContent').html('<p class="text-danger">Failed to load</p>');
        });

    });

});
</script>

@endsection