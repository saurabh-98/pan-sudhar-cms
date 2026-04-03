@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">🎁 Manage Offers</h3>

    <div class="row">

        <!-- 🔥 FORM -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Add / Edit Offer
                </div>

                <div class="card-body">

                    <form id="offerForm" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" id="offer_id">

                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control">
                        </div>

                        <!-- ✅ IMAGE FIELD -->
                        <div class="mb-3">
                            <label>Offer Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <!-- PREVIEW -->
                        <div class="mb-3 text-center">
                            <img id="previewImage" style="max-height:80px; display:none;">
                        </div>

                        <div class="mb-3">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option value="percent">Percent (%)</option>
                                <option value="fixed">Fixed (₹)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Value</label>
                            <input type="number" name="value" class="form-control">
                        </div>

                        <button class="btn btn-success w-100">
                            💾 Save Offer
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <!-- 🔥 TABLE -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    Offer List
                </div>

                <div class="card-body">

                    <table id="offerTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th> <!-- ✅ NEW -->
                                <th>Title</th>
                                <th>Type</th>
                                <th>Value</th>
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

    // ✅ IMAGE PREVIEW
    $('input[name="image"]').on('change', function(){
        let reader = new FileReader();
        reader.onload = function(e){
            $('#previewImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

    let table = $('#offerTable').DataTable({
        ajax: {
            url: "{{ route('admin.offers.list') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },

            // ✅ IMAGE COLUMN
            {
                data: 'image',
                render: function(data){
                    return data 
                        ? `<img src="/${data}" height="50">`
                        : 'No Image';
                }
            },

            { data: 'title' },

            { 
                data: 'type',
                render: function(data){
                    return data === 'percent'
                        ? '<span class="badge bg-info">%</span>'
                        : '<span class="badge bg-warning">₹</span>';
                }
            },

            { 
                data: 'value',
                render: function(data, type, row){
                    return row.type === 'percent'
                        ? data + '%'
                        : '₹' + data;
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
                data: null,
                render: function(data, type, row){
                    return `
                        <button class="btn btn-sm btn-primary editBtn"
                            data-id="${row.id}"
                            data-title="${row.title}"
                            data-type="${row.type}"
                            data-value="${row.value}">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    // ✏️ EDIT
    $(document).on('click', '.editBtn', function(){

        $('#offer_id').val($(this).data('id'));

        $('input[name="title"]').val($(this).data('title'));
        $('select[name="type"]').val($(this).data('type'));
        $('input[name="value"]').val($(this).data('value'));
    });

    // 💾 SAVE / UPDATE
    $('#offerForm').submit(function(e){
        e.preventDefault();

        let id = $('#offer_id').val();

        let url = id 
            ? "{{ route('admin.offers.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.offers.store') }}";

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#offerForm')[0].reset();
                $('#offer_id').val('');
                $('#previewImage').hide();

                table.ajax.reload();
            },

            error: function(err){

                let errors = err.responseJSON.errors;
                let msg = '';

                if(errors){
                    $.each(errors, function(key, val){
                        msg += val[0] + '<br>';
                    });
                } else {
                    msg = 'Something went wrong';
                }

                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // ❌ DELETE
    $(document).on('click', '.deleteBtn', function(){

        let id = $(this).data('id');

        let url = "{{ route('admin.offers.delete', ':id') }}".replace(':id', id);

        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url: url,
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