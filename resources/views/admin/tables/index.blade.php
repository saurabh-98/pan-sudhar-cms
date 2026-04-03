@extends('layout.admin')

@section('content')

<style>
.modern-card {
    background:#fff;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

.action-btn {
    border:none;
    border-radius:8px;
    padding:6px 10px;
    margin:2px;
    transition:0.2s;
}

.btn-edit { background:#0d6efd; color:#fff; }
.btn-delete { background:#dc3545; color:#fff; }
.btn-toggle { background:#ffc107; color:#000; }

.action-btn:hover {
    transform:scale(1.1);
}
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">🪑 Table Management</h3>

        <button class="btn btn-success" id="addBtn">
            ➕ Add Table
        </button>
    </div>

    <div class="modern-card p-4">

        <table id="tableTable" class="table w-100 text-center">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Capacity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>

</div>


<!-- 🔥 MODAL -->
<div class="modal fade" id="tableModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <h5 id="modalTitle">Add Table</h5>

            <form id="tableForm">

                <input type="hidden" id="table_id">

                <input type="text" id="name" class="form-control mb-2" placeholder="Table Name" required>

                <input type="number" id="capacity" class="form-control mb-2" placeholder="Capacity" required>

                <button class="btn btn-success w-100">Save</button>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let table = $('#tableTable').DataTable({

    ajax: "{{ route('admin.tables.list') }}",

    columns: [
        { data: 'id' },
        { data: 'name' },
        { data: 'capacity' },

        {
            data: 'is_active',
            render: s => s
                ? `<span class="badge bg-success">Active</span>`
                : `<span class="badge bg-danger">Inactive</span>`
        },

        {
            data: null,
            render: function(row){

                return `
                    <button class="action-btn btn-edit editBtn"
                        data-id="${row.id}"
                        data-name="${row.name}"
                        data-capacity="${row.capacity}">
                        ✏️
                    </button>

                    <button class="action-btn btn-delete deleteBtn"
                        data-id="${row.id}">
                        🗑
                    </button>

                    <button class="action-btn btn-toggle toggleBtn"
                        data-id="${row.id}">
                        🔄
                    </button>
                `;
            }
        }
    ]

});


/* =========================
   ADD
========================= */
$('#addBtn').click(function(){
    $('#tableForm')[0].reset();
    $('#table_id').val('');
    $('#modalTitle').text('Add Table');
    $('#tableModal').modal('show');
});


/* =========================
   EDIT
========================= */
$(document).on('click','.editBtn',function(){

    $('#modalTitle').text('Edit Table');

    $('#table_id').val($(this).data('id'));
    $('#name').val($(this).data('name'));
    $('#capacity').val($(this).data('capacity'));

    $('#tableModal').modal('show');

});


/* =========================
   SAVE (ADD + UPDATE)
========================= */
$('#tableForm').submit(function(e){

    e.preventDefault();

    let id = $('#table_id').val();

    let url = id
        ? `/admin/tables/update/${id}`
        : `/admin/tables/store`;

    $.post(url,{
        _token:"{{ csrf_token() }}",
        name:$('#name').val(),
        capacity:$('#capacity').val()
    },function(){

        $('#tableModal').modal('hide');

        Swal.fire('Saved!','','success');

        table.ajax.reload();

    });

});


/* =========================
   DELETE
========================= */
$(document).on('click','.deleteBtn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete table?',
        icon:'warning',
        showCancelButton:true
    }).then(res=>{

        if(res.isConfirmed){

            $.post(`/admin/tables/delete/${id}`,{
                _token:"{{ csrf_token() }}"
            },function(){

                Swal.fire('Deleted','','success');
                table.ajax.reload();

            });

        }

    });

});


/* =========================
   TOGGLE STATUS
========================= */
$(document).on('click','.toggleBtn',function(){

    let id = $(this).data('id');

    $.post(`/admin/tables/toggle/${id}`,{
        _token:"{{ csrf_token() }}"
    },function(){

        Swal.fire({
            toast:true,
            position:'top-end',
            icon:'success',
            title:'Status Updated',
            timer:1500,
            showConfirmButton:false
        });

        table.ajax.reload();

    });

});

</script>

@endsection