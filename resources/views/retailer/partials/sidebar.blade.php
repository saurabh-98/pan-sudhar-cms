
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

   <div class="stf-menu-wrapper">

    <ul class="stf-menu">

        @foreach($retailerMenus as $menu)

            @include(
                'retailer.partials.menu-item',
                ['menu' => $menu]
            )

        @endforeach

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

                        <span>Logout</span>

                    </div>

                </button>

            </form>

        </li>

    </ul>

</div>
</div>