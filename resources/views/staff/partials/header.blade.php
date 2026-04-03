<div class="stfh-header">

    <!-- LEFT -->
    <div class="stfh-left">

        <h5 class="stfh-title">Staff Dashboard</h5>

    </div>


    <!-- RIGHT -->
    <div class="stfh-right">

        <!-- Notification -->
        <div class="dropdown stfh-notif">
            <i class="fa fa-bell stfh-notif-icon" data-bs-toggle="dropdown"></i>
            <span class="stfh-badge">0</span>

            <div class="dropdown-menu dropdown-menu-end stfh-dropdown">
                <p class="mb-1 fw-bold">Notifications</p>
                <small class="text-muted">No new notifications</small>
            </div>
        </div>

        <!-- USER -->
        <div class="dropdown stfh-user">

            <button class="stfh-user-btn" data-bs-toggle="dropdown">

                <img id="headerProfileImage"
                    src="{{ auth()->user()->image ? asset('uploads/'.auth()->user()->image) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                    class="stfh-avatar">

                <span class="stfh-username">
                    {{ auth()->user()->name }}
                </span>

                <i class="fa fa-angle-down"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end stfh-dropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('staff.profile') }}">
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