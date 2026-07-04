@php
$user = auth()->user();


$isAdmin = $user->hasRole('admin');
$isSuperDistributor = $user->hasRole('Super Distributor');
$isExecutive = $user->hasRole('Executive');
$isDistributor = $user->hasRole('Distributor');


@endphp

<div class="hdrx-header">


<!-- =====================================================
| LEFT
====================================================== -->
<div class="hdrx-left">

    <button
        id="toggleSidebar"
        class="hdrx-menu-btn"
    >
        <i class="fa fa-bars"></i>
    </button>

    <div class="hdrx-page-content">

        <h5 class="hdrx-title mb-0">

            @if($isAdmin)

                {{ $pageTitle ?? 'Admin Dashboard' }}

            @elseif($isSuperDistributor)

                {{ $pageTitle ?? 'Super Distributor Dashboard' }}

            @elseif($isExecutive)

                {{ $pageTitle ?? 'Executive Dashboard' }}

            @elseif($isDistributor)

                {{ $pageTitle ?? 'Distributor Dashboard' }}

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

    {{-- WALLET --}}
    @if($isAdmin || $isSuperDistributor || $isExecutive || $isDistributor)

        <div class="admin-wallet-box">

            <div class="wallet-icon">

                @if($isAdmin)

                    <i class="fa fa-wallet"></i>

                @elseif($isSuperDistributor)

                    <i class="fa fa-sitemap"></i>

                @elseif($isExecutive)

                    <i class="fa fa-briefcase"></i>

                @elseif($isDistributor)

                    <i class="fa fa-network-wired"></i>

                @endif

            </div>

            <div class="wallet-content">

                <span>

                    @if($isAdmin)

                        Admin Wallet

                    @elseif($isSuperDistributor)

                        Super Distributor Wallet

                    @elseif($isExecutive)

                        Executive Wallet

                    @elseif($isDistributor)

                        Distributor Wallet

                    @endif

                </span>

                <h5>

                    ₹{{ number_format($user->wallet_balance ?? 0, 2) }}

                </h5>

            </div>

        </div>

    @endif


    {{-- USER PROFILE --}}
    <div class="dropdown hdrx-user">

        <button
            class="hdrx-user-btn"
            data-bs-toggle="dropdown"
        >

            <div class="hdrx-avatar-wrapper">

                <img
                    id="headerProfileImage"

                    src="{{ $user->image
                        ? asset('uploads/'.$user->image)
                        : 'https://ui-avatars.com/api/?name='.urlencode($user->name)
                    }}"

                    class="hdrx-avatar"
                >

                <span class="hdrx-online-dot"></span>

            </div>

            <div class="hdrx-user-content">

                <span class="hdrx-username">

                    {{ $user->name }}

                </span>

                <small class="hdrx-role">

                    @if($isAdmin)

                        Admin

                    @elseif($isSuperDistributor)

                        Super Distributor

                    @elseif($isExecutive)

                        Executive

                    @elseif($isDistributor)

                        Distributor

                    @else

                        User

                    @endif

                </small>

            </div>

            <i class="fa fa-angle-down hdrx-angle"></i>

        </button>

        <ul class="dropdown-menu dropdown-menu-end hdrx-dropdown hdrx-user-dropdown">

            {{-- PROFILE --}}
            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('admin.profile') }}"
                >

                    <i class="fa fa-user"></i>

                    My Profile

                </a>

            </li>

            {{-- ADMIN --}}
            @if($isAdmin)

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

            {{-- SUPER DISTRIBUTOR --}}
            @if($isSuperDistributor)

                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('admin.dashboard') }}"
                    >

                        <i class="fa fa-sitemap"></i>

                        Super Distributor Panel

                    </a>

                </li>

            @endif

            {{-- EXECUTIVE --}}
            @if($isExecutive)

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

            {{-- DISTRIBUTOR --}}
            @if($isDistributor)

                <li>

                    <a
                        class="dropdown-item"
                        href="{{ route('admin.dashboard') }}"
                    >

                        <i class="fa fa-network-wired"></i>

                        Distributor Panel

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