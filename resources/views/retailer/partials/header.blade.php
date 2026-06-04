@php

    $retailer = \App\Models\Retailer::where(

        'email',
        auth()->user()->email

    )->first();

@endphp

<div class="stfh-header">

    <!-- =====================================================
    | LEFT
    ====================================================== -->
    <div class="stfh-left">

        <!-- MOBILE MENU -->
        <button
            class="btn d-lg-none me-2"
            onclick="toggleRetailerSidebar()"
            aria-label="Toggle Sidebar">

            <i class="fa fa-bars"></i>

        </button>

        <div>

            <h5 class="stfh-title mb-0">

                🏪 Retailer Dashboard

            </h5>

            <small class="text-muted">

                {{ $retailer->shop_name ?? 'Retailer Panel' }}

                •

                {{ $retailer->mobile ?? 'No Mobile' }}

            </small>

        </div>

    </div>

    <!-- =====================================================
    | RIGHT
    ====================================================== -->
    <div class="stfh-right">

        <!-- =====================================================
        | WALLET BALANCE
        ====================================================== -->
        <div class="stfh-wallet d-none d-lg-flex">

            <div class="stfh-wallet-icon">

                <i class="fa fa-wallet"></i>

            </div>

            <div>

                <div class="stfh-wallet-label">

                    Wallet Balance

                </div>

                <div class="stfh-wallet-balance">

                   ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}

                </div>

            </div>

        </div>

         {{-- =====================================================
            | SESSION TIMER
            ====================================================== --}}
            <div class="session-timer-box">

                <i class="fa fa-clock"></i>

                <span id="sessionCountdown">

                    Loading...

                </span>

            </div>




        @if(session()->has('admin_id'))

        <div class="ms-3">
            <a
                href="{{ route('admin.retailer-approvals.back-to-admin') }}"
                class="btn btn-danger btn-sm">

                <i class="fas fa-arrow-left"></i>
                Back To Admin

            </a>
        </div>

        @endif

        <!-- =====================================================
        | NOTIFICATION
        ====================================================== -->
        <div class="dropdown stfh-notif">

            <button
                class="border-0 bg-transparent position-relative"
                data-bs-toggle="dropdown"
                aria-label="Notifications">

                <i class="fa fa-bell stfh-notif-icon"></i>

                @if(($notificationCount ?? 0) > 0)

                    <span class="stfh-badge">

                        {{ $notificationCount }}

                    </span>

                @endif

            </button>

            <div class="dropdown-menu dropdown-menu-end stfh-dropdown shadow border-0 rounded-4 p-3">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h6 class="mb-0 fw-bold">

                        Notifications

                    </h6>

                    <small class="text-muted">

                        {{ $notificationCount ?? 0 }} New

                    </small>

                </div>

                @if(!empty($notifications) && count($notifications))

                    @foreach($notifications as $notification)

                        <div class="mb-3 border-bottom pb-2">

                            <small class="d-block fw-semibold text-dark">

                                {{ $notification['title'] ?? 'Notification' }}

                            </small>

                            <small class="text-muted">

                                {{ $notification['message'] ?? 'Message' }}

                            </small>

                        </div>

                    @endforeach

                @else

                    <div class="text-center py-3">

                        <i
                            class="fa fa-bell-slash text-muted mb-2"
                            style="font-size:22px;"
                        ></i>

                        <p class="small text-muted mb-0">

                            No new notifications

                        </p>

                    </div>

                @endif

            </div>

        </div>

        <!-- =====================================================
        | USER
        ====================================================== -->
        <div class="dropdown stfh-user">

            <button
                class="stfh-user-btn"
                data-bs-toggle="dropdown"
                aria-expanded="false">

                <img
                    id="headerProfileImage"
                    src="https://ui-avatars.com/api/?background=2563eb&color=fff&name={{ urlencode(auth()->user()->name ?? 'Retailer') }}"
                    alt="Retailer Avatar"
                    class="stfh-avatar"
                >

                <div class="text-start d-none d-md-block">

                    <span class="stfh-username d-block">

                        {{ auth()->user()->name }}

                    </span>

                    <small class="text-muted">

                        Retailer Portal

                    </small>

                </div>

                <i class="fa fa-angle-down ms-2"></i>

            </button>

            <ul class="dropdown-menu dropdown-menu-end stfh-dropdown shadow border-0 rounded-4 overflow-hidden">

                <!-- =====================================================
                | PROFILE HEADER
                ====================================================== -->
                <li class="px-3 py-3 bg-light border-bottom">

                    <div class="d-flex align-items-center gap-3">

                        <img
                            src="https://ui-avatars.com/api/?background=2563eb&color=fff&name={{ urlencode(auth()->user()->name ?? 'Retailer') }}"
                            class="rounded-circle"
                            style="
                                width:55px;
                                height:55px;
                            "
                        >

                        <div>

                            <h6 class="mb-0 fw-bold">

                                {{ auth()->user()->name }}

                            </h6>

                            <small class="text-muted">

                                {{ auth()->user()->email }}

                            </small>

                        </div>

                    </div>

                </li>

                <!-- PROFILE -->
                <li>

                    <a
                        class="dropdown-item py-2"
                        href="{{ route('retailer.profile') }}"
                    >

                        <i class="fa fa-user me-2"></i>

                        My Profile

                    </a>

                </li>

                <!-- CUSTOMERS -->
                <li>

                    <a
                        class="dropdown-item py-2"
                        href="{{ route('retailer.customers') }}"
                    >

                        <i class="fa fa-users me-2"></i>

                        Customers

                    </a>

                </li>

                <!-- PAN SERVICES -->
                <li>

                    <a
                        class="dropdown-item py-2"
                        href="{{ route('retailer.pan.apply') }}"
                    >

                        <i class="fa fa-id-card me-2"></i>

                        PAN Services

                    </a>

                </li>

                <!-- AADHAAR -->
                <li>

                    <a
                        class="dropdown-item py-2"
                        href=""
                    >

                        <i class="fa fa-fingerprint me-2"></i>

                        Aadhaar Services

                    </a>

                </li>

                <!-- WALLET -->
                <li>

                    <a
                        class="dropdown-item py-2"
                        href=""
                    >

                        <i class="fa fa-wallet me-2"></i>

                        Wallet

                    </a>

                </li>

                <!-- WALLET BALANCE -->
                <li class="px-3 py-3 border-top">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <small class="text-muted d-block">

                                Available Balance

                            </small>

                            <h5 class="mb-0 fw-bold text-primary">

                                ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}

                            </h5>

                        </div>

                        <div class="stfh-mini-wallet">

                            <i class="fa fa-wallet"></i>

                        </div>

                    </div>

                </li>

                <li>
                    <hr class="dropdown-divider my-1">
                </li>

                <!-- LOGOUT -->
                <li>

                    <form
                        method="POST"
                        action="{{ route('retailer.logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item py-2 text-danger"
                        >

                            <i class="fa fa-sign-out-alt me-2"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</div>