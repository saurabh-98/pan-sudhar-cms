@extends('layout.admin')

@section('title', $title)

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h4 class="mb-0">{{ $title }} — {{ $row->application_no }}</h4>

            <a href="{{ route('admin.reports.service', $service) }}" class="btn btn-light btn-sm">

                <i class="fa fa-arrow-left"></i> Back

            </a>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>
                    <th width="220">Application No</th>
                    <td>{{ $row->application_no }}</td>
                </tr>

                <tr>
                    <th>Customer</th>
                    <td>{{ $customer }}</td>
                </tr>

                <tr>
                    <th>Retailer</th>
                    <td>{{ optional($row->user)->name }}</td>
                </tr>

                <tr>
                    <th>Distributor</th>
                    <td>{{ optional(optional($row->user)->retailer)->distributor?->name }}</td>
                </tr>

                <tr>
                    <th>Executive</th>
                    <td>{{ optional($row->assignedUser)->name }}</td>
                </tr>

                <tr>
                    <th>Amount</th>
                    <td>₹{{ number_format($row->amount ?? $row->charge ?? 0, 2) }}</td>
                </tr>

                <tr>
                    <th>Payment Status</th>
                    <td>{!! $row->payment_badge !!}</td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>{!! $row->status_badge !!}</td>
                </tr>

                <tr>
                    <th>Date</th>
                    <td>{{ $row->created_at->format('d-m-Y h:i A') }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>

@endsection
