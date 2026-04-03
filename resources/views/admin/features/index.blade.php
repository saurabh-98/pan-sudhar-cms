@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">Manage Features</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="card-title">Add / Edit Feature</h5>

                <form id="featureForm">
                    @csrf

                    <input type="hidden" id="feature_id">

                    <!-- ICON -->
                    <div class="mb-3">
                        <label>Icon (Emoji / HTML)</label>
                        <input type="text" name="icon" class="form-control modern-input" placeholder="e.g. 🚀 or <i class='fa fa-star'></i>">
                    </div>

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control modern-input">
                    </div>

                    <!-- DESCRIPTION (CKEDITOR) -->
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" id="descriptionEditor" class="form-control modern-input"></textarea>
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        Save Feature
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <div class="table-header">
                    <h5>Feature List</h5>
                </div>

                <div class="table-responsive">

                    <table id="featureTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Description</th>
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

    /* =========================
       CKEDITOR INIT
    ========================= */
    let editor = CKEDITOR.replace('descriptionEditor');

    /* =========================
       DATATABLE
    ========================= */
    let table = $('#featureTable').DataTable({
        ajax: {
            url: "{{ route('admin.features.index') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { 
                data: 'icon',
                render: function(data){
                    return `<span style="font-size:20px">${data}</span>`;
                }
            },
            { data: 'title' },
            { data: 'description' },
            {
                data: null,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-edit editBtn"
                            data-id="${row.id}"
                            data-icon="${row.icon}"
                            data-title="${row.title}"
                            data-description="${row.description}">
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

        $('#feature_id').val($(this).data('id'));
        $('input[name="icon"]').val($(this).data('icon'));
        $('input[name="title"]').val($(this).data('title'));

        editor.setData($(this).data('description'));

        Swal.fire({
            icon:'info',
            title:'Edit Mode',
            text:'Update and submit'
        });
    });

    /* =========================
       SAVE / UPDATE
    ========================= */
    $('#featureForm').submit(function(e){
        e.preventDefault();

        let btn = $('#saveBtn');
        btn.prop('disabled', true).text('Saving...');

        let id = $('#feature_id').val();

        let url = id 
            ? "{{ route('admin.features.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.features.store') }}";

        let formData = $(this).serializeArray();

        /* ADD CKEDITOR VALUE */
        formData.push({
            name: 'description',
            value: editor.getData()
        });

        $.ajax({
            url: url,
            method: "POST",
            data: formData,

            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#featureForm')[0].reset();
                editor.setData('');
                $('#feature_id').val('');

                table.ajax.reload();

                btn.prop('disabled', false).text('Save Feature');
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

                btn.prop('disabled', false).text('Save Feature');
            }
        });
    });

    /* =========================
       DELETE
    ========================= */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this feature?',
            text:'This action cannot be undone!',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff5722'
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.features.delete', ':id') }}".replace(':id', id),
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