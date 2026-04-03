// =========================
// DOM READY
// =========================
document.addEventListener("DOMContentLoaded", function () {

    const toggleButtons = document.querySelectorAll("#toggleSidebar");
    const sidebar = document.querySelector(".sidebar");
    const mainArea = document.querySelector(".main-area");
    const overlay = document.getElementById("sidebarOverlay");

    // SAFETY CHECK
    if (!sidebar || !mainArea) {
        console.warn("Sidebar or Main Area not found");
        return;
    }

    // =========================
    // SIDEBAR TOGGLE
    // =========================
    toggleButtons.forEach(btn => {
        btn.addEventListener("click", function () {

            // 📱 MOBILE
            if (window.innerWidth <= 768) {

                sidebar.classList.toggle("active");

                if (overlay) {
                    overlay.classList.toggle("active");
                }

                // LOCK BODY SCROLL
                document.body.style.overflow =
                    sidebar.classList.contains("active") ? "hidden" : "auto";

            } 
            // 💻 DESKTOP
            else {

                sidebar.classList.toggle("collapsed");
                mainArea.classList.toggle("expanded");

                // SAVE STATE
                localStorage.setItem(
                    "sidebarCollapsed",
                    sidebar.classList.contains("collapsed")
                );
            }

        });
    });

    // =========================
    // OVERLAY CLICK (MOBILE CLOSE)
    // =========================
    if (overlay) {
        overlay.addEventListener("click", function () {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
            document.body.style.overflow = "auto";
        });
    }

    // =========================
    // AUTO RESET ON RESIZE
    // =========================
    window.addEventListener("resize", function () {

        if (window.innerWidth > 768) {
            sidebar.classList.remove("active");

            if (overlay) {
                overlay.classList.remove("active");
            }

            document.body.style.overflow = "auto";
        }

    });

    // =========================
    // RESTORE DESKTOP STATE
    // =========================
    if (window.innerWidth > 768) {
        const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";

        if (isCollapsed) {
            sidebar.classList.add("collapsed");
            mainArea.classList.add("expanded");
        }
    }

    // =========================
    // RIPPLE EFFECT
    // =========================
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function (e) {

            const ripple = document.createElement("span");
            ripple.classList.add("ripple");

            const rect = this.getBoundingClientRect();
            ripple.style.left = (e.clientX - rect.left) + "px";
            ripple.style.top = (e.clientY - rect.top) + "px";

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });
    });

    // =========================
    // CLOSE SIDEBAR ON LINK CLICK (MOBILE)
    // =========================
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', function () {

            if (window.innerWidth <= 768) {

                sidebar.classList.remove("active");

                if (overlay) {
                    overlay.classList.remove("active");
                }

                document.body.style.overflow = "auto";
            }

        });
    });

});