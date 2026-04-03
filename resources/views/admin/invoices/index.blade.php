@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">📄 Invoice Management</h3>

    <div class="modern-card">

        <!-- 🔍 Optional Search Bar -->
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="customSearch" class="form-control w-25" placeholder="Search invoices...">
        </div>

        <div class="table-responsive">

            <table id="invoiceTable" class="modern-table w-100 text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <!-- ❌ REMOVE STATIC DATA -->
                <tbody></tbody>

            </table>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>
$(document).ready(function() {

    let table = $('#invoiceTable').DataTable({
        processing: true,
        ajax: "{{ route('admin.orders.invoice.list') }}",

        columns: [
            { data: 'id' },

            { 
                data: 'invoice_no',
                render: function(data){
                    return `<strong>${data}</strong>`;
                }
            },

            { data: 'customer' },

            { 
                data: 'total',
                render: function(data){
                    return `<span class="text-success fw-bold">₹${data}</span>`;
                }
            },

            { 
                data: 'payment_status',
                render: function(data){
                    let cls = data === 'paid' ? 'bg-success' : 'bg-warning';
                    return `<span class="badge ${cls}">${data}</span>`;
                }
            },

            { 
                data: 'status',
                render: function(data){
                    let cls = data === 'completed' ? 'bg-success' : 'bg-info';
                    return `<span class="badge ${cls}">${data}</span>`;
                }
            },

            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    /* 🔍 Custom Search */
    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

});
</script>

@endsection