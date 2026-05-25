<!DOCTYPE html>
<html>
<head>
    <title>Auth</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    
    <link href="{{ asset('assets/css/auth.css') }}" rel="stylesheet">
     <link href="{{ asset('assets/css/student-auth.css') }}" rel="stylesheet">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* 🔥 GLOBAL BACKGROUND FIX */
        body {
            margin: 0;
            padding: 0;
            background: none !important;
        }

        /* 🔥 PARTICLES BACKGROUND */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            z-index: -1;
        }
    </style>

</head>

<body>

    <!-- 🔥 PARTICLES LAYER (VERY IMPORTANT) -->
    <div id="particles-js"></div>

    <!-- PAGE CONTENT -->
    @yield('content')

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>

    <!-- 🔥 PARTICLES LIB -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>

    <!-- 🔥 PAGE SCRIPTS (IMPORTANT) -->
    @yield('scripts')

</body>
</html>