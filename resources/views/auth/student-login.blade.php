@extends('layout.app')

@section('content')

{{-- =========================================================
| TOASTR CSS
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

{{-- =========================================================
| FONT AWESOME
========================================================= --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

:root{
    --primary:#4f46e5;
    --secondary:#06b6d4;
    --dark:#0f172a;
    --light:#f8fafc;
}

body{
    background: linear-gradient(135deg,#0f172a,#1e293b,#312e81);
    min-height:100vh;
    overflow-x:hidden;
}

.student-login-wrapper{
    position:relative;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 15px;
}

.bg-circle{
    position:absolute;
    border-radius:50%;
    filter:blur(80px);
    opacity:.35;
    animation: float 8s infinite ease-in-out;
}

.bg-circle.one{
    width:300px;
    height:300px;
    background:#06b6d4;
    top:-100px;
    left:-100px;
}

.bg-circle.two{
    width:250px;
    height:250px;
    background:#8b5cf6;
    right:-80px;
    bottom:-80px;
}

@keyframes float{
    0%,100%{transform:translateY(0px);}
    50%{transform:translateY(-20px);}
}

.student-login-card{
    width:100%;
    max-width:1200px;
    background:rgba(255,255,255,.08);
    backdrop-filter:blur(16px);
    border:1px solid rgba(255,255,255,.1);
    border-radius:30px;
    overflow:hidden;
    box-shadow:0 20px 60px rgba(0,0,0,.35);
}

.student-left-panel{
    background:
    linear-gradient(rgba(15,23,42,.85),rgba(15,23,42,.85)),
    url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1200');
    background-size:cover;
    background-position:center;
    height:100%;
    position:relative;
    padding:60px 45px;
    color:#fff;
}

.logo-box img{
    width:90px;
    margin-bottom:25px;
}

.student-left-panel h1{
    font-size:42px;
    font-weight:800;
    margin-bottom:20px;
}

.student-left-panel p{
    color:rgba(255,255,255,.85);
    line-height:1.8;
    margin-bottom:35px;
}

.student-feature{
    display:flex;
    align-items:flex-start;
    gap:15px;
    margin-bottom:25px;
}

.student-feature-icon{
    width:55px;
    height:55px;
    border-radius:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:rgba(255,255,255,.12);
    font-size:24px;
    backdrop-filter:blur(10px);
}

.student-feature h5{
    margin:0;
    font-weight:700;
}

.student-feature span{
    color:rgba(255,255,255,.75);
    font-size:14px;
}

.student-right-panel{
    padding:60px;
    background:#fff;
}

.login-header{
    text-align:center;
    margin-bottom:40px;
}

.login-avatar{
    width:95px;
    height:95px;
    border-radius:50%;
    margin:auto;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:42px;
    margin-bottom:20px;
    box-shadow:0 10px 30px rgba(79,70,229,.35);
}

.login-header h2{
    font-weight:800;
    color:var(--dark);
}

.login-header p{
    color:#64748b;
}

.form-group-modern{
    margin-bottom:28px;
}

.form-group-modern label{
    font-weight:700;
    margin-bottom:10px;
    display:block;
    color:#1e293b;
}

.input-box{
    position:relative;
}

.input-box i{
    position:absolute;
    top:50%;
    left:18px;
    transform:translateY(-50%);
    color:#64748b;
}

.input-box input{
    width:100%;
    height:58px;
    border-radius:16px;
    border:2px solid #e2e8f0;
    padding-left:52px;
    padding-right:55px;
    font-size:15px;
    transition:.3s;
    background:#fff;
}

.input-box input:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 4px rgba(79,70,229,.1);
    outline:none;
}

.toggle-password{
    position:absolute;
    top:50%;
    right:18px;
    transform:translateY(-50%);
    cursor:pointer;
    color:#64748b;
}

.extra-options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:28px;
}

.extra-options a{
    text-decoration:none;
    color:var(--primary);
    font-weight:600;
}

.btn-student-login{
    width:100%;
    height:58px;
    border:none;
    border-radius:16px;
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    color:#fff;
    font-weight:700;
    font-size:16px;
    transition:.3s;
    box-shadow:0 10px 25px rgba(79,70,229,.25);
}

.btn-student-login:hover{
    transform:translateY(-2px);
    box-shadow:0 15px 35px rgba(79,70,229,.35);
}

.login-footer{
    text-align:center;
    margin-top:30px;
    color:#64748b;
}

@media(max-width:991px){

    .student-left-panel{
        display:none;
    }

    .student-right-panel{
        padding:40px 25px;
    }
}

</style>

<div class="student-login-wrapper">

    <div class="bg-circle one"></div>
    <div class="bg-circle two"></div>

    <div class="student-login-card">

        <div class="row g-0">

            {{-- LEFT PANEL --}}
            <div class="col-lg-5">

                <div class="student-left-panel">

                    <div class="logo-box">

                        <img src="{{ asset('logo.png') }}" alt="Logo">

                    </div>

                    <h1>Student Portal</h1>

                    <p>
                        Access assignments, attendance, exam results,
                        online classes, notices, fee details,
                        and your digital academic dashboard securely.
                    </p>

                    <div class="student-feature">

                        <div class="student-feature-icon">📘</div>

                        <div>
                            <h5>Online Classes</h5>
                            <span>Attend live digital sessions</span>
                        </div>

                    </div>

                    <div class="student-feature">

                        <div class="student-feature-icon">📝</div>

                        <div>
                            <h5>Exam & Results</h5>
                            <span>Track marks and report cards</span>
                        </div>

                    </div>

                    <div class="student-feature">

                        <div class="student-feature-icon">📅</div>

                        <div>
                            <h5>Attendance Tracking</h5>
                            <span>View daily attendance reports</span>
                        </div>

                    </div>

                    <div class="student-feature">

                        <div class="student-feature-icon">🎓</div>

                        <div>
                            <h5>Digital Student ID</h5>
                            <span>Smart virtual identity card</span>
                        </div>

                    </div>

                </div>

            </div>

            {{-- RIGHT PANEL --}}
            <div class="col-lg-7">

                <div class="student-right-panel">

                    <div class="login-header">

                        <div class="login-avatar">

                            <i class="fa-solid fa-user-graduate"></i>

                        </div>

                        <h2>Welcome Student</h2>

                        <p>
                            Secure login to continue your learning journey
                        </p>

                    </div>

                    <form id="studentLoginForm">

                        @csrf

                        {{-- STUDENT ID --}}
                        <div class="form-group-modern">

                            <label>Student Email / Admission No.</label>

                            <div class="input-box">

                                <i class="fa-solid fa-id-card"></i>

                                <input
                                    type="text"
                                    name="email"
                                    id="email"
                                    placeholder="Enter Email or Admission Number"
                                    required>

                            </div>

                            <span class="text-danger error-email"></span>

                        </div>

                        {{-- PASSWORD --}}
                        <div class="form-group-modern">

                            <label>Password</label>

                            <div class="input-box">

                                <i class="fa-solid fa-lock"></i>

                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Enter Password"
                                    required>

                                <span class="toggle-password">

                                    <i class="fa-solid fa-eye"></i>

                                </span>

                            </div>

                            <span class="text-danger error-password"></span>

                        </div>

                        <div class="extra-options">

                            <div class="form-check">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="remember">

                                <label
                                    class="form-check-label"
                                    for="remember">

                                    Remember Me

                                </label>

                            </div>

                            <a href="#">
                                Forgot Password?
                            </a>

                        </div>

                        <button
                            type="submit"
                            class="btn-student-login"
                            id="loginBtn">

                            <i class="fa-solid fa-right-to-bracket me-2"></i>

                            Login Securely

                        </button>

                    </form>

                    <div class="login-footer">

                        © {{ date('Y') }} Smart School ERP

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>

$(document).ready(function () {

    toastr.options = {

        closeButton:true,
        progressBar:true,
        positionClass:"toast-top-right",
        timeOut:"3000"
    };

    /*
    |--------------------------------------------------------------------------
    | PASSWORD TOGGLE
    |--------------------------------------------------------------------------
    */

    $('.toggle-password').click(function(){

        let input = $('#password');
        let icon = $(this).find('i');

        if(input.attr('type') === 'password'){

            input.attr('type','text');

            icon.removeClass('fa-eye')
                .addClass('fa-eye-slash');

        }else{

            input.attr('type','password');

            icon.removeClass('fa-eye-slash')
                .addClass('fa-eye');
        }
    });

    /*
    |--------------------------------------------------------------------------
    | LOGIN FORM
    |--------------------------------------------------------------------------
    */

    $('#studentLoginForm').submit(function(e){

        e.preventDefault();

        $('.text-danger').html('');

        let btn = $('#loginBtn');

        btn.prop('disabled', true);

        btn.html(`
            <span class="spinner-border spinner-border-sm me-2"></span>
            Authenticating...
        `);

        $.ajax({

            url:"{{ route('student.login.submit') }}",

            type:"POST",

            data:{
                _token:"{{ csrf_token() }}",
                email:$('#email').val(),
                password:$('#password').val(),
                remember:$('#remember').is(':checked') ? 1 : 0
            },

            success:function(response){

                toastr.success(
                    response.message || 'Login Successful'
                );

                btn.html(`
                    <i class="fa-solid fa-check me-2"></i>
                    Redirecting...
                `);

                setTimeout(function(){

                    window.location.href =
                    response.redirect ||
                    "{{ route('student.dashboard') }}";

                },1500);
            },

            error:function(xhr){

                btn.prop('disabled', false);

                btn.html(`
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Login Securely
                `);

                if(xhr.status === 422){

                    let errors = xhr.responseJSON.errors;

                    $.each(errors,function(key,value){

                        $('.error-' + key).html(value[0]);

                    });

                    toastr.error('Validation Error');

                }else{

                    toastr.error(
                        xhr.responseJSON.message ||
                        'Invalid Credentials'
                    );
                }
            }
        });
    });

});

</script>

@endsection