@php

    /*
    |--------------------------------------------------------------------------
    | PAN COUNT
    |--------------------------------------------------------------------------
    */

    $panCount = \App\Models\PanApplication::query()

        ->when(

            auth()->user()->hasRole('Executive'),

            function($query){

                $query->where(

                    'assigned_to',

                    auth()->id()

                );

            },

            function($query){

                $query->whereNull('assigned_to');

            }

        )

        ->count();



        $panCorrectionCount = \App\Models\PanCorrectionApplication::query()

        ->when(

            auth()->user()->hasRole('Executive'),

            function($query){

                $query->where(

                    'assigned_to',

                    auth()->id()

                );

            },

            function($query){

                $query->whereNull('assigned_to');

            }

        )

        ->count();

    /*
    |--------------------------------------------------------------------------
    | ITR COUNT
    |--------------------------------------------------------------------------
    */

    $itrCount = \App\Models\ItrFile::query()

        ->when(

            auth()->user()->hasRole('Executive'),

            function($query){

                $query->where(

                    'assigned_to',

                    auth()->id()

                );

            },

            function($query){

                $query->whereNull('assigned_to');

            }

        )

        ->count();

        $aadhaarCount = \App\Models\AadhaarService::query()

        ->when(

            auth()->user()->hasRole('Executive'),

            function ($query) {

                $query->where(
                    'assigned_to',
                    auth()->id()
                );

            },

            function ($query) {

                $query->whereNull('assigned_to');

            }

        )

        ->count();




        $cscCount = \App\Models\CscService::query()

        ->when(

            auth()->user()->hasRole('Executive'),

            function ($query) {

                $query->where(
                    'assigned_to',
                    auth()->id()
                );

            },

            function ($query) {

                $query->whereNull('assigned_to');

            }

        )

        ->count();

@endphp

