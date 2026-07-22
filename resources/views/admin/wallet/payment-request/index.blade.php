@extends('layout.admin')

@section('title','Payment Requests')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">
                <i class="fas fa-qrcode me-2"></i>
                Payment Requests
            </h4>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped align-middle"
                       id="paymentRequestTable">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>Retailer</th>

                            <th>Amount</th>

                            <th>UTR</th>

                            <th>Status</th>

                            <th>Submitted</th>

                            <th>Action</th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>

$(function(){

    let table = $('#paymentRequestTable').DataTable({

        processing:true,

        serverSide:true,

        responsive:true,

        ajax:"{{ route('admin.wallet.payment-requests') }}",

        columns:[

            {
                data:'DT_RowIndex',
                name:'DT_RowIndex',
                searchable:false,
                orderable:false
            },

            {
                data:'retailer',
                name:'retailer',
                orderable:false
            },

            {
                data:'amount',
                name:'amount'
            },

            {
                data:'utr',
                name:'utr',
                defaultContent:'-'
            },

            {
                data:'status',
                name:'status',
                searchable:false,
                orderable:false
            },

            {
                data:'created_at',
                name:'created_at'
            },

            {
                data:'action',
                name:'action',
                searchable:false,
                orderable:false
            }

        ],

        pageLength:10,

        order:[[5,'desc']]

    });



    /*
    |--------------------------------------------------------------------------
    | Approve Payment
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.btn-approve',function(){

        let url = $(this).data('url');

        Swal.fire({

            title:'Approve Payment',

            input:'textarea',

            inputLabel:'Admin Remarks',

            inputPlaceholder:'Enter remarks (optional)',

            showCancelButton:true,

            confirmButtonText:'Approve',

            cancelButtonText:'Cancel',

            confirmButtonColor:'#198754'

        }).then((result)=>{

            if(!result.isConfirmed){
                return;
            }

            Swal.fire({

                title:'Processing...',

                allowOutsideClick:false,

                didOpen:()=>{
                    Swal.showLoading();
                }

            });

            $.ajax({

                url:url,

                type:'POST',

                data:{
                    _token:"{{ csrf_token() }}",
                    admin_remarks:result.value
                },

                success:function(response){

                    Swal.fire({

                        icon:'success',

                        title:'Success',

                        text:response.message

                    });

                    table.ajax.reload(null,false);

                },

                error:function(xhr){

                    Swal.fire({

                        icon:'error',

                        title:'Error',

                        text:xhr.responseJSON?.message ?? 'Something went wrong.'

                    });

                }

            });

        });

    });



    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.btn-reject',function(){

        let url=$(this).data('url');

        Swal.fire({

            title:'Reject Payment',

            input:'textarea',

            inputLabel:'Reason',

            inputPlaceholder:'Reason for rejection',

            inputValidator:(value)=>{

                if(!value){

                    return 'Reason is required';

                }

            },

            showCancelButton:true,

            confirmButtonText:'Reject',

            confirmButtonColor:'#dc3545'

        }).then((result)=>{

            if(!result.isConfirmed){
                return;
            }

            Swal.fire({

                title:'Processing...',

                allowOutsideClick:false,

                didOpen:()=>{

                    Swal.showLoading();

                }

            });

            $.ajax({

                url:url,

                type:'POST',

                data:{

                    _token:"{{ csrf_token() }}",

                    admin_remarks:result.value

                },

                success:function(response){

                    Swal.fire({

                        icon:'success',

                        title:'Rejected',

                        text:response.message

                    });

                    table.ajax.reload(null,false);

                },

                error:function(xhr){

                    Swal.fire({

                        icon:'error',

                        title:'Error',

                        text:xhr.responseJSON?.message ?? 'Something went wrong.'

                    });

                }

            });

        });

    });

});

</script>

@endsection