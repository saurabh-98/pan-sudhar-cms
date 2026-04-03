@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header mb-3">
        <h3>📂 Manage Categories</h3>
        <p>Create, update and manage categories easily</p>
    </div>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="mb-3">➕ Add / Edit Category</h5>

                <form id="categoryForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="cat_id">

                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" name="name" class="form-control modern-input" placeholder="Enter category name">
                    </div>

                    <div class="mb-3">
                        <label>Upload Image</label>
                        <input type="file" name="image" id="imageInput" class="form-control modern-input">

                        <div class="preview-box mt-2">
                            <img id="previewImg">
                        </div>
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        💾 Save Category
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <h5 class="mb-3">📋 Category List</h5>

                <div class="table-responsive">
                    <table id="categoryTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th width="150">Action</th>
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

    /* IMAGE PREVIEW */
    $('#imageInput').on('change', function(e){
        let reader = new FileReader();
        reader.onload = function(){
            $('#previewImg').attr('src', reader.result);
            $('.preview-box').show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    /* DATATABLE */
    let table = $('#categoryTable').DataTable({
        ajax: {
            url: "{{ route('admin.categories.list') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            {
                data: 'image',
                render: function(data){
                    return `<img src="/storage/${data}" class="table-img">`;
                }
            },
            {
                data: null,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-edit editBtn"
                            data-id="${row.id}"
                            data-name="${row.name}">
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
        $('#cat_id').val($(this).data('id'));
        $('input[name="name"]').val($(this).data('name'));

        Swal.fire('Edit Mode','Update and save','info');
    });

    /* SAVE */
    $('#categoryForm').submit(function(e){
        e.preventDefault();

        let btn = $('#saveBtn');
        btn.prop('disabled', true).text('Saving...');

        let id = $('#cat_id').val();

        let url = id 
            ? "{{ route('admin.categories.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.categories.store') }}";

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(){
                Swal.fire('Success','Saved successfully','success');

                $('#categoryForm')[0].reset();
                $('.preview-box').hide();
                $('#cat_id').val('');

                table.ajax.reload();
                btn.prop('disabled', false).text('Save Category');
            },

            error: function(err){
                let errors = err.responseJSON.errors;
                let msg = '';

                if(errors){
                    $.each(errors,function(k,v){
                        msg += v[0] + '<br>';
                    });
                }

                Swal.fire('Error', msg, 'error');
                btn.prop('disabled', false).text('Save Category');
            }
        });
    });

    /* DELETE */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this category?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff5722'
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.categories.delete', ':id') }}".replace(':id', id),
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