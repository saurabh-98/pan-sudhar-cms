@extends('layout.admin')

@section('title', 'Reports')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm mb-3">

        <div class="card-header bg-primary text-white">

            <h4 class="mb-0">Reports Dashboard</h4>

        </div>

    </div>

    <div class="row">

        @foreach($services as $key => $service)

            <div class="col-md-3 mb-3">

                <a href="{{ route('admin.reports.service', $key) }}" class="text-decoration-none">

                    <div class="card shadow-sm h-100">

                        <div class="card-body text-center">

                            <i class="fa fa-file-alt fa-2x text-primary mb-2"></i>

                            <h6 class="mb-0">{{ $service['title'] }}</h6>

                        </div>

                    </div>

                </a>

            </div>

        @endforeach

    </div>

    <div class="row mt-2">

        <div class="col-md-3 mb-3">

            <a href="{{ route('admin.reports.wallet') }}" class="text-decoration-none">

                <div class="card shadow-sm h-100 border-success">

                    <div class="card-body text-center">

                        <i class="fa fa-wallet fa-2x text-success mb-2"></i>

                        <h6 class="mb-0">Wallet Ledger</h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="{{ route('admin.reports.commission') }}" class="text-decoration-none">

                <div class="card shadow-sm h-100 border-success">

                    <div class="card-body text-center">

                        <i class="fa fa-hand-holding-usd fa-2x text-success mb-2"></i>

                        <h6 class="mb-0">Commission Ledger</h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="{{ route('admin.reports.revenue') }}" class="text-decoration-none">

                <div class="card shadow-sm h-100 border-warning">

                    <div class="card-body text-center">

                        <i class="fa fa-chart-line fa-2x text-warning mb-2"></i>

                        <h6 class="mb-0">Revenue Report</h6>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-md-3 mb-3">

            <a href="{{ route('admin.reports.profit') }}" class="text-decoration-none">

                <div class="card shadow-sm h-100 border-warning">

                    <div class="card-body text-center">

                        <i class="fa fa-balance-scale fa-2x text-warning mb-2"></i>

                        <h6 class="mb-0">Profit &amp; Loss</h6>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>

@endsection
