@php

    $paymentRequestCount = \App\Models\PaymentRequest::where(
        'status',
        'pending'
    )->count();

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


        $voterIdCount = \App\Models\VoterIdService::query()
        ->when(
            auth()->user()->hasRole('Executive'),
            fn ($query) => $query->where('assigned_to', auth()->id()),
            fn ($query) => $query->whereNull('assigned_to')
        )
        ->count();

    $bankAccountCount = \App\Models\BankAccountService::query()
        ->when(
            auth()->user()->hasRole('Executive'),
            fn ($query) => $query->where('assigned_to', auth()->id()),
            fn ($query) => $query->whereNull('assigned_to')
        )
        ->count();

    $otherServiceCount = \App\Models\OtherService::query()
        ->when(
            auth()->user()->hasRole('Executive'),
            fn ($query) => $query->where('assigned_to', auth()->id()),
            fn ($query) => $query->whereNull('assigned_to')
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

                auth()->user()->can('wallet.transactions') ||

                auth()->user()->can('payment.requests.view')

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

                    {{-- PAYMENT REQUESTS --}}
                        @can('payment.requests.view')

                        <li>

                            <a href="{{ route('admin.wallet.payment-requests') }}"
                            class="sbx-link
                            {{ request()->routeIs('admin.wallet.payment-request*') ||
                                request()->routeIs('admin.wallet.payment-requests')
                                    ? 'sbx-active'
                                    : '' }}">

                                <i class="fas fa-qrcode"></i>

                                <span>

                                    Payment Requests

                                </span>

                                @if($paymentRequestCount > 0)

                                    <span class="sbx-count-badge">

                                        {{ $paymentRequestCount }}

                                    </span>

                                @endif

                            </a>

                        </li>

                        @endcan

                    @endcan

                </ul>

            </li>

            @endif


        {{-- =====================================================
        | CITIZEN SERVICES
        ===================================================== --}}
        @if(
            auth()->user()->can('pan.view') ||
            auth()->user()->can('aadhaar.view') ||
            auth()->user()->can('csc.view') ||
            auth()->user()->can('itr.view') ||
            auth()->user()->can('voter-id.view') ||
            auth()->user()->can('bank-account.view')
        )

        <li class="sbx-section">
            Citizen Services
        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                {{-- PAN APPLICATIONS --}}
                @can('pan.view')
                <li>
                    <a href="{{ route('admin.pan.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.pan.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-id-card"></i>
                        <span>New PAN Applications</span>

                        @if($panCount > 0)
                            <span class="sbx-count-badge">
                                {{ $panCount }}
                            </span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.pan-correction.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.pan-correction.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-edit"></i>
                        <span>PAN Corrections</span>

                        @if($panCorrectionCount > 0)
                            <span class="sbx-count-badge">
                                {{ $panCorrectionCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- AADHAAR --}}
                @can('aadhaar.view')
                <li>
                    <a href="{{ route('admin.aadhaar.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.aadhaar.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-address-card"></i>
                        <span>Aadhaar Services</span>

                        @if($aadhaarCount > 0)
                            <span class="sbx-count-badge">
                                {{ $aadhaarCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- CSC --}}
                @can('csc.view')
                <li>
                    <a href="{{ route('admin.csc.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.csc.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-cogs"></i>
                        <span>CSC Services</span>

                        @if($cscCount > 0)
                            <span class="sbx-count-badge">
                                {{ $cscCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- ITR --}}
                @can('itr.view')
                <li>
                    <a href="{{ route('admin.itr.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.itr.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-file-invoice-dollar"></i>
                        <span>ITR Services</span>

                        @if($itrCount > 0)
                            <span class="sbx-count-badge">
                                {{ $itrCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- VOTER ID --}}
                @can('voter-id.view')
                <li>
                    <a href="{{ route('admin.voter-id.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.voter-id.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-vote-yea"></i>
                        <span>Voter ID Services</span>

                        @if($voterIdCount > 0)
                            <span class="sbx-count-badge">
                                {{ $voterIdCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- BANK ACCOUNT --}}
                @can('bank-account.view')
                <li>
                    <a href="{{ route('admin.bank-account.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.bank-account.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-university"></i>
                        <span>Bank Account Services</span>

                        @if($bankAccountCount > 0)
                            <span class="sbx-count-badge">
                                {{ $bankAccountCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

                {{-- OTHER SERVICE --}}
                @can('other-service.view')
                <li>
                    <a href="{{ route('admin.other-service.index') }}"
                    class="sbx-link {{ request()->routeIs('admin.other-service.*') ? 'sbx-active' : '' }}">
                        <i class="fa fa-university"></i>
                        <span>Other Services</span>

                        @if($otherServiceCount > 0)
                            <span class="sbx-count-badge">
                                {{ $otherServiceCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endcan

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



        {{-- ================= COMMUNICATION ================= --}}
        @if(
            auth()->user()->can('notice.view')
            
        )

        <li class="sbx-section">
            NOTICE
        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('notice.view')
                <li>
                    <a href="{{ route('admin.notice.index') }}"
                    class="sbx-link">

                        <i class="fa fa-bullhorn"></i>

                        <span>Notice Board</span>

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