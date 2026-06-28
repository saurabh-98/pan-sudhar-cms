
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