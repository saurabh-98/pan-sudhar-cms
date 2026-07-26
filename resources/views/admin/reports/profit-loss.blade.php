@extends('layout.admin')

@section('title', 'Profit & Loss')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/profit-loss.css') }}">
@endpush

@section('content')

<div class="container-fluid profit-loss-page">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="card report-card mb-4">

        <div class="card-header report-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h4 class="mb-1">

                        <i class="fas fa-chart-pie me-2"></i>

                        Profit & Loss Report

                    </h4>

                    <small class="text-white-50">

                        Business Revenue • Commission • Partner Profit • Company Profit

                    </small>

                </div>

            </div>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">

                            <i class="fa fa-calendar-alt me-1"></i>

                            From Date

                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="form-control">

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">

                            <i class="fa fa-calendar-alt me-1"></i>

                            To Date

                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ request('to') }}"
                            class="form-control">

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <button class="btn btn-primary w-100">

                            <i class="fa fa-filter me-2"></i>

                            Apply Filter

                        </button>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <a href="{{ route('admin.reports.profit') }}"
                           class="btn btn-outline-secondary w-100">

                            <i class="fa fa-sync me-2"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Revenue --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-success">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['amount'],2) }}

                    </h3>

                    <p>

                        Total Revenue

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-wallet"></i>

                </div>

            </div>

        </div>

        {{-- Executive --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-warning">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['executive_commission'],2) }}

                    </h3>

                    <p>

                        Executive Commission

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-user-tie"></i>

                </div>

            </div>

        </div>

        {{-- Distributor --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-info">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['distributor_commission'],2) }}

                    </h3>

                    <p>

                        Distributor Commission

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-users"></i>

                </div>

            </div>

        </div>

        {{-- Net Profit --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-dark">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['net_profit'],2) }}

                    </h3>

                    <p>

                        Net Business Profit

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-chart-line"></i>

                </div>

            </div>

        </div>

        {{-- Partner Profit --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-danger">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['partner_profit'],2) }}

                    </h3>

                    <p>

                        Partner Profit

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-handshake"></i>

                </div>

            </div>

        </div>

        {{-- Company Profit --}}
        <div class="col-xl-2 col-lg-4 col-md-6">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($totals['company_profit'],2) }}

                    </h3>

                    <p>

                        Company Profit

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-building"></i>

                </div>

            </div>

        </div>

    </div>
        {{-- =========================================================
        PROFIT ANALYSIS TABLE
    ========================================================== --}}

    <div class="card report-card shadow-sm">

        <div class="card-header bg-white border-bottom">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h5 class="mb-0">

                    <i class="fa fa-chart-bar text-primary me-2"></i>

                    Service Wise Profit Analysis

                </h5>

                <span class="badge bg-primary">

                    {{ count($breakdown) }} Services

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover profit-table align-middle">

                    <thead>

                        <tr>

                            <th width="250">

                                Service

                            </th>

                            <th class="text-end">

                                Revenue

                            </th>

                            <th class="text-end">

                                Executive

                            </th>

                            <th class="text-end">

                                Distributor

                            </th>

                            <th class="text-end">

                                Net Profit

                            </th>

                            <th class="text-end">

                                Partner Profit

                            </th>

                            <th class="text-end">

                                Company Profit

                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($breakdown as $service => $row)

                            <tr>

                                <td>

                                    <a href="{{ route('admin.reports.service',$service) }}"
                                       class="service-link">

                                        <i class="fa fa-file-alt text-primary me-2"></i>

                                        {{ ucwords(str_replace('-', ' ', $service)) }}

                                    </a>

                                </td>

                                <td class="text-end fw-bold text-success">

                                    ₹{{ number_format($row['amount'],2) }}

                                </td>

                                <td class="text-end text-warning">

                                    ₹{{ number_format($row['executive_commission'],2) }}

                                </td>

                                <td class="text-end text-info">

                                    ₹{{ number_format($row['distributor_commission'],2) }}

                                </td>

                                <td class="text-end fw-bold text-dark">

                                    ₹{{ number_format($row['net_profit'],2) }}

                                </td>

                                <td class="text-end fw-bold text-danger">

                                    ₹{{ number_format($row['partner_profit'],2) }}

                                </td>

                                <td class="text-end fw-bold text-primary">

                                    ₹{{ number_format($row['company_profit'],2) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5">

                                    <div class="empty-state">

                                        <i class="fa fa-folder-open fa-4x text-muted mb-3"></i>

                                        <h5 class="mb-2">

                                            No Profit Records Found

                                        </h5>

                                        <p class="text-muted">

                                            There are no records available for the selected filters.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr class="table-dark">

                            <th>

                                Grand Total

                            </th>

                            <th class="text-end text-success">

                                ₹{{ number_format($totals['amount'],2) }}

                            </th>

                            <th class="text-end text-warning">

                                ₹{{ number_format($totals['executive_commission'],2) }}

                            </th>

                            <th class="text-end text-info">

                                ₹{{ number_format($totals['distributor_commission'],2) }}

                            </th>

                            <th class="text-end">

                                ₹{{ number_format($totals['net_profit'],2) }}

                            </th>

                            <th class="text-end text-danger">

                                ₹{{ number_format($totals['partner_profit'],2) }}

                            </th>

                            <th class="text-end text-primary">

                                ₹{{ number_format($totals['company_profit'],2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

        {{-- =========================================================
        PROFIT SUMMARY
    ========================================================== --}}

    <div class="row mt-4">

        <div class="col-xl-6">

            <div class="card report-card h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fa fa-chart-line text-success me-2"></i>

                        Financial Summary

                    </h5>

                </div>

                <div class="card-body">

                    <table class="table table-borderless summary-table mb-0">

                        <tbody>

                            <tr>

                                <td>

                                    <strong>Total Revenue</strong>

                                </td>

                                <td class="text-end text-success fw-bold">

                                    ₹{{ number_format($totals['amount'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    Executive Commission

                                </td>

                                <td class="text-end text-warning">

                                    ₹{{ number_format($totals['executive_commission'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    Distributor Commission

                                </td>

                                <td class="text-end text-info">

                                    ₹{{ number_format($totals['distributor_commission'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <strong>Net Business Profit</strong>

                                </td>

                                <td class="text-end text-dark fw-bold">

                                    ₹{{ number_format($totals['net_profit'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    Business Partner Profit

                                </td>

                                <td class="text-end text-danger fw-bold">

                                    ₹{{ number_format($totals['partner_profit'],2) }}

                                </td>

                            </tr>

                            <tr class="table-success">

                                <td>

                                    <strong>Company Profit</strong>

                                </td>

                                <td class="text-end">

                                    <strong>

                                        ₹{{ number_format($totals['company_profit'],2) }}

                                    </strong>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-xl-6">

            <div class="card report-card h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fas fa-handshake text-primary me-2"></i>

                        Business Partner Distribution

                    </h5>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th width="60">

                                        #

                                    </th>

                                    <th>

                                        Partner

                                    </th>

                                    <th class="text-center">

                                        Share %

                                    </th>

                                    <th class="text-end">

                                        Profit

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($partnerDistribution as $partner)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            <strong>

                                                {{ $partner->partner->partner_name }}

                                            </strong>

                                        </td>

                                        <td class="text-center">

                                            <span class="badge bg-primary">

                                                {{ number_format($partner->profit_percentage,2) }}%

                                            </span>

                                        </td>

                                        <td class="text-end">

                                            <span class="fw-bold text-success">

                                                ₹{{ number_format($partner->total_profit,2) }}

                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center py-4">

                                            <i class="fa fa-users fa-2x text-muted mb-2"></i>

                                            <br>

                                            No Partner Profit Available

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                            <tfoot>

                                <tr class="table-light">

                                    <th colspan="3">

                                        Total Distributed

                                    </th>

                                    <th class="text-end text-danger">

                                        ₹{{ number_format($partnerDistribution->sum('total_profit'),2) }}

                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
        {{-- =========================================================
        REPORT FOOTER
    ========================================================== --}}

    <div class="row mt-4">

        {{-- Report Notes --}}
        <div class="col-lg-8">

            <div class="card report-card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fa fa-circle-info text-primary me-2"></i>

                        Profit Calculation Formula

                    </h5>

                </div>

                <div class="card-body">

                    <div class="alert alert-success mb-3">

                        <strong>Revenue</strong>

                        <span class="mx-2">−</span>

                        <strong>Executive Commission</strong>

                        <span class="mx-2">−</span>

                        <strong>Distributor Commission</strong>

                        <span class="mx-2">=</span>

                        <strong>Net Business Profit</strong>

                    </div>

                    <div class="alert alert-warning mb-3">

                        <strong>Net Business Profit</strong>

                        <span class="mx-2">−</span>

                        <strong>Partner Profit Distribution</strong>

                        <span class="mx-2">=</span>

                        <strong>Company Profit</strong>

                    </div>

                    <div class="alert alert-info mb-0">

                        <ul class="mb-0">

                            <li>

                                Revenue is the total amount received from customers.

                            </li>

                            <li>

                                Executive & Distributor commissions are deducted first.

                            </li>

                            <li>

                                Remaining amount becomes Net Business Profit.

                            </li>

                            <li>

                                Net Profit is distributed among business partners based on their configured percentage.

                            </li>

                            <li>

                                Remaining amount is Company/Admin Profit.

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

        {{-- Action Buttons --}}
        <div class="col-lg-4">

            <div class="card report-card">

                <div class="card-header bg-white">

                    <h5 class="mb-0">

                        <i class="fa fa-gears text-primary me-2"></i>

                        Report Actions

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-grid gap-3">

                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="window.print();">

                            <i class="fa fa-print me-2"></i>

                            Print Report

                        </button>

                        <button
                            type="button"
                            class="btn btn-success">

                            <i class="fa fa-file-excel me-2"></i>

                            Export Excel

                        </button>

                        <button
                            type="button"
                            class="btn btn-danger">

                            <i class="fa fa-file-pdf me-2"></i>

                            Export PDF

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    // Summary Card Hover Effect

    document.querySelectorAll('.small-box').forEach(function(card){

        card.addEventListener('mouseenter', function(){

            this.style.transform = 'translateY(-6px)';

        });

        card.addEventListener('mouseleave', function(){

            this.style.transform = 'translateY(0px)';

        });

    });

});

</script>

@endsection