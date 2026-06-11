<!DOCTYPE html>
<html lang="en">

<head>

    {{-- =====================================================
    | META
    ====================================================== --}}
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>

        @yield('title', 'Retailer Panel')

    </title>

    {{-- =====================================================
    | GOOGLE FONT
    ====================================================== --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    {{-- =====================================================
    | BOOTSTRAP
    ====================================================== --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- =====================================================
    | FONT AWESOME
    ====================================================== --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >

    {{-- =====================================================
    | DATATABLE
    ====================================================== --}}
    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css"
    >

    {{-- =====================================================
    | TOASTR
    ====================================================== --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    >

    {{-- =====================================================
    | GLOBAL CSS FILES
    ====================================================== --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-layout.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-sidebar.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-header.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-footer.css') }}"
    >

    {{-- =====================================================
    | PAGE CSS
    ====================================================== --}}
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-dashboard.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/pan-new-application.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/preview-new-pan.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/pan-acknowledgement.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/pan-history.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/new-pan-show.css') }}"
    >

     <link
        rel="stylesheet"
        href="{{ asset('assets/css/retailer-wallet.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/itr-file.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/itr-file-history.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('assets/css/aadhaar-create.css') }}"
    >


</head>

<body>


    {{-- =====================================================
    | MAIN LAYOUT
    ====================================================== --}}
    <div class="retailer-layout">

        {{-- =================================================
        | SIDEBAR
        ================================================== --}}
        @include('retailer.partials.sidebar')

        {{-- =================================================
        | MAIN CONTENT
        ================================================== --}}
        <div class="main-content">

            {{-- HEADER --}}
            @include('retailer.partials.header')

            {{-- PAGE CONTENT --}}
            <main class="page-content">

                @yield('content')

            </main>

            {{-- FOOTER --}}
            @include('retailer.partials.footer')

        </div>

    </div>

    {{-- =====================================================
    | MOBILE OVERLAY
    ====================================================== --}}
    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>

    {{-- =====================================================
    | JQUERY
    ====================================================== --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- =====================================================
    | BOOTSTRAP
    ====================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- =====================================================
    | DATATABLE
    ====================================================== --}}
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    {{-- =====================================================
    | SWEET ALERT
    ====================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- =====================================================
    | TOASTR
    ====================================================== --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- =====================================================
    | SIDEBAR SCRIPT
    ====================================================== --}}
    <script>

        function toggleRetailerSidebar()
        {
            document
                .getElementById('retailerSidebar')
                ?.classList
                .toggle('active');

            document
                .getElementById('sidebarOverlay')
                ?.classList
                .toggle('active');
        }

        document
            .getElementById('sidebarOverlay')
            ?.addEventListener(

                'click',

                function () {

                    document
                        .getElementById('retailerSidebar')
                        ?.classList
                        .remove('active');

                    this.classList.remove('active');

                }

            );

    </script>

    {{-- =====================================================
    | PAGE SCRIPT
    ====================================================== --}}
    @yield('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        document
            .querySelectorAll('.stf-dropdown > .stf-link')
            .forEach(function (link) {

                link.addEventListener('click', function (e) {

                    e.preventDefault();

                    this.parentElement.classList.toggle('open');

                });

            });

    });
    </script>

   <script>

    document.addEventListener('DOMContentLoaded', function () {

        let sessionMinutes =
            {{ config('session.lifetime') }};

        let remainingSeconds =
            sessionMinutes * 60;

        let logoutTriggered = false;

        const countdownEl =
            document.getElementById(
                'sessionCountdown'
            );

        function formatTime(seconds)
        {
            let mins =
                Math.floor(seconds / 60);

            let secs =
                seconds % 60;

            return String(mins).padStart(2,'0')
                + ':'
                + String(secs).padStart(2,'0');
        }

        function forceLogout()
        {
            if(logoutTriggered){
                return;
            }

            logoutTriggered = true;

            Swal.fire({

                icon: 'warning',

                title: 'Session Expired',

                text: 'You have been logged out due to inactivity.',

                allowOutsideClick: false,

                allowEscapeKey: false,

                confirmButtonText: 'OK'

            }).then(function(){

                window.location.replace(
                    "{{ route('retailer.logout.idle') }}"
                );

            });
        }

        function updateCountdown()
        {
            if(logoutTriggered){
                return;
            }

            if(countdownEl){

                countdownEl.innerHTML =
                    formatTime(
                        remainingSeconds
                    );
            }

            if(remainingSeconds === 60){

                Swal.fire({

                    toast:true,

                    position:'top-end',

                    icon:'warning',

                    title:'Session will expire in 1 minute',

                    timer:5000,

                    showConfirmButton:false

                });
            }

            if(remainingSeconds <= 0){

                forceLogout();

                return;
            }

            remainingSeconds--;
        }

        /*
        |--------------------------------------------------------------------------
        | RESET TIMER ON ACTIVITY
        |--------------------------------------------------------------------------
        */

        function resetSessionTimer()
        {
            remainingSeconds =
                sessionMinutes * 60;
        }

        [
            'mousemove',
            'mousedown',
            'click',
            'scroll',
            'keypress',
            'touchstart'
        ].forEach(function(event){

            document.addEventListener(
                event,
                resetSessionTimer,
                true
            );

        });

        updateCountdown();

        setInterval(
            updateCountdown,
            1000
        );

        /*
        |--------------------------------------------------------------------------
        | DETECT LARAVEL SESSION EXPIRY
        |--------------------------------------------------------------------------
        */

        setInterval(function(){

            fetch(
                "{{ route('retailer.dashboard') }}",
                {
                    method: 'GET',
                    credentials: 'same-origin'
                }
            )
            .then(function(response){

                if (
                    response.redirected ||
                    response.status === 401 ||
                    response.status === 419
                ) {

                    forceLogout();

                }

            })
            .catch(function(){

                forceLogout();

            });

        }, 30000);

    });

</script>

</body></html>