@extends('layout.retailer')

@section('content')

<div class="container-fluid aadhaar-show-page">

{{-- HEADER --}}
<div class="show-header">

    <div>

        <h2 class="show-title">

            Voter-Id Service Details

        </h2>

        <p class="show-subtitle">

            View complete Voter-Id service application information.

        </p>

    </div>

    <div>

        <a
            href="{{ route('retailer.voter-id.history') }}"
            class="btn show-btn-dark"
        >

            <i class="fas fa-arrow-left me-2"></i>

            Back

        </a>

    </div>

</div>

{{-- STATUS CARD --}}
<div class="card show-card mb-4">

    <div class="card-body">

        <div class="row g-4">

            <div class="col-lg-3 col-md-6">

                <div class="status-box">

                    <label>

                        Application No

                    </label>

                    <h5>

                        {{ $application->application_no }}

                    </h5>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="status-box">

                    <label>

                        Status

                    </label>

                    <div>

                        {!! $application->status_badge !!}

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="status-box">

                    <label>

                        Payment Status

                    </label>

                    <div>

                        {!! $application->payment_badge !!}

                    </div>

                </div>

            </div>

            <div class="col-lg-3 col-md-6">

                <div class="status-box">

                    <label>

                        Submitted Date

                    </label>

                    <h5>

                        {{ $application->created_at->format('d M Y h:i A') }}

                    </h5>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- SERVICE DETAILS --}}
<div class="card show-card mb-4">

    <div class="card-header show-card-header">

        Application Details

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="show-box">

                    <label>

                        Service Name

                    </label>

                    <h6>

                        {{ $application->service_name }}

                    </h6>

                </div>

            </div>

            @foreach(($application->form_data ?? []) as $field => $value)

                @if($field !== 'remarks')

                    <div class="col-md-4">

                        <div class="show-box">

                            <label>

                                {{ ucwords(str_replace('_',' ', $field)) }}

                            </label>

                            <h6>

                                {{ $value ?: 'N/A' }}

                            </h6>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    </div>

</div>

{{-- REMARKS --}}
@if(!empty($application->form_data['remarks']))

    <div class="card show-card mb-4">

        <div class="card-header show-card-header">

            Remarks

        </div>

        <div class="card-body">

            <div class="show-box">

                <h6>

                    {{ $application->form_data['remarks'] }}

                </h6>

            </div>

        </div>

    </div>

@endif

{{-- DOCUMENTS --}}
<div class="card show-card mb-4">

    <div class="card-header show-card-header">

        Uploaded Documents

    </div>

    <div class="card-body">

        <div class="row g-4">

            @forelse($application->documents ?? [] as $title => $file)

                <div class="col-lg-4">

                    <div class="document-card">

                        <div class="document-title">

                            {{ ucwords(str_replace('_',' ', $title)) }}

                        </div>

                        <div class="document-preview">

                            @php

                                $ext = strtolower(
                                    pathinfo(
                                        $file,
                                        PATHINFO_EXTENSION
                                    )
                                );

                            @endphp

                            @if(
                                in_array(
                                    $ext,
                                    [
                                        'jpg',
                                        'jpeg',
                                        'png',
                                        'webp'
                                    ]
                                )
                            )

                                <a
                                    href="{{ file_url($file) }}"
                                    target="_blank"
                                >

                                    <img
                                        src="{{ file_url($file) }}"
                                        class="img-fluid rounded"
                                        alt="{{ $title }}"
                                        loading="lazy"
                                    >

                                </a>

                            @else

                                <div class="text-center p-4">

                                    <a
                                        href="{{ file_url($file) }}"
                                        target="_blank"
                                        class="btn btn-danger"
                                    >

                                        <i class="fas fa-file-pdf me-2"></i>

                                        View PDF

                                    </a>

                                </div>

                            @endif

                        </div>

                        <div class="document-footer">

                            <a
                                href="{{ file_url($file) }}"
                                target="_blank"
                                class="btn document-btn"
                            >

                                <i class="fas fa-eye me-2"></i>

                                View Document

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-light border">

                        No documents uploaded.

                    </div>

                </div>

            @endforelse

        </div>

    </div>

</div>


</div>

@endsection
