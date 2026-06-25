@extends('layout.admin')

@section('title','Dashboard')

@section('content')

@php

$isAdmin = auth()->user()->hasRole('admin');

$isDistributor = auth()->user()->hasRole('Distributor');

$isExecutive = auth()->user()->hasRole('Executive');

@endphp

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

                <strong>{{ auth()->user()->name }}</strong>

            </p>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- SERVICE CARDS --}}
    {{-- ========================================= --}}

    <div class="row g-4">

        {{-- PAN --}}

        <div class="col-xl-2 col-lg-3 col-md-4">

            <a href="{{ route('admin.pan.index') }}"

               class="text-decoration-none">

                <div class="dash-card-modern bg-primary">

                    <div>

                        <small>PAN</small>

                        <h2 class="counter">

                            {{ $totalPanApplications }}

                        </h2>

                    </div>

                    <i class="fa fa-id-card fa-2x"></i>

                </div>

            </a>

        </div>

        {{-- ITR --}}

        <div class="col-xl-2 col-lg-3 col-md-4">

            <a href="{{ route('admin.itr.index') }}"

               class="text-decoration-none">

                <div class="dash-card-modern bg-success">

                    <div>

                        <small>ITR</small>

                        <h2 class="counter">

                            {{ $totalItrApplications }}

                        </h2>

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

                    <h2 class="counter">

                        {{ $totalAadhaarServices }}

                    </h2>

                </div>

                <i class="fa fa-address-card fa-2x"></i>

            </div>

        </div>

        {{-- Bank --}}

        <div class="col-xl-2 col-lg-3 col-md-4">

            <div class="dash-card-modern bg-dark">

                <div>

                    <small>Bank Account</small>

                    <h2 class="counter">

                        {{ $totalBankAccounts }}

                    </h2>

                </div>

                <i class="fa fa-university fa-2x"></i>

            </div>

        </div>

        {{-- CSC --}}

        <div class="col-xl-2 col-lg-3 col-md-4">

            <div class="dash-card-modern bg-purple">

                <div>

                    <small>CSC</small>

                    <h2 class="counter">

                        {{ $totalCscServices }}

                    </h2>

                </div>

                <i class="fa fa-cogs fa-2x"></i>

            </div>

        </div>

        {{-- Wallet --}}

        <div class="col-xl-2 col-lg-3 col-md-4">

            <div class="dash-card-modern bg-warning">

                <div>

                    <small>Revenue</small>

                    <h2>

                        ₹{{ number_format($totalRevenue,0) }}

                    </h2>

                </div>

                <i class="fa fa-wallet fa-2x"></i>

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- USER CARDS --}}
    {{-- ========================================= --}}

    <div class="row g-4 mt-2">

        {{-- ========================================= --}}
    {{-- USER & APPLICATION CARDS --}}
    {{-- ========================================= --}}

    @if($isAdmin || $isDistributor)

    {{-- Retailers --}}
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

        <div class="dash-card-modern bg-info">

            <div>

                <small>

                    {{ $isAdmin ? 'Retailers' : 'My Retailers' }}

                </small>

                <h2 class="counter">

                    {{ $totalRetailers }}

                </h2>

            </div>

            <i class="fa fa-store fa-2x"></i>

        </div>

    </div>

    @endif

    {{-- Admin Only --}}

    @if($isAdmin)

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

        <div class="dash-card-modern bg-primary">

            <div>

                <small>Total Users</small>

                <h2 class="counter">

                    {{ $totalUsers }}

                </h2>

            </div>

            <i class="fa fa-user-friends fa-2x"></i>

        </div>

    </div>

    @endif

    {{-- Assigned --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Pending --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Processing --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Completed --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Rejected --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Fresh Applications --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

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

    {{-- Today's Uploads --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

        <div class="dash-card-modern bg-info">

            <div>

                <small>Today's Uploads</small>

                <h2 class="counter">

                    {{ $todayUploads }}

                </h2>

            </div>

            <i class="fa fa-upload fa-2x"></i>

        </div>

    </div>

    {{-- Wallet Transactions --}}

    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">

        <div class="dash-card-modern bg-success">

            <div>

                <small>Wallet Txns</small>

                <h2 class="counter">

                    {{ $walletTransactions }}

                </h2>

            </div>

            <i class="fa fa-wallet fa-2x"></i>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- CHART SECTION START --}}
{{-- ========================================= --}}

<div class="row mt-4">

    {{-- ========================================= --}}
    {{-- SERVICE ANALYTICS --}}
    {{-- ========================================= --}}

    <div class="col-xl-8 col-lg-7 col-12">

        <div class="card border-0 shadow-lg h-100">

            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">

                    <i class="fa fa-chart-line text-primary me-2"></i>

                    Monthly Service Analytics

                </h5>

                <span class="badge bg-primary">

                    {{ now()->year }}

                </span>

            </div>

            <div class="card-body">

                <canvas
                    id="dashboardChart"
                    height="120">
                </canvas>

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- QUICK STATS --}}
    {{-- ========================================= --}}

    <div class="col-xl-4 col-lg-5 col-12">

        <div class="card border-0 shadow-lg h-100">

            <div class="card-header bg-white border-0">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-chart-pie text-success me-2"></i>

                    Quick Statistics

                </h5>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">

                    <span>

                        PAN Services

                    </span>

                    <strong class="text-primary">

                        {{ $totalPanApplications }}

                    </strong>

                </div>

                <div class="progress mb-4">

                    <div
                        class="progress-bar bg-primary"
                        style="width:100%">
                    </div>

                </div>

                <div class="d-flex justify-content-between mb-3">

                    <span>

                        ITR Services

                    </span>

                    <strong class="text-success">

                        {{ $totalItrApplications }}

                    </strong>

                </div>

                <div class="progress mb-4">

                    <div
                        class="progress-bar bg-success"
                        style="width:85%">
                    </div>

                </div>

                <div class="d-flex justify-content-between mb-3">

                    <span>

                        Aadhaar Services

                    </span>

                    <strong class="text-danger">

                        {{ $totalAadhaarServices }}

                    </strong>

                </div>

                <div class="progress mb-4">

                    <div
                        class="progress-bar bg-danger"
                        style="width:75%">
                    </div>

                </div>

                <div class="d-flex justify-content-between mb-3">

                    <span>

                        Bank Account

                    </span>

                    <strong class="text-dark">

                        {{ $totalBankAccounts }}

                    </strong>

                </div>

                <div class="progress mb-4">

                    <div
                        class="progress-bar bg-dark"
                        style="width:70%">
                    </div>

                </div>

                <div class="d-flex justify-content-between mb-3">

                    <span>

                        CSC Services

                    </span>

                    <strong class="text-warning">

                        {{ $totalCscServices }}

                    </strong>

                </div>

                <div class="progress">

                    <div
                        class="progress-bar bg-warning"
                        style="width:65%">
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- REVENUE & WALLET --}}
{{-- ========================================= --}}

