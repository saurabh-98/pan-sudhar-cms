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

/* =========================================================
   ROLE-WISE TABS
========================================================= */

.role-tabs {

    display: flex;

    flex-wrap: wrap;

    gap: 8px;

    margin-bottom: 18px;
}

.role-tab {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    border: 1px solid #e2e8f0;

    background: #fff;

    color: #334155;

    font-size: 13px;

    font-weight: 600;

    padding: 8px 16px;

    border-radius: 999px;

    cursor: pointer;

    user-select: none;

    transition: all 0.2s ease;
}

.role-tab:hover {

    border-color: #94a3b8;

    transform: translateY(-1px);
}

.role-tab.active {

    background: linear-gradient(135deg, #1e293b, #0f172a);

    border-color: transparent;

    color: #fff;

    box-shadow: 0 8px 16px -8px rgba(15, 23, 42, 0.5);
}

.role-tab .tab-count {

    background: rgba(100, 116, 139, 0.15);

    color: inherit;

    font-size: 11px;

    font-weight: 700;

    padding: 2px 8px;

    border-radius: 999px;

    min-width: 22px;

    text-align: center;
}

.role-tab.active .tab-count {

    background: rgba(255, 255, 255, 0.18);
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

    <!-- ROLE-WISE TABS -->
    <div class="role-tabs" id="roleTabs">

    

        <button type="button" class="role-tab active" data-role="all">
            All <span class="tab-count" data-count="all">0</span>
        </button>

        <button type="button" class="role-tab" data-role="admin">
            Admin <span class="tab-count" data-count="admin">0</span>
        </button>

        <button type="button" class="role-tab" data-role="retailer">
            Retailer <span class="tab-count" data-count="retailer">0</span>
        </button>

        <button class="role-tab" data-role="executive">
            Executive <span class="tab-count" data-count="executive">0</span>
        </button>

        <button class="role-tab" data-role="distributor">
            Distributor <span class="tab-count" data-count="distributor">0</span>
        </button>

        <button class="role-tab" data-role="super distributor">
            Super Distributor
            <span class="tab-count" data-count="super distributor">0</span>
        </button>

         

       
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
    | ROLE CATEGORY HELPER
    |--------------------------------------------------------------------------
    | Single source of truth for both the badge color AND the tab filter,
    | so a role always lands in the same bucket in both places.
    */

    function roleCategory(roleValue) {
        if (!roleValue || String(roleValue).trim() === '') {
            return 'no-role';
        }

        let role = String(roleValue).toLowerCase();

        if (role.includes('admin')) return 'admin';
        if (role.includes('retailer')) return 'retailer';
        if (role.includes('super distributor')) return 'super distributor';
        if (role.includes('sub distributor')) return 'sub distributor';
        if (role.includes('distributor')) return 'distributor';
        if (role.includes('executive')) return 'executive';

        return 'other';
    }

    function badgeClassFor(category) {
        switch (category) {
            case 'admin':
                return 'bg-danger text-white';
            case 'retailer':
                return 'bg-success text-white';
            case 'executive':
                return 'bg-dark text-white';
            case 'super distributor':
                return 'bg-warning text-dark';
            case 'distributor':
                return 'bg-info text-dark';
            case 'sub distributor':
                return 'bg-secondary text-white';
            default:
                return 'bg-primary text-white';
        }
    }

    let activeRoleFilter = 'all';

    /*
    |--------------------------------------------------------------------------
    | ROLE COUNTS (always computed from the full dataset, independent of
    | whichever tab / search is currently active)
    |--------------------------------------------------------------------------
    */

    function updateRoleCounts(rows) {

       let counts = {
            all: rows.length,
            admin: 0,
            retailer: 0,
            executive: 0,
            distributor: 0,
            'super distributor': 0
        };

        rows.forEach(function (row) {

            let category = roleCategory(row.role);

            counts[category] = (counts[category] || 0) + 1;
        });

        Object.keys(counts).forEach(function (key) {

            $('.tab-count[data-count="' + key + '"]').text(counts[key] || 0);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CUSTOM DATATABLES FILTER — restricts rows to the active role tab
    |--------------------------------------------------------------------------
    */

    $.fn.dataTable.ext.search.push(function (settings, searchData, index, rowData) {

        if (settings.nTable.id !== 'userTable') {

            return true;
        }

        if (activeRoleFilter === 'all') {

            return true;
        }

        return roleCategory(rowData.role) === activeRoleFilter;
    });

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    let table = $('#userTable').DataTable({

        processing: true,

        ajax: {

            url: "{{ route('admin.users.list') }}",

            dataSrc: function (json) {

                updateRoleCounts(json.data || []);

                return json.data;
            }
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
            | type-aware render: DataTables asks for a 'display' string for what
            | you see, but a 'filter'/'sort' string for searching and ordering.
            | Returning the badge HTML for every type used to break search/sort.
            */

            {
                data: 'role',

                render: function (data, type) {

                    if (type !== 'display') {

                        return data || '';
                    }

                    if (!data || data.length === 0) {

                        return `
                            <span class="role-badge bg-secondary text-white">

                                No Role

                            </span>
                        `;
                    }

                    let badgeClass = badgeClassFor(roleCategory(data));

                    return `
                        <span class="role-badge ${badgeClass}">

                            ${data}

                        </span>
                    `;
                }
            },

           {
                data: 'status',

                render: function (data, type) {

                    let isActive = (data == 1 || data === true);

                    if (type !== 'display') {

                        return isActive ? 'Active' : 'Inactive';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVE
                    |--------------------------------------------------------------------------
                    */

                    if (isActive) {

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
    | ROLE TAB CLICK — swap the active filter and redraw
    |--------------------------------------------------------------------------
    */

    $('#roleTabs').on('click', '.role-tab', function () {

        $('.role-tab').removeClass('active');

        $(this).addClass('active');

        activeRoleFilter = $(this).data('role');

        table.draw();
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

                                showConfirmButton: true
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