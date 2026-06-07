@extends('layout.retailer')

@section('content')

@php

    $heroButtons = collect();

    foreach ($retailerMenus ?? [] as $menu) {

        foreach ($menu->children as $child) {

            if (
                !empty($child->route_name)
                &&
                Route::has($child->route_name)
            ) {

                $heroButtons->push($child);
            }
        }
    }

    $heroButtons = $heroButtons->take(2);

@endphp

<div class="container-fluid rtd-custom-dashboard">

    {{-- =====================================================
       HERO SECTION
    ====================================================== --}}
    <div class="rtd-hero">

        <div class="rtd-welcome-card">

            <h2 class="rtd-welcome-title">

                👋 Welcome,
                {{ auth()->user()->name }}

            </h2>

            <p class="rtd-welcome-text">

                Manage PAN, Aadhaar, Verification &
                Retailer Services from one smart dashboard.

            </p>

            <div class="rtd-action-group">

                @forelse($heroButtons as $service)

                    <a
                        href="{{ route($service->route_name) }}"
                        class="rtd-action-btn rtd-action-btn-primary"
                    >

                        <i class="{{ $service->icon ?: 'fa fa-link' }}"></i>

                        {{ $service->name }}

                    </a>

                @empty

                    <a
                        href="{{ route('retailer.dashboard') }}"
                        class="rtd-action-btn rtd-action-btn-primary"
                    >

                        <i class="fa fa-home"></i>

                        Dashboard

                    </a>

                @endforelse

            </div>

        </div>

        <div class="rtd-wallet-card">

            <div class="rtd-wallet-top">

                <div>

                    <div class="rtd-wallet-label">

                        Wallet Balance

                    </div>

                    <div class="rtd-wallet-balance">

                        ₹{{ number_format($walletBalance ?? 0, 2) }}

                    </div>

                </div>

                <div class="rtd-wallet-icon">

                    <i class="fa fa-wallet"></i>

                </div>

            </div>

            <div class="rtd-wallet-bottom">

                <div class="rtd-wallet-mini">

                    <span>Total Transactions</span>

                    <strong>{{ $totalTransactions ?? 0 }}</strong>

                </div>

            </div>

             @if(Route::has('retailer.wallet.history'))

                <a
                    href="{{ route('retailer.wallet.history') }}"
                    class="rtd-wallet-btn"
                >
                    <i class="fa fa-wallet"></i>
                    <span>Wallet History</span>
                </a>

            @endif

        </div>

    </div>

    {{-- =====================================================
       STATS (PERMISSION BASED)
    ====================================================== --}}
    <div class="rtd-stats-grid">

        @if($hasPanModule)

            <div class="rtd-stat-card">

                <div class="rtd-stat-icon">

                    🪪

                </div>

                <div class="rtd-stat-value">

                    {{ $panServices ?? 0 }}

                </div>

                <div class="rtd-stat-text">

                    PAN Applications

                </div>

            </div>

        @endif

        @if($hasAadhaarModule)

            <div class="rtd-stat-card">

                <div class="rtd-stat-icon">

                    🔐

                </div>

                <div class="rtd-stat-value">

                    {{ $aadhaarServices ?? 0 }}

                </div>

                <div class="rtd-stat-text">

                    Aadhaar Services

                </div>

            </div>

        @endif

         @if($hasPanModule)

            <div class="rtd-stat-card">

                <div class="rtd-stat-icon">

                    🪪

                </div>

                <div class="rtd-stat-value">

                    {{ $panCorrectioServices ?? 0 }}

                </div>

                <div class="rtd-stat-text">

                    PAN Correction Applications

                </div>

            </div>

        @endif



    </div>
       
       
    {{-- =====================================================
       EXTRA STATS (PERMISSION BASED)
    ====================================================== --}}
    <div class="rtd-stats-grid">

        @if($hasVerificationModule)

            <div class="rtd-stat-card">

                <div class="rtd-stat-icon">

                    🔍

                </div>

                <div class="rtd-stat-value">

                    {{ $totalVerifications ?? 0 }}

                </div>

                <div class="rtd-stat-text">

                    Verification Services

                </div>

            </div>

        @endif

        @if($hasUtilityModule)

            <div class="rtd-stat-card">

                <div class="rtd-stat-icon">

                    ⚙️

                </div>

                <div class="rtd-stat-value">

                    {{ $utilityServices ?? 0 }}

                </div>

                <div class="rtd-stat-text">

                    Utility Services

                </div>

            </div>

        @endif

    </div>

    {{-- =====================================================
       SERVICES (SIDEBAR PERMISSION BASED)
    ====================================================== --}}

    @forelse($retailerMenus ?? [] as $parent)

        @if($parent->children->count())

            <div class="rtd-section">

                <div class="rtd-section-head">

                    <h4 class="rtd-section-title">

                        @if(!empty($parent->icon))

                            <i class="{{ $parent->icon }}"></i>

                        @endif

                        {{ $parent->name }}

                    </h4>

                    <span class="rtd-section-count">

                        {{ $parent->children->count() }}

                        Services

                    </span>

                </div>

                <div class="rtd-services">

                    @foreach($parent->children as $child)

                        @if(
                            !empty($child->route_name)
                            &&
                            Route::has($child->route_name)
                        )

                            <a
                                href="{{ route($child->route_name) }}"
                                class="rtd-service-card"
                            >

                                <div class="rtd-service-icon">

                                    <i class="{{ $child->icon ?: 'fa fa-circle' }}"></i>

                                </div>

                                <div class="rtd-service-title">

                                    {{ $child->name }}

                                </div>

                            </a>

                        @endif

                    @endforeach

                </div>

            </div>

        @endif

    @empty

        <div class="rtd-section">

            <div class="alert alert-info">

                No services have been assigned to your account.

            </div>

        </div>

    @endforelse

</div>

@endsection