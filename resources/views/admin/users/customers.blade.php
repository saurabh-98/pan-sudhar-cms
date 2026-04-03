@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">👥 Customer Management</h3>

    <div class="modern-card">

        <!-- 🔍 Search + Stats -->
        <div class="d-flex justify-content-between mb-3">

            <input type="text" id="customSearch" class="form-control w-25" placeholder="Search customers...">

            <div class="d-flex gap-3">
                <div class="badge bg-primary p-2">Total Customers: <span id="totalCustomers">0</span></div>
                <div class="badge bg-success p-2">Total Revenue: ₹<span id="totalRevenue">0</span></div>
            </div>

        </div>

        <div class="table-responsive">

            <table id="customerTable" class="modern-table w-100 text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Status</th>
                        <th>Actions</th>
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

    let totalCustomers = 0;
    let totalRevenue = 0;

    let table = $('#customerTable').DataTable({
        processing: true,
        ajax: {
            url: "{{ route('admin.users.customer.list') }}",
            dataSrc: function(json){

                // 🔥 CALCULATE STATS
                totalCustomers = json.data.length;

                totalRevenue = 0;
                json.data.forEach(row => {
                    let amount = row.spent.replace(/[₹,]/g,'');
                    totalRevenue += parseFloat(amount || 0);
                });

                $('#totalCustomers').text(totalCustomers);
                $('#totalRevenue').text(totalRevenue.toFixed(2));

                return json.data;
            }
        },

        columns: [
            { data: 'id' },

            {
                data: 'name',
                render: function(data){
                    return `<strong>${data}</strong>`;
                }
            },

            { data: 'email' },

            {
                data: 'orders',
                render: function(data){
                    return `<span class="badge bg-info">${data}</span>`;
                }
            },

            {
                data: 'spent',
                render: function(data){
                    return `<span class="text-success fw-bold">${data}</span>`;
                }
            },

            {
                data: 'status',
                render: function(data){
                    let cls = data === 'active' ? 'bg-success' : 'bg-danger';
                    return `<span class="badge ${cls}">${data}</span>`;
                }
            },

            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    /* 🔍 Custom Search */
    $('#customSearch').on('keyup', function(){
        table.search(this.value).draw();
    });


    /* 🗑 DELETE USER */
    $(document).on('click','.deleteUser',function(){

        let id = $(this).data('id');

        Swal.fire({
            title: 'Delete user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes delete'
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url: "/admin/users/delete/" + id,
                    method: "DELETE",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(){
                        table.ajax.reload();
                        Swal.fire('Deleted!', '', 'success');
                    }
                });

            }

        });

    });

});
</script>

@endsection