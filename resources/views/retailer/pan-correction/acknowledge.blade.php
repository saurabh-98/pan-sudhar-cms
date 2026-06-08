@extends('layout.retailer')

@section('content')

<div class="container-fluid py-4">

    {{-- =====================================================
    | SUCCESS CARD
    ====================================================== --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <div class="card-body p-5 text-center">

            {{-- ICON --}}
            <div class="mb-4">

                <div class="success-icon mx-auto">

                    <i class="fas fa-check"></i>

                </div>

            </div>

            {{-- TITLE --}}
            <h2 class="fw-bold text-success mb-3">

                PAN Correction Application Submitted Successfully

            </h2>

            <p class="text-muted fs-5 mb-5">

                Your PAN Correction application has been submitted successfully.

            </p>

            {{-- =====================================================
            | APPLICATION DETAILS
            ====================================================== --}}
            <div class="row g-4 text-start">

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>

                            Application Number

                        </label>

                        <h5>

                            {{ $application->application_no }}

                        </h5>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>
                            Applicant Name
                        </label>

                        <h5>
                            {{ trim(
                                ($application->first_name ?? '') . ' ' .
                                ($application->middle_name ?? '') . ' ' .
                                ($application->last_name ?? '')
                            ) }}
                        </h5>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>

                            Mobile Number

                        </label>

                        <h5>

                            {{ $application->mobile_no }}

                        </h5>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>

                            Status

                        </label>

                        <h5>

                            {!! $application->status_badge !!}

                        </h5>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>

                            Payment Status

                        </label>

                        <h5>

                            {!! $application->payment_badge !!}

                        </h5>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="ack-box">

                        <label>

                            Submitted At

                        </label>

                        <h5>

                            {{ $application->created_at }}

                        </h5>

                    </div>

                </div>

            </div>

            {{-- =====================================================
            | ACTION BUTTONS
            ====================================================== --}}
            <div class="d-flex flex-wrap justify-content-center gap-3 mt-5">

                <a
                    href="{{ route('retailer.pan-correction.print', $application->id) }}"
                    class="btn btn-primary btn-lg px-5 rounded-pill"
                >

                    Print Application

                </a>

                <a
                    href="{{ route('retailer.pan-correction.history') }}"
                    class="btn btn-dark btn-lg px-5 rounded-pill"
                >

                    Application History

                </a>

            </div>

        </div>

    </div>

</div>


@endsection