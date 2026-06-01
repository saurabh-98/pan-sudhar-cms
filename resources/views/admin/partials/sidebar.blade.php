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

@endphp

<div id="sbxSidebar" class="sbx-sidebar">

    {{-- =========================================================
    | TITLE
    ========================================================== --}}
    <div class="sbx-title">

        Pan Sudhar Portal

    </div>

    {{-- =========================================================
    | CLOSE BUTTON
    ========================================================== --}}
    <button id="closeSidebar"
            class="sbx-close-btn">

        <i class="fa fa-times"></i>

    </button>

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

                @if($panCount > 0)

                    <span class="sbx-count-badge">

                        {{ $panCount }}

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



        {{-- =====================================================
        | HR & PAYROLL
        ====================================================== --}}
        @if(
            auth()->user()->can('departments.view') ||
            auth()->user()->can('designations.view') ||
            auth()->user()->can('employees.view') ||
            auth()->user()->can('employee-attendance.view') ||
            auth()->user()->can('payroll.view') ||
            auth()->user()->can('payslip.view')
        )

        <li class="sbx-section">

            HR & Payroll

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('departments.view')

                <li>

                    <a href="{{ route('admin.departments.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.departments.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-building"></i>

                        <span>

                            Departments

                        </span>

                    </a>

                </li>

                @endcan

                @can('designations.view')

                <li>

                    <a href="{{ route('admin.designations.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.designations.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-user-tag"></i>

                        <span>

                            Designations

                        </span>

                    </a>

                </li>

                @endcan

                @can('employees.view')

                <li>

                    <a href="{{ route('admin.employees.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.employees.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-users"></i>

                        <span>

                            Employees

                        </span>

                    </a>

                </li>

                @endcan

                @can('employee-attendance.view')

                <li>

                    <a href="{{ route('admin.employee-attendance.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.employee-attendance.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-calendar-check"></i>

                        <span>

                            Employee Attendance

                        </span>

                    </a>

                </li>

                @endcan

                @can('payroll.view')

                <li>

                    <a href="{{ route('admin.payroll.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.payroll.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-money-check"></i>

                        <span>

                            Salary Management

                        </span>

                    </a>

                </li>

                @endcan

                @can('payslip.view')

                <li>

                    <a href="{{ route('admin.payslip.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.payslip.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-file-invoice"></i>

                        <span>

                            Payslips

                        </span>

                    </a>

                </li>

                @endcan

            </ul>

        </li>

        @endif



        {{-- =====================================================
        | COMMUNICATION
        ====================================================== --}}
        @if(
            auth()->user()->can('notice.view') ||
            auth()->user()->can('messages.view')
        )

        <li class="sbx-section">

            Communication

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('notice.view')

                <li>

                    <a href="{{ route('admin.notice.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.notice.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-bullhorn"></i>

                        <span>

                            Notice Board

                        </span>

                    </a>

                </li>

                @endcan

                @can('messages.view')

                <li>

                    <a href="{{ route('admin.messages.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.messages.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-envelope"></i>

                        <span>

                            Messages

                        </span>

                    </a>

                </li>

                @endcan

            </ul>

        </li>

        @endif




        {{-- =====================================================
        | USER MANAGEMENT
        ====================================================== --}}
        @if(
            auth()->user()->can('users.view') ||
            auth()->user()->can('roles.view')
        )

        <li class="sbx-section">

            User Management

        </li>

        <li class="sbx-group">

            <ul class="sbx-submenu">

                @can('users.view')

                <li>

                    <a href="{{ route('admin.users.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.users.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-user-shield"></i>

                        <span>

                            Manage User

                        </span>

                    </a>

                </li>

                @endcan

                @can('roles.view')

                <li>

                    <a href="{{ route('admin.roles.index') }}"
                       class="sbx-link
                       {{ request()->routeIs('admin.roles.*')
                            ? 'sbx-active' : '' }}">

                        <i class="fa fa-user-lock"></i>

                        <span>

                            Roles & Permissions

                        </span>

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

            </ul>

        </li>

        @endif

    </ul>

</div>