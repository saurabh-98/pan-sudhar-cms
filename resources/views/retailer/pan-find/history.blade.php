@extends('layout.retailer')

@section('title','PAN Find History')

@section('content')

<div class="container-fluid py-4">

    <div class="row">

        <div class="col-12">

            <div class="card border-0 shadow-lg rounded-4">

                <div class="card-header bg-primary text-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h4 class="mb-1">

                                <i class="fas fa-history me-2"></i>

                                PAN Find History

                            </h4>

                            <small>

                                View all PAN Find requests

                            </small>

                        </div>

                        <div>

                            <a

                                href="{{ route('retailer.pan-find.apply') }}"

                                class="btn btn-light"

                            >

                                <i class="fas fa-plus-circle me-2"></i>

                                New Request

                            </a>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle">

                            <thead class="table-primary">

                                <tr>

                                    <th width="70">

                                        #

                                    </th>

                                    <th>

                                        Aadhaar Number

                                    </th>

                                    <th>

                                        Charge

                                    </th>

                                    <th>

                                        Status

                                    </th>

                                    <th>

                                        Date

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($histories as $history)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            {{ $history->aadhaar_number }}

                                        </td>

                                        <td>

                                            ₹ {{ number_format($history->amount,2) }}

                                        </td>

                                        <td>

                                            @if($history->status=='Completed')

                                                <span class="badge bg-success">

                                                    Completed

                                                </span>

                                            @else

                                                <span class="badge bg-warning">

                                                    {{ $history->status }}

                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            {{ $history->created_at->format('d M Y h:i A') }}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5" class="text-center py-5">

                                            <img

                                                src="https://cdn-icons-png.flaticon.com/512/7486/7486740.png"

                                                width="90"

                                                class="mb-3"

                                            >

                                            <h5>

                                                No Record Found

                                            </h5>

                                            <p class="text-muted">

                                                No PAN Find request submitted yet.

                                            </p>

                                            <a

                                                href="{{ route('retailer.pan-find.apply') }}"

                                                class="btn btn-primary"

                                            >

                                                Submit First Request

                                            </a>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    @if($histories->count())

                        <div class="mt-4">

                            {{ $histories->links() }}

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection