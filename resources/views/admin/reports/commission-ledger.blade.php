@extends('layout.admin')

@section('title', 'Commission Ledger')

@section('content')

<div class="container-fluid commission-ledger-page">

    {{-- ============================
        PAGE HEADER
    ============================= --}}

    <div class="card report-card mb-4">

        <div class="card-header report-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h4 class="mb-1">

                        <i class="fas fa-hand-holding-usd me-2"></i>

                        Commission Ledger

                    </h4>

                    <small class="text-white-50">

                        View commission earned by retailers and distributors

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

                        <label class="form-label">

                            <i class="fa fa-user me-1"></i>

                            User

                        </label>

                        <select
                            name="user_id"
                            class="form-control">

                            <option value="">

                                All Users

                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(request('user_id') == $user->id)>

                                    {{ $user->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-lg-3 col-md-6">

                        <button class="btn btn-primary w-100">

                            <i class="fa fa-filter me-2"></i>

                            Apply Filter

                        </button>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-3">

                        <a href="{{ route('admin.reports.commission') }}"
                           class="btn btn-outline-secondary w-100">

                            <i class="fa fa-rotate-right me-2"></i>

                            Reset Filter

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- ============================
        SUMMARY CARDS
    ============================= --}}

    <div class="row mb-4">

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3>

                        {{ number_format($transactions->total()) }}

                    </h3>

                    <p>

                        Total Commission Entries

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-list"></i>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-success">

                <div class="inner">

                    <h3>

                        ₹{{ number_format($transactions->sum('amount'),2) }}

                    </h3>

                    <p>

                        Current Page Commission

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-indian-rupee-sign"></i>

                </div>

            </div>

        </div>

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-warning">

                <div class="inner">

                    <h3>

                        {{ $transactions->count() }}

                    </h3>

                    <p>

                        Records On This Page

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-chart-line"></i>

                </div>

            </div>

        </div>

    </div>

        {{-- ============================
        COMMISSION LEDGER TABLE
    ============================= --}}

    <div class="card report-card">

        <div class="card-header bg-white border-bottom">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h5 class="mb-0">

                    <i class="fa fa-table text-primary me-2"></i>

                    Commission Transactions

                </h5>

                <span class="badge bg-primary px-3 py-2">

                    Total :
                    {{ number_format($transactions->total()) }}

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table commission-table align-middle">

                    <thead>

                        <tr>

                            <th width="70">#</th>

                            <th>User</th>

                            <th width="180">Role</th>

                            <th width="150">Commission</th>

                            <th>Remarks</th>

                            <th width="180">Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transactions as $i => $txn)

                            @php

                                $role = optional($txn->user)->getRoleNames()->first();

                            @endphp

                            <tr>

                                <td>

                                    <span class="serial-number">

                                        {{ $transactions->firstItem() + $i }}

                                    </span>

                                </td>

                                <td>

                                    <div class="fw-bold">

                                        {{ optional($txn->user)->name }}

                                    </div>

                                </td>

                                <td>

                                    @switch(strtolower($role))

                                        @case('admin')

                                            <span class="badge bg-danger">

                                                Admin

                                            </span>

                                            @break

                                        @case('super distributor')

                                            <span class="badge bg-dark">

                                                Super Distributor

                                            </span>

                                            @break

                                        @case('distributor')

                                            <span class="badge bg-info">

                                                Distributor

                                            </span>

                                            @break

                                        @case('retailer')

                                            <span class="badge bg-success">

                                                Retailer

                                            </span>

                                            @break

                                        @case('executive')

                                            <span class="badge bg-warning text-dark">

                                                Executive

                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-secondary">

                                                {{ $role ?? '-' }}

                                            </span>

                                    @endswitch

                                </td>

                                <td class="fw-bold text-success">

                                    ₹{{ number_format($txn->amount,2) }}

                                </td>

                                <td>

                                    @if($txn->remarks)

                                        {{ $txn->remarks }}

                                    @else

                                        <span class="text-muted">

                                            —

                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <div>

                                        {{ $txn->created_at->format('d M Y') }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $txn->created_at->format('h:i A') }}

                                    </small>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5">

                                    <i class="fa fa-wallet fa-3x text-muted mb-3"></i>

                                    <h5>

                                        No Commission Entries Found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        No commission records are available for the selected filters.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="3">

                                Total Commission

                            </th>

                            <th class="text-success">

                                ₹{{ number_format($transactions->sum('amount'),2) }}

                            </th>

                            <th colspan="2">

                                Total Records :
                                {{ number_format($transactions->total()) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            @if($transactions->hasPages())

                <div class="d-flex justify-content-end mt-4">

                    {{ $transactions->appends(request()->query())->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection