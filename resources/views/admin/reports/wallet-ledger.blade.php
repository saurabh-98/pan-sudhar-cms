@extends('layout.admin')

@section('title', 'Wallet Ledger')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/wallet-ledger.css') }}">
@endpush

@section('content')

<div class="container-fluid wallet-ledger-page">

    {{-- ============================
        PAGE HEADER
    ============================= --}}
    <div class="card report-card mb-4">

        <div class="card-header report-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div>

                    <h4 class="mb-1">
                        <i class="fas fa-wallet me-2"></i>
                        Wallet Ledger
                    </h4>

                    <small class="text-white-50">
                        View retailer wallet credit/debit history
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
                            Retailer
                        </label>

                        <select
                            name="user_id"
                            class="form-control">

                            <option value="">
                                All Retailers
                            </option>

                            @foreach($users as $user)

                                <option
                                    value="{{ $user->id }}"
                                    @selected(request('user_id')==$user->id)>

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

                        <a href="{{ route('admin.reports.wallet') }}"
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
        SUMMARY CARD
    ============================= --}}

    <div class="row mb-4">

        <div class="col-lg-4 col-md-6">

            <div class="small-box bg-primary">

                <div class="inner">

                    <h3>

                        {{ number_format($transactions->total()) }}

                    </h3>

                    <p>

                        Total Transactions

                    </p>

                </div>

                <div class="icon">

                    <i class="fa fa-wallet"></i>

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

                        Current Page Amount

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

                    <i class="fa fa-list"></i>

                </div>

            </div>

        </div>

    </div>

        {{-- ============================
        WALLET LEDGER TABLE
    ============================= --}}

    <div class="card report-card">

        <div class="card-header bg-white border-bottom">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <h5 class="mb-0">

                    <i class="fa fa-table text-primary me-2"></i>

                    Wallet Transactions

                </h5>

                <span class="badge bg-primary px-3 py-2">

                    Total :
                    {{ number_format($transactions->total()) }}

                </span>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table wallet-table align-middle">

                    <thead>

                        <tr>

                            <th width="70">#</th>

                            <th>User</th>

                            <th width="120">Type</th>

                            <th width="140">Amount</th>

                            <th width="140">Balance</th>

                            <th>Remarks</th>

                            <th width="180">Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($transactions as $i => $txn)

                            <tr>

                                <td>

                                    <span class="serial-number">

                                        {{ $transactions->firstItem() + $i }}

                                    </span>

                                </td>

                                <td>

                                    <div class="fw-semibold">

                                        {{ optional($txn->user)->name }}

                                    </div>

                                </td>

                                <td>

                                    @php
                                        $type = strtolower($txn->type);
                                    @endphp

                                    @if($type == 'credit')

                                        <span class="badge bg-success">

                                            <i class="fa fa-arrow-down me-1"></i>

                                            Credit

                                        </span>

                                    @elseif($type == 'debit')

                                        <span class="badge bg-danger">

                                            <i class="fa fa-arrow-up me-1"></i>

                                            Debit

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst($txn->type) }}

                                        </span>

                                    @endif

                                </td>

                                <td class="text-success fw-bold">

                                    ₹{{ number_format($txn->amount,2) }}

                                </td>

                                <td class="text-primary fw-bold">

                                    ₹{{ number_format($txn->balance ?? 0,2) }}

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

                                <td colspan="7" class="text-center py-5">

                                    <i class="fa fa-wallet fa-3x text-muted mb-3"></i>

                                    <h5>

                                        No Wallet Transactions Found

                                    </h5>

                                    <p class="text-muted mb-0">

                                        There are no transactions available for the selected filters.

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="3">

                                Total Records

                            </th>

                            <th colspan="4">

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