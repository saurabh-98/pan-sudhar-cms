<div class="header">

    <!-- LEFT -->
    <div class="cust-header-left">

        <button class="cust-menu-toggle" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>

        <h5 class="cust-header-title">Customer Dashboard</h5>

    </div>


    <!-- RIGHT -->
    <div class="header-right d-flex align-items-center gap-3">

        <!-- 🔔 Notification -->
        <div class="dropdown position-relative">
            <i class="fa fa-bell notif-icon" data-bs-toggle="dropdown"></i>

            <!-- Badge -->
            <span class="notif-badge">0</span>

            <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                <p class="mb-1 fw-bold">Notifications</p>
                <small class="text-muted">No new notifications</small>
            </div>
        </div>

       
        <!-- 👤 USER -->
        <div class="dropdown user-dropdown">
            <button class="user-btn d-flex align-items-center gap-2" data-bs-toggle="dropdown">

                <!-- PROFILE IMAGE -->
                <img id="headerProfileImage"
                    src="{{ auth()->user()->image ? asset('uploads/'.auth()->user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="rounded-circle"
                    width="35" height="35"
                    style="object-fit: cover;">

                <!-- NAME -->
                <span id="headerUserName">
                    {{ auth()->user()->name }}
                </span>

                <i class="fa fa-angle-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end">

                <li>
                    <a class="dropdown-item" href="{{ route('customer.profile') }}">
                        <i class="fa fa-user"></i> Profile
                    </a>
                </li>


                <li><hr class="dropdown-divider"></li>

                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="dropdown-item text-danger">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>

            </ul>
        </div>

    </div>

</div>