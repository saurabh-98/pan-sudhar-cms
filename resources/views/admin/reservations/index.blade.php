@extends('layout.admin')

@section('content')

<style>

/* ===== CARD ===== */
.modern-card {
    background:#fff;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

/* ===== TABLE ===== */
.modern-table {
    border-collapse:separate;
    border-spacing:0 12px;
}

.modern-table thead th {
    background:#f8f9fa;
    padding:12px;
    border:none;
    font-size:13px;
    color:#666;
}

.modern-table tbody tr {
    background:#fff;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    transition:0.2s;
    border-radius:12px;
}

.modern-table tbody tr:hover {
    transform:translateY(-3px);
}

/* ===== BADGE ===== */
.badge-pill {
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    text-transform:capitalize;
}

.pending { background:#fff3cd; color:#856404; }
.confirmed { background:#d4edda; color:#155724; }
.cancelled { background:#f8d7da; color:#721c24; }

/* ===== BUTTON ===== */
.action-btn {
    border:none;
    border-radius:8px;
    padding:6px 10px;
    margin:0 2px;
    transition:0.2s;
}

.btn-success {
    background:#28a745;
    color:#fff;
}

.btn-danger {
    background:#dc3545;
    color:#fff;
}

.action-btn:hover {
    transform:scale(1.1);
}

/* ===== TEXT ===== */
.small-muted {
    font-size:12px;
    color:#888;
}

</style>


<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h3 class="fw-bold">📅 Reservation Management</h3>
    </div>

    <div class="modern-card p-4">

        <table id="reservationTable" class="modern-table w-100 text-center">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Guests</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Table</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

        </table>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let table = $('#reservationTable').DataTable({

    responsive:true,
    pageLength:10,

    ajax: "{{ route('admin.reservations.list') }}",

    columns: [

        { data: 'id' },

        { data: 'name' },

        {
            data: 'guests',
            render: g => `👥 ${g}`
        },

        /* ✅ FIXED DATE FORMAT */
        {
            data: 'date',
            render: function(d){

                let date = new Date(d);

                return date.toLocaleDateString('en-IN', {
                    day:'2-digit',
                    month:'short',
                    year:'numeric'
                });
            }
        },

        /* ✅ FIXED TIME FORMAT */
        {
            data: 'time',
            render: function(t){

                // handle ISO or normal
                let clean = t.includes('T') ? t.split('T')[1] : t;

                let time = new Date('1970-01-01T' + clean);

                return time.toLocaleTimeString('en-IN', {
                    hour:'2-digit',
                    minute:'2-digit',
                    hour12:true
                });
            }
        },

        {
            data: 'table',
            render: t => `🍽️ ${t ?? 'N/A'}`
        },

        {
            data: 'status',
            render: function(status){

                return `<span class="badge-pill ${status}">
                    ${status}
                </span>`;
            }
        },

        {
            data: 'id',
            render: function(id, type, row){

                return `
                    <button class="action-btn btn-success updateStatus"
                        data-id="${id}" data-status="confirmed">
                        ✔
                    </button>

                    <button class="action-btn btn-danger updateStatus"
                        data-id="${id}" data-status="cancelled">
                        ✖
                    </button>
                `;
            }
        }

    ]

});


/* =========================
   STATUS UPDATE
========================= */
$(document).on('click','.updateStatus', function(){

    let id = $(this).data('id');
    let status = $(this).data('status');

    $.post(`/admin/reservations/status/${id}`,{
        _token:"{{ csrf_token() }}",
        status:status
    }, function(){

        Swal.fire({
            toast:true,
            position:'top-end',
            icon:'success',
            title:'Status updated',
            timer:1500,
            showConfirmButton:false
        });

        table.ajax.reload();

    });

});

</script>

@endsection