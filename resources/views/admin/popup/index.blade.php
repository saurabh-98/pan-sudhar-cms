@extends('layout.admin')

@section('content')

<div class="container">

    <h3>Popup Offers</h3>

    <div class="row">

        <!-- FORM -->
        <div class="col-md-4">
            <form id="popupForm" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="popup_id">

                <input type="text" name="title" placeholder="Title" class="form-control mb-2">

                <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>

                <input type="file" name="image" class="form-control mb-2">

                <input type="datetime-local" name="start_at" class="form-control mb-2">
                <input type="datetime-local" name="end_at" class="form-control mb-2">

                <select name="is_active" class="form-control mb-2">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>

                <button class="btn btn-primary w-100">Save</button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="col-md-8">
            <table id="popupTable" class="table table-bordered"></table>
        </div>

    </div>

</div>

@endsection

@section('scripts')

@section('scripts')

<script>
$(document).ready(function(){

    let table = $('#popupTable').DataTable({
        ajax: {
            url: "{{ route('admin.popup.list') }}",
            dataSrc: ''
        },
        columns: [
            {data:'id', title:'ID'},
            {data:'title', title:'Title'},

            {
                data:'image',
                title:'Image',
                render:(d)=> d ? `<img src="/${d}" height="50">` : '-'
            },

            {
                data:'start_at',
                title:'Start',
                render:(d)=> d ? new Date(d).toLocaleString() : '-'
            },

            {
                data:'end_at',
                title:'End',
                render:(d)=> d ? new Date(d).toLocaleString() : '-'
            },

            {
                data:'is_active',
                title:'Status',
                render:(d)=> d == 1 
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>'
            },

            {
                data:null,
                title:'Action',
                render:(row)=> `
                    <button class="btn btn-sm btn-primary editBtn"
                        data-id="${row.id}"
                        data-title="${row.title}"
                        data-description="${row.description ?? ''}"
                        data-start="${row.start_at ?? ''}"
                        data-end="${row.end_at ?? ''}"
                        data-active="${row.is_active}">
                        Edit
                    </button>

                    <button class="btn btn-sm btn-danger deleteBtn"
                        data-id="${row.id}">
                        Delete
                    </button>
                `
            }
        ]
    });

    // ✅ EDIT CLICK
    $(document).on('click', '.editBtn', function(){

        $('#popup_id').val($(this).data('id'));
        $('input[name="title"]').val($(this).data('title'));
        $('textarea[name="description"]').val($(this).data('description'));

        $('input[name="start_at"]').val(formatDate($(this).data('start')));
        $('input[name="end_at"]').val(formatDate($(this).data('end')));

        $('select[name="is_active"]').val($(this).data('active'));
    });

    // ✅ SAVE / UPDATE
    $('#popupForm').submit(function(e){
        e.preventDefault();

        let id = $('#popup_id').val();

        let url = id 
            ? "{{ route('admin.popup.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.popup.store') }}";

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData:false,
            contentType:false,

            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#popupForm')[0].reset();
                $('#popup_id').val('');

                table.ajax.reload();
            }
        });
    });

    // ✅ DELETE
    $(document).on('click', '.deleteBtn', function(){

        let id = $(this).data('id');

        Swal.fire({
            title: 'Delete popup?',
            icon: 'warning',
            showCancelButton: true
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.popup.delete', ':id') }}".replace(':id', id),
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

    // 🔥 DATE FORMAT FIX
    function formatDate(date){
        if(!date) return '';
        let d = new Date(date);
        return d.toISOString().slice(0,16);
    }

});
</script>
@endsection