<div id="sbxSidebar" class="sbx-sidebar">

    {{-- =========================================================
    | TITLE
    ========================================================== --}}
        <div class="sbx-title">

        Pan Sudhar Portal

        <button id="closeSidebar"
                class="sbx-close-btn">

            <i class="fa fa-times"></i>

        </button>

    </div>

    {{-- =========================================================
    | SIDEBAR MENU
    ========================================================== --}}
    <ul class="sbx-menu">

        {{-- =====================================================
        | DASHBOARD
        ====================================================== --}}
        @can('dashboard.view')

        <li>

            <a href="{{ route('admin.dashboard') }}"
               class="sbx-link
               {{ request()->routeIs('admin.dashboard')
                    ? 'sbx-active' : '' }}">

                <i class="fa fa-home"></i>

                <span>

                    Dashboard

                </span>

            </a>

        </li>

        @endcan


        @can('retailer-approval.view')

        <li>

            <a href="{{ route('admin.retailer-approvals.index') }}"
            class="sbx-link">

                <i class="fa fa-user-check"></i>

                <span>

                    Retailer Approvals

                </span>

            </a>

        </li>

        @endcan

        {{-- =====================================================
            | WALLET MANAGEMENT
            ====================================================== --}}
            @if(
                auth()->user()->can('wallet.view') ||
                auth()->user()->can('wallet.transactions')
            )

            <li class="sbx-section">

                Wallet Management

            </li>

            <li class="sbx-group">

                <ul class="sbx-submenu">

                    {{-- RETAILER WALLET --}}
                    @can('wallet.view')

                    <li>

                        <a href="{{ route('admin.wallet.index') }}"
                        class="sbx-link
                        {{ request()->routeIs('admin.wallet.*')
                                ? 'sbx-active' : '' }}">

                            <i class="fa fa-wallet"></i>

                            <span>

                                Retailer Wallet

                            </span>

                        </a>

                    </li>

                    @endcan

                    {{-- WALLET TRANSACTIONS --}}
                    @can('wallet.transactions')

                    <li>

                        <a href="{{ route('admin.wallet.transactions') }}"
                        class="sbx-link
                        {{ request()->routeIs('admin.wallet.transactions')
                                ? 'sbx-active' : '' }}">

                            <i class="fa fa-money-check-alt"></i>

                            <span>

                                Wallet Transactions

                            </span>

                        </a>

                    </li>

                    @endcan

                </ul>

            </li>

            @endif


        {{-- =====================================================
        | NEW PAN MODULE
        ====================================================== --}}
        @if(
            auth()->user()->can('pan.view')
        )

        <li class="sbx-section">

            PAN Services

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                {{-- NEW PAN APPLICATIONS --}}
                <li>

                    <a href="{{ route('admin.pan.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.pan.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-id-card"></i>

                        <span>

                            New PAN Applications

                        </span>

                        @if($panCount > 0)

                            <span class="sbx-count-badge">

                                {{ $panCount }}

                            </span>

                        @endif

                    </a>

                </li>

                {{-- PAN CORRECTION APPLICATIONS --}}
                <li>

                    <a href="{{ route('admin.pan-correction.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.pan-correction.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-id-card"></i>

                        <span>

                            PAN Correction Applications

                        </span>

                        @if($panCorrectionCount > 0)

                            <span class="sbx-count-badge">

                                {{ $panCorrectionCount }}

                            </span>

                        @endif

                    </a>

                </li>

            </ul>

        </li>

        @endif


        {{-- =====================================================
        | ITR MODULE
        ====================================================== --}}
        @if(
            auth()->user()->can('itr.view')
        )

        <li class="sbx-section">

            ITR Services

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                {{-- ITR APPLICATIONS --}}
                <li>

                    <a href="{{ route('admin.itr.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.itr.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-file-invoice-dollar"></i>

                        <span>

                            ITR Applications

                        </span>

                        @if($itrCount > 0)

                            <span class="sbx-count-badge">

                                {{ $itrCount }}

                            </span>

                        @endif

                    </a>

                </li>

            </ul>

        </li>

        @endif

        {{-- =====================================================
        | AADHAAR MODULE
        ====================================================== --}}
        @if(
            auth()->user()->can('aadhaar.view')
        )

        <li class="sbx-section">

            Aadhaar Services

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                <li>

                    <a href="{{ route('admin.aadhaar.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.aadhaar.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-id-card"></i>

                        <span>

                            Aadhaar Applications

                        </span>

                        @if($aadhaarCount > 0)

                            <span class="sbx-count-badge">

                                {{ $aadhaarCount }}

                            </span>

                        @endif

                    </a>

                </li>

            </ul>

        </li>

        @endif




         {{-- =====================================================
        | CSC MODULE
        ====================================================== --}}
        @if(
            auth()->user()->can('csc.view')
        )

        <li class="sbx-section">

            Csc Services

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                <li>

                    <a href="{{ route('admin.csc.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.csc.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-id-card"></i>

                        <span>

                            Csc Applications

                        </span>

                        @if($aadhaarCount > 0)

                            <span class="sbx-count-badge">

                                {{ $cscCount }}

                            </span>

                        @endif

                    </a>

                </li>

            </ul>

        </li>

        @endif


        {{-- =====================================================
        | LOCATION MASTER
        ====================================================== --}}
        @if(
            auth()->user()->can('states.view') ||
            auth()->user()->can('districts.view')
        )

        <li class="sbx-section">

            Location Master

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                {{-- STATES --}}
                @can('states.view')

                <li>

                    <a href="{{ route('admin.states.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.states.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-map"></i>

                        <span>

                            States

                        </span>

                    </a>

                </li>

                @endcan

                {{-- DISTRICTS --}}
                @can('districts.view')

                <li>

                    <a href="{{ route('admin.districts.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.districts.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-map-marker-alt"></i>

                        <span>

                            Districts

                        </span>

                    </a>

                </li>

                @endcan

            </ul>

        </li>

        @endif



       




      @if(
                auth()->user()->can('users.view') ||
                auth()->user()->can('roles.view') ||
                auth()->user()->can('modules.view')
            )

            <li class="sbx-section">
                User Management
            </li>

            <li class="sbx-group">

                <ul class="sbx-submenu">

                    @can('users.view')
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                        class="sbx-link {{ request()->routeIs('admin.users.*') ? 'sbx-active' : '' }}">
                            <i class="fa fa-user-shield"></i>
                            <span>Manage User</span>
                        </a>
                    </li>
                    @endcan

                    @can('roles.view')
                    <li>
                        <a href="{{ route('admin.roles.index') }}"
                        class="sbx-link {{ request()->routeIs('admin.roles.*') ? 'sbx-active' : '' }}">
                            <i class="fa fa-user-lock"></i>
                            <span>Roles & Permissions</span>
                        </a>
                    </li>
                    @endcan

                    @can('modules.view')
                    <li>
                        <a href="{{ route('admin.modules.index') }}"
                        class="sbx-link {{ request()->routeIs('admin.modules.*') ? 'sbx-active' : '' }}">
                            <i class="fa fa-layer-group"></i>
                            <span>Module Management</span>
                        </a>
                    </li>
                    @endcan

                </ul>

            </li>

            @endif

        {{-- =====================================================
        | CMS MANAGEMENT
        ====================================================== --}}
        @if(
            auth()->user()->can('settings.view') ||
            auth()->user()->can('banners.view') ||
            auth()->user()->can('pages.view') ||
            auth()->user()->can('navigation.view')
        )

        <li class="sbx-section">

            CMS Management

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('settings.view')

                <li>

                    <a href="{{ route('admin.logo.form') }}"
                       class="sbx-link">

                        <i class="fa fa-image"></i>

                        <span>

                            Logo Settings

                        </span>

                    </a>

                </li>

                @endcan

                @can('banners.view')

                <li>

                    <a href="{{ route('admin.banners.index') }}"
                       class="sbx-link">

                        <i class="fa fa-image"></i>

                        <span>

                            Hero Banner

                        </span>

                    </a>

                </li>

                @endcan

                @can('pages.view')

                <li>

                    <a href="{{ route('admin.pages.index') }}"
                       class="sbx-link">

                        <i class="fa fa-file"></i>

                        <span>

                            Pages

                        </span>

                    </a>

                </li>

                @endcan

                @can('navigation.view')

                <li>

                    <a href="{{ route('admin.navigation.index') }}"
                       class="sbx-link">

                        <i class="fa fa-bars"></i>

                        <span>

                            Navigation Menu

                        </span>

                    </a>

                </li>

                @endcan

            </ul>

        </li>

        @endif



        


        {{-- =====================================================
        | PAYMENT SETTINGS
        ====================================================== --}}
       @if(
            auth()->user()->can('upi.view') ||
            auth()->user()->can('charges.view') ||
            auth()->user()->can('footer.view')
        )

        <li class="sbx-section">

            Payment Settings

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('upi.view')

                <li>

                    <a href="{{ route('admin.upi.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.upi.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-qrcode"></i>

                        <span>

                            UPI Settings

                        </span>

                    </a>

                </li>

                @endcan


                @can('charges.view')

                <li>

                    <a href="{{ route('admin.charges.index') }}"
                    class="sbx-link
                    {{ request()->routeIs('admin.charges.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-money-bill-wave"></i>

                        <span>

                            Charges Management

                        </span>

                    </a>

                </li>

                @endcan

            </ul>

        </li>

        @endif

    </ul>

</div>