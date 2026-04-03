@extends('layout.admin')

@section('content')
<style>

    /* ================= USER TABLE MODERN ================= */

#userTable {
    border-radius: 12px;
    overflow: hidden;
}

/* HEADER */
#userTable thead {
    background: linear-gradient(135deg, #1e293b, #0f172a);
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

/* BADGES */
.role-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.role-admin { background: #ef4444; color: #fff; }
.role-staff { background: #f59e0b; color: #fff; }
.role-customer { background: #10b981; color: #fff; }

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
        <h3 class="fw-bold">👥 Users Management</h3>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary shadow-sm">
            ➕ Add User
        </a>
    </div>

    <!-- CARD -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body">

            <table class="table align-middle" id="userTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="160">Action</th>
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
            { data: 'name' },
            { data: 'email' },

            // 🔥 MODERN ROLE BADGE
            {
                data: 'role',
                render: function (data) {

                    if (data === 'admin') {
                        return '<span class="role-badge role-admin">👑 Admin</span>';
                    } 
                    else if (data === 'staff') {
                        return '<span class="role-badge role-staff">👨‍🍳 Staff</span>';
                    } 
                    else if (data === 'customer') {
                        return '<span class="role-badge role-customer">🛒 Customer</span>';
                    } 
                    else {
                        return '<span class="role-badge bg-secondary">Unknown</span>';
                    }
                }
            },

            // 🔥 ACTION BUTTONS
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function (id) {

                    let editUrl = "{{ route('admin.users.edit', '__id__') }}".replace('__id__', id);
                    let deleteUrl = "{{ route('admin.users.delete', '__id__') }}".replace('__id__', id);

                    return `
                        <a href="${editUrl}" class="btn btn-sm btn-info me-1 shadow-sm">
                            ✏ Edit
                        </a>

                        <button class="btn btn-sm btn-danger shadow-sm deleteUser"
                            data-id="${id}"
                            data-url="${deleteUrl}">
                            🗑 Delete
                        </button>
                    `;
                }
            }
        ]
    });


    // 🔥 ROW CLICK EFFECT
    $('#userTable tbody').on('click', 'tr', function () {
        $(this).toggleClass('table-active');
    });


    // 🔥 DELETE USER
    $(document).on('click', '.deleteUser', function () {

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
                            text: response.message || 'User deleted successfully',
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

    });

});
</script>

@endsection