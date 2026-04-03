@extends('layout.auth')

@section('content')

<style>
/* ===== BACKGROUND ===== */
body {
    background: linear-gradient(135deg, #ff5a00, #ff8c42);
    min-height: 100vh;
}

/* ===== CARD ===== */
.auth-card {
    max-width: 420px;
    margin: 80px auto;
    padding: 35px;
    border-radius: 18px;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    color: #fff;
    animation: fadeIn 0.6s ease;
}

/* ===== TITLE ===== */
.auth-card h2 {
    font-weight: 700;
    margin-bottom: 10px;
}

.auth-card p {
    font-size: 14px;
    opacity: 0.9;
}

/* ===== INPUT ===== */
.input-group {
    position: relative;
    margin-top: 20px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: none;
    outline: none;
    background: rgba(255,255,255,0.2);
    color: #fff;
}

/* FLOAT LABEL */
.input-group label {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    font-size: 13px;
    color: #eee;
    transition: 0.3s;
}

.input-group input:focus + label,
.input-group input:valid + label {
    top: -8px;
    font-size: 11px;
    background: #ff5a00;
    padding: 2px 6px;
    border-radius: 6px;
}

/* ===== BUTTON ===== */
.btn-auth {
    width: 100%;
    margin-top: 25px;
    padding: 12px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #fff, #f1f1f1);
    color: #ff5a00;
    font-weight: 700;
    transition: 0.3s;
}

.btn-auth:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

/* ===== LINK ===== */
.link {
    margin-top: 15px;
    text-align: center;
}

.link a {
    color: #fff;
    text-decoration: underline;
}

/* ===== SUCCESS MESSAGE ===== */
.alert-success {
    background: rgba(40,167,69,0.2);
    padding: 10px;
    border-radius: 10px;
    margin-top: 10px;
}

/* ===== ERROR ===== */
.error {
    font-size: 12px;
    color: #ffdddd;
}

/* ===== ANIMATION ===== */
@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}
</style>


<div class="auth-card">

    <h2>🔐 Forgot Password</h2>
    <p>Enter your email to reset your password</p>

    {{-- SUCCESS MESSAGE --}}
    @if (session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-group">
            <input type="email" name="email" required value="{{ old('email') }}">
            <label>Email Address</label>
        </div>

        {{-- ERROR --}}
        @error('email')
            <div class="error mt-1">{{ $message }}</div>
        @enderror

        <button class="btn-auth">
            📩 Send Reset Link
        </button>

        <div class="link">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </form>

</div>

@endsection