@extends('layout.admin')

@section('title','Dashboard')

@section('content')

@php

    $user = auth()->user();

    $isAdmin = $user->hasRole('admin');

    $isSuperDistributor = $user->hasRole('Super Distributor');

    $isDistributor = $user->hasRole('Distributor');

    $isExecutive = $user->hasRole('Executive');

@endphp

{{-- ========================================================= --}}
{{-- SUPER DISTRIBUTOR DASHBOARD --}}
{{-- ========================================================= --}}

@if($isSuperDistributor)

<div class="container-fluid">

    <div class="row mb-4">

        <div class="col-lg-12">

            <div class="card border-0 shadow-sm">

                <div class="card-body d-flex justify-content-between align-items-center">

                    <div>

                        <h2 class="fw-bold mb-2">

                            <i class="fa fa-sitemap text-primary me-2"></i>

                            Super Distributor Dashboard

                        </h2>

                        <p class="text-muted mb-0">

                            Welcome,

                            <strong>{{ $user->name }}</strong>

                        </p>

                    </div>

                    <div>

                        <img src="{{ asset('assets/images/dashboard.svg') }}"
                             alt="Dashboard"
                             style="max-height:80px;"
                             onerror="this.style.display='none'">

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="row justify-content-center">

        <div class="col-xl-4 col-lg-5 col-md-6">

            <div class="dash-card-modern bg-primary">

                <div>

                    <small class="text-white">

                        My Distributors

                    </small>

                    <h2 class="counter text-white">

                        {{ $totalDistributors }}

                    </h2>

                </div>

                <i class="fa fa-network-wired fa-3x text-white"></i>

            </div>

        </div>

    </div>

</div>

@else

{{-- ========================================================= --}}
{{-- ADMIN / DISTRIBUTOR / EXECUTIVE DASHBOARD --}}
{{-- ========================================================= --}}

<div class="container-fluid">

    {{-- ========================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================= --}}

    <div class="row mb-4">

        <div class="col-lg-8">

            <h2 class="fw-bold mb-1">

                @if($isAdmin)

                    <i class="fa fa-user-shield text-primary"></i>

                    Admin Dashboard

                @elseif($isDistributor)

                    <i class="fa fa-network-wired text-success"></i>

                    Distributor Dashboard

                @else

                    <i class="fa fa-user-check text-warning"></i>

                    Executive Dashboard

                @endif

            </h2>

            <p class="text-muted">

                Welcome back,

                <strong>{{ $user->name }}</strong>

            </p>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- SERVICE CARDS START --}}
    {{-- ========================================= --}}

    {{-- ========================================= --}}
{{-- SERVICE CARDS --}}
{{-- ========================================= --}}

@if($isAdmin)

