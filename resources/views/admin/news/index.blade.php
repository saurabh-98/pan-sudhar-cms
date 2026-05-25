@section('scripts')

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>

$(document).ready(function(){

    /*
    |--------------------------------------------------------------------------
    | CKEDITOR
    |--------------------------------------------------------------------------
    */

    let editor = CKEDITOR.replace('descriptionEditor');

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    let table = $('#onlineClassTable').DataTable({

        ajax: {

            url: "{{ route('admin.online-classes.index') }}",

            dataSrc: 'data'
        },

        responsive: true,

        autoWidth: false,

        columns: [

            {
                data: 'id'
            },

            {
                data: null,

                render: function(row){

                    return `

                        <strong>

                            ${row.title}

                        </strong>

                        <br>

                        <small>

                            ${row.class_name}

                            |

                            ${row.section_name}

                            |

                            ${row.subject_name}

                        </small>

                    `;
                }
            },

            {
                data: 'teacher_name'
            },

            {
                data: null,

                render: function(row){

                    return `

                        ${row.class_date}

                        <br>

                        <small>

                            ${row.time}

                        </small>

                    `;
                }
            },

            {
                data: 'status_badge'
            },

            {
                data: null,

                render: function(row){

                    return `

                        <button
                            class="btn btn-sm btn-edit editBtn"
                            data-id="${row.id}">

                            Edit

                        </button>

                        <button
                            class="btn btn-sm btn-delete deleteBtn"
                            data-id="${row.id}">

                            Delete

                        </button>

                    `;
                }
            }
        ]
    });

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.editBtn',function(){

        let id = $(this).data('id');

        $.ajax({

            url: "{{ route('admin.online-classes.edit', ':id') }}"
                .replace(':id', id),

            method: "GET",

            success: function(res){

                /*
                |--------------------------------------------------------------------------
                | SET ID
                |--------------------------------------------------------------------------
                */

                $('#class_id').val(res.id);

                /*
                |--------------------------------------------------------------------------
                | FIELDS
                |--------------------------------------------------------------------------
                */

                $('input[name="title"]')
                    .val(res.title);

                $('select[name="class_id"]')
                    .val(res.class_id);

                $('select[name="section_id"]')
                    .val(res.section_id);

                $('select[name="subject_id"]')
                    .val(res.subject_id);

                $('select[name="teacher_id"]')
                    .val(res.teacher_id);

                $('input[name="class_date"]')
                    .val(res.class_date);

                $('input[name="start_time"]')
                    .val(res.start_time);

                $('input[name="end_time"]')
                    .val(res.end_time);

                $('input[name="meeting_link"]')
                    .val(res.meeting_link);

                $('input[name="meeting_id"]')
                    .val(res.meeting_id);

                $('input[name="meeting_password"]')
                    .val(res.meeting_password);

                $('input[name="recording_link"]')
                    .val(res.recording_link);

                $('select[name="platform"]')
                    .val(res.platform);

                $('select[name="status"]')
                    .val(res.status);

                /*
                |--------------------------------------------------------------------------
                | DESCRIPTION
                |--------------------------------------------------------------------------
                */

                editor.setData(

                    res.description ?? ''

                );

                /*
                |--------------------------------------------------------------------------
                | ALERT
                |--------------------------------------------------------------------------
                */

                Swal.fire({

                    icon:'info',

                    title:'Edit Mode',

                    text:'Update class details'

                });
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SAVE / UPDATE
    |--------------------------------------------------------------------------
    */

    $('#onlineClassForm').submit(function(e){

        e.preventDefault();

        let id = $('#class_id').val();

        let url = id

            ? "{{ route('admin.online-classes.update', ':id') }}"
                .replace(':id', id)

            : "{{ route('admin.online-classes.store') }}";

        /*
        |--------------------------------------------------------------------------
        | CONFIRM
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: id
                ? 'Update Online Class?'
                : 'Create Online Class?',

            text:'Please confirm action',

            icon:'question',

            showCancelButton:true,

            confirmButtonColor:'#3085d6',

            cancelButtonColor:'#d33',

            confirmButtonText:'Yes'

        }).then((result)=>{

            if(result.isConfirmed){

                let formData = new FormData(

                    $('#onlineClassForm')[0]

                );

                formData.set(

                    'description',

                    editor.getData()

                );

                $.ajax({

                    url: url,

                    method: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(res){

                        /*
                        |--------------------------------------------------------------------------
                        | SUCCESS
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon:'success',

                            title:'Success',

                            text:res.message

                        });

                        /*
                        |--------------------------------------------------------------------------
                        | RESET
                        |--------------------------------------------------------------------------
                        */

                        $('#onlineClassForm')[0].reset();

                        $('#class_id').val('');

                        editor.setData('');

                        /*
                        |--------------------------------------------------------------------------
                        | RELOAD
                        |--------------------------------------------------------------------------
                        */

                        table.ajax.reload(
                            null,
                            false
                        );
                    },

                    error: function(err){

                        let msg = '';

                        if(err.responseJSON.errors){

                            $.each(

                                err.responseJSON.errors,

                                function(k,v){

                                    msg += v[0] + '<br>';

                                }
                            );
                        }

                        Swal.fire({

                            icon:'error',

                            title:'Validation Error',

                            html:msg
                        });
                    }
                });
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({

            title:'Delete Online Class?',

            text:'This action cannot be undone',

            icon:'warning',

            showCancelButton:true,

            confirmButtonColor:'#d33',

            confirmButtonText:'Yes Delete'

        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({

                    url: "{{ route('admin.online-classes.delete', ':id') }}"
                        .replace(':id', id),

                    method: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        _method: "DELETE"
                    },

                    success: function(res){

                        Swal.fire({

                            icon:'success',

                            title:'Deleted',

                            text:res.message

                        });

                        table.ajax.reload(
                            null,
                            false
                        );
                    }
                });
            }
        });
    });

});
</script>

@endsection