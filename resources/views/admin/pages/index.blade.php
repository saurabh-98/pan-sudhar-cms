@extends('layout.admin')

@section('content')

<style>
.modern-card {
    background:#fff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

/* BUTTONS */
.btn-edit {
    background:#0d6efd;
    color:#fff;
    border-radius:6px;
    padding:5px 10px;
}

.btn-delete {
    background:#dc3545;
    color:#fff;
    border-radius:6px;
    padding:5px 10px;
}

/* BADGE */
.badge-active {
    background:#28a745;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
}

.badge-inactive {
    background:#dc3545;
    color:#fff;
    padding:5px 10px;
    border-radius:20px;
}
</style>

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">📄 Manage Pages</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5>Add / Edit Page</h5>

                <form id="pageForm">
                    @csrf

                    <input type="hidden" id="page_id">

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" name="slug" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Content</label>
                        <textarea name="content" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- 🔥 NEW FIELD -->
                    <div class="mb-3">
                        <label>Show in Navbar</label>
                        <select name="show_in_navbar" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- 🔥 MENU ORDER -->
                    <div class="mb-3">
                        <label>Menu Position</label>
                        <input type="number" name="position" class="form-control" value="0">
                    </div>

                    <button class="btn btn-success w-100">
                        💾 Save Page
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <h5>📋 Page List</h5>

                <table id="pageTable" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Navbar</th>
                            <th>Order</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    /* AUTO SLUG */
    $('input[name="title"]').on('keyup', function(){
        let slug = $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'');

        $('input[name="slug"]').val(slug);
    });

    /* DATATABLE */
    let table = $('#pageTable').DataTable({
        ajax: {
            url: "{{ route('admin.pages.list') }}",
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'slug' },

            {
                data: 'status',
                render: d => d
                    ? '<span class="badge-active">Active</span>'
                    : '<span class="badge-inactive">Inactive</span>'
            },

            {
                data: 'show_in_navbar',
                render: d => d
                    ? '✅ Yes'
                    : '❌ No'
            },

            { data: 'position' },

            {
                data: null,
                render: function(row){
                    return `
                        <button class="btn-edit editBtn"
                            data-id="${row.id}"
                            data-title="${row.title}"
                            data-slug="${row.slug}"
                            data-content="${row.content}"
                            data-status="${row.status}"
                            data-navbar="${row.show_in_navbar}"
                            data-position="${row.position}">
                            ✏️
                        </button>

                        <button class="btn-delete deleteBtn"
                            data-id="${row.id}">
                            🗑
                        </button>
                    `;
                }
            }
        ]
    });

    /* EDIT */
    $(document).on('click','.editBtn',function(){

        $('#page_id').val($(this).data('id'));
        $('input[name="title"]').val($(this).data('title'));
        $('input[name="slug"]').val($(this).data('slug'));
        $('textarea[name="content"]').val($(this).data('content'));
        $('select[name="status"]').val($(this).data('status'));
        $('select[name="show_in_navbar"]').val($(this).data('navbar'));
        $('input[name="position"]').val($(this).data('position'));

        Swal.fire('Edit Mode','','info');
    });

    /* SAVE */
    $('#pageForm').submit(function(e){
        e.preventDefault();

        let id = $('#page_id').val();

        let url = id
            ? "{{ route('admin.pages.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.pages.store') }}";

        $.ajax({
            url: url,
            method: "POST",
            data: $(this).serialize(),

            success: function(res){
                Swal.fire('Saved','','success');
                $('#pageForm')[0].reset();
                $('#page_id').val('');
                table.ajax.reload();
            },

            error: function(err){
                Swal.fire('Error','Check fields','error');
            }
        });
    });

    /* DELETE */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this page?',
            icon:'warning',
            showCancelButton:true
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.pages.delete', ':id') }}".replace(':id', id),
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