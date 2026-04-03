@extends('layout.customer')

@section('content')

<div class="container mt-4">

    <h3 class="mb-4">👤 My Profile</h3>

    <div class="card p-4">

        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

    </div>

</div>

@endsection