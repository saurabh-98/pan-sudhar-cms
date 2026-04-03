<div class="stf-sidebar" id="stfSidebar">

    <!-- TOP -->
    <div class="stf-top">
        <h4 class="stf-title">👨‍🍳 Staff Panel</h4>

        <!-- ❌ Close (mobile) -->
        <button class="stf-close" id="stfClose">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- MENU -->
    <ul class="stf-menu">

        <li>
            <a href="{{ route('staff.dashboard') }}"
               class="stf-link {{ request()->routeIs('staff.dashboard') ? 'stf-active' : '' }}">
                <i class="fa fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li>
            <a href="{{ route('staff.orders.index') }}"
               class="stf-link {{ request()->routeIs('staff.orders*') ? 'stf-active' : '' }}">
                <i class="fa fa-list"></i>
                <span>Orders</span>
            </a>
        </li>

    </ul>

</div>