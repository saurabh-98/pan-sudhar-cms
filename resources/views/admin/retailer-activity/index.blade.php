@extends('layout.admin')

@section('title', 'Retailer Activity')

@section('content')

<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-md-12">

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        <i class="fa fa-clock me-2"></i>
                        Retailer Activity
                    </h5>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover"
                            id="retailerActivityTable">

                            <thead>

                            <tr>

                                <th>#</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Status</th>
                                <th>Last Active</th>
                                <th>Today's Time</th>
                                <th>Total Hours</th>
                                <th>Active Days</th>

                            </tr>

                            </thead>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')


<script>

$(function () {

    $('#retailerActivityTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: "{{ route('admin.retailer-activity.datatable') }}",

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'name',
                name: 'name'
            },

            {
                data: 'mobile',
                name: 'mobile'
            },

            {
                data: 'status',
                name: 'status',
                orderable: false,
                searchable: false
            },

            {
                data: 'last_activity_at',
                name: 'last_activity_at'
            },

            {
                data: 'today_time',
                name: 'today_time'
            },

            {
                data: 'total_time',
                name: 'total_time'
            },

            {
                data: 'active_days',
                name: 'active_days'
            }

        ]

    });

});

</script>
@endsection

@section('styles')

<style>

/* ==========================================
   Page
========================================== */

.container-fluid{
    padding:25px;
}

/* ==========================================
   Card
========================================== */

.card{
    border:none;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.card-header{
    background:#ffffff;
    border-bottom:1px solid #edf2f7;
    padding:18px 25px;
}

.card-header h5{
    font-size:20px;
    font-weight:700;
    color:#2d3748;
    margin:0;
}

.card-header i{
    color:#0d6efd;
}



/* ==========================================
   Table
========================================== */

#retailerActivityTable{
    width:100% !important;
    border-collapse:separate;
    border-spacing:0;
}

#retailerActivityTable thead th{

    background:#0d6efd;
    color:#fff;

    border:none;

    font-size:14px;
    font-weight:600;

    padding:14px;

    text-transform:uppercase;
    letter-spacing:.5px;
}

#retailerActivityTable tbody td{

    vertical-align:middle;

    padding:14px;

    font-size:14px;

    border-bottom:1px solid #edf2f7;
}

#retailerActivityTable tbody tr{

    transition:.3s;
}

.avatar-circle{

    width:45px;
    height:45px;

    border-radius:50%;

    background:#0d6efd;

    color:#fff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:700;

    font-size:18px;

    flex-shrink:0;
}

.badge{

    padding:8px 12px;

    font-size:12px;

    border-radius:30px;
}

#retailerActivityTable tbody tr:hover{

    background:#f8fbff;
}

/* ==========================================
   Badge
========================================== */

.badge{

    padding:8px 14px;

    border-radius:30px;

    font-size:12px;

    font-weight:600;
}

.bg-success{

    background:#16a34a !important;
}

.bg-danger{

    background:#dc3545 !important;
}

/* ==========================================
   DataTables
========================================== */

.dataTables_wrapper .dataTables_filter input{

    border:1px solid #dee2e6;

    border-radius:10px;

    padding:8px 14px;

    margin-left:8px;

    outline:none;

    transition:.3s;
}

.dataTables_wrapper .dataTables_filter input:focus{

    border-color:#0d6efd;

    box-shadow:0 0 0 .15rem rgba(13,110,253,.15);
}

.dataTables_wrapper .dataTables_length select{

    border-radius:10px;

    padding:6px 10px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button{

    border-radius:8px !important;

    margin:2px;

    padding:6px 12px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current{

    background:#0d6efd !important;

    color:#fff !important;

    border:none !important;
}

.dataTables_wrapper .dataTables_info{

    font-size:14px;

    color:#6c757d;
}

.dataTables_processing{

    background:#fff;

    border-radius:10px;

    box-shadow:0 5px 20px rgba(0,0,0,.15);

    padding:15px;
}

/* ==========================================
   Responsive
========================================== */

@media(max-width:768px){

    .card-header{

        flex-direction:column;

        align-items:flex-start !important;

        gap:10px;
    }

    #retailerActivityTable thead th,
    #retailerActivityTable tbody td{

        font-size:13px;

        padding:10px;
    }

}

</style>

@endsection