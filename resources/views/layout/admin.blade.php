<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- ✅ CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/order.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/category-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-admin.css') }}">
    
</head>

<body>

<!-- 🔥 OVERLAY (VERY IMPORTANT - FIXES YOUR ERROR) -->
<div id="sidebarOverlay"></div>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    @include('admin.partials.sidebar')

    <!-- MAIN AREA -->
    <div class="main-area">

        <!-- HEADER -->
        @include('admin.partials.header')

        <!-- CONTENT -->
        <div class="content-wrapper">

            <div class="content content-inner">
                @yield('content')
            </div>

            <!-- FOOTER -->
            @include('admin.partials.footer')

        </div>

    </div>

</div>

<!-- JS LIBRARIES -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- ✅ CUSTOM JS -->
<script src="{{ asset('assets/js/admin.js') }}"></script>

<!-- PAGE JS -->
@yield('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById("toggleSidebar");
    const closeBtn = document.getElementById("closeSidebar");
    const sidebar = document.getElementById("sbxSidebar");
    const body = document.body;

    // OPEN
    toggleBtn.addEventListener("click", function () {

        if (window.innerWidth <= 768) {
            sidebar.classList.add("sbx-show");
        } else {
            sidebar.classList.toggle("sbx-collapse");
            body.classList.toggle("sbx-body-collapse");
        }

    });

    // ❌ CLOSE BUTTON (ALL DEVICES)
    closeBtn.addEventListener("click", function () {

        if (window.innerWidth <= 768) {
            sidebar.classList.remove("sbx-show");
        } else {
            sidebar.classList.remove("sbx-collapse");
            body.classList.remove("sbx-body-collapse");
        }

    });

});
</script>
</body>
</html>