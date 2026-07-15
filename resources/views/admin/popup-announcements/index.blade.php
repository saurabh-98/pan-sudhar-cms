@extends('layout.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">

    <div class="row">

        <!-- ================= ADD POPUP ================= -->

        <div class="col-lg-4">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        ➕ Add Popup

                    </h5>

                </div>

                <div class="card-body">

                    <form id="popupForm"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">

                            <label>Title</label>

                            <input
                                type="text"
                                name="title"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Slug</label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Banner Image</label>

                            <input
                                type="file"
                                name="image"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Description</label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"></textarea>

                        </div>

                        <div class="mb-3">

                            <label>Button Text</label>

                            <input
                                type="text"
                                name="button_text"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Button Link</label>

                            <input
                                type="text"
                                name="button_link"
                                class="form-control">

                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <label>Start Date</label>

                                <input
                                    type="date"
                                    name="start_date"
                                    class="form-control">

                            </div>

                            <div class="col-md-6">

                                <label>End Date</label>

                                <input
                                    type="date"
                                    name="end_date"
                                    class="form-control">

                            </div>

                        </div>

                        <hr>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_on_login"
                                value="1"
                                class="form-check-input">

                            <label class="form-check-label">

                                Show on Login

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_on_dashboard"
                                value="1"
                                class="form-check-input">

                            <label class="form-check-label">

                                Show on Dashboard

                            </label>

                        </div>

                         <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_on_home"
                                value="1"
                                class="form-check-input">

                            <label class="form-check-label">

                                Show on Home

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_once_per_day"
                                value="1"
                                checked
                                class="form-check-input">

                            <label class="form-check-label">

                                Show Once Per Day

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                checked
                                class="form-check-input">

                            <label class="form-check-label">

                                Active

                            </label>

                        </div>

                        <button
                            class="btn btn-primary w-100 mt-3">

                            Save Popup

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ================= TABLE ================= -->

        <div class="col-lg-8">

            <div class="card shadow border-0">

                <div class="card-header bg-dark text-white">

                    <h5 class="mb-0">

                        Popup List

                    </h5>

                </div>

                <div class="card-body">

                    <table
                        id="popupTable"
                        class="table table-bordered">

                        <thead>

                        <tr>

                            <th>ID</th>

                            <th>Image</th>

                            <th>Title</th>

                            <th>Login</th>

                            <th>Dashboard</th>

                            <th>Home</th>

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

@include('admin.popup-announcements.edit-modal')

@endsection

@section('scripts')

<script>

let table;

$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    }
});

/*=====================================
=            DATATABLE                =
=====================================*/

$(document).ready(function(){

    table = $('#popupTable').DataTable({

        processing:true,

        serverSide:true,

        ajax:"{{ route('admin.popup-announcements.list') }}",

        columns:[

            {
                data:'id',
                name:'id'
            },

            {
                data:'image',
                name:'image',
                orderable:false,
                searchable:false
            },

            {
                data:'title',
                name:'title'
            },

            {
                data:'show_on_login',
                name:'show_on_login',
                orderable:false,
                searchable:false
            },

            {
                data:'show_on_dashboard',
                name:'show_on_dashboard',
                orderable:false,
                searchable:false
            },

            {
                data:'show_on_home',
                name:'show_on_home',
                orderable:false,
                searchable:false
            },

            {
                data:'status',
                name:'status',
                orderable:false,
                searchable:false
            },

            {
                data:'action',
                name:'action',
                orderable:false,
                searchable:false
            }

        ]

    });

});


/*=====================================
=            STORE                    =
=====================================*/

$('#popupForm').submit(function(e){

    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({

        url:"{{ route('admin.popup-announcements.store') }}",

        type:"POST",

        data:formData,

        processData:false,

        contentType:false,

        success:function(response){

            Swal.fire({

                icon:'success',

                title:'Success',

                text:'Popup created successfully.'

            });

            $('#popupForm')[0].reset();

            table.ajax.reload();

        },

        error:function(xhr){

            $('.text-danger').html('');

            if(xhr.status==422){

                $.each(xhr.responseJSON.errors,function(key,value){

                    $('#err_'+key).html(value[0]);

                });

            }

        }

    });

});


