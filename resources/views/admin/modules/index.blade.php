@extends('layout.admin')

@section('content')

<div class="container-fluid">

<h3 class="dashboard-title mb-4">

    Module Management

</h3>

<div class="row g-4">

    {{-- FORM --}}
    <div class="col-lg-4">

        <div class="modern-card">

            <h5 class="card-title">

                Add / Edit Module

            </h5>

            <form id="moduleForm">

                @csrf

                <input
                    type="hidden"
                    id="module_id"
                >

                <div class="mb-3">

                    <label>

                        Module Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control modern-input"
                    >

                </div>

                <div class="mb-3">

                    <label>

                        Slug

                    </label>

                    <input
                        type="text"
                        name="slug"
                        class="form-control modern-input"
                    >

                </div>

                <div class="mb-3">

                    <label>

                        Parent Module

                    </label>

                    <select
                        name="parent_id"
                        class="form-control modern-input"
                    >

                        <option value="">

                            Main Menu

                        </option>

                        @foreach($parents as $parent)

                            <option value="{{ $parent->id }}">

                                {{ $parent->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="mb-3">

                    <label>

                        Route Name

                    </label>

                    <select
                        name="route_name"
                        id="route_name"
                        class="form-control modern-input"
                    >

                        <option value="">

                            Select Route

                        </option>

                        @foreach($routes as $route)

                            <option value="{{ $route }}">

                                {{ $route }}

                            </option>

                        @endforeach

                    </select>

                </div>
                
                <div class="mb-3">

                    <label>

                        Icon

                    </label>

                    <input
                        type="text"
                        name="icon"
                        class="form-control modern-input"
                        placeholder="fa-id-card"
                    >

                </div>

                <div class="mb-3">

                    <label>

                        Sort Order

                    </label>

                    <input
                        type="number"
                        name="sort_order"
                        class="form-control modern-input"
                        value="0"
                    >

                </div>

                <div class="mb-3">

                    <label>

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-control modern-input"
                    >

                        <option value="1">

                            Active

                        </option>

                        <option value="0">

                            Inactive

                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="btn btn-gradient w-100"
                >

                    Save Module

                </button>

            </form>

        </div>

    </div>

    {{-- TABLE --}}
    <div class="col-lg-8">

        <div class="modern-card">

            <div class="table-header">

                <h5>

                    Module List

                </h5>

            </div>

            <div class="table-responsive">

                <table
                    id="moduleTable"
                    class="modern-table"
                >

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Name</th>

                            <th>Parent</th>

                            <th>Route</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection

@section('scripts')

<script>

$(document).ready(function(){

    let table = $('#moduleTable').DataTable({

        ajax: {

            url: "{{ route('admin.modules.list') }}",

            dataSrc: ''

        },

        columns: [

            { data: 'id' },

            { data: 'name' },

            {
                data: 'parent',
                render:function(data){

                    return data
                        ? data.name
                        : 'Main Menu';

                }
            },

            {
                data: 'route_name',
                defaultContent:'-'
            },

            {
                data: 'status',
                render:function(data){

                    return data
                        ? 'Active'
                        : 'Inactive';

                }
            },

            {
                data:null,
                render:function(row){

                    return `

                        <button
                            class="btn btn-sm btn-edit editBtn"

                            data-id="${row.id}"

                            data-name="${row.name}"

                            data-slug="${row.slug}"

                            data-parent_id="${row.parent_id ?? ''}"

                            data-route_name="${row.route_name ?? ''}"

                            data-icon="${row.icon ?? ''}"

                            data-sort_order="${row.sort_order}"

                            data-status="${row.status}"
                        >

                            Edit

                        </button>

                        <button
                            class="btn btn-sm btn-delete deleteBtn"
                            data-id="${row.id}"
                        >

                            Delete

                        </button>

                    `;

                }
            }

        ]

    });

    $(document).on('click','.editBtn',function(){

        $('#module_id').val(
            $(this).data('id')
        );

        $('input[name="name"]').val(
            $(this).data('name')
        );

        $('input[name="slug"]').val(
            $(this).data('slug')
        );

        $('select[name="parent_id"]').val(
            $(this).data('parent_id')
        );

        $('input[name="route_name"]').val(
            $(this).data('route_name')
        );

        $('input[name="icon"]').val(
            $(this).data('icon')
        );

        $('input[name="sort_order"]').val(
            $(this).data('sort_order')
        );

        $('select[name="status"]').val(
            $(this).data('status')
        );

    });

    $('#moduleForm').submit(function(e){

        e.preventDefault();

        let id = $('#module_id').val();

        let url = id

            ? "{{ route('admin.modules.update', ':id') }}"
                .replace(':id', id)

            : "{{ route('admin.modules.store') }}";

        $.ajax({

            url:url,

            method:'POST',

            data:$(this).serialize(),

            success:function(){

                Swal.fire(
                    'Success',
                    'Module saved successfully',
                    'success'
                );

                $('#moduleForm')[0].reset();

                $('#module_id').val('');

                table.ajax.reload();

            },

            error:function(){

                Swal.fire(
                    'Error',
                    'Validation failed',
                    'error'
                );

            }

        });

    });

    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({

            title:'Delete Module?',

            icon:'warning',

            showCancelButton:true

        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({

                    url:
                    "{{ route('admin.modules.delete', ':id') }}"
                    .replace(':id', id),

                    method:'POST',

                    data:{

                        _token:
                        "{{ csrf_token() }}",

                        _method:'DELETE'

                    },

                    success:function(){

                        Swal.fire(
                            'Deleted',
                            '',
                            'success'
                        );

                        table.ajax.reload();

                    }

                });

            }

        });

    });

});

</script>

@endsection
