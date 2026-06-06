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

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
     <link rel="stylesheet" href="{{ asset('assets/css/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/gallery-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-new-pan.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-new-pan-show.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/charges-admin.css') }}">



</head>

<body>

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

            <!-- ✅ OPTIONAL PAGE HEADER (Reusable) -->
            @hasSection('pageHeader')
                <div class="px-3 pt-3">
                    @yield('pageHeader')
                </div>
            @endif

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

<!-- CUSTOM JS -->
<script src="{{ asset('assets/js/admin.js') }}"></script>

<!-- PAGE JS -->
@yield('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function () {

        /*
        |--------------------------------------------------------------------------
        | ELEMENTS
        |--------------------------------------------------------------------------
        */

        const body = document.body;

        const sidebar =
            document.getElementById("sbxSidebar");

        const toggleBtn =
            document.getElementById("toggleSidebar");

        const closeBtn =
            document.getElementById("closeSidebar");

        const sections =
            document.querySelectorAll(".sbx-section");


        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        if (!sidebar) {

            console.warn("Sidebar not found");

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE OPEN FUNCTION
        |--------------------------------------------------------------------------
        */

        window.openSidebar = function () {

            sidebar.classList.add("sbx-show");

            body.classList.add("sidebar-open");

        };


        /*
        |--------------------------------------------------------------------------
        | TOGGLE BUTTON
        |--------------------------------------------------------------------------
        */

        if (toggleBtn) {

            toggleBtn.addEventListener("click", function () {

                /*
                |--------------------------------------------------------------------------
                | MOBILE
                |--------------------------------------------------------------------------
                */

                if (window.innerWidth <= 768) {

                    sidebar.classList.toggle("sbx-show");

                    body.classList.toggle("sidebar-open");

                }

                /*
                |--------------------------------------------------------------------------
                | DESKTOP
                |--------------------------------------------------------------------------
                */

                else {

                    sidebar.classList.toggle("sbx-collapse");

                    body.classList.toggle("sbx-body-collapse");

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE BUTTON
        |--------------------------------------------------------------------------
        */

        if (closeBtn) {

            closeBtn.addEventListener("click", function () {

                sidebar.classList.remove("sbx-show");

                body.classList.remove("sidebar-open");

            });

        }


        /*
        |--------------------------------------------------------------------------
        | SECTION ACCORDION
        |--------------------------------------------------------------------------
        */

        sections.forEach(function (section) {

            section.addEventListener("click", function (e) {

                e.preventDefault();

                e.stopPropagation();

                /*
                |--------------------------------------------------------------------------
                | FIND GROUP
                |--------------------------------------------------------------------------
                */

                let group =
                    this.nextElementSibling;

                /*
                |--------------------------------------------------------------------------
                | SAFETY
                |--------------------------------------------------------------------------
                */

                if (
                    !group ||
                    !group.classList.contains("sbx-group")
                ) {

                    group =
                        this.parentElement
                            .querySelector(".sbx-group");
                }

                if (!group) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | CURRENT STATE
                |--------------------------------------------------------------------------
                */

                const isOpen =
                    group.classList.contains("open");

                /*
                |--------------------------------------------------------------------------
                | CLOSE ALL
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll(".sbx-group")
                    .forEach(function (item) {

                        item.classList.remove("open");

                    });

                document
                    .querySelectorAll(".sbx-section")
                    .forEach(function (item) {

                        item.classList.remove("active");

                    });

                /*
                |--------------------------------------------------------------------------
                | TOGGLE CURRENT
                |--------------------------------------------------------------------------
                */

                if (!isOpen) {

                    group.classList.add("open");

                    section.classList.add("active");

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | SUB LEVEL SUPPORT
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(".sbx-group li > a")
            .forEach(function (link) {

                link.addEventListener("click", function (e) {

                    const next =
                        this.nextElementSibling;

                    if (
                        next &&
                        next.classList.contains("sbx-group")
                    ) {

                        e.preventDefault();

                        next.classList.toggle("open");

                        this.classList.toggle("active");

                    }

                });

            });


        /*
        |--------------------------------------------------------------------------
        | AUTO OPEN ACTIVE PATH
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(".sbx-active")
            .forEach(function (active) {

                let parentGroup =
                    active.closest(".sbx-group");

                while (parentGroup) {

                    parentGroup.classList.add("open");

                    const section =
                        parentGroup.previousElementSibling;

                    if (
                        section &&
                        section.classList.contains("sbx-section")
                    ) {

                        section.classList.add("active");

                    }

                    parentGroup =
                        parentGroup.parentElement
                            ?.closest(".sbx-group");
                }

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
                "{{ route('admin.logout.idle') }}"
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
            "{{ route('admin.dashboard') }}",
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
<script>

    const closeBtn =
    document.getElementById("closeSidebar");

if (closeBtn) {

    closeBtn.addEventListener("click", function () {

        sidebar.classList.remove("sbx-show");

        document.body.classList.remove("sidebar-open");

    });

}
</script>
</body>
</html>