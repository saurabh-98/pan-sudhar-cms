@extends('layout.app')

@section('content')

<h2>Dashboard</h2>

<div class="row">

    <div class="col-md-3">
        <div class="card bg-success text-white p-3">
            <h4>Total Orders</h4>
            <p>120</p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white p-3">
            <h4>Total Revenue</h4>
            <p>₹50,000</p>
        </div>
    </div>

</div>

@endsection