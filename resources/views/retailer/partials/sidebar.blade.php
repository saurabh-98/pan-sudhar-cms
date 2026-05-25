<div
    class="stf-sidebar"
    id="retailerSidebar"
>

    {{-- =====================================================
    | SIDEBAR TOP
    ====================================================== --}}
    <div class="stf-top">

        <div class="stf-brand">

            <div class="stf-brand-icon">

                <i class="fa fa-store"></i>

            </div>

            <div>

                <h4 class="stf-title">

                    Retailer Portal

                </h4>

                <small class="stf-subtitle">

                    Digital Service Panel

                </small>

            </div>

        </div>

        {{-- MOBILE CLOSE --}}
        <button
            class="stf-close d-lg-none"
            onclick="toggleRetailerSidebar()"
        >

            <i class="fa fa-times"></i>

        </button>

    </div>

    {{-- =====================================================
    | PROFILE CARD
    ====================================================== --}}
    <div class="stf-profile">

        <div class="stf-profile-image-wrap">

            <div class="stf-avatar">

                {{ strtoupper(substr(auth()->user()->name,0,1)) }}

            </div>

            <span class="stf-status-dot"></span>

        </div>

        <h5 class="stf-profile-name">

            {{ auth()->user()->name }}

        </h5>

        <p class="stf-profile-role">

            Authorized Retailer

        </p>

        {{-- WALLET --}}
        <div class="stf-wallet-card">

            <div>

                <small>

                    Wallet Balance

                </small>

                <h4>

                    ₹{{ number_format(auth()->user()->wallet_balance ?? 0,2) }}

                </h4>

            </div>

            <div class="stf-wallet-icon">

                <i class="fa fa-wallet"></i>

            </div>

        </div>

    </div>

    {{-- =====================================================
    | MENU WRAPPER
    ====================================================== --}}
    <div class="stf-menu-wrapper">

        <ul class="stf-menu">

            {{-- =====================================================
            | MAIN MENU
            ====================================================== --}}
            <li class="stf-menu-title">

                MAIN MENU

            </li>

            {{-- DASHBOARD --}}
            <li>

                <a
                    href="{{ route('retailer.dashboard') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.dashboard') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-home"></i>

                        <span>

                            Dashboard

                        </span>

                    </div>

                </a>

            </li>


            {{-- =====================================================
            | PAN SERVICES
            ====================================================== --}}
            <li class="stf-menu-title mt-4">

                PAN SERVICES

            </li>

            {{-- APPLY PAN --}}
            <li>

                <a
                    href="{{ route('retailer.pan.apply') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.pan.apply') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-id-card"></i>

                        <span>

                            Apply PAN

                        </span>

                    </div>

                    <span class="stf-badge">

                        New

                    </span>

                </a>

            </li>

            {{-- PAN CORRECTION --}}
            <li>

                <a
                    href="{{ route('retailer.pan-correction.history') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.pan-correction.*') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-pen"></i>

                        <span>

                            PAN Correction

                        </span>

                    </div>

                </a>

            </li>

            {{-- PAN HISTORY --}}
            <li>

                <a
                    href="{{ route('retailer.pan.history') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.pan.history') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-clock-rotate-left"></i>

                        <span>

                            PAN History

                        </span>

                    </div>

                </a>

            </li>

            {{-- COMPANY PAN --}}
            <li>

                <a
                    href="{{ route('retailer.pan.company') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.pan.company') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-building"></i>

                        <span>

                            Company PAN

                        </span>

                    </div>

                </a>

            </li>


            {{-- =====================================================
            | ITR SERVICES
            ====================================================== --}}
            <li class="stf-menu-title mt-4">

                ITR SERVICES

            </li>

            {{-- FILE ITR --}}
            <li>

                <a
                    href="{{ route('retailer.itr.file') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.itr.file') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-file-invoice-dollar"></i>

                        <span>

                            File ITR

                        </span>

                    </div>

                    <span class="stf-badge">

                        New

                    </span>

                </a>

            </li>

            {{-- ITR HISTORY --}}
            <li>

                <a
                    href="{{ route('retailer.itr.history') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.itr.history') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-history"></i>

                        <span>

                            ITR History

                        </span>

                    </div>

                </a>

            </li>

            {{-- ITR CORRECTION --}}
            <li>

                <a
                    href="{{ route('retailer.itr.correction') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.itr.correction') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-pen"></i>

                        <span>

                            ITR Correction

                        </span>

                    </div>

                </a>

            </li>

            {{-- FORM 16 --}}
            <li>

                <a
                    href="{{ route('retailer.itr.form16') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.itr.form16') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-file-alt"></i>

                        <span>

                            Form 16 Upload

                        </span>

                    </div>

                </a>

            </li>

            {{-- GST RETURN --}}
            <li>

                <a
                    href="{{ route('retailer.itr.gst.return') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.itr.gst.return') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-receipt"></i>

                        <span>

                            GST Return

                        </span>

                    </div>

                </a>

            </li>


            {{-- =====================================================
            | ACCOUNT
            ====================================================== --}}
            <li class="stf-menu-title mt-4">

                ACCOUNT

            </li>

            {{-- WALLET --}}
            <li>

                <a
                    href="{{ route('retailer.wallet.history') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.wallet.history') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-wallet"></i>

                        <span>

                            Wallet History

                        </span>

                    </div>

                </a>

            </li>

            {{-- PROFILE --}}
            <li>

                <a
                    href="{{ route('retailer.profile') }}"
                    class="stf-link
                    {{ request()->routeIs('retailer.profile') ? 'stf-active' : '' }}"
                >

                    <div class="stf-link-left">

                        <i class="fa fa-user"></i>

                        <span>

                            My Profile

                        </span>

                    </div>

                </a>

            </li>

            {{-- LOGOUT --}}
            <li class="mt-4">

                <form
                    method="POST"
                    action="{{ route('retailer.logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="stf-link stf-logout-btn border-0 bg-transparent w-100"
                    >

                        <div class="stf-link-left">

                            <i class="fa fa-sign-out-alt"></i>

                            <span>

                                Logout

                            </span>

                        </div>

                    </button>

                </form>

            </li>

        </ul>

    </div>

</div>