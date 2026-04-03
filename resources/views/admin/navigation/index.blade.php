<div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
</div>
@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">Manage Navigation Menu</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="card-title">Add / Edit Menu</h5>

                <form id="navForm">
                    @csrf

                    <input type="hidden" id="nav_id">

                    <div class="mb-3">
                        <label>Menu Name</label>
                        <input type="text" name="name" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Order</label>
                        <input type="number" name="order" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control modern-input">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        Save Menu
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <div class="table-header">
                    <h5>Menu List</h5>
                </div>

                <div class="table-responsive">

                    <table id="navTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>
$(document).ready(function(){

    let table = $('#navTable').DataTable({
        ajax: {
            url: "{{ route('admin.navigation.list') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'url' },
            { data: 'order' },
            {
                data: 'status',
                render: function(data){
                    return data ? 'Active' : 'Inactive';
                }
            },
            {
                data: null,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-edit editBtn"
                            data-id="${row.id}"
                            data-name="${row.name}"
                            data-url="${row.url}"
                            data-order="${row.order}"
                            data-status="${row.status}">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-delete deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    /* EDIT */
    $(document).on('click','.editBtn',function(){

        $('#nav_id').val($(this).data('id'));
        $('input[name="name"]').val($(this).data('name'));
        $('input[name="url"]').val($(this).data('url'));
        $('input[name="order"]').val($(this).data('order'));
        $('select[name="status"]').val($(this).data('status'));

        Swal.fire('Edit Mode','','info');
    });

    /* SAVE */
    $('#navForm').submit(function(e){
        e.preventDefault();

        let id = $('#nav_id').val();

        let url = id
            ? "{{ route('admin.navigation.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.navigation.store') }}";

        $.ajax({
            url: url,
            method: "POST",
            data: $(this).serialize(),

            success: function(){
                Swal.fire('Success','Saved','success');
                $('#navForm')[0].reset();
                $('#nav_id').val('');
                table.ajax.reload();
            },

            error: function(err){
                Swal.fire('Error','Validation failed','error');
            }
        });
    });

    /* DELETE */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete?',
            icon:'warning',
            showCancelButton:true
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.navigation.delete', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    },
                    success: function(){
                        Swal.fire('Deleted','','success');
                        table.ajax.reload();
                    }
                });

            }

        });

    });

});
</script>

@endsection