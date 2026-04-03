@extends('layout.admin')

@section('content')

<style>
body { background:#f4f6f9; }

.modern-card {
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.05);
}

.modern-table thead th {
    background: linear-gradient(90deg,#ff6b3d,#ff8c42);
    color:#fff;
    padding:12px;
}

.modern-table tbody tr:hover {
    background:#eef5ff;
    transform:scale(1.01);
}

.final-amount { color:#27ae60; font-weight:bold; }

/* STATUS */
.status-wrapper {
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
}

.badge-status {
    padding:5px 14px;
    border-radius:20px;
    font-size:11px;
    color:#fff;
    min-width:90px;
}

.pending{background:#f39c12;}
.preparing{background:#3498db;}
.delivered{background:#2ecc71;}

.payment-paid{background:#2ecc71;}
.payment-pending{background:#e74c3c;}
.payment-failed{background:#7f8c8d;}

.statusChange,.paymentChange {
    border-radius:6px;
    padding:4px 8px;
    border:1px solid #ddd;
}

/* BUTTONS */
.action-btn {
    padding:6px 10px;
    border-radius:8px;
    border:none;
    cursor:pointer;
}

.btn-view{background:#17a2b8;color:#fff;}
.btn-eye{background:#007bff;color:#fff;}
.btn-pdf{background:#28a745;color:#fff;}
</style>

<div class="container-fluid">

<h3 class="mb-4 fw-bold">📦 Manage Orders</h3>

<div class="modern-card">

<table id="orderTable" class="modern-table w-100 text-center">
<thead>
<tr>
<th>SR No</th>
<th>Name</th>
<th>Date</th>
<th>Total</th>
<th>Discount</th>
<th>Final</th>
<th>Status</th>
<th>Payment</th>
<th>Action</th>
</tr>
</thead>
</table>

</div>
</div>

<!-- 🔥 RESPONSIVE MODAL -->
<div class="modal fade" id="orderModal">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">

            <div class="modal-header">
                <h5>📦 Order Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderDetailsBody">
                Loading...
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
$(function(){

let listRoute     = "{{ route('admin.orders.list') }}";
let statusRoute   = "{{ route('admin.orders.status', ['id' => ':id']) }}";
let showRoute     = "{{ route('admin.orders.show', ['id' => ':id']) }}"; // ✅ FIXED
let paymentRoute  = "{{ route('admin.orders.payment', ['id' => ':id']) }}";
let invoiceRoute  = "{{ route('admin.orders.invoice', ['id' => ':id']) }}";
let downloadRoute = "{{ route('admin.orders.invoice.download', ['id' => ':id']) }}";

let table = $('#orderTable').DataTable({
    ajax: listRoute,
    order: [],

    columns: [

        {
            data:null,
            orderable:false,
            render:(d,t,r,m)=> m.row + m.settings._iDisplayStart + 1
        },

        { data:'user.name', render:n=>`<b>${n}</b>` },

        {
            data:'date',
           
        },

        { data:'total', render:d=>`₹${d}` },

        { data:'discount', render:d=>`<span style="color:red">₹${d}</span>` },

        { data:'final_total', render:d=>`<span class="final-amount">₹${d}</span>` },

        {
            data:'status',
            render:(s,t,r)=>`
                <div class="status-wrapper">
                    <span class="badge-status ${s}">${s}</span>
                    <select class="statusChange" data-id="${r.id}">
                        <option value="pending" ${s=='pending'?'selected':''}>Pending</option>
                        <option value="preparing" ${s=='preparing'?'selected':''}>Preparing</option>
                        <option value="delivered" ${s=='delivered'?'selected':''}>Delivered</option>
                    </select>
                </div>
            `
        },

        {
            data:'payment_status',
            render:(p,t,r)=>`
                <div class="status-wrapper">
                    <span class="badge-status payment-${p}">${p}</span>
                    <select class="paymentChange" data-id="${r.id}">
                        <option value="pending" ${p=='pending'?'selected':''}>Pending</option>
                        <option value="paid" ${p=='paid'?'selected':''}>Paid</option>
                        <option value="failed" ${p=='failed'?'selected':''}>Failed</option>
                    </select>
                </div>
            `
        },

        {
            data:'id',
            orderable:false,
            render:id=>`
                <button class="action-btn btn-view" data-id="${id}">🔍</button>
                <a href="${invoiceRoute.replace(':id',id)}" target="_blank" class="action-btn btn-eye">👁</a>
                <a href="${downloadRoute.replace(':id',id)}" class="action-btn btn-pdf">📄</a>
            `
        }
    ]
});


// 🔥 VIEW BUTTON USING SHOW ROUTE
 /* 🔥 MODAL */
  // ✅ FIXED VIEW BUTTON
    $(document).on('click','.btn-view',function(){

    let id = $(this).data('id');

    // ✅ Bootstrap 5 safe modal open
    let modal = new bootstrap.Modal(document.getElementById('orderModal'));
    modal.show();

    $('#orderDetailsBody').html('<div class="text-center p-4">Loading...</div>');

    $.get(showRoute.replace(':id', id), function(order){

        let total = 0;

        // 🔥 BUILD ITEMS UI (CARD STYLE)
        let itemsHtml = '';

        if(order.items && order.items.length > 0){

            order.items.forEach(item => {

                let name = item?.menu?.name 
                    ? item.menu.name 
                    : '<span class="badge bg-danger">Deleted</span>';

                let itemTotal = item.quantity * item.price;
                total += itemTotal;

                itemsHtml += `
                    <div style="display:flex;justify-content:space-between;
                                border-bottom:1px dashed #ddd;padding:10px 0;">
                        
                        <div>
                            <b>${name}</b><br>
                            <small>Qty: ${item.quantity} × ₹${item.price}</small>
                        </div>

                        <div><b>₹${itemTotal}</b></div>
                    </div>
                `;
            });

        } else {
            itemsHtml = `<div class="text-center">No items found</div>`;
        }

        total = total.toFixed(2);
        let discount = parseFloat(order.discount || 0).toFixed(2);
        let finalTotal = parseFloat(order.final_total || 0).toFixed(2);

        // ✅ DATE FORMAT
        let date = new Date(order.created_at);
        let formattedDate = date.toLocaleString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // ✅ COLORS
        let statusColor = {
            pending: 'secondary',
            preparing: 'warning',
            delivered: 'success'
        }[order.status] || 'secondary';

        let paymentColor = {
            pending: 'warning',
            paid: 'success',
            failed: 'danger'
        }[order.payment_status] || 'secondary';

        // 🔥 PREMIUM UI
        let html = `
        <div style="font-family:system-ui;">

            <!-- 🔥 CUSTOMER CARD -->
            <div style="background:#f1f3f6;padding:15px;border-radius:12px;margin-bottom:15px;">
                <div class="d-flex justify-content-between flex-wrap">

                    <div>
                        <b>👤 ${order.user?.name ?? '-'}</b><br>
                        <small>📱 ${order.mobile ?? '-'}</small><br>
                        <small>📦 ${order.order_type}</small><br>
                        <small>📍 ${
                            order.order_type === 'inside'
                            ? 'Table: ' + (order.table_number ?? '-')
                            : (order.address ?? '-')
                        }</small>
                    </div>

                    <div class="text-end">
                        <span class="badge bg-${statusColor}">${order.status}</span><br>
                        <span class="badge bg-${paymentColor} mt-1">${order.payment_status}</span><br>
                        <small class="d-block mt-1">📅 ${formattedDate}</small>
                    </div>

                </div>
            </div>

            <!-- 🔥 ITEMS -->
            <div style="background:#fff;border-radius:12px;padding:12px;margin-bottom:15px;
                        box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                <h6 class="mb-2">🛒 Items</h6>
                ${itemsHtml}
            </div>

            <!-- 🔥 TOTAL -->
            <div style="background:#fff3cd;padding:15px;border-radius:12px;">
                <div class="d-flex justify-content-between">
                    <span>Total</span>
                    <strong>₹${total}</strong>
                </div>

                <div class="d-flex justify-content-between text-danger">
                    <span>Discount</span>
                    <strong>- ₹${discount}</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <h5>Final Amount</h5>
                    <h5 class="text-success fw-bold">₹${finalTotal}</h5>
                </div>
            </div>

           

        </div>
        `;

        $('#orderDetailsBody').html(html);

    });

});

// STATUS UPDATE
$(document).on('change','.statusChange',function(){

    let id = $(this).data('id');
    let status = $(this).val();

    Swal.fire({
        title:'Change Status?',
        icon:'warning',
        showCancelButton:true
    }).then(res=>{
        if(res.isConfirmed){
            $.post(statusRoute.replace(':id',id),{
                status:status,
                _token:"{{ csrf_token() }}"
            },()=>{
                toastr.success("Status updated ✅");
                table.ajax.reload(null,false);
            });
        } else {
            table.ajax.reload(null,false);
        }
    });
});


// PAYMENT UPDATE
$(document).on('change','.paymentChange',function(){

    let id = $(this).data('id');
    let status = $(this).val();

    Swal.fire({
        title:'Change Payment?',
        icon:'question',
        showCancelButton:true
    }).then(res=>{
        if(res.isConfirmed){
            $.post(paymentRoute.replace(':id',id),{
                payment_status:status,
                _token:"{{ csrf_token() }}"
            },()=>{
                toastr.success("Payment updated 💰");
                table.ajax.reload(null,false);
            });
        } else {
            table.ajax.reload(null,false);
        }
    });
});

});
</script>

@endsection