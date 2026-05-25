@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header">

            <h4>

                Wallet Transactions

            </h4>

        </div>

        <div class="card-body">

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>User</th>

                        <th>Amount</th>

                        <th>Type</th>

                        <th>Remark</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($transactions as $row)

                    <tr>

                        <td>{{ $row->id }}</td>

                        <td>{{ $row->user->name ?? 'N/A' }}</td>

                        <td>

                            ₹{{ number_format($row->amount,2) }}

                        </td>

                        <td>

                            @if($row->type == 'credit')

                                <span class="badge bg-success">

                                    Credit

                                </span>

                            @else

                                <span class="badge bg-danger">

                                    Debit

                                </span>

                            @endif

                        </td>

                        <td>{{ $row->remark }}</td>

                        <td>

                            {{ $row->created_at->format('d M Y h:i A') }}

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="6">

                            No Transactions Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $transactions->links() }}

        </div>

    </div>

</div>

@endsection