<div class="row mt-4">

    <div class="col-xl-6">

        <div class="card border-0 shadow-lg">

            <div class="card-body">

                <h5 class="fw-bold">

                    <i class="fa fa-wallet text-success me-2"></i>

                    Wallet Summary

                </h5>

                <hr>

                <div class="row text-center">

                    <div class="col-6">

                        <h6>

                            Wallet Balance

                        </h6>

                        <h3 class="text-success">

                            ₹{{ number_format(auth()->user()->wallet_balance,2) }}

                        </h3>

                    </div>

                    <div class="col-6">

                        <h6>

                            Transactions

                        </h6>

                        <h3 class="text-primary">

                            {{ $walletTransactions }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-6">

        <div class="card border-0 shadow-lg">

            <div class="card-body">

                <h5 class="fw-bold">

                    <i class="fa fa-coins text-warning me-2"></i>

                    Revenue Summary

                </h5>

                <hr>

                <div class="text-center">

                    <h1 class="display-5 fw-bold text-success">

                        ₹{{ number_format($totalRevenue,2) }}

                    </h1>

                    <small class="text-muted">

                        Total Revenue Generated

                    </small>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- RECENT APPLICATIONS START --}}
{{-- ========================================= --}}

<div class="row mt-4">
        {{-- ========================================= --}}
    {{-- RECENT APPLICATIONS --}}
    {{-- ========================================= --}}

    <div class="col-xl-8">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0 fw-bold">

                    <i class="fa fa-history text-primary me-2"></i>

                    Recent Applications

                </h5>

                <a href="{{ route('admin.pan.index') }}"
                   class="btn btn-primary btn-sm">

                    View All

                </a>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>#</th>

                            <th>Applicant</th>

                            <th>Service</th>

                            <th>Status</th>

                            <th>Date</th>

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($recentApplications ?? [] as $application)

                            <tr>

                                <td>

                                    {{ $loop->iteration }}

                                </td>

                                <td>

                                    <strong>

                                        {{ $application->applicant_name }}

                                    </strong>

                                </td>

                                <td>

                                    <span class="badge bg-primary">

                                        {{ $application->service_name }}

                                    </span>

                                </td>

                                <td>

                                    @switch(strtolower($application->status))

                                        @case('approved')

                                            <span class="badge bg-success">

                                                Approved

                                            </span>

                                            @break

                                        @case('processing')

                                            <span class="badge bg-info">

                                                Processing

                                            </span>

                                            @break

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">

                                                Pending

                                            </span>

                                            @break

                                        @case('rejected')

                                            <span class="badge bg-danger">

                                                Rejected

                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">

                                                {{ $application->status }}

                                            </span>

                                    @endswitch

                                </td>

                                <td>

                                    {{ $application->created_at->format('d M Y') }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="text-center py-5">

                                    <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>

                                    <br>

                                    No Recent Applications Found

                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- RECENT WALLET TRANSACTIONS --}}
    {{-- ========================================= --}}

    <div class="col-xl-4">

        <div class="card border-0 shadow-lg h-100">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-wallet text-success me-2"></i>

                    Wallet History

                </h5>

                <a href="{{ route('admin.wallet.transactions') }}"

                   class="btn btn-success btn-sm">

                    View

                </a>

            </div>

            <div class="card-body">

                @forelse($recentTransactions ?? [] as $transaction)

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">

                        <div>

                            <strong>

                                {{ ucfirst($transaction->type) }}

                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ $transaction->created_at->diffForHumans() }}

                            </small>

                        </div>

                        <div>

                            @if($transaction->type=='credit')

                                <span class="text-success fw-bold">

                                    + ₹{{ number_format($transaction->amount,2) }}

                                </span>

                            @else

                                <span class="text-danger fw-bold">

                                    - ₹{{ number_format($transaction->amount,2) }}

                                </span>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5">

                        <i class="fa fa-wallet fa-3x text-muted"></i>

                        <p class="mt-3">

                            No Wallet Transactions

                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- QUICK ACTIONS START --}}
{{-- ========================================= --}}

