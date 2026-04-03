<div class="sbx-sidebar" id="sbxSidebar">

    <h4 class="sbx-title">🍽️ Admin Panel</h4>
    <button id="closeSidebar" class="sbx-close-btn">
            <i class="fa fa-times"></i>
    </button>

    <ul class="sbx-menu">

        <!-- DASHBOARD -->
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="sbx-link {{ request()->routeIs('admin.dashboard') ? 'sbx-active' : '' }}">
                <i class="fa fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- MANAGEMENT -->
        <li class="sbx-section">Management</li>

        <li>
            <a href="{{ route('admin.categories.index') }}"
               class="sbx-link {{ request()->routeIs('admin.categories*') ? 'sbx-active' : '' }}">
                <i class="fa fa-list"></i>
                <span>Categories</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.menus.index') }}"
               class="sbx-link {{ request()->routeIs('admin.menus*') ? 'sbx-active' : '' }}">
                <i class="fa fa-utensils"></i>
                <span>Menu Items</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.orders.index') }}"
               class="sbx-link {{ request()->routeIs('admin.orders*') ? 'sbx-active' : '' }}">
                <i class="fa fa-shopping-cart"></i>
                <span>Orders</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.order.tracking') }}"
               class="sbx-link {{ request()->routeIs('admin.order.tracking') ? 'sbx-active' : '' }}">
                <i class="fa fa-truck"></i>
                <span>Order Tracking</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.invoices.index') }}"
               class="sbx-link {{ request()->routeIs('admin.invoices*') ? 'sbx-active' : '' }}">
                <i class="fa fa-file-invoice"></i>
                <span>Invoices</span>
            </a>
        </li>

        <!-- RESERVATION -->
        <li class="sbx-section">Reservation System</li>

        <li>
            <a href="{{ route('admin.reservations.index') }}"
               class="sbx-link {{ request()->routeIs('admin.reservations*') ? 'sbx-active' : '' }}">
                <i class="fa fa-calendar-check"></i>
                <span>Reservations</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.tables.index') }}"
               class="sbx-link {{ request()->routeIs('admin.tables*') ? 'sbx-active' : '' }}">
                <i class="fa fa-chair"></i>
                <span>Tables</span>
            </a>
        </li>

        <!-- USERS -->
        <li class="sbx-section">User Management</li>

        <li>
            <a href="{{ route('admin.users.index') }}"
               class="sbx-link {{ request()->routeIs('admin.users.index') ? 'sbx-active' : '' }}">
                <i class="fa fa-user-shield"></i>
                <span>Admin / Staff</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.users.customers.index') }}"
               class="sbx-link {{ request()->routeIs('admin.users.customers*') ? 'sbx-active' : '' }}">
                <i class="fa fa-users"></i>
                <span>Customers</span>
            </a>
        </li>

        <!-- OFFERS -->
        <li>
            <a href="{{ route('admin.offers.index') }}"
               class="sbx-link {{ request()->routeIs('admin.offers*') ? 'sbx-active' : '' }}">
                <i class="fa fa-tags"></i>
                <span>Offers</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.popup.index') }}"
               class="sbx-link {{ request()->routeIs('admin.popup*') ? 'sbx-active' : '' }}">
                <i class="fa fa-bullhorn"></i>
                <span>Popup Offers</span>
            </a>
        </li>

        <!-- CMS -->
        <li class="sbx-section">CMS Management</li>

        <li>
            <a href="{{ route('admin.logo.form') }}"
               class="sbx-link {{ request()->routeIs('admin.logo*') ? 'sbx-active' : '' }}">
                <i class="fa fa-image"></i>
                <span>Logo Settings</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.banners.index') }}"
               class="sbx-link {{ request()->routeIs('admin.banners*') ? 'sbx-active' : '' }}">
                <i class="fa fa-image"></i>
                <span>Hero Banner</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.campaigns.index') }}"
               class="sbx-link {{ request()->routeIs('admin.campaigns*') ? 'sbx-active' : '' }}">
                <i class="fa fa-bullhorn"></i>
                <span>Campaign</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.features.index') }}"
               class="sbx-link {{ request()->routeIs('admin.features*') ? 'sbx-active' : '' }}">
                <i class="fa fa-star"></i>
                <span>Why Choose Us</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.news.index') }}"
               class="sbx-link {{ request()->routeIs('admin.news*') ? 'sbx-active' : '' }}">
                <i class="fa fa-newspaper"></i>
                <span>News</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.pages.index') }}"
               class="sbx-link {{ request()->routeIs('admin.pages*') ? 'sbx-active' : '' }}">
                <i class="fa fa-file"></i>
                <span>Pages</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.navigation.index') }}"
               class="sbx-link {{ request()->routeIs('admin.navigation*') ? 'sbx-active' : '' }}">
                <i class="fa fa-bars"></i>
                <span>Navigation Menu</span>
            </a>
        </li>

        <!-- PAYMENT -->
        <li class="sbx-section">Payment Settings</li>

        <li>
            <a href="{{ route('admin.upi.index') }}"
               class="sbx-link {{ request()->routeIs('admin.upi*') ? 'sbx-active' : '' }}">
                <i class="fa fa-qrcode"></i>
                <span>UPI Settings</span>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.footer.index') }}"
               class="sbx-link {{ request()->routeIs('admin.footer*') ? 'sbx-active' : '' }}">
                <i class="fa fa-link"></i>
                <span>Footer Links</span>
            </a>
        </li>

    </ul>

</div>