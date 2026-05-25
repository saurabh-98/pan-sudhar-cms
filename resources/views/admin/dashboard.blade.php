@extends('layout.admin')

@section('title', 'Dashboard')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    $isExecutive = auth()->user()->hasRole('Executive');

@endphp

<div class="container-fluid dashboard">

    {{-- =========================================================
    | HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div>

            <h3 class="fw-bold mb-1">

                <i class="fa fa-chart-line me-2 text-primary"></i>

                {{ $isExecutive ? 'Executive Dashboard' : 'Admin Dashboard' }}

            </h3>

            <p class="text-muted mb-0">

                Welcome back,
                {{ auth()->user()->name }}

            </p>

        </div>

        <div class="dashboard-date-box">

            <i class="fa fa-calendar-alt me-2"></i>

            {{ now()->format('d M Y') }}

        </div>

    </div>


    {{-- =========================================================
    | MAIN KPI CARDS
    ========================================================== --}}
    <div class="row g-4">

        {{-- TOTAL PAN --}}
        <div class="col-xl-3 col-md-6 col-12">

            <a
                href="{{ route('admin.pan.index') }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-primary">

                    <div>

                        <h6>

                            PAN Applications

                        </h6>

                        <h2 class="counter">

                            {{ $totalPanApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-id-card"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- TOTAL ITR --}}
        <div class="col-xl-3 col-md-6 col-12">

            <a
                href="{{ route('admin.itr.index') }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-success">

                    <div>

                        <h6>

                            ITR Applications

                        </h6>

                        <h2 class="counter">

                            {{ $totalItrApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-file-invoice-dollar"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- ASSIGNED --}}
        <div class="col-xl-3 col-md-6 col-12">

            <a
                href="{{ route('admin.pan.index', ['filter' => 'assigned']) }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-warning">

                    <div>

                        <h6>

                            Assigned Applications

                        </h6>

                        <h2 class="counter">

                            {{ $assignedApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-user-check"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- COMPLETED --}}
        <div class="col-xl-3 col-md-6 col-12">

            <a
                href="{{ route('admin.pan.index', ['status' => 'completed']) }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-info">

                    <div>

                        <h6>

                            Completed Services

                        </h6>

                        <h2 class="counter">

                            {{ $completedApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-check-circle"></i>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- =========================================================
    | SECOND ROW
    ========================================================== --}}
    <div class="row mt-4 g-4">

        {{-- FRESH --}}
        <div class="col-xl-4 col-md-6 col-12">

            <a
                href="{{ route('admin.pan.index', ['filter' => 'fresh']) }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-dark">

                    <div>

                        <h6>

                            Fresh Applications

                        </h6>

                        <h2 class="counter">

                            {{ $freshApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-bolt"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- PROCESSING --}}
        <div class="col-xl-4 col-md-6 col-12">

            <a
                href="{{ route('admin.pan.index', ['status' => 'processing']) }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-secondary">

                    <div>

                        <h6>

                            Processing

                        </h6>

                        <h2 class="counter">

                            {{ $processingApplications ?? 0 }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-spinner"></i>

                    </div>

                </div>

            </a>

        </div>

        {{-- TOTAL EARNINGS --}}
        <div class="col-xl-4 col-md-12 col-12">

            <a
                href="{{ route('admin.wallet.transactions') }}"
                class="dashboard-link-card"
            >

                <div class="dash-card-modern bg-danger">

                    <div>

                        <h6>

                            {{ $isExecutive ? 'Commission Earned' : 'Total Revenue' }}

                        </h6>

                        <h2 class="counter revenue-counter">

                            {{ number_format($totalRevenue ?? 0, 2) }}

                        </h2>

                    </div>

                    <div class="icon">

                        <i class="fa fa-wallet"></i>

                    </div>

                </div>

            </a>

        </div>

    </div>


    {{-- =========================================================
    | CHART SECTION
    ========================================================== --}}
    <div class="row mt-4">

        <div class="col-xl-8 col-12">

            <div class="card shadow-sm border-0 dashboard-chart-card">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0 fw-bold">

                        <i class="fa fa-chart-area me-2 text-primary"></i>

                        Service Overview

                    </h5>

                </div>

                <div class="card-body">

                    <canvas id="serviceChart"></canvas>

                </div>

            </div>

        </div>

        {{-- QUICK STATS --}}
        <div class="col-xl-4 col-12">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white border-0">

                    <h5 class="mb-0 fw-bold">

                        <i class="fa fa-layer-group me-2 text-success"></i>

                        Quick Statistics

                    </h5>

                </div>

                <div class="card-body">

                    <div class="quick-stat-item">

                        <span>

                            Pending Applications

                        </span>

                        <strong>

                            {{ $pendingApplications ?? 0 }}

                        </strong>

                    </div>

                    <div class="quick-stat-item">

                        <span>

                            Approved Applications

                        </span>

                        <strong>

                            {{ $approvedApplications ?? 0 }}

                        </strong>

                    </div>

                    <div class="quick-stat-item">

                        <span>

                            Rejected Applications

                        </span>

                        <strong>

                            {{ $rejectedApplications ?? 0 }}

                        </strong>

                    </div>

                    <div class="quick-stat-item">

                        <span>

                            Today's Uploads

                        </span>

                        <strong>

                            {{ $todayUploads ?? 0 }}

                        </strong>

                    </div>

                    <div class="quick-stat-item border-0">

                        <span>

                            Wallet Balance

                        </span>

                        <strong class="text-success">

                            ₹{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/*
|--------------------------------------------------------------------------
| SERVICE CHART
|--------------------------------------------------------------------------
*/

new Chart(document.getElementById('serviceChart'), {

    type: 'line',

    data: {

        labels: @json($months ?? []),

        datasets: [{

            label: 'Applications',

            data: @json($chartData ?? []),

            fill: true,

            tension: 0.4

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                display: false

            }

        }

    }

});


/*
|--------------------------------------------------------------------------
| COUNTER ANIMATION
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.counter').forEach(counter => {

    let target = +counter.innerText
        .replace(/,/g,'')
        .replace('₹','');

    let count = 0;

    let speed = target / 40;

    let update = () => {

        count += speed;

        if(count < target){

            counter.innerText = Math.floor(count)
                .toLocaleString();

            requestAnimationFrame(update);

        } else {

            counter.innerText = target
                .toLocaleString();

        }

    };

    update();

});

</script>

@endsection