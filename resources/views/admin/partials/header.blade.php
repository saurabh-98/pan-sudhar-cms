<div class="hdrx-header">

    <!-- =====================================================
    | LEFT
    ====================================================== -->
    <div class="hdrx-left">

        {{-- SIDEBAR TOGGLE --}}
        <button
            id="toggleSidebar"
            class="hdrx-menu-btn"
        >
            <i class="fa fa-bars"></i>
        </button>

        {{-- =====================================================
        | PAGE TITLE
        ====================================================== --}}
        <div class="hdrx-page-content">

            <h5 class="hdrx-title mb-0">

                @if(auth()->user()->hasRole('admin'))

                    {{ $pageTitle ?? 'Admin Dashboard' }}

                @elseif(auth()->user()->hasRole('Executive'))

                    {{ $pageTitle ?? 'Executive Dashboard' }}

                @else

                    {{ $pageTitle ?? 'Dashboard' }}

                @endif

            </h5>

            @if(!empty($pageSubtitle))

                <small class="hdrx-subtitle">

                    {{ $pageSubtitle }}

                </small>

            @endif

        </div>

        {{-- =====================================================
        | ACTION BUTTON
        ====================================================== --}}
        @if(!empty($pageAction))

            <div class="hdrx-action-area">

                {!! $pageAction !!}

            </div>

        @endif

    </div>

    <!-- =====================================================
    | RIGHT
    ====================================================== -->
    <div class="hdrx-right">

        {{-- =====================================================
        | WALLET BOX
        ====================================================== --}}
        @if(auth()->user()->hasAnyRole(['admin','Executive']))

            <div class="admin-wallet-box">

                <div class="wallet-icon">

                    @if(auth()->user()->hasRole('admin'))

                        <i class="fa fa-wallet"></i>

                    @else

                        <i class="fa fa-briefcase"></i>

                    @endif

                </div>

                <div class="wallet-content">

                    <span>

                        @if(auth()->user()->hasRole('admin'))

                            Admin Wallet

                        @elseif(auth()->user()->hasRole('Executive'))

                            Executive Wallet

                        @endif

                    </span>

                    <h5>

                        ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}

                    </h5>

                </div>

            </div>

        @endif

        {{-- =====================================================
        | SESSION TIMER
        ====================================================== --}}
        <div class="session-timer-box">

            <i class="fa fa-clock"></i>

            <span id="sessionCountdown">

                Loading...

            </span>

        </div>

       

        {{-- =====================================================
        | USER PROFILE
        ====================================================== --}}
        <div class="dropdown hdrx-user">

            <button
                class="hdrx-user-btn"
                data-bs-toggle="dropdown"
            >

                {{-- AVATAR --}}
                <div class="hdrx-avatar-wrapper">

                    <img
                        id="headerProfileImage"

                        src="{{ auth()->user()->image
                            ? asset('uploads/'.auth()->user()->image)
                            : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name)
                        }}"

                        class="hdrx-avatar"
                    >

                    <span class="hdrx-online-dot"></span>

                </div>

                {{-- USER INFO --}}
                <div class="hdrx-user-content">

                    <span class="hdrx-username">

                        {{ auth()->user()->name }}

                    </span>

                    <small class="hdrx-role">

                        @if(auth()->user()->hasRole('admin'))

                            Admin

                        @elseif(auth()->user()->hasRole('Executive'))

                            Executive

                        @else

                            User

                        @endif

                    </small>

                </div>

                <i class="fa fa-angle-down hdrx-angle"></i>

            </button>

            {{-- =====================================================
            | DROPDOWN MENU
            ====================================================== --}}
            <ul class="dropdown-menu dropdown-menu-end hdrx-dropdown hdrx-user-dropdown">

                {{-- PROFILE --}}
                <li>

                    <a
                        class="dropdown-item"

                        href="
                        @if(auth()->user()->hasRole('admin'))

                            {{ route('admin.profile') }}

                        @elseif(auth()->user()->hasRole('Executive'))

                            {{ route('admin.profile') }}

                        @else

                            #
                        @endif
                        "
                    >

                        <i class="fa fa-user"></i>

                        My Profile

                    </a>

                </li>

               

                {{-- ADMIN MENU --}}
                @if(auth()->user()->hasRole('admin'))

                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('admin.dashboard') }}"
                        >

                            <i class="fa fa-shield-alt"></i>

                            Admin Panel

                        </a>

                    </li>

                @endif

                {{-- EXECUTIVE MENU --}}
                @if(auth()->user()->hasRole('Executive'))

                    <li>

                        <a
                            class="dropdown-item"
                            href="{{ route('admin.dashboard') }}"
                        >

                            <i class="fa fa-briefcase"></i>

                            Executive Panel

                        </a>

                    </li>

                @endif

                <li>

                    <hr class="dropdown-divider">

                </li>

                {{-- LOGOUT --}}
                <li>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button class="dropdown-item text-danger">

                            <i class="fa fa-sign-out-alt"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</div>