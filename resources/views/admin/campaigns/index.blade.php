@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">Manage Campaign</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="card-title">Add / Edit Campaign</h5>

                <form id="campaignForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="campaign_id">

                    <!-- TAG -->
                    <div class="mb-3">
                        <label>Tag</label>
                        <input type="text" name="tag" class="form-control modern-input">
                    </div>

                    <!-- TITLE -->
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control modern-input">
                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control modern-input"></textarea>
                    </div>

                    <!-- PRICE -->
                    <div class="mb-3">
                        <label>Price</label>
                        <input type="number" name="price" class="form-control modern-input">
                    </div>

                    <!-- IMAGE -->
                    <div class="mb-3">
                        <label>Upload Image</label>
                        <input type="file" name="image" id="imageInput" class="form-control modern-input">

                        <!-- PREVIEW -->
                        <div class="mt-2">
                            <img id="previewImg" style="display:none; width:80px; border-radius:10px;">
                        </div>
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        Save Campaign
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <div class="table-header">
                    <h5>Campaign List</h5>
                </div>

                <div class="table-responsive">

                    <table id="campaignTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Price</th>
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

<script>
$(document).ready(function(){

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
    let table = $('#campaignTable').DataTable({
        ajax: {
            url: "{{ route('admin.campaigns.index') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'price' },
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
                            data-tag="${row.tag}"
                            data-description="${row.description}"
                            data-price="${row.price}">
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

        $('#campaign_id').val($(this).data('id'));
        $('input[name="title"]').val($(this).data('title'));
        $('input[name="tag"]').val($(this).data('tag'));
        $('textarea[name="description"]').val($(this).data('description'));
        $('input[name="price"]').val($(this).data('price'));

        Swal.fire({
            icon:'info',
            title:'Edit Mode',
            text:'Update and submit'
        });
    });

    /* =========================
       SAVE / UPDATE
    ========================= */
    $('#campaignForm').submit(function(e){
        e.preventDefault();

        let btn = $('#saveBtn');
        btn.prop('disabled', true).text('Saving...');

        let id = $('#campaign_id').val();

        let url = id 
            ? "{{ route('admin.campaigns.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.campaigns.store') }}";

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#campaignForm')[0].reset();
                $('#previewImg').hide();
                $('#campaign_id').val('');

                table.ajax.reload();

                btn.prop('disabled', false).text('Save Campaign');
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

                btn.prop('disabled', false).text('Save Campaign');
            }
        });
    });

    /* =========================
       DELETE
    ========================= */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this campaign?',
            text:'This action cannot be undone!',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff5722'
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.campaigns.delete', ':id') }}".replace(':id', id),
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