@extends('layout.retailer')

@section('content')

<div class="container-fluid py-4">

<div class="card border-0 shadow-lg rounded-4 overflow-hidden">

    <div class="card-body p-5 text-center">

        {{-- SUCCESS ICON --}}
        <div class="mb-4">

            <div class="success-icon mx-auto">

                <i class="fas fa-check"></i>

            </div>

        </div>

        {{-- TITLE --}}
        <h2 class="fw-bold text-success mb-3">

            Aadhaar Service Submitted Successfully

        </h2>

        <p class="text-muted fs-5 mb-5">

            Your Aadhaar service request has been submitted successfully.

        </p>

        {{-- APPLICATION DETAILS --}}
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

                        Service Name

                    </label>

                    <h5>

                        {{ $application->service_name }}

                    </h5>

                </div>

            </div>

            @foreach(($application->form_data ?? []) as $key => $value)

                @if(!empty($value))

                    <div class="col-md-6">

                        <div class="ack-box">

                            <label>

                                {{ ucwords(str_replace('_', ' ', $key)) }}

                            </label>

                            <h5>

                                @if($key === 'aadhaar_number')

                                    XXXXXXXX{{ substr($value,-4) }}

                                @else

                                    {{ $value }}

                                @endif

                            </h5>

                        </div>

                    </div>

                @endif

            @endforeach

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

                        Amount Paid

                    </label>

                    <h5 class="text-success">

                        ₹{{ number_format($application->amount,2) }}

                    </h5>

                </div>

            </div>

            <div class="col-md-6">

                <div class="ack-box">

                    <label>

                        Submitted At

                    </label>

                    <h5>

                        {{ $application->created_at->format('d M Y h:i A') }}

                    </h5>

                </div>

            </div>

        </div>

        {{-- DOCUMENTS --}}
        @if(!empty($application->documents))

            <div class="mt-5">

                <h4 class="fw-bold mb-4">

                    Uploaded Documents

                </h4>

                <div class="row g-4">

                    @foreach($application->documents as $title => $file)

                        <div class="col-md-4">

                            <div class="card border shadow-sm h-100">

                                <div class="card-body text-center">

                                    <h6 class="mb-3">

                                        {{ ucwords(str_replace('_',' ', $title)) }}

                                    </h6>

                                    <a
                                        href="{{ file_url($file) }}"
                                        target="_blank"
                                        class="btn btn-outline-primary"
                                    >

                                        <i class="fa fa-eye me-2"></i>

                                        View Document

                                    </a>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

        {{-- ACTION BUTTONS --}}
        <div class="d-flex flex-wrap justify-content-center gap-3 mt-5">

            <a
                href="{{ route('retailer.aadhaar.print', $application->id) }}"
                class="btn btn-primary btn-lg px-5 rounded-pill"
            >

                <i class="fa fa-print me-2"></i>

                Print Application

            </a>

            <a
                href="{{ route('retailer.aadhaar.history') }}"
                class="btn btn-dark btn-lg px-5 rounded-pill"
            >

                <i class="fa fa-list me-2"></i>

                Application History

            </a>

        </div>

    </div>

</div>


</div>

@endsection
