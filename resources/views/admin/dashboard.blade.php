@extends('layout.admin')

@section('title','Dashboard')


@section('content')

@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isSuperDistributor = $user->hasRole('Super Distributor');
    $isDistributor = $user->hasRole('Distributor');
    $isExecutive = $user->hasRole('Executive');

    $roleLabel = $isAdmin ? 'Admin' : ($isSuperDistributor ? 'Super Distributor' : ($isDistributor ? 'Distributor' : 'Executive'));
    $roleIcon  = $isAdmin ? 'fa-user-shield' : ($isSuperDistributor ? 'fa-sitemap' : ($isDistributor ? 'fa-network-wired' : 'fa-user-check'));
@endphp

<div id="dashRoot" data-theme="light">

    {{-- ============================================================= --}}
    {{-- HERO --}}
    {{-- ============================================================= --}}

    <div class="dash-hero">
        <span class="dash-hero__blob dash-hero__blob--a"></span>
        <span class="dash-hero__blob dash-hero__blob--b"></span>
        <span class="dash-hero__blob dash-hero__blob--c"></span>

        <div class="dash-hero__content d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="dash-hero__eyebrow">Ledger &middot; {{ now()->format('l, d M Y') }}</div>
                <h2 class="dash-hero__title"><i class="fa {{ $roleIcon }}"></i> {{ $roleLabel }} Dashboard</h2>
                <p class="dash-hero__sub">Welcome back, <strong>{{ $user->name }}</strong></p>
                <div class="dash-hero__meta" id="lastUpdatedLabel">Last updated just now</div>
            </div>

            <div class="dash-hero__actions">
                <button type="button" class="btn-glass" id="refreshBtn"><i class="fa fa-rotate"></i> Refresh</button>
                <button type="button" class="btn-glass" data-theme-toggle><i class="fa fa-moon"></i> <span>Dark mode</span></button>
            </div>
        </div>
    </div>

    {{-- ============================================================= --}}
    {{-- SUPER DISTRIBUTOR --}}
    {{-- ============================================================= --}}

    @if($isSuperDistributor)

        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-6">
                <div class="stat-card" data-accent="primary" data-tip="Total distributors currently under you">
                    <div class="stat-card__top">
                        <span class="stat-card__icon"><i class="fa fa-network-wired"></i></span>
                    </div>
                    <div class="stat-card__label">My Distributors</div>
                    <div class="stat-card__value" data-target="{{ $totalDistributors }}">0</div>
                </div>
            </div>
        </div>

    @else

        {{-- ========================================================= --}}
        {{-- SERVICE OVERVIEW (Admin only) --}}
        {{-- ========================================================= --}}

        @if($isAdmin)

            <div class="dash-section-label">Overview</div>

            <div class="stat-grid">

                <a href="{{ route('admin.pan.index') }}" class="text-decoration-none">
                    <div class="stat-card is-clickable" data-accent="primary" data-tip="View all PAN applications">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-id-card"></i></span></div>
                        <div class="stat-card__label">PAN</div>
                        <div class="stat-card__value" data-target="{{ $totalPanApplications }}">0</div>
                    </div>
                </a>

                <a href="{{ route('admin.itr.index') }}" class="text-decoration-none">
                    <div class="stat-card is-clickable" data-accent="success" data-tip="View all ITR applications">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-file-invoice-dollar"></i></span></div>
                        <div class="stat-card__label">ITR</div>
                        <div class="stat-card__value" data-target="{{ $totalItrApplications }}">0</div>
                    </div>
                </a>

                <div class="stat-card" data-accent="danger" data-tip="Total Aadhaar service requests">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-address-card"></i></span></div>
                    <div class="stat-card__label">Aadhaar</div>
                    <div class="stat-card__value" data-target="{{ $totalAadhaarServices }}">0</div>
                </div>

                <div class="stat-card" data-accent="dark" data-tip="Total bank accounts opened">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-university"></i></span></div>
                    <div class="stat-card__label">Bank Account</div>
                    <div class="stat-card__value" data-target="{{ $totalBankAccounts }}">0</div>
                </div>

                <div class="stat-card" data-accent="purple" data-tip="Total CSC services delivered">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-cogs"></i></span></div>
                    <div class="stat-card__label">CSC</div>
                    <div class="stat-card__value" data-target="{{ $totalCscServices }}">0</div>
                </div>

                <div class="stat-card" data-accent="warning" data-tip="Total revenue generated">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-wallet"></i></span></div>
                    <div class="stat-card__label">Revenue</div>
                    <div class="stat-card__value" data-target="{{ $totalRevenue }}" data-prefix="&#8377;">&#8377;0</div>
                </div>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- TEAM --}}
        {{-- ========================================================= --}}

        @if($isAdmin || $isDistributor)

            <div class="dash-section-label">Team</div>

            <div class="stat-grid">

                @if($isAdmin)

                    <div class="stat-card" data-accent="info" data-tip="Total retailers onboarded">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-store"></i></span></div>
                        <div class="stat-card__label">Retailers</div>
                        <div class="stat-card__value" data-target="{{ $totalRetailers }}">0</div>
                    </div>

                    <div class="stat-card" data-accent="purple" data-tip="Total distributors onboarded">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-network-wired"></i></span></div>
                        <div class="stat-card__label">Distributors</div>
                        <div class="stat-card__value" data-target="{{ $totalDistributors }}">0</div>
                    </div>

                    <div class="stat-card" data-accent="secondary" data-tip="Total executives onboarded">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-user-tie"></i></span></div>
                        <div class="stat-card__label">Executives</div>
                        <div class="stat-card__value" data-target="{{ $totalExecutives }}">0</div>
                    </div>

                    <div class="stat-card" data-accent="primary" data-tip="Total users across all roles">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-users"></i></span></div>
                        <div class="stat-card__label">Total Users</div>
                        <div class="stat-card__value" data-target="{{ $totalUsers }}">0</div>
                    </div>

                @endif

                @if($isDistributor)

                    <div class="stat-card" data-accent="info" data-tip="Retailers under your network">
                        <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-store"></i></span></div>
                        <div class="stat-card__label">My Retailers</div>
                        <div class="stat-card__value" data-target="{{ $totalRetailers }}">0</div>
                    </div>

                @endif

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- APPLICATION STATUS --}}
        {{-- ========================================================= --}}

        @if($isAdmin)

            <div class="dash-section-label">Application Status</div>

            <div class="stat-grid">

                <div class="stat-card is-clickable" data-accent="warning" data-status-card="assigned" data-tip="Filter table by Assigned">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-user-check"></i></span></div>
                    <div class="stat-card__label">Assigned</div>
                    <div class="stat-card__value" data-target="{{ $assignedApplications }}">0</div>
                </div>

                <div class="stat-card is-clickable" data-accent="danger" data-status-card="pending" data-tip="Filter table by Pending">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-clock"></i></span></div>
                    <div class="stat-card__label">Pending</div>
                    <div class="stat-card__value" data-target="{{ $pendingApplications }}">0</div>
                </div>

                <div class="stat-card is-clickable" data-accent="secondary" data-status-card="processing" data-tip="Filter table by Processing">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-spinner"></i></span></div>
                    <div class="stat-card__label">Processing</div>
                    <div class="stat-card__value" data-target="{{ $processingApplications }}">0</div>
                </div>

                <div class="stat-card is-clickable" data-accent="success" data-status-card="completed" data-tip="Filter table by Completed">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-check-circle"></i></span></div>
                    <div class="stat-card__label">Completed</div>
                    <div class="stat-card__value" data-target="{{ $completedApplications }}">0</div>
                </div>

                <div class="stat-card is-clickable" data-accent="dark" data-status-card="rejected" data-tip="Filter table by Rejected">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-times-circle"></i></span></div>
                    <div class="stat-card__label">Rejected</div>
                    <div class="stat-card__value" data-target="{{ $rejectedApplications }}">0</div>
                </div>

                <div class="stat-card is-clickable" data-accent="primary" data-status-card="fresh" data-tip="Filter table by Fresh">
                    <div class="stat-card__top"><span class="stat-card__icon"><i class="fa fa-bolt"></i></span></div>
                    <div class="stat-card__label">Fresh</div>
                    <div class="stat-card__value" data-target="{{ $freshApplications }}">0</div>
                </div>

            </div>

        @endif

        @if($isAdmin)

            {{-- ========================================================= --}}
            {{-- CHARTS --}}
            {{-- ========================================================= --}}

            <div class="dash-section-label">Analytics</div>

            <div class="row g-4">

                <div class="col-xl-8">
                    <div class="panel">
                        <div class="panel__header">
                            <h5 class="panel__title"><i class="fa fa-chart-line"></i> Monthly Service Analytics</h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="segmented">
                                    <button type="button" class="active" data-chart-type="bar">Bar</button>
                                    <button type="button" data-chart-type="line">Line</button>
                                </div>
                                <span class="badge-year">{{ now()->year }}</span>
                            </div>
                        </div>
                        <div class="panel__body">
                            <canvas id="dashboardChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="panel">
                        <div class="panel__header">
                            <h5 class="panel__title"><i class="fa fa-chart-pie"></i> Quick Statistics</h5>
                        </div>
                        <div class="panel__body">
                            <canvas id="servicePieChart" height="190" class="mb-3"></canvas>
                            <div class="d-flex justify-content-between mb-2"><span>PAN Services</span><strong>{{ $totalPanApplications }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span>ITR Services</span><strong>{{ $totalItrApplications }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span>Aadhaar Services</span><strong>{{ $totalAadhaarServices }}</strong></div>
                            <div class="d-flex justify-content-between mb-2"><span>Bank Accounts</span><strong>{{ $totalBankAccounts }}</strong></div>
                            <div class="d-flex justify-content-between"><span>CSC Services</span><strong>{{ $totalCscServices }}</strong></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- WALLET & REVENUE --}}
            {{-- ========================================================= --}}

            <div class="dash-section-label">Finance</div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="money-card money-card--wallet">
                        <div class="money-card__label">Wallet Balance</div>
                        <div class="money-card__value">&#8377;{{ number_format(auth()->user()->wallet_balance,2) }}</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="money-card money-card--revenue">
                        <div class="money-card__label">Revenue Summary</div>
                        <div class="money-card__value">&#8377;{{ number_format($totalRevenue,2) }}</div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- RECENT APPLICATIONS + WALLET TRANSACTIONS --}}
            {{-- ========================================================= --}}

            <div class="dash-section-label">Applications</div>

            <div class="row g-4">

                <div class="col-lg-8">
                    <div class="panel">
                        <div class="panel__header flex-column align-items-stretch">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="panel__title mb-0"><i class="fa fa-list"></i> Recent Applications</h5>
                                <div class="search-box">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="appSearchInput" placeholder="Search by name or service...">
                                </div>
                            </div>
                            <div class="segmented mt-3">
                                <button type="button" class="active" data-status-filter="all">All</button>
                                <button type="button" data-status-filter="assigned">Assigned</button>
                                <button type="button" data-status-filter="pending">Pending</button>
                                <button type="button" data-status-filter="processing">Processing</button>
                                <button type="button" data-status-filter="completed">Completed</button>
                                <button type="button" data-status-filter="rejected">Rejected</button>
                            </div>
                        </div>
                        <div class="panel__body">
                            <div class="ledger-table-wrap">
                                <table class="ledger-table" id="appTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="sortable" data-sort-key="name">Name <i class="fa fa-sort"></i></th>
                                            <th class="sortable" data-sort-key="service">Service <i class="fa fa-sort"></i></th>
                                            <th class="sortable" data-sort-key="status">Status <i class="fa fa-sort"></i></th>
                                            <th class="sortable" data-sort-key="date">Date <i class="fa fa-sort"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody id="appTableBody">
                                        @forelse($recentApplications ?? [] as $application)
                                            <tr
                                                data-name="{{ strtolower($application->applicant_name) }}"
                                                data-service="{{ strtolower($application->service_name) }}"
                                                data-status="{{ strtolower($application->status) }}"
                                                data-date="{{ $application->created_at->format('Y-m-d') }}"
                                            >
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="avatar-chip">{{ strtoupper(substr($application->applicant_name,0,1)) }}</span>
                                                    {{ $application->applicant_name }}
                                                </td>
                                                <td>{{ $application->service_name }}</td>
                                                <td><span class="status-chip" data-status="{{ strtolower($application->status) }}">{{ $application->status }}</span></td>
                                                <td class="num">{{ $application->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="no-results">No Applications Found</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="no-results" id="noResultsMessage" style="display:none;">No applications match your search or filter.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="panel">
                        <div class="panel__header">
                            <h5 class="panel__title mb-0"><i class="fa fa-receipt"></i> Wallet Transactions</h5>
                            @if(($recentTransactions ?? collect())->count() > 4)
                                <button type="button" class="link-btn" id="toggleTransactionsBtn">Show all</button>
                            @endif
                        </div>
                        <div class="panel__body pt-2">
                            @forelse(($recentTransactions ?? []) as $index => $transaction)
                                <div class="transaction-item transaction-row {{ $index >= 4 ? 'transaction-extra d-none' : '' }}">
                                    <span class="transaction-type">{{ $transaction->type }}</span>
                                    <span class="transaction-amount {{ strtolower($transaction->type) === 'credit' ? 'credit' : 'debit' }}">
                                        &#8377;{{ number_format($transaction->amount,2) }}
                                    </span>
                                </div>
                            @empty
                                <p class="no-results mb-0">No Transactions</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- QUICK ACTIONS --}}
            {{-- ========================================================= --}}

            <div class="dash-section-label">Quick Actions</div>

            <div class="panel">
                <div class="panel__body">
                    <a href="{{ route('admin.pan.index') }}" class="quick-action quick-action--pan"><i class="fa fa-id-card"></i> PAN</a>
                    <a href="{{ route('admin.itr.index') }}" class="quick-action quick-action--itr"><i class="fa fa-file-invoice-dollar"></i> ITR</a>
                    <a href="{{ route('admin.wallet.transactions') }}" class="quick-action quick-action--wallet"><i class="fa fa-wallet"></i> Wallet</a>
                </div>
            </div>

        @endif

    @endif

</div>

{{-- JSON data island: the only bridge between Blade/PHP and the external JS file --}}
@if($isAdmin)
<script id="dash-data" type="application/json">
    {
        "months": @json($months),
        "chartValues": @json($chartData),
        "serviceBreakdown": {
            "labels": ["PAN", "ITR", "Aadhaar", "Bank", "CSC"],
            "values": [
                {{ $totalPanApplications }},
                {{ $totalItrApplications }},
                {{ $totalAadhaarServices }},
                {{ $totalBankAccounts }},
                {{ $totalCscServices }}
            ]
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endif

@endsection
