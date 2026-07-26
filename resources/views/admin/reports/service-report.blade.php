@extends('layout.admin')

@section('title',$title)

@section('content')

<div class="container-fluid">

    <div class="card report-card">

        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0">

                    {{ $title }}

                </h4>

                <div>

                    <button
                        class="btn btn-light btn-sm"
                        id="btnFilter"
                    >

                        <i class="fa fa-filter"></i>

                        Filter

                    </button>

                </div>

            </div>

        </div>

        <div class="card-body">

            <form id="filterForm">

                <div class="row">

                    <div class="col-md-2">

                        <label>From</label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-2">

                        <label>To</label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                        >

                    </div>

                    <div class="col-md-2">

                        <label>Status</label>

                        <select
                            name="status"
                            class="form-control"
                        >

                            <option value="">All</option>

                            <option value="Pending">Pending</option>

                            <option value="Processing">Processing</option>

                            <option value="Approved">Approved</option>

                            <option value="Rejected">Rejected</option>

                            <option value="Completed">Completed</option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>Payment</label>

                        <select
                            name="payment_status"
                            class="form-control"
                        >

                            <option value="">All</option>

                            <option value="Paid">

                                Paid

                            </option>

                            <option value="Pending">

                                Pending

                            </option>

                            <option value="Failed">

                                Failed

                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>Retailer</label>

                        <select
                            name="user_id"
                            class="form-control"
                        >

                            <option value="">

                                All

                            </option>

                            @foreach($users as $user)

                                <option value="{{ $user->id }}">

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label>Executive</label>

                        <select
                            name="assigned_to"
                            class="form-control"
                        >

                            <option value="">

                                All

                            </option>

                            @foreach($executives as $executive)

                                <option value="{{ $executive->id }}">

                                    {{ $executive->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Distributor filter was missing: $distributors is
                         passed by the controller but no filter used it,
                         so the "Distributor" column had no way to be
                         filtered from this page. --}}
                    <div class="col-md-2 mt-2">

                        <label>Distributor</label>

                        <select
                            name="distributor_id"
                            class="form-control"
                        >

                            <option value="">

                                All

                            </option>

                            @foreach($distributors as $distributor)

                                <option value="{{ $distributor->id }}">

                                    {{ $distributor->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="row mt-3">

        <div class="col-md-3">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3 id="applications">

                        0

                    </h3>

                    <p>Total Applications</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box bg-success">

                <div class="inner">

                    <h3 id="collection">

                        ₹0

                    </h3>

                    <p>Total Collection</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box bg-warning">

                <div class="inner">

                    <h3 id="approved">

                        0

                    </h3>

                    <p>Approved</p>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="small-box bg-danger">

                <div class="inner">

                    <h3 id="pending">

                        0

                    </h3>

                    <p>Pending</p>

                </div>

            </div>

        </div>

    </div>

    <div class="card report-card">
        <div class="card-body">

            <div class="table-responsive report-table-wrapper">

                <table
                    id="reportTable"
                    class="table table-hover table-bordered align-middle w-100"
                >
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Application</th>
                            <th>Customer</th>
                            <th>Retailer</th>
                            <th>Distributor</th>
                            <th>Executive</th>
                            <th width="120">Amount</th>
                            <th width="120">Payment</th>
                            <th width="120">Status</th>
                            <th width="160">Date</th>
                            <th width="90" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                </table>

            </div>

    </div>
</div>
</div>

@endsection


@section('scripts')

<script>

let table = $('#reportTable').DataTable({

    processing:true,

    serverSide:true,

    responsive:true,

    ajax:{

        url:"{{ route('admin.reports.data',$service) }}",

        data:function(d){

            d.from = $('[name=from]').val();

            d.to = $('[name=to]').val();

            d.status = $('[name=status]').val();

            d.payment_status = $('[name=payment_status]').val();

            d.user_id = $('[name=user_id]').val();

            d.assigned_to = $('[name=assigned_to]').val();

            // was missing: the controller's distributorFilter() reads
            // distributor_id, but it was never sent from this form.
            d.distributor_id = $('[name=distributor_id]').val();

        }

    },

    columns:[

        {

            data:'DT_RowIndex',

            searchable:false,

            orderable:false

        },

        {

            data:'application'

        },

        {

            data:'customer'

        },

        {

            data:'retailer'

        },

        {

            data:'distributor'

        },

        {

            data:'executive'

        },

        {

            data:'amount'

        },

        {

            data:'payment'

        },

        {

            data:'status'

        },

        {

            data:'created_at'

        },

        {

            data:'action',

            searchable:false,

            orderable:false

        }

    ],

    drawCallback:function(){

        let json = table.ajax.json();

        if(json.summary){

            $('#applications').html(

                json.summary.applications

            );

            $('#collection').html(

                '₹'+json.summary.amount

            );

            $('#approved').html(

                json.summary.approved

            );

            $('#pending').html(

                json.summary.pending

            );

        }

    }

});


$('#filterForm select,#filterForm input').change(function(){

    table.draw();

});

</script>

@endsection