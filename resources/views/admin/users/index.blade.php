@extends('layout.admin')

@section('content')

<style>

/* =========================================================
   USER TABLE MODERN
========================================================= */

#userTable {
    border-radius: 12px;
    overflow: hidden;
}

/* HEADER */
#userTable thead {
    background: linear-gradient(
        135deg,
        #1e293b,
        #0f172a
    );

    color: #fff;
}

#userTable thead th {

    border: none;

    font-size: 13px;

    letter-spacing: 0.5px;

    padding: 14px;
}

/* ROW */
#userTable tbody tr {

    transition: 0.3s;
}

#userTable tbody tr:hover {

    background: #f8fafc;

    transform: scale(1.01);
}

/* ROLE BADGE */
.role-badge {

    padding: 6px 14px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 600;

    display: inline-block;

    letter-spacing: 0.4px;
}

/* BUTTONS */
.btn-info {

    background: #0ea5e9;

    border: none;
}

.btn-info:hover {

    background: #0284c7;

    transform: scale(1.05);
}

.btn-danger {

    background: #ef4444;

    border: none;
}

.btn-danger:hover {

    background: #dc2626;

    transform: scale(1.05);
}

/* SEARCH */
.dataTables_filter input {

    border-radius: 20px !important;

    padding: 6px 12px;
}

/* PAGINATION */
.dataTables_paginate .paginate_button {

    border-radius: 6px !important;
}

</style>


<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">

            👥 Users Management

        </h3>

        @can('users.create')

        <a href="{{ route('admin.users.create') }}"
           class="btn btn-primary shadow-sm">

            ➕ Add User

        </a>

        @endcan

    </div>


    <!-- CARD -->
    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-body">

            <table class="table align-middle"
                   id="userTable">

                <thead>

                    <tr>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Status</th>
                        <th width="160">

                            Action

                        </th>

                    </tr>

                </thead>

            </table>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    let table = $('#userTable').DataTable({

        processing: true,

        ajax: {

            url: "{{ route('admin.users.list') }}",

            dataSrc: 'data'
        },

        language: {

            processing: "⏳ Loading users..."
        },

        columns: [

            {
                data: 'name'
            },

            {
                data: 'email'
            },

            /*
            |--------------------------------------------------------------------------
            | DYNAMIC ROLE BADGE
            |--------------------------------------------------------------------------
            */

            {
                data: 'role',

                render: function (data) {

                    /*
                    |--------------------------------------------------------------------------
                    | NO ROLE
                    |--------------------------------------------------------------------------
                    */

                    if (!data || data.length === 0) {

                        return `
                            <span class="role-badge bg-secondary text-white">

                                No Role

                            </span>
                        `;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | LOWERCASE
                    |--------------------------------------------------------------------------
                    */

                    let role = data.toLowerCase();

                    /*
                    |--------------------------------------------------------------------------
                    | BADGE CLASS
                    |--------------------------------------------------------------------------
                    */

                    let badgeClass = 'bg-primary text-white';

                    if (role.includes('admin')) {

                        badgeClass = 'bg-danger text-white';
                    }
                    else if (role.includes('teacher')) {

                        badgeClass = 'bg-success text-white';
                    }
                    else if (role.includes('principal')) {

                        badgeClass = 'bg-dark text-white';
                    }
                    else if (role.includes('accountant')) {

                        badgeClass = 'bg-warning text-dark';
                    }
                    else if (role.includes('hr')) {

                        badgeClass = 'bg-info text-dark';
                    }
                    else if (role.includes('reception')) {

                        badgeClass = 'bg-secondary text-white';
                    }
                    else if (role.includes('transport')) {

                        badgeClass = 'bg-primary text-white';
                    }
                    else if (role.includes('library')) {

                        badgeClass = 'bg-success text-white';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | RETURN
                    |--------------------------------------------------------------------------
                    */

                    return `
                        <span class="role-badge ${badgeClass}">

                            ${data}

                        </span>
                    `;
                }
            },

           {
                data: 'status',

                render: function(data){

                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data == 1 ||
                        data === true
                    ) {

                        return `
                            <span class="badge rounded-pill bg-success px-3 py-2">

                                Active

                            </span>
                        `;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | INACTIVE
                    |--------------------------------------------------------------------------
                    */

                    return `
                        <span class="badge rounded-pill bg-danger px-3 py-2">

                            Inactive

                        </span>
                    `;
                }
            },

            /*
            |--------------------------------------------------------------------------
            | ACTION BUTTONS
            |--------------------------------------------------------------------------
            */

            {
                data: 'id',

                orderable: false,

                searchable: false,

                render: function (id) {

                    let editUrl =
                        "{{ route('admin.users.edit', '__id__') }}"
                        .replace('__id__', id);

                    let deleteUrl =
                        "{{ route('admin.users.delete', '__id__') }}"
                        .replace('__id__', id);

                    return `

                        @can('users.edit')

                        <a href="${editUrl}"
                           class="btn btn-sm btn-info me-1 shadow-sm">

                            ✏ Edit

                        </a>

                        @endcan

                        @can('users.delete')

                        <button
                            class="btn btn-sm btn-danger shadow-sm deleteUser"

                            data-id="${id}"

                            data-url="${deleteUrl}">

                            🗑 Delete

                        </button>

                        @endcan
                    `;
                }
            }
        ]
    });


    /*
    |--------------------------------------------------------------------------
    | ROW CLICK EFFECT
    |--------------------------------------------------------------------------
    */

    $('#userTable tbody').on(
        'click',
        'tr',
        function () {

            $(this).toggleClass(
                'table-active'
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.deleteUser',
        function () {

            let url = $(this).data('url');

            Swal.fire({

                title: "Delete User?",

                text: "This action cannot be undone!",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#ef4444",

                confirmButtonText: "Yes, Delete"

            }).then((result) => {

                if (result.isConfirmed) {

                    Swal.fire({

                        title: 'Deleting...',

                        allowOutsideClick: false,

                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({

                        url: url,

                        type: "DELETE",

                        data: {

                            _token: "{{ csrf_token() }}"
                        },

                        success: function (response) {

                            Swal.fire({

                                icon: 'success',

                                title: 'Deleted!',

                                text:
                                    response.message
                                    || 'User deleted successfully',

                                timer: 2000,

                                showConfirmButton: false
                            });

                            table.ajax.reload();
                        },

                        error: function () {

                            Swal.fire({

                                icon: 'error',

                                title: 'Error',

                                text: 'Something went wrong!'
                            });
                        }
                    });
                }
            });
        }
    );

});

</script>

@endsection