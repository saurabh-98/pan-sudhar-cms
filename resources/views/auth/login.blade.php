@extends('layout.auth')

@section('content')

<div class="login-wrapper">

    <div class="login-card">

        <h2 class="login-title"> Foodies Login</h2>
        <p class="login-sub">Welcome back! Please login</p>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <!-- EMAIL -->
            <div class="input-group">
                <span class="input-icon"></span>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    class="input-field"
                >
                <label>Email Address</label>
            </div>

            <!-- PASSWORD -->
            <div class="input-group">
                <span class="input-icon"></span>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    class="input-field"
                >
                <label>Password</label>
                <span class="toggle-password">👁</span>
            </div>

            <!-- REMEMBER -->
            <div style="text-align:left; margin-bottom:15px;">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="btn-login-full">
                Login
            </button>

            <!-- LINKS -->
           
            <div class="extra-links">

                <a href="{{ route('password.request') }}">
                    Forgot Password?
                </a>

                <br>

                <span style="display:block; margin-top:10px;">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="signup-link">
                        Sign Up
                    </a>
                </span>

            </div>

        </form>

    </div>

</div>

<script>
// =======================
// PASSWORD TOGGLE (SAFE)
// =======================
const toggle = document.querySelector(".toggle-password");

if (toggle) {
    toggle.addEventListener("click", function () {
        let input = document.getElementById("password");
        if (!input) return;

        input.type = input.type === "password" ? "text" : "password";
    });
}
</script>

@endsection