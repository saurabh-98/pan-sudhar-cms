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
        | WALLET CARD
        ===================================================== -->
        <div class="stfh-wallet d-none d-lg-flex">

            <div class="stfh-wallet-left">

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

                   <div class="stfh-wallet-meta">

                
                        <div class="stfh-wallet-item">

                            <small>Outstanding Due</small>

                            <strong class="{{ (auth()->user()->wallet_due ?? 0) > 0 ? 'text-warning' : 'text-white' }}">

                                ₹{{ number_format(auth()->user()->wallet_due ?? 0,2) }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

            <div class="stfh-wallet-actions">

                <a href="{{ route('retailer.wallet.recharge') }}"
                class="stfh-wallet-btn">

                    <i class="fa fa-plus"></i>

                    Recharge

                </a>

                <a href="{{ route('retailer.wallet.recharge-history') }}"
                class="stfh-wallet-history">

                    <i class="fa fa-history"></i>

                </a>

            </div>

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


                <!-- WALLET BALANCE -->
                <li class="px-3 py-3 border-top">

                    <div class="d-flex align-items-center justify-content-between">

                        <div>

                            <small class="text-muted d-block">
                                Available Balance
                            </small>

                            <h5 class="mb-2 fw-bold text-primary">
                                ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}
                            </h5>

                            <small class="text-muted d-block">
                                Outstanding Due
                            </small>

                            @if((auth()->user()->wallet_due ?? 0) > 0)

                                <h6 class="mb-0 fw-bold text-danger">
                                    ₹{{ number_format(auth()->user()->wallet_due,2) }}
                                </h6>

                            @else

                                <h6 class="mb-0 fw-bold text-success">
                                    ₹0.00
                                </h6>

                            @endif

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