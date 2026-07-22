@extends('layout.admin')

@section('title','Payment Request Details')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-10">
            

            <div class="card shadow">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">

                        <i class="fas fa-qrcode me-2"></i>

                        Payment Request Details

                    </h4>

                    <a href="{{ route('admin.wallet.payment-requests') }}"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

                <div class="card-body">

                    <div class="row">

                        {{-- Left Side --}}
                        <div class="col-lg-6">

                            <table class="table table-bordered">

                                <tr>

                                    <th width="35%">Request ID</th>

                                    <td>#{{ $payment->id }}</td>

                                </tr>

                                <tr>

                                    <th>Retailer</th>

                                    <td>

                                        {{ $payment->retailer->name }}

                                        <br>

                                        <small class="text-muted">

                                            {{ $payment->retailer->mobile }}

                                        </small>

                                    </td>

                                </tr>

                                <tr>

                                    <th>Amount</th>

                                    <td>

                                        <span class="text-success fw-bold">

                                            ₹{{ number_format($payment->amount,2) }}

                                        </span>

                                    </td>

                                </tr>

                                <tr>

                                    <th>UPI ID</th>

                                    <td>

                                        {{ $payment->upi_id }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>Merchant</th>

                                    <td>

                                        {{ $payment->merchant_name }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>UTR</th>

                                    <td>

                                        {{ $payment->utr ?: '-' }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>Status</th>

                                    <td>

                                        @if($payment->status=='approved')

                                            <span class="badge bg-success">

                                                Approved

                                            </span>

                                        @elseif($payment->status=='pending')

                                            <span class="badge bg-warning">

                                                Pending

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Rejected

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                                <tr>

                                    <th>Submitted</th>

                                    <td>

                                        {{ $payment->created_at->format('d M Y h:i A') }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>Verified</th>

                                    <td>

                                        {{ $payment->verified_at ? $payment->verified_at->format('d M Y h:i A') : '-' }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>Retailer Remarks</th>

                                    <td>

                                        {{ $payment->remarks ?: '-' }}

                                    </td>

                                </tr>

                                <tr>

                                    <th>Admin Remarks</th>

                                    <td>

                                        {{ $payment->admin_remarks ?: '-' }}

                                    </td>

                                </tr>

                            </table>

                        </div>

                        {{-- Right Side --}}
                        <div class="col-lg-6">

                            <div class="card">

                                <div class="card-header">

                                    Payment Screenshot

                                </div>

                                <div class="card-body text-center">

                                    @if($payment->screenshot)

                                        <a href="{{ asset($payment->screenshot) }}"
                                           target="_blank">

                                            <img src="{{ asset($payment->screenshot) }}"
                                                 class="img-fluid img-thumbnail"
                                                 style="max-height:450px;">

                                        </a>

                                    @else

                                        <div class="alert alert-warning mb-0">

                                            Screenshot Not Uploaded

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    @if($payment->status=='pending')

                    <hr>

                    <div class="row">

                        <div class="col-lg-12">

                            <form id="approveForm"
                                  action="{{ route('admin.wallet.payment-request.approve',$payment->id) }}"
                                  method="POST">

                                @csrf

                                <div class="mb-3">

                                    <label>

                                        Admin Remarks

                                    </label>

                                    <textarea
                                        name="admin_remarks"
                                        rows="4"
                                        class="form-control"
                                        placeholder="Enter verification remarks..."></textarea>

                                </div>

                                <button
                                    type="button"
                                    id="approveBtn"
                                    class="btn btn-success">

                                    <i class="fas fa-check"></i>

                                    Approve Payment

                                </button>

                            </form>

                            <form id="rejectForm"
                                  class="mt-2"
                                  action="{{ route('admin.wallet.payment-request.reject',$payment->id) }}"
                                  method="POST">

                                @csrf

                                <input
                                    type="hidden"
                                    name="admin_remarks"
                                    id="rejectRemarks">

                                <button
                                    type="button"
                                    id="rejectBtn"
                                    class="btn btn-danger">

                                    <i class="fas fa-times"></i>

                                    Reject Payment

                                </button>

                            </form>

                        </div>

                    </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

$(function(){

    // ============================
    // APPROVE PAYMENT
    // ============================

    $('#approveBtn').click(function(){

        Swal.fire({

            title:'Approve Payment?',

            text:'Wallet balance / due amount will be adjusted.',

            icon:'question',

            showCancelButton:true,

            confirmButtonColor:'#198754',

            confirmButtonText:'Approve'

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

                url:$('#approveForm').attr('action'),

                type:'POST',

                data:$('#approveForm').serialize(),

                success:function(response){

                    Swal.fire({

                        icon:'success',

                        title:'Success',

                        text:response.message

                    }).then(()=>{

                        window.location.href="{{ route('admin.wallet.payment-requests') }}";

                    });

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


    // ============================
    // REJECT PAYMENT
    // ============================

    $('#rejectBtn').click(function(){

        Swal.fire({

            title:'Reject Payment',

            input:'textarea',

            inputPlaceholder:'Enter rejection reason...',

            inputValidator:(value)=>{

                if(!value){
                    return 'Remarks are required';
                }

            },

            showCancelButton:true,

            confirmButtonColor:'#dc3545',

            confirmButtonText:'Reject'

        }).then((result)=>{

            if(!result.isConfirmed){
                return;
            }

            $('#rejectRemarks').val(result.value);

            Swal.fire({

                title:'Processing...',

                allowOutsideClick:false,

                didOpen:()=>{
                    Swal.showLoading();
                }

            });

            $.ajax({

                url:$('#rejectForm').attr('action'),

                type:'POST',

                data:$('#rejectForm').serialize(),

                success:function(response){

                    Swal.fire({

                        icon:'success',

                        title:'Rejected',

                        text:response.message

                    }).then(()=>{

                        window.location.href="{{ route('admin.wallet.payment-requests') }}";

                    });

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
