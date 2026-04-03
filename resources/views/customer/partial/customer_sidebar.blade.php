<div class="cust-sidebar" id="custSidebar">

    <div class="cust-sidebar-header">

        <h3>🍽️ Foodies</h3>

        <!-- CLOSE BUTTON -->
        <button class="cust-sidebar-close" onclick="toggleSidebar()">
            <i class="fa fa-times"></i>
        </button>

    </div>

    <ul class="cust-sidebar-menu">

        <li>
            <a href="{{ route('customer.dashboard') }}"
               class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('customer.orders') }}"
               class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>My Orders</span>
            </a>
        </li>

        <li>
            <a href="{{ route('customer.reservations') }}"
               class="{{ request()->routeIs('customer.reservations') ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Reservations</span>
            </a>
        </li>

    </ul>

</div>

<!-- MOBILE OVERLAY -->
<div class="cust-sidebar-overlay" id="custSidebarOverlay"></div>