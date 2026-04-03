@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">Manage News</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="card-title">Add / Edit News</h5>

                <form id="newsForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="news_id">

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control modern-input">
                    </div>

                    <!-- SLUG -->
                    <div class="mb-3">
                        <label>Slug (Auto)</label>
                        <input type="text" name="slug" id="slugField" class="form-control modern-input" readonly>
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="descriptionEditor" class="form-control modern-input"></textarea>
                    </div>

                    <!-- SEO -->
                    <div class="mb-3">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Meta Description</label>
                        <textarea name="meta_description" class="form-control modern-input"></textarea>
                    </div>

                    <!-- IMAGE -->
                    <div class="mb-3">
                        <label>Upload Image</label>
                        <input type="file" name="image" id="imageInput" class="form-control modern-input">

                        <div class="mt-2">
                            <img id="previewImg" style="display:none; width:80px; border-radius:10px;">
                        </div>
                    </div>

                    <!-- STATUS -->
                    <div class="mb-3">
                        <label>Status</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" id="statusToggle" class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        Save News
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <div class="table-header">
                    <h5>News List</h5>
                </div>

                <div class="table-responsive">

                    <table id="newsTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>News</th>
                                <th>Status</th>
                                <th>Image</th>
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

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>
$(document).ready(function(){

    let editor = CKEDITOR.replace('descriptionEditor');

    /* =========================
       AUTO SLUG
    ========================= */
    $('input[name="title"]').on('keyup', function(){
        let slug = $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'');

        $('#slugField').val(slug);
    });

    /* =========================
       IMAGE PREVIEW
    ========================= */
    $('#imageInput').on('change', function(e){
        let reader = new FileReader();
        reader.onload = function(){
            $('#previewImg').attr('src', reader.result).show();
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    /* =========================
       DATATABLE
    ========================= */
    let table = $('#newsTable').DataTable({
        ajax: {
            url: "{{ route('admin.news.index') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            {
                data: 'title',
                render: function(data, type, row){
                    return `
                        <strong>${data}</strong><br>
                        <small>${(row.description || '').substring(0,60)}...</small>
                    `;
                }
            },
            {
                data: 'is_active',
                render: function(data){
                    return data == 1 
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                }
            },
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
                            data-title="${row.title}"
                            data-description="${row.description}"
                            data-active="${row.is_active}">
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

    /* =========================
       EDIT
    ========================= */
    $(document).on('click','.editBtn',function(){

        let title = $(this).data('title');

        $('#news_id').val($(this).data('id'));
        $('input[name="title"]').val(title);

        editor.setData($(this).data('description'));

        $('#statusToggle').prop('checked', $(this).data('active') == 1);

        let slug = title.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
        $('#slugField').val(slug);

        Swal.fire({
            icon:'info',
            title:'Edit Mode',
            text:'Update and submit'
        });
    });

    /* =========================
       SAVE
    ========================= */
    $('#newsForm').submit(function(e){
        e.preventDefault();

        let btn = $('#saveBtn');
        btn.prop('disabled', true).text('Saving...');

        let id = $('#news_id').val();

        let url = id 
            ? "{{ route('admin.news.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.news.store') }}";

        let formData = new FormData(this);

        formData.set('description', editor.getData());
        formData.set('is_active', $('#statusToggle').is(':checked') ? 1 : 0);
        formData.set('slug', $('#slugField').val());

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#newsForm')[0].reset();
                editor.setData('');
                $('#previewImg').hide();
                $('#news_id').val('');

                table.ajax.reload();

                btn.prop('disabled', false).text('Save News');
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

                btn.prop('disabled', false).text('Save News');
            }
        });
    });

    /* =========================
       DELETE
    ========================= */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this news?',
            text:'This action cannot be undone!',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff5722'
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.news.delete', ':id') }}".replace(':id', id),
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