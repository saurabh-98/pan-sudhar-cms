<!DOCTYPE html>
<html>
<head>
    <title>Staff Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
     <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
   
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-staff.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-staff.css') }}">

    <style>
        body { margin:0; font-family:Segoe UI; }

        .sidebar {
            width:220px;
            height:100vh;
            position:fixed;
            background:#1e272e;
            color:#fff;
            padding-top:20px;
        }

        .sidebar a {
            display:block;
            padding:12px;
            color:#fff;
            text-decoration:none;
        }

        .sidebar a:hover {
            background:#485460;
        }

        .header {
            margin-left:220px;
            height:60px;
            background:#fff;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 20px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
        }

        .content {
            margin-left:220px;
            padding:20px;
            background:#f1f2f6;
            min-height:100vh;
        }

        .card-box {
            border-radius:12px;
            padding:20px;
            color:#fff;
        }
    </style>
</head>

<body>

@include('staff.partials.sidebar')
@include('staff.partials.header')

<div class="content">
    @yield('content')
</div>

@include('staff.partials.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



@yield('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchBox = document.querySelector(".stfh-search");
    const searchIcon = document.querySelector(".stfh-search i");

    if (window.innerWidth <= 768) {
        searchIcon.addEventListener("click", () => {
            searchBox.classList.toggle("active");
        });
    }

});
</script>

</body>
</html>