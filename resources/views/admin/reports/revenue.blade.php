@extends('layout.admin')

@section('title', 'Revenue Report')

@section('content')

<div class="container-fluid revenue-report">

    <!-- Header -->
    <div class="card report-card mb-4">

        <div class="card-header report-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-chart-line me-2"></i>
                        Revenue Report
                    </h4>
                    <small class="text-white-50">
                        View revenue generated from all services
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

                        <a href="{{ route('admin.reports.revenue') }}"
                           class="btn btn-outline-secondary w-100">

                            <i class="fa fa-rotate-right me-2"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- Summary Card -->

    <div class="row mb-4">

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-success revenue-summary">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($grand_total,2) }}

                    </h3>

                    <p>

                        Total Revenue (All Services)

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-wallet"></i>

                </div>

            </div>

        </div>

    </div>

    <!-- Revenue Table -->

    <div class="card report-card">

        <div class="card-header bg-white border-bottom">

            <h5 class="mb-0">

                <i class="fa fa-table me-2 text-primary"></i>

                Revenue Breakdown

            </h5>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table revenue-table align-middle">

                    <thead>

                    <tr>

                        <th>Service</th>

                        <th class="text-center">
                            Applications
                        </th>

                        <th class="text-end">
                            Amount
                        </th>

                        <th class="text-center">
                            Approved
                        </th>

                        <th class="text-center">
                            Pending
                        </th>

                        <th class="text-center">
                            Processing
                        </th>

                        <th class="text-center">
                            Rejected
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

                                    {{ $service }}

                                </a>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary">

                                    {{ $row['applications'] }}

                                </span>

                            </td>

                            <td class="text-end fw-bold text-success">

                                ₹{{ number_format($row['amount'],2) }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">

                                    {{ $row['approved'] }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-warning text-dark">

                                    {{ $row['pending'] }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-info">

                                    {{ $row['processing'] }}

                                </span>

                            </td>

                            <td class="text-center">

                                <span class="badge bg-danger">

                                    {{ $row['rejected'] }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>

                                <h5>No Revenue Found</h5>

                                <p class="text-muted mb-0">

                                    No records found for selected filters.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                    <tfoot>

                    <tr>

                        <th>Total</th>

                        <th class="text-center">

                            {{ collect($breakdown)->sum('applications') }}

                        </th>

                        <th class="text-end text-success">

                            ₹{{ number_format($grand_total,2) }}

                        </th>

                        <th class="text-center">

                            {{ collect($breakdown)->sum('approved') }}

                        </th>

                        <th class="text-center">

                            {{ collect($breakdown)->sum('pending') }}

                        </th>

                        <th class="text-center">

                            {{ collect($breakdown)->sum('processing') }}

                        </th>

                        <th class="text-center">

                            {{ collect($breakdown)->sum('rejected') }}

                        </th>

                    </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection