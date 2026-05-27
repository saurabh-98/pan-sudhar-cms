@extends('layout.retailer')

@section('content')

<div class="container-fluid rtd-custom-dashboard">

    {{-- =====================================================
       HERO SECTION
    ====================================================== --}}
    <div class="rtd-hero">

        {{-- WELCOME CARD --}}
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

                <a href="{{ route('retailer.pan.apply') }}"
                   class="rtd-action-btn
                          rtd-action-btn-primary">

                    <i class="fa fa-plus-circle"></i>

                    Apply New PAN

                </a>

                <a href="{{ route('retailer.pan.verify') }}"
                   class="rtd-action-btn
                          rtd-action-btn-secondary">

                    <i class="fa fa-search"></i>

                    PAN Verification

                </a>

            </div>

        </div>

        {{-- =====================================================
           WALLET CARD
        ====================================================== --}}

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

            <a href="{{ route('retailer.wallet.history') }}"
               class="btn-primary">

                <i class="fa fa-money-check-alt"></i>

                Wallet History

            </a>

        </div>

    </div>

    {{-- =====================================================
       STATS
    ====================================================== --}}
    <div class="rtd-stats-grid">

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

        <div class="rtd-stat-card">

            <div class="rtd-stat-icon">

                👥

            </div>

            <div class="rtd-stat-value">

                {{ $totalCustomers ?? 0 }}

            </div>

            <div class="rtd-stat-text">

                Retailer Customers

            </div>

        </div>

        <div class="rtd-stat-card">

            <div class="rtd-stat-icon">

                📈

            </div>

            <div class="rtd-stat-value">

                {{ $successRate ?? 0 }}%

            </div>

            <div class="rtd-stat-text">

                Success Rate

            </div>

        </div>

    </div>

    {{-- =====================================================
       EXTRA STATS
    ====================================================== --}}
    <div class="rtd-stats-grid">

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

    </div>

    {{-- =====================================================
       PAN SERVICES
    ====================================================== --}}
    <div class="rtd-section">

        <div class="rtd-section-head">

            <h4 class="rtd-section-title">

                🪪 PAN Services

            </h4>

            <span class="rtd-section-count">

                {{ $panServices ?? 0 }} Services Used

            </span>

        </div>

        <div class="rtd-services">

            <a href="{{ route('retailer.pan.apply') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">

                    <i class="fa fa-id-card"></i>

                </div>

                <div class="rtd-service-title">

                    Apply New PAN

                </div>

            </a>

            <a href="{{ route('retailer.pan-correction.history') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">

                    <i class="fa fa-pen"></i>

                </div>

                <div class="rtd-service-title">

                    PAN Correction

                </div>

            </a>

            <a href="{{ route('retailer.pan.company') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">

                    <i class="fa fa-building"></i>

                </div>

                <div class="rtd-service-title">

                    Company PAN

                </div>

            </a>

            <a href="{{ route('retailer.pan.training') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">

                    <i class="fa fa-headset"></i>

                </div>

                <div class="rtd-service-title">

                    PAN Training

                </div>

            </a>

        </div>

    </div>

    {{-- =====================================================
       VERIFICATION SERVICES
    ====================================================== --}}
    <div class="rtd-section">

        <div class="rtd-section-head">

            <h4 class="rtd-section-title">

                🔍 Verification Services

            </h4>

            <span class="rtd-section-count">

                {{ $totalVerifications ?? 0 }} Verifications

            </span>

        </div>

        <div class="rtd-services">

            <a href="{{ route('retailer.pan.find') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-search"></i>
                </div>

                <div class="rtd-service-title">
                    PAN Find
                </div>

            </a>

            <a href="{{ route('retailer.pan.verify') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-check-circle"></i>
                </div>

                <div class="rtd-service-title">
                    PAN Verification
                </div>

            </a>

            <a href="{{ route('retailer.verification.bank') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-university"></i>
                </div>

                <div class="rtd-service-title">
                    Bank Verify
                </div>

            </a>

            <a href="{{ route('retailer.verification.voter') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-vote-yea"></i>
                </div>

                <div class="rtd-service-title">
                    Voter ID Verify
                </div>

            </a>

            <a href="{{ route('retailer.verification.rc') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-car"></i>
                </div>

                <div class="rtd-service-title">
                    RC Verify
                </div>

            </a>

            <a href="{{ route('retailer.verification.dl') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-id-badge"></i>
                </div>

                <div class="rtd-service-title">
                    DL Verify
                </div>

            </a>

        </div>

    </div>

    {{-- =====================================================
       UTILITY TOOLS
    ====================================================== --}}
    <div class="rtd-section">

        <div class="rtd-section-head">

            <h4 class="rtd-section-title">

                ⚙️ Utility Tools

            </h4>

            <span class="rtd-section-count">

                {{ $utilityServices ?? 0 }} Utilities Used

            </span>

        </div>

        <div class="rtd-services">

            <a href="{{ route('retailer.tools.aadhaar.pvc') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-address-card"></i>
                </div>

                <div class="rtd-service-title">
                    Aadhaar PVC Print
                </div>

            </a>

            <a href="{{ route('retailer.tools.hisab.kitab') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-book"></i>
                </div>

                <div class="rtd-service-title">
                    Hisab Kitab
                </div>

            </a>

            <a href="{{ route('retailer.tools.passport.photo') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-camera"></i>
                </div>

                <div class="rtd-service-title">
                    Passport Photo Maker
                </div>

            </a>

            <a href="{{ route('retailer.tools.file.converter') }}"
               class="rtd-service-card">

                <div class="rtd-service-icon">
                    <i class="fa fa-file"></i>
                </div>

                <div class="rtd-service-title">
                    File Converter
                </div>

            </a>

        </div>

    </div>

</div>

@endsection