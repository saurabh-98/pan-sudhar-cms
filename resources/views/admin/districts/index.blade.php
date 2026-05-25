@extends('layout.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">

    <div class="row">

        <!-- ================= ADD DISTRICT ================= -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">➕ Add District</h5>
                </div>

                <div class="card-body">
                    <form id="districtForm">
                        @csrf

                        <div class="mb-3">
                            <label>Select State</label>
                            <select name="state_id" id="state_id" class="form-select">
                                <option value="">Select State</option>
                                @foreach(\App\Models\State::where('status',1)->get() as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger" id="err_state"></small>
                        </div>

                        <div class="mb-3">
                            <label>District Name</label>
                            <input type="text" name="name" id="district_name" class="form-control">
                            <small class="text-danger" id="err_name"></small>
                        </div>

                        <button class="btn btn-primary w-100">
                            Save District
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📍 District List</h5>
                </div>

                <div class="card-body">

                    <table class="table table-bordered" id="districtTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>State</th>
                                <th>District</th>
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
                <h5>Edit District</h5>
            </div>

            <div class="modal-body">
                <input type="hidden" id="edit_id">

                <label>State</label>
                <select id="edit_state" class="form-select mb-2">
                    @foreach(\App\Models\State::where('status',1)->get() as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>

                <label>District Name</label>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let table;

/* ================= LOAD TABLE ================= */
$(document).ready(function(){

    table = $('#districtTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "{{ route('admin.districts.list') }}",
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'state_name' },
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
                            data-name="${data.name}"
                            data-state="${data.state_id}">
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
$('#districtForm').submit(function(e){
    e.preventDefault();

    $.post("{{ route('admin.districts.store') }}", $(this).serialize(), function(){

        Swal.fire("Success", "District added", "success");
        $('#districtForm')[0].reset();
        table.ajax.reload();

    }).fail(function(err){

        let errors = err.responseJSON.errors;

        if(errors.state_id) $('#err_state').text(errors.state_id[0]);
        if(errors.name) $('#err_name').text(errors.name[0]);

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
                url: "/admin/districts/"+id,
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

/* ================= EDIT ================= */
$(document).on('click', '.editBtn', function(){

    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_state').val($(this).data('state'));

    $('#editModal').modal('show');
});

/* ================= UPDATE ================= */
$('#updateBtn').click(function(){

    let id = $('#edit_id').val();

    $.ajax({
        url: "/admin/districts/"+id,
        type: "PUT",
        data: {
            _token: "{{ csrf_token() }}",
            name: $('#edit_name').val(),
            state_id: $('#edit_state').val()
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