<div class="row mt-4">
        {{-- ========================================= --}}
    {{-- QUICK ACTIONS --}}
    {{-- ========================================= --}}

    <div class="col-xl-4">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-bolt text-warning me-2"></i>

                    Quick Actions

                </h5>

            </div>

            <div class="card-body">

                <div class="d-grid gap-3">

                    @if($isAdmin)

                        <a href="{{ route('admin.pan.index') }}" class="btn btn-primary">

                            <i class="fa fa-id-card me-2"></i>

                            PAN Applications

                        </a>

                        <a href="{{ route('admin.itr.index') }}" class="btn btn-success">

                            <i class="fa fa-file-invoice-dollar me-2"></i>

                            ITR Applications

                        </a>

                        <a href="{{ route('admin.retailer-approvals.index') }}" class="btn btn-info">

                            <i class="fa fa-store me-2"></i>

                            Retailers

                        </a>

                        <a href="{{ route('admin.wallet.transactions') }}" class="btn btn-warning">

                            <i class="fa fa-wallet me-2"></i>

                            Wallet

                        </a>

                    @elseif($isDistributor)

                        <a href="{{ route('admin.pan.index') }}" class="btn btn-primary">

                            My PAN Applications

                        </a>

                        <a href="{{ route('admin.itr.index') }}" class="btn btn-success">

                            My ITR Applications

                        </a>

                        <a href="{{ route('admin.wallet.transactions') }}" class="btn btn-warning">

                            Wallet

                        </a>

                    @else

                        <a href="{{ route('admin.pan.index') }}" class="btn btn-primary">

                            Assigned PAN

                        </a>

                        <a href="{{ route('admin.itr.index') }}" class="btn btn-success">

                            Assigned ITR

                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- ========================================= --}}
    {{-- SERVICE DISTRIBUTION --}}
    {{-- ========================================= --}}

    <div class="col-xl-8">

        <div class="card border-0 shadow-lg">

            <div class="card-header bg-white">

                <h5 class="fw-bold mb-0">

                    <i class="fa fa-chart-pie text-danger me-2"></i>

                    Service Distribution

                </h5>

            </div>

            <div class="card-body">

                <canvas id="servicePieChart" height="120"></canvas>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- CHART JS --}}
{{-- ========================================= --}}

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

});

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

});

</script>

{{-- ========================================= --}}
{{-- COUNTER ANIMATION --}}
{{-- ========================================= --}}

<script>

document.querySelectorAll('.counter').forEach(function(counter){

let target=parseInt(counter.innerText);

let count=0;

let speed=Math.max(10,Math.floor(target/60));

counter.innerText='0';

const timer=setInterval(function(){

count+=speed;

if(count>=target){

counter.innerText=target;

clearInterval(timer);

}else{

counter.innerText=count;

}

},20);

});

</script>

@endsection