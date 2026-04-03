@extends('layout.auth')

@section('content')

<div class="login-wrapper">

    <div class="login-card">

        <h2 class="login-title">🍔 Create Account</h2>
        <p class="login-sub">Join Foodies today</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAME -->
            <div class="input-group">
                <input type="text" name="name" required class="input-field" placeholder=" ">
                <label>Full Name</label>
                <small class="error-text"></small>
            </div>

            <!-- EMAIL -->
            <div class="input-group">
                <input type="email" name="email" required class="input-field" placeholder=" ">
                <label>Email Address</label>
                <small class="error-text"></small>
            </div>

            <!-- PASSWORD -->
            <div class="input-group">
                <input type="password" id="password" name="password" required class="input-field" placeholder=" ">
                <label>Password</label>
                <small class="error-text"></small>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="input-group">
                <input type="password" name="password_confirmation" required class="input-field" placeholder=" ">
                <label>Confirm Password</label>
                <small class="error-text"></small>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-login-full">
                Register
            </button>

            <!-- LINKS -->
            <div class="extra-links">
                <a href="{{ route('login') }}">Already have account? Login</a>
            </div>

        </form>

    </div>

</div>

@endsection