/*=====================================
=            EDIT                     =
=====================================*/

$(document).on('click','.editBtn',function(){

    let id=$(this).data('id');

    $.get(
        "{{ url('admin/popup-announcements/edit') }}/"+id,

        function(res){

            $('#edit_id').val(res.id);

            $('#edit_title').val(res.title);

            $('#edit_slug').val(res.slug);

            $('#edit_description').val(res.description);

            $('#edit_button_text').val(res.button_text);

            $('#edit_button_link').val(res.button_link);

            $('#edit_start_date').val(res.start_date);

            $('#edit_end_date').val(res.end_date);

            $('#edit_show_on_login').prop(
                'checked',
                res.show_on_login==1
            );

            $('#edit_show_on_dashboard').prop(
                'checked',
                res.show_on_dashboard==1
            );

            $('#edit_show_on_home').prop(
                'checked',
                res.show_on_home==1
            );


            $('#edit_show_once_per_day').prop(
                'checked',
                res.show_once_per_day==1
            );

            $('#edit_status').prop(
                'checked',
                res.status==1
            );

            $('#editModal').modal('show');

        }

    );

});


/*=====================================
=            UPDATE                   =
=====================================*/

$('#updateBtn').click(function(){

    let id=$('#edit_id').val();

    let formData=new FormData();

    formData.append('_token',$('meta[name="csrf-token"]').attr('content'));

    formData.append('title',$('#edit_title').val());

    formData.append('slug',$('#edit_slug').val());

    formData.append('description',$('#edit_description').val());

    formData.append('button_text',$('#edit_button_text').val());

    formData.append('button_link',$('#edit_button_link').val());

    formData.append('start_date',$('#edit_start_date').val());

    formData.append('end_date',$('#edit_end_date').val());

    formData.append(
        'show_on_login',
        $('#edit_show_on_login').is(':checked')?1:0
    );

    formData.append(
        'show_on_dashboard',
        $('#edit_show_on_dashboard').is(':checked')?1:0
    );

    formData.append(
        'show_on_home',
        $('#edit_show_on_home').is(':checked')?1:0
    );


    formData.append(
        'show_once_per_day',
        $('#edit_show_once_per_day').is(':checked')?1:0
    );

    formData.append(
        'status',
        $('#edit_status').is(':checked')?1:0
    );

    if($('#edit_image')[0].files.length){

        formData.append(
            'image',
            $('#edit_image')[0].files[0]
        );

    }

    $.ajax({

        url:"{{ url('admin/popup-announcements/update') }}/"+id,

        type:"POST",

        data:formData,

        processData:false,

        contentType:false,

        success:function(){

            $('#editModal').modal('hide');

            Swal.fire({

                icon:'success',

                title:'Updated',

                text:'Popup updated successfully.'

            });

            table.ajax.reload();

        },

        error:function(xhr){

            Swal.fire({

                icon:'error',

                title:'Error',

                text:'Unable to update popup.'

            });

        }

    });

});


/*=====================================
=            DELETE                   =
=====================================*/

$(document).on('click','.deleteBtn',function(){

    let id=$(this).data('id');

    Swal.fire({

        title:'Delete Popup?',

        text:'This action cannot be undone.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonText:'Yes, Delete'

    }).then((result)=>{

        if(result.isConfirmed){

            $.ajax({

                url:"{{ url('admin/popup-announcements/delete') }}/"+id,

                type:"DELETE",

                data:{
                    _token:$('meta[name="csrf-token"]').attr('content')
                },

                success:function(){

                    Swal.fire({

                        icon:'success',

                        title:'Deleted',

                        text:'Popup deleted successfully.'

                    });

                    table.ajax.reload();

                }

            });

        }

    });

});

</script>
@endsection