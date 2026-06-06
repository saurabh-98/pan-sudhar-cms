<footer class="admin-footer">

    <div class="footer-wrapper">

        <!-- LEFT -->
        <div class="footer-left">

            <span class="brand">
                <i class="fa fa-id-card me-1"></i>
                PAN Sudhar Portal
            </span>

            <span class="version">
                v1.0
            </span>

            <div class="footer-status">

                <span class="footer-status-dot"></span>

                <span>System Online</span>

            </div>

        </div>

        <!-- CENTER -->
        <div class="footer-center">

            <span class="footer-copy">

                © <span id="year"></span>

                PAN Sudhar Portal. All Rights Reserved.

            </span>

        </div>

        <!-- RIGHT -->
        <div class="footer-right">

            <a
                href="{{ route('admin.profile') }}"
                title="My Profile"
            >
                <i class="fa fa-user"></i>
            </a>

            <a
                href="{{ route('admin.dashboard') }}"
                title="Dashboard"
            >
                <i class="fa fa-chart-line"></i>
            </a>

            <a
                href="#"
                title="System Settings"
            >
                <i class="fa fa-cog"></i>
            </a>

            <a
                href="#"
                title="Support"
            >
                <i class="fa fa-headset"></i>
            </a>

        </div>

    </div>

</footer>

<script>
document.getElementById('year').textContent =
    new Date().getFullYear();
</script>