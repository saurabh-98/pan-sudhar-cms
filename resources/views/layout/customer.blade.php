<!DOCTYPE html>
<html>
<head>
    <title>Customer Panel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- ✅ CUSTOMER CSS ONLY -->
    <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/order.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-header.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-sidebar.css') }}">


</head>

<body>

<div class="customer-wrapper">

    <!-- Sidebar -->
    @include('customer.partial.customer_sidebar')

    <div class="main-area">

        <!-- Header -->
        @include('customer.partial.header')

        <!-- CONTENT + FOOTER -->
        <div class="content-wrapper">

            <div class="content-inner">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('customer.partial.footer')

        </div>

    </div>

</div>

<!-- JS LIBRARIES -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ✅ CUSTOMER JS -->
<script src="{{ asset('assets/js/customer.js') }}"></script>

<!-- PAGE JS -->
@yield('scripts')

<script>
function toggleSidebar() {
    document.getElementById("custSidebar").classList.toggle("active");
    document.getElementById("custSidebarOverlay").classList.toggle("active");
}

// CLOSE ON OVERLAY CLICK
document.getElementById("custSidebarOverlay")?.addEventListener("click", function () {
    toggleSidebar();
});</script>

</body>
</html>