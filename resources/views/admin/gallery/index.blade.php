@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">📸 Gallery</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5>Add Media</h5>

                <form id="galleryForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="gallery_id">

                    <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>

                    <select name="type" id="type" class="form-control mb-2">
                        <option value="photo">Photo</option>
                        <option value="video">Video</option>
                    </select>

                    <input type="file" name="file" id="fileInput" class="form-control mb-2">

                    <input type="text" name="file" id="videoInput"
                           class="form-control mb-2"
                           placeholder="YouTube URL"
                           style="display:none;">

                    <button class="btn btn-gradient w-100">Save</button>
                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <h5>Gallery List</h5>

                <table id="galleryTable" class="modern-table w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Preview</th>
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


<script>
$(document).ready(function(){

// ================= CSRF =================
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    }
});

// ================= DATATABLE =================
let table = $('#galleryTable').DataTable({
    ajax: {
        url: "{{ route('admin.gallery.index') }}",
        dataSrc: ''
    },
    columns: [
        { data: 'id' },
        { data: 'title' },
        { data: 'type' },
        {
            data: null,
            render: function(row){
                return row.type === 'photo'
                    ? `<img src="/uploads/gallery/${row.file}" width="60" style="border-radius:6px;">`
                    : `<a href="${row.file}" target="_blank" class="btn btn-dark btn-sm">Video</a>`;
            }
        },
        {
            data: null,
            render: function(row){
                return `
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="${row.id}"
                    data-title="${row.title}"
                    data-type="${row.type}"
                    data-file="${row.file}">
                    Edit
                </button>

                <button class="btn btn-danger btn-sm deleteBtn"
                    data-id="${row.id}">
                    Delete
                </button>`;
            }
        }
    ]
});


// ================= TYPE SWITCH =================
$('#type').change(function(){
    if(this.value === 'photo'){
        $('#fileInput').show();
        $('#videoInput').hide();
    } else {
        $('#fileInput').hide();
        $('#videoInput').show();
    }
});


// ================= EDIT =================
$(document).on('click','.editBtn',function(){

    $('#gallery_id').val($(this).data('id'));
    $('input[name=title]').val($(this).data('title'));
    $('#type').val($(this).data('type')).trigger('change');

    if($(this).data('type') === 'video'){
        $('#videoInput').val($(this).data('file'));
    }
});


// ================= SAVE / UPDATE =================
$('#galleryForm').submit(function(e){
    e.preventDefault();

    let id = $('#gallery_id').val();
    let isUpdate = id ? true : false;

    let url = isUpdate
        ? "{{ route('admin.gallery.update', ':id') }}".replace(':id', id)
        : "{{ route('admin.gallery.store') }}";

    let formData = new FormData(this);
    let btn = $(this).find('button');

    // 🔥 CONFIRMATION ALERT
    Swal.fire({
        title: isUpdate ? 'Update this media?' : 'Save this media?',
        text: isUpdate ? "Changes will be updated." : "New media will be added.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: isUpdate ? 'Yes, update it' : 'Yes, save it'
    }).then((result) => {

        if(result.isConfirmed){

            btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,

                success: function(res){

                    btn.prop('disabled', false).text('Save');

                    $('#galleryForm')[0].reset();
                    $('#gallery_id').val('');
                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: isUpdate ? 'Updated!' : 'Saved!',
                        text: res.message || 'Operation successful'
                    });
                },

                error: function(xhr){

                    btn.prop('disabled', false).text('Save');

                    let errors = xhr.responseJSON?.errors;
                    let msg = '';

                    if(errors){
                        for(let key in errors){
                            msg += errors[key][0] + '\n';
                        }
                    } else {
                        msg = 'Something went wrong!';
                    }

                    Swal.fire('Error', msg, 'error');
                }
            });

        }
    });
});


// ================= DELETE =================
$(document).on('click','.deleteBtn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title: 'Delete this media?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, delete it'
    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({
                url: "{{ route('admin.gallery.delete', ':id') }}".replace(':id', id),
                method: "POST",
                data: {_method:"DELETE"},

                success: function(res){
                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message || 'Deleted successfully'
                    });
                },

                error: function(){
                    Swal.fire('Error','Delete failed','error');
                }
            });

        }

    });

});

});
</script>

@endsection
