@extends('layout.retailer')

@section('title', 'Recharge Wallet')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow">

                <div class="card-header">

                    <h4 class="mb-0">
                        Recharge Wallet
                    </h4>

                </div>

                <div class="card-body">

                    @if(session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>

                    @endif

                    @if(session('error'))

                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>

                    @endif

                  <form action="{{ route('retailer.wallet.submit-payment') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        {{-- Wallet Balance --}}
                        <div class="alert alert-success d-flex justify-content-between align-items-center">

                            <div>

                                <h5 class="mb-0">
                                    <i class="fas fa-wallet"></i>
                                    Wallet Balance
                                </h5>

                            </div>

                            <div>

                                <h4 class="mb-0 text-success">
                                    ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}
                                </h4>

                            </div>

                        </div>

                        {{-- Instructions --}}
                        <div class="alert alert-warning">

                            <h6 class="fw-bold">
                                Payment Instructions
                            </h6>

                            <ol class="mb-0">

                                <li>Enter recharge amount.</li>

                                <li>Click <strong>Generate QR</strong>.</li>

                                <li>Scan QR using any UPI App.</li>

                                <li>Complete payment.</li>

                                <li>Enter UTR or Upload Screenshot.</li>

                                <li>Submit your payment request.</li>

                            </ol>

                        </div>

                        {{-- Amount --}}
                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                Recharge Amount

                            </label>

                            <input type="number"
                                class="form-control form-control-lg"
                                name="amount"
                                id="amount"
                                placeholder="Enter Amount"
                                required>

                        </div>

                        <div class="text-center mb-4">

                            <button type="button"
                                    class="btn btn-primary btn-lg px-5"
                                    id="generateQR">

                                <i class="fas fa-qrcode"></i>

                                Generate QR

                            </button>

                        </div>

                        <div id="paymentSection" style="display:none;">

                            <div class="card border-0 shadow-sm">

                                <div class="card-body">

                                    <h5 class="text-center mb-4">

                                        Scan & Pay

                                    </h5>

                                    <div class="text-center mb-4">

                                        <img id="qrImage"
                                            src=""
                                            class="img-thumbnail p-3 bg-white"
                                            style="max-width:250px;">

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <div class="mb-3">

                                                <label class="fw-bold">

                                                    UPI ID

                                                </label>

                                                <div class="input-group">

                                                    <input type="text"
                                                        id="upiId"
                                                        class="form-control"
                                                        readonly>

                                                    <button type="button"
                                                            class="btn btn-outline-primary"
                                                            id="copyUpi">

                                                        <i class="fas fa-copy"></i>

                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                        <div class="col-md-6">

                                            <div class="mb-3">

                                                <label class="fw-bold">

                                                    Merchant Name

                                                </label>

                                                <input type="text"
                                                    id="merchantName"
                                                    class="form-control"
                                                    readonly>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="card mt-4">

                                <div class="card-header">

                                    <strong>

                                        Payment Details

                                    </strong>

                                </div>

                                <div class="card-body">

                                    <div class="mb-3">

                                        <label class="fw-bold">

                                            Transaction ID (UTR)

                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="utr"
                                            placeholder="Enter UTR Number">

                                    </div>

                                    <div class="mb-3">

                                        <label class="fw-bold">

                                            Upload Payment Screenshot

                                        </label>

                                        <input type="file"
                                            class="form-control"
                                            id="screenshot"
                                            name="screenshot">

                                    </div>

                                    <div class="text-center mb-3">

                                        <img id="preview"
                                            src=""
                                            class="img-thumbnail"
                                            style="display:none;max-width:250px;">

                                    </div>

                                    <div class="mb-3">

                                        <label class="fw-bold">

                                            Remarks

                                        </label>

                                        <textarea name="remarks"
                                                rows="4"
                                                class="form-control"
                                                placeholder="Enter Remarks"></textarea>

                                    </div>

                                    <button type="submit"
                                            class="btn btn-success btn-lg w-100">

                                        <i class="fas fa-paper-plane"></i>

                                        Submit Payment Request

                                    </button>

                                </div>

                            </div>

                        </div>

                    </form>


                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | GENERATE QR
    |--------------------------------------------------------------------------
    */

    $('#generateQR').click(function () {

        let amount = $('#amount').val();

        if (amount == '') {

            Swal.fire({
                icon: 'warning',
                title: 'Amount Required',
                text: 'Please enter recharge amount first.'
            });

            $('#amount').focus();

            return;
        }

        $.ajax({

            url: "{{ route('retailer.wallet.generate-qr') }}",

            type: "POST",

            data: {

                _token: "{{ csrf_token() }}",

                amount: amount

            },

            beforeSend: function () {

                $('#generateQR')
                    .prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin"></i> Generating...');

            },

            success: function (response) {

                $('#generateQR')
                    .prop('disabled', false)
                    .html('<i class="fa fa-qrcode"></i> Generate QR');

                if (response.success) {

                    $('#paymentSection').slideDown();

                    $('#upiId').val(response.upi.upi_id);

                    $('#merchantName').val(response.upi.name);

                    $('#qrImage')
                        .attr('src', response.qr)
                        .show();

                    Swal.fire({
                        icon: 'success',
                        title: 'QR Generated',
                        text: 'Scan the QR code and complete your payment.',
                        timer: 1800,
                        showConfirmButton: false
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });

                }

            },

            error: function () {

                $('#generateQR')
                    .prop('disabled', false)
                    .html('<i class="fa fa-qrcode"></i> Generate QR');

                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'Something went wrong.'
                });

            }

        });

    });


    /*
    |--------------------------------------------------------------------------
    | COPY UPI
    |--------------------------------------------------------------------------
    */

    $('#copyUpi').click(function () {

        navigator.clipboard.writeText($('#upiId').val());

        Swal.fire({
            icon: 'success',
            title: 'Copied',
            text: 'UPI ID copied successfully.',
            timer: 1500,
            showConfirmButton: false
        });

    });


    /*
    |--------------------------------------------------------------------------
    | SCREENSHOT PREVIEW
    |--------------------------------------------------------------------------
    */

    $('#screenshot').change(function () {

        let file = this.files[0];

        if (file) {

            $('#preview')
                .attr('src', URL.createObjectURL(file))
                .show();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | SUBMIT CONFIRMATION
    |--------------------------------------------------------------------------
    */

    $('form').submit(function (e) {

        e.preventDefault();

        let form = this;

        Swal.fire({

            title: 'Submit Payment Request?',

            text: 'After submission, it will be sent to the admin for verification.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonColor: '#198754',

            cancelButtonColor: '#dc3545',

            confirmButtonText: 'Yes, Submit',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (result.isConfirmed) {

                form.submit();

            }

        });

    });

});


/*
|--------------------------------------------------------------------------
| SUCCESS MESSAGE
|--------------------------------------------------------------------------
*/

@if(session('success'))

Swal.fire({

    icon: 'success',

    title: 'Success',

    text: "{{ session('success') }}"

});

@endif


/*
|--------------------------------------------------------------------------
| ERROR MESSAGE
|--------------------------------------------------------------------------
*/

@if(session('error'))

Swal.fire({

    icon: 'error',

    title: 'Error',

    text: "{{ session('error') }}"

});

@endif

</script>

@endsection