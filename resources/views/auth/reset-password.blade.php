@extends('layout.auth')

@section('content')

<div class="auth-card">

    <h2>🔑 Reset Password</h2>
    <p>Create new password</p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input-group">
            <input type="email" name="email" required>
            <label>Email Address</label>
        </div>

        <div class="input-group">
            <input type="password" name="password" required>
            <label>New Password</label>
        </div>

        <div class="input-group">
            <input type="password" name="password_confirmation" required>
            <label>Confirm Password</label>
        </div>

        <button class="btn-auth">Reset Password</button>

    </form>

</div>

@endsection