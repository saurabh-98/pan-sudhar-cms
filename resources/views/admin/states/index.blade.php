@extends('layout.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">

    <div class="row">

        <!-- ================= ADD STATE ================= -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Add State</h5>
                </div>

                <div class="card-body">
                    <form id="stateForm">
                        @csrf

                        <div class="mb-3">
                            <label>State Name</label>
                            <input type="text" name="name" id="state_name" class="form-control">
                            <small class="text-danger" id="err_name"></small>
                        </div>

                        <button class="btn btn-primary w-100" id="saveBtn">
                            Save State
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📍 State List</h5>
                </div>

                <div class="card-body">

                    <table class="table table-bordered" id="stateTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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


<!-- ================= EDIT MODAL ================= -->
<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit State</h5>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit_id">

                <label>State Name</label>
                <input type="text" id="edit_name" class="form-control">
            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="updateBtn">Update</button>
            </div>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<!-- jQuery + DataTable -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let table;

/* ================= LOAD TABLE ================= */
$(document).ready(function(){

    table = $('#stateTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('admin.states.list') }}",
        columns: [
            { data: 'id' },
            { data: 'name' },
            {
                data: 'status',
                render: function(data){
                    return data == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            },
            {
                data: null,
                render: function(data){
                    return `
                        <button class="btn btn-warning btn-sm editBtn"
                            data-id="${data.id}"
                            data-name="${data.name}">
                            Edit
                        </button>

                        <button class="btn btn-danger btn-sm deleteBtn"
                            data-id="${data.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

});


/* ================= ADD ================= */
$('#stateForm').submit(function(e){
    e.preventDefault();

    let formData = $(this).serialize();

    $.post("{{ route('admin.states.store') }}", formData, function(res){

        Swal.fire("Success", "State added", "success");

        $('#stateForm')[0].reset();
        table.ajax.reload();

    }).fail(function(err){

        $('#err_name').text(err.responseJSON.errors.name[0]);

    });

});


/* ================= DELETE ================= */
$(document).on('click', '.deleteBtn', function(){

    let id = $(this).data('id');

    Swal.fire({
        title: "Delete?",
        icon: "warning",
        showCancelButton: true
    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({
                url: "/admin/states/"+id,
                type: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function(){
                    Swal.fire("Deleted!", "", "success");
                    table.ajax.reload();
                }
            });

        }
    });

});


/* ================= EDIT OPEN ================= */
$(document).on('click', '.editBtn', function(){

    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));

    $('#editModal').modal('show');
});


/* ================= UPDATE ================= */
$('#updateBtn').click(function(){

    let id = $('#edit_id').val();

    $.ajax({
        url: "/admin/states/"+id,
        type: "PUT",
        data: {
            _token: "{{ csrf_token() }}",
            name: $('#edit_name').val()
        },
        success: function(){

            Swal.fire("Updated!", "", "success");
            $('#editModal').modal('hide');
            table.ajax.reload();

        }
    });

});

</script>

@endsection