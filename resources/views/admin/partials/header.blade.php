<div class="hdrx-header">

    <!-- LEFT -->
    <div class="hdrx-left">

        <!-- Toggle -->
        <button id="toggleSidebar" class="hdrx-menu-btn">
            <i class="fa fa-bars"></i>
        </button>

        <h5 class="hdrx-title">Admin Dashboard</h5>
    </div>

    <!-- RIGHT -->
    <div class="hdrx-right">

        <!-- Notification -->
        <div class="dropdown hdrx-notif">
            <i class="fa fa-bell hdrx-notif-icon" data-bs-toggle="dropdown"></i>
            <span class="hdrx-badge">0</span>

            <div class="dropdown-menu dropdown-menu-end hdrx-dropdown">
                <p class="mb-1 fw-bold">Notifications</p>
                <small class="text-muted">No new notifications</small>
            </div>
        </div>

        <!-- USER -->
        <div class="dropdown hdrx-user">

            <button class="hdrx-user-btn" data-bs-toggle="dropdown">

                <img id="headerProfileImage"
                    src="{{ auth()->user()->image ? asset('uploads/'.auth()->user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="hdrx-avatar">

                <span class="hdrx-username">
                    {{ auth()->user()->name }}
                </span>

                <i class="fa fa-angle-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end hdrx-dropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
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