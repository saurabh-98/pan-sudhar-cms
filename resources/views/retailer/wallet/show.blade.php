@extends('layout.retailer')

@section('title','Recharge Details')


@section('content')

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-10 mx-auto">

            <div class="recharge-details-card">

                <div class="details-header">

                    <div>

                        <h3>

                            <i class="fas fa-wallet"></i>

                            Recharge Request #{{ $payment->id }}

                        </h3>

                        <p>

                            Submitted on

                            {{ $payment->created_at->format('d M Y h:i A') }}

                        </p>

                    </div>

                    <div>

                        <a href="{{ route('retailer.wallet.recharge-history') }}"
                           class="btn btn-light back-btn">

                            <i class="fas fa-arrow-left"></i>

                            Back

                        </a>

                    </div>

                </div>


                <div class="row mt-4">

                    <div class="col-lg-8">

                        <div class="detail-card">

                            <h5>

                                Payment Information

                            </h5>

                            <div class="detail-row">

                                <span>Amount</span>

                                <strong class="amount">

                                    ₹{{ number_format($payment->amount,2) }}

                                </strong>

                            </div>

                            <div class="detail-row">

                                <span>UPI ID</span>

                                <strong>

                                    {{ $payment->upi_id }}

                                </strong>

                            </div>

                            <div class="detail-row">

                                <span>Merchant</span>

                                <strong>

                                    {{ $payment->merchant_name }}

                                </strong>

                            </div>

                            <div class="detail-row">

                                <span>Transaction ID</span>

                                <strong>

                                    {{ $payment->utr ?: '-' }}

                                </strong>

                            </div>

                            <div class="detail-row">

                                <span>Verified At</span>

                                <strong>

                                    {{ $payment->verified_at ? $payment->verified_at->format('d M Y h:i A') : 'Not Verified Yet' }}

                                </strong>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-4">

                        <div class="status-card">

                            <h6>Status</h6>

                            @if($payment->status=='approved')

                                <span class="status-badge status-approved">

                                    Approved

                                </span>

                            @elseif($payment->status=='pending')

                                <span class="status-badge status-pending">

                                    Pending

                                </span>

                            @else

                                <span class="status-badge status-rejected">

                                    Rejected

                                </span>

                            @endif

                        </div>

                        <div class="status-card mt-3">

                            <h6>Admin Remarks</h6>

                            <p>

                                {{ $payment->admin_remarks ?: 'No Remarks' }}

                            </p>

                        </div>

                    </div>

                </div>


                <div class="detail-card mt-4">

                    <h5>

                        Remarks

                    </h5>

                    <p>

                        {{ $payment->remarks ?: 'No Remarks' }}

                    </p>

                </div>


                <div class="detail-card mt-4">

                    <h5>

                        Payment Screenshot

                    </h5>

                    @if($payment->screenshot)

                        <div class="text-center">

                            <a href="{{ asset($payment->screenshot) }}"
                               target="_blank">

                                <img src="{{ asset($payment->screenshot) }}"
                                     class="payment-image">

                            </a>

                        </div>

                    @else

                        <div class="empty-image">

                            <i class="fas fa-image"></i>

                            <p>No Screenshot Uploaded</p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection