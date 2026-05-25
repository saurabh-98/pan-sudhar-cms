@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">

        Manage Roles & Permissions

    </h3>

    <div class="row g-4 align-items-stretch">

        <!-- FORM -->
        <div class="col-lg-4 d-flex">

            <div class="modern-card w-100">

                <h5 class="mb-3">

                    Add / Edit Role

                </h5>

                <form id="roleForm">

                    @csrf

                    <input type="hidden" id="role_id">

                    <!-- ROLE NAME -->
                    <div class="mb-3">

                        <label class="form-label">

                            Role Name

                        </label>

                        <input type="text"
                               name="name"
                               class="form-control modern-input"
                               required>

                    </div>

                    <!-- PERMISSIONS -->
                    <div class="mb-3">

                        <label class="form-label mb-2">

                            Assign Permissions

                        </label>

                        <div class="permission-box">

                            <div class="row">

                                @foreach($permissions as $permission)

                                <div class="col-md-6 mb-2">

                                    <div class="form-check">

                                        <input type="checkbox"
                                               class="form-check-input permission-checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               id="perm{{ $permission->id }}">

                                        <label class="form-check-label"
                                               for="perm{{ $permission->id }}">

                                            {{ $permission->name }}

                                        </label>

                                    </div>

                                </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <button class="btn btn-gradient w-100">

                        Save Role

                    </button>

                </form>

            </div>

        </div>

        <!-- TABLE -->
        <div class="col-lg-8 d-flex">

            <div class="modern-card w-100">

                <h5 class="mb-3">

                    Role List

                </h5>

                <div class="table-responsive">

                    <table id="roleTable"
                           class="modern-table w-100">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Role</th>

                                <th>Permissions</th>

                                <th width="180">

                                    Action

                                </th>

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

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    let table = $('#roleTable').DataTable({

        ajax: {

            url: "{{ route('admin.roles.index') }}",

            dataSrc: ''
        },

        responsive: true,

        autoWidth: false,

        columns: [

            { data: 'id' },

            { data: 'name' },

            {
                data: 'permissions',

                render: function(data){

                    let html = '';

                    data.forEach(function(permission){

                        html += `
                            <span class="badge bg-success me-1 mb-1">
                                ${permission.name}
                            </span>
                        `;
                    });

                    return html;
                }
            },

            {
                data: null,

                render: function(row){

                    return `

                        <button class="btn btn-edit editBtn"
                                data-id="${row.id}"
                                data-name="${row.name}"
                                data-permissions='${JSON.stringify(row.permissions)}'>

                            Edit

                        </button>

                        <button class="btn btn-delete deleteBtn"
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

        $('#role_id').val($(this).data('id'));

        $('input[name="name"]').val(
            $(this).data('name')
        );

        /*
        |--------------------------------------------------------------------------
        | RESET PERMISSIONS
        |--------------------------------------------------------------------------
        */

        $('.permission-checkbox')
            .prop('checked', false);

        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING
        |--------------------------------------------------------------------------
        */

        let permissions = $(this).data('permissions');

        permissions.forEach(function(permission){

            $(
                `.permission-checkbox[value="${permission.name}"]`
            ).prop('checked', true);
        });

        Swal.fire(
            'Edit Mode',
            '',
            'info'
        );
    });


    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

    $('#roleForm').submit(function(e){

        e.preventDefault();

        let id = $('#role_id').val();

        let url = id
            ? "{{ route('admin.roles.update', ':id') }}"
                .replace(':id', id)
            : "{{ route('admin.roles.store') }}";

        let formData = $(this).serialize();

        $.ajax({

            url: url,

            method: "POST",

            data: formData,

            success: function(){

                Swal.fire(
                    'Success',
                    'Saved successfully',
                    'success'
                );

                $('#roleForm')[0].reset();

                $('#role_id').val('');

                $('.permission-checkbox')
                    .prop('checked', false);

                table.ajax.reload();
            },

            error: function(err){

                let msg = '';

                $.each(
                    err.responseJSON.errors,
                    function(k,v){

                        msg += v[0] + '<br>';
                    }
                );

                Swal.fire(
                    'Error',
                    msg,
                    'error'
                );
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

            title:'Delete Role?',

            icon:'warning',

            showCancelButton:true

        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({

                    url: "{{ route('admin.roles.delete', ':id') }}"
                        .replace(':id', id),

                    method: "POST",

                    data: {

                        _token: "{{ csrf_token() }}",

                        _method: "DELETE"
                    },

                    success: function(){

                        table.ajax.reload();

                        Swal.fire(
                            'Deleted',
                            '',
                            'success'
                        );
                    }
                });
            }
        });
    });

});

</script>

@endsection