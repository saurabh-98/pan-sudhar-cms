@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4>

                Retailer Wallet Management

            </h4>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <table class="table table-bordered table-striped">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Wallet Balance</th>

                        <th>Add Balance</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($retailers as $user)

                    <tr>

                        <td>{{ $user->id }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>

                            <strong class="text-success">

                                ₹{{ number_format($user->wallet_balance,2) }}

                            </strong>

                        </td>

                        <td>

                            <form action="{{ route('admin.wallet.add',$user->id) }}"
                                  method="POST">

                                @csrf

                                <div class="d-flex gap-2">

                                    <input type="number"
                                           name="amount"
                                           class="form-control"
                                           placeholder="Enter Amount"
                                           required>

                                    <button class="btn btn-primary">

                                        Add

                                    </button>

                                </div>

                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5">

                            No Retailers Found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $retailers->links() }}

        </div>

    </div>

</div>

@endsection