<div class="row g-4">

    {{-- PAN --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <a href="{{ route('admin.pan.index') }}" class="text-decoration-none">
            <div class="dash-card-modern bg-primary">
                <div>
                    <small>PAN</small>
                    <h2 class="counter">{{ $totalPanApplications }}</h2>
                </div>
                <i class="fa fa-id-card fa-2x"></i>
            </div>
        </a>
    </div>

    {{-- ITR --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <a href="{{ route('admin.itr.index') }}" class="text-decoration-none">
            <div class="dash-card-modern bg-success">
                <div>
                    <small>ITR</small>
                    <h2 class="counter">{{ $totalItrApplications }}</h2>
                </div>
                <i class="fa fa-file-invoice-dollar fa-2x"></i>
            </div>
        </a>
    </div>

    {{-- Aadhaar --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <div class="dash-card-modern bg-danger">
            <div>
                <small>Aadhaar</small>
                <h2 class="counter">{{ $totalAadhaarServices }}</h2>
            </div>
            <i class="fa fa-address-card fa-2x"></i>
        </div>
    </div>

    {{-- Bank --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <div class="dash-card-modern bg-dark">
            <div>
                <small>Bank Account</small>
                <h2 class="counter">{{ $totalBankAccounts }}</h2>
            </div>
            <i class="fa fa-university fa-2x"></i>
        </div>
    </div>

    {{-- CSC --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <div class="dash-card-modern bg-purple">
            <div>
                <small>CSC</small>
                <h2 class="counter">{{ $totalCscServices }}</h2>
            </div>
            <i class="fa fa-cogs fa-2x"></i>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="col-xl-2 col-lg-3 col-md-4">
        <div class="dash-card-modern bg-warning">
            <div>
                <small>Revenue</small>
                <h2>₹{{ number_format($totalRevenue,0) }}</h2>
            </div>
            <i class="fa fa-wallet fa-2x"></i>
        </div>
    </div>

</div>

@endif

{{-- ========================================= --}}
{{-- USER CARDS --}}
{{-- ========================================= --}}

<div class="row g-4 mt-2">

    {{-- ============================= --}}
    {{-- ADMIN --}}
    {{-- ============================= --}}
    @if($isAdmin)

        {{-- Retailers --}}
        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="dash-card-modern bg-info">

                <div>

                    <small>Retailers</small>

                    <h2 class="counter">
                        {{ $totalRetailers }}
                    </h2>

                </div>

                <i class="fa fa-store fa-2x"></i>

            </div>

        </div>

        {{-- Distributors --}}
        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="dash-card-modern bg-purple">

                <div>

                    <small>Distributors</small>

                    <h2 class="counter">
                        {{ $totalDistributors }}
                    </h2>

                </div>

                <i class="fa fa-network-wired fa-2x"></i>

            </div>

        </div>

        {{-- Executives --}}
        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="dash-card-modern bg-secondary">

                <div>

                    <small>Executives</small>

                    <h2 class="counter">
                        {{ $totalExecutives }}
                    </h2>

                </div>

                <i class="fa fa-user-tie fa-2x"></i>

            </div>

        </div>

        {{-- Total Users --}}
        <div class="col-xl-3 col-lg-4 col-md-6">

            <div class="dash-card-modern bg-primary">

                <div>

                    <small>Total Users</small>

                    <h2 class="counter">
                        {{ $totalUsers }}
                    </h2>

                </div>

                <i class="fa fa-users fa-2x"></i>

            </div>

        </div>

    @endif


    {{-- ============================= --}}
    {{-- DISTRIBUTOR --}}
    {{-- ============================= --}}
    @if($isDistributor)

        <div class="col-xl-4 col-lg-5 col-md-6">

            <div class="dash-card-modern bg-info">

                <div>

                    <small>My Retailers</small>

                    <h2 class="counter">
                        {{ $totalRetailers }}
                    </h2>

                </div>

                <i class="fa fa-store fa-2x"></i>

            </div>

        </div>

    @endif

</div>


{{-- ========================================= --}}
{{-- APPLICATION STATUS CARDS --}}
{{-- ========================================= --}}

@if($isAdmin)

<div class="row g-4 mt-3">

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-warning">

            <div>

                <small>Assigned</small>

                <h2 class="counter">
                    {{ $assignedApplications }}
                </h2>

            </div>

            <i class="fa fa-user-check fa-2x"></i>

        </div>

    </div>

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-danger">

            <div>

                <small>Pending</small>

                <h2 class="counter">
                    {{ $pendingApplications }}
                </h2>

            </div>

            <i class="fa fa-clock fa-2x"></i>

        </div>

    </div>

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-secondary">

            <div>

                <small>Processing</small>

                <h2 class="counter">
                    {{ $processingApplications }}
                </h2>

            </div>

            <i class="fa fa-spinner fa-2x"></i>

        </div>

    </div>

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-success">

            <div>

                <small>Completed</small>

                <h2 class="counter">
                    {{ $completedApplications }}
                </h2>

            </div>

            <i class="fa fa-check-circle fa-2x"></i>

        </div>

    </div>

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-dark">

            <div>

                <small>Rejected</small>

                <h2 class="counter">
                    {{ $rejectedApplications }}
                </h2>

            </div>

            <i class="fa fa-times-circle fa-2x"></i>

        </div>

    </div>

    <div class="col-xl-2 col-lg-3 col-md-4">

        <div class="dash-card-modern bg-primary">

            <div>

                <small>Fresh</small>

                <h2 class="counter">
                    {{ $freshApplications }}
                </h2>

            </div>

            <i class="fa fa-bolt fa-2x"></i>

        </div>

    </div>

</div>

@endif

{{-- ===================================================== --}}
{{-- ADMIN ONLY CONTENT --}}
{{-- ===================================================== --}}
@if($isAdmin)

{{-- ========================================= --}}
{{-- CHART SECTION --}}
{{-- ========================================= --}}

<div class="row mt-4">

    <div class="col-xl-8">

        <div class="card border-0 shadow-lg h-100">

            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-chart-line text-primary me-2"></i>

                    Monthly Service Analytics

                </h5>

                <span class="badge bg-primary">

                    {{ now()->year }}

                </span>

            </div>

            <div class="card-body">

                <canvas id="dashboardChart" height="120"></canvas>

            </div>

        </div>

    </div>

    <div class="col-xl-4">

        <div class="card border-0 shadow-lg h-100">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-chart-pie text-success me-2"></i>

                    Quick Statistics

                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between">
                    <span>PAN Services</span>
                    <strong>{{ $totalPanApplications }}</strong>
                </div>

                <div class="mb-3 d-flex justify-content-between">
                    <span>ITR Services</span>
                    <strong>{{ $totalItrApplications }}</strong>
                </div>

                <div class="mb-3 d-flex justify-content-between">
                    <span>Aadhaar Services</span>
                    <strong>{{ $totalAadhaarServices }}</strong>
                </div>

                <div class="mb-3 d-flex justify-content-between">
                    <span>Bank Accounts</span>
                    <strong>{{ $totalBankAccounts }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>CSC Services</span>
                    <strong>{{ $totalCscServices }}</strong>
                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- WALLET & REVENUE --}}
{{-- ========================================= --}}

<div class="row mt-4">

    <div class="col-lg-6">

        <div class="card border-0 shadow-lg">

            <div class="card-body text-center">

                <h5 class="fw-bold">

                    Wallet Summary

                </h5>

                <hr>

                <h3 class="text-success">

                    ₹{{ number_format(auth()->user()->wallet_balance,2) }}

                </h3>

                <p class="text-muted">

                    Wallet Balance

                </p>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card border-0 shadow-lg">

            <div class="card-body text-center">

                <h5 class="fw-bold">

                    Revenue Summary

                </h5>

                <hr>

                <h2 class="text-primary">

                    ₹{{ number_format($totalRevenue,2) }}

                </h2>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- RECENT APPLICATIONS --}}
{{-- ========================================= --}}

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white">

                <h5 class="fw-bold">

                    Recent Applications

                </h5>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover">

                        <thead>

                            <tr>

                                <th>#</th>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Status</th>
                                <th>Date</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($recentApplications ?? [] as $application)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $application->applicant_name }}</td>

                                    <td>{{ $application->service_name }}</td>

                                    <td>{{ $application->status }}</td>

                                    <td>{{ $application->created_at->format('d M Y') }}</td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center">

                                        No Applications Found

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white">

                <h5 class="fw-bold">

                    Wallet Transactions

                </h5>

            </div>

            <div class="card-body">

                @forelse($recentTransactions ?? [] as $transaction)

                    <div class="border-bottom pb-2 mb-2">

                        <strong>{{ ucfirst($transaction->type) }}</strong>

                        <div>

                            ₹{{ number_format($transaction->amount,2) }}

                        </div>

                    </div>

                @empty

                    No Transactions

                @endforelse

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- QUICK ACTIONS --}}
{{-- ========================================= --}}

<div class="row mt-4">

    <div class="col-lg-12">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white">

                <h5 class="fw-bold">

                    Quick Actions

                </h5>

            </div>

            <div class="card-body">

                <a href="{{ route('admin.pan.index') }}" class="btn btn-primary me-2">

                    PAN

                </a>

                <a href="{{ route('admin.itr.index') }}" class="btn btn-success me-2">

                    ITR

                </a>

                <a href="{{ route('admin.wallet.transactions') }}" class="btn btn-warning">

                    Wallet

                </a>

            </div>

        </div>

    </div>

</div>

@endif

{{-- ========================================= --}}
{{-- ADMIN ONLY CHARTS --}}
{{-- ========================================= --}}

@if($isAdmin)

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const dashboardChart = new Chart(

    document.getElementById('dashboardChart'),

    {

        type:'bar',

        data:{

            labels:@json($months),

            datasets:[{

                label:'Applications',

                data:@json($chartData),

                backgroundColor:'#0d6efd',

                borderRadius:8

            }]

        },

        options:{

            responsive:true,

            plugins:{

                legend:{

                    display:false

                }

            }

        }

    }

);

const servicePieChart = new Chart(

    document.getElementById('servicePieChart'),

    {

        type:'doughnut',

        data:{

            labels:[

                'PAN',

                'ITR',

                'Aadhaar',

                'Bank',

                'CSC'

            ],

            datasets:[{

                data:[

                    {{ $totalPanApplications }},

                    {{ $totalItrApplications }},

                    {{ $totalAadhaarServices }},

                    {{ $totalBankAccounts }},

                    {{ $totalCscServices }}

                ],

                backgroundColor:[

                    '#0d6efd',

                    '#198754',

                    '#dc3545',

                    '#212529',

                    '#ffc107'

                ]

            }]

        },

        options:{

            responsive:true,

            plugins:{

                legend:{

                    position:'bottom'

                }

            }

        }

    }

);

</script>

@endif


{{-- ========================================= --}}
{{-- COUNTER --}}
{{-- ========================================= --}}

<script>

document.querySelectorAll('.counter').forEach(function(counter){

    let target = parseInt(counter.innerText);

    let count = 0;

    let speed = Math.max(1, Math.floor(target / 50));

    counter.innerText = '0';

    const timer = setInterval(function(){

        count += speed;

        if(count >= target){

            counter.innerText = target;

            clearInterval(timer);

        }else{

            counter.innerText = count;

        }

    },20);

});

</script>

{{-- Close the @else started in Part 1 --}}
@endif

@endsection