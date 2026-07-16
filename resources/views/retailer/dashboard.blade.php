@extends('layout.retailer')

@php

$popup = getActivePopup('dashboard');

@endphp


@section('content')

@php

    $trendingServicesCount = 0;

    // Category accent colors — keyed by parent module slug.
    // Feeds the --cat / --cat-2 custom properties in dashboard.css,
    // so each category's icons/badges/hover states are color-coded.
    $rtdCategoryColors = [
        'wallet'                => ['#f59e0b', '#fbbf24'],
        'pan-services'          => ['#2B4570', '#3D5A80'],
        'itr-services'          => ['#16a34a', '#22c55e'],
        'aadhaar-services'      => ['#B1502E', '#C96A46'],
        'csc-services'          => ['#7A3B69', '#96517F'],
        'voter-id-services'     => ['#3B6E8F', '#4E88AC'],
        'bank-account-services' => ['#1F6F78', '#2C8992'],
        'other-services'        => ['#55606B', '#6D7883'],
    ];

    $rtdDefault = ['#0b1220', '#162033'];

    $rtdColorVars = function ($slug) use ($rtdCategoryColors, $rtdDefault) {
        $pair = $rtdCategoryColors[$slug] ?? $rtdDefault;
        return "--cat: {$pair[0]}; --cat-2: {$pair[1]};";
    };

    // A service is eligible for the Trending Services section only if:
    // - it has a valid, resolvable route
    // - it isn't a "...History" entry
    // - its parent isn't Wallet (Wallet is hidden entirely on the dashboard)
    $rtdIsEligible = function ($child) {
        if (empty($child->route_name) || !Route::has($child->route_name)) {
            return false;
        }
        if (str_contains(strtolower($child->slug ?? ''), 'history')) {
            return false;
        }
        if (str_contains(strtolower($child->name ?? ''), 'history')) {
            return false;
        }
        return true;
    };

    foreach ($retailerMenus ?? [] as $menu) {

        if ($menu->slug === 'wallet') {
            continue;
        }

        foreach ($menu->children as $child) {
            if ($rtdIsEligible($child)) {
                $trendingServicesCount++;
            }
        }
    }

@endphp


<div class="container-fluid rtd-custom-dashboard">


    {{-- =====================================================
       STATS
    ====================================================== --}}
    <div class="rtd-stats-grid">

        @if($hasPanModule)
            <div class="rtd-stat-card" style="{{ $rtdColorVars('pan-services') }}">
                <div class="rtd-stat-icon"><i class="fa-solid fa-id-card"></i></div>
                <div>
                    <div class="rtd-stat-value" data-count="{{ $panServices ?? 0 }}">0</div>
                    <div class="rtd-stat-text">PAN Applications</div>
                </div>
            </div>
        @endif

        @if($hasAadhaarModule)
            <div class="rtd-stat-card" style="{{ $rtdColorVars('aadhaar-services') }}">
                <div class="rtd-stat-icon"><i class="fa-solid fa-fingerprint"></i></div>
                <div>
                    <div class="rtd-stat-value" data-count="{{ $aadhaarServices ?? 0 }}">0</div>
                    <div class="rtd-stat-text">Aadhaar Services</div>
                </div>
            </div>
        @endif

        @if($hasPanModule)
            <div class="rtd-stat-card" style="{{ $rtdColorVars('pan-services') }}">
                <div class="rtd-stat-icon"><i class="fa-solid fa-pen-to-square"></i></div>
                <div>
                    <div class="rtd-stat-value" data-count="{{ $panCorrectioServices ?? 0 }}">0</div>
                    <div class="rtd-stat-text">PAN Correction Applications</div>
                </div>
            </div>
        @endif

        @if($hasVerificationModule)
            <div class="rtd-stat-card" style="{{ $rtdColorVars('csc-services') }}">
                <div class="rtd-stat-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <div>
                    <div class="rtd-stat-value" data-count="{{ $totalVerifications ?? 0 }}">0</div>
                    <div class="rtd-stat-text">Verification Services</div>
                </div>
            </div>
        @endif

        @if($hasUtilityModule)
            <div class="rtd-stat-card" style="{{ $rtdColorVars('bank-account-services') }}">
                <div class="rtd-stat-icon"><i class="fa-solid fa-gear"></i></div>
                <div>
                    <div class="rtd-stat-value" data-count="{{ $utilityServices ?? 0 }}">0</div>
                    <div class="rtd-stat-text">Utility Services</div>
                </div>
            </div>
        @endif

    </div>

    {{-- =====================================================
       TRENDING SERVICES
       (Wallet category and any "...History" service are excluded)
    ====================================================== --}}
    @if(($retailerMenus ?? collect())->count())

        <div class="rtd-section">

            <div class="rtd-section-head">
                <h4 class="rtd-section-title">
                    <i class="fas fa-fire text-warning"></i>
                    Trending Services
                </h4>
                <span class="rtd-section-count">{{ $trendingServicesCount }} Services</span>
            </div>

            {{-- Search --}}
            <div class="rtd-search-wrap">
                <i class="fas fa-magnifying-glass rtd-search-icon"></i>
                <input
                    type="text"
                    id="rtdServiceSearch"
                    class="rtd-search-input"
                    placeholder="Search for a service — e.g. Aadhaar, GST, Passport..."
                    autocomplete="off"
                >
                <button type="button" id="rtdSearchClear" class="rtd-search-clear" aria-label="Clear search">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            {{-- Category quick-filter pills — single scrollable line --}}
            <div class="rtd-pill-row" id="rtdPillRow">
                @foreach($retailerMenus as $parent)
                    @continue($parent->slug === 'wallet')
                    @php
                        $count = $parent->children->filter($rtdIsEligible)->count();
                    @endphp
                    @if($count)
                        <button type="button" class="rtd-pill" data-target="cat-{{ $parent->slug }}" style="{{ $rtdColorVars($parent->slug) }}">
                            <span class="rtd-pill-dot"></span>{{ $parent->name }}
                        </button>
                    @endif
                @endforeach
            </div>

            <div id="rtdNoResults" class="rtd-no-results" hidden>
                <i class="fas fa-inbox"></i>
                No services match your search. Try a different term.
            </div>

            @foreach($retailerMenus as $parent)

                @continue($parent->slug === 'wallet')

                @php
                    $services = $parent->children->filter($rtdIsEligible);
                @endphp

                @if($services->count())

                    <div class="rtd-category-block" id="cat-{{ $parent->slug }}" data-category-block style="{{ $rtdColorVars($parent->slug) }}">

                        <div class="rtd-category-head">
                            <div class="rtd-category-title">
                                @if(!empty($parent->icon))
                                    <i class="{{ $parent->icon }}"></i>
                                @endif
                                {{ $parent->name }}
                            </div>
                            <span class="rtd-category-badge">{{ $services->count() }}</span>
                        </div>

                        <div class="rtd-services">

                            @foreach($services as $child)

                                @php
                                    $serviceUrl = '#';
                                    try {
                                        $route = app('router')->getRoutes()->getByName($child->route_name);
                                        $params = [];
                                        if ($route) {
                                            foreach ($route->parameterNames() as $param) {
                                                switch ($param) {
                                                    case 'service':
                                                        $params[$param] = $child->slug;
                                                        break;
                                                    case 'slug':
                                                        $params[$param] = $child->slug;
                                                        break;
                                                    case 'id':
                                                        $params[$param] = $child->id;
                                                        break;
                                                }
                                            }
                                        }
                                        $serviceUrl = route($child->route_name, $params);
                                    } catch (\Throwable $e) {
                                        $serviceUrl = '#';
                                    }
                                @endphp

                                <a
                                    href="{{ $serviceUrl }}"
                                    class="rtd-service-card"
                                    data-name="{{ \Illuminate\Support\Str::lower($child->name) }}"
                                    style="{{ $rtdColorVars($parent->slug) }}"
                                >
                                    <div class="rtd-service-icon">
                                        <i class="{{ $child->icon ?: 'fa fa-circle' }}"></i>
                                    </div>
                                    <div class="rtd-service-title">{{ $child->name }}</div>
                                </a>

                            @endforeach

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    @else

        <div class="alert alert-info">
            No services have been assigned to your account.
        </div>

    @endif

</div>

@include('components.popup',[
    'popup' => $popup
])


<script>
(function () {
    // Stagger index for entrance animation (used by --i in CSS)
    document.querySelectorAll('.rtd-stat-card, .rtd-service-card').forEach(function (el, i) {
        el.style.setProperty('--i', i % 12);
    });

    var searchInput = document.getElementById('rtdServiceSearch');
    var clearBtn = document.getElementById('rtdSearchClear');
    var noResults = document.getElementById('rtdNoResults');
    var pills = document.querySelectorAll('.rtd-pill');

    function filterServices(term) {
        term = term.trim().toLowerCase();
        clearBtn.classList.toggle('show', term.length > 0);

        var anyVisible = false;

        document.querySelectorAll('[data-category-block]').forEach(function (block) {
            var visibleInBlock = 0;

            block.querySelectorAll('.rtd-service-card').forEach(function (card) {
                var match = !term || (card.dataset.name || '').indexOf(term) !== -1;
                card.classList.toggle('rtd-hide', !match);
                if (match) visibleInBlock++;
            });

            block.classList.toggle('rtd-hide', visibleInBlock === 0);
            if (visibleInBlock > 0) anyVisible = true;
        });

        noResults.hidden = anyVisible;
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            filterServices(searchInput.value);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            filterServices('');
            searchInput.focus();
        });
    }

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            pills.forEach(function (p) { p.classList.remove('is-active'); });
            pill.classList.add('is-active');
            var target = document.getElementById(pill.dataset.target);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Animated stat count-up
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('.rtd-stat-value').forEach(function (el) {
        var target = parseInt(el.dataset.count, 10) || 0;

        if (prefersReducedMotion || target === 0) {
            el.textContent = target;
            return;
        }

        var start = null;
        var duration = 700;

        function step(timestamp) {
            if (!start) start = timestamp;
            var progress = Math.min((timestamp - start) / duration, 1);
            el.textContent = Math.floor(progress * target);
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target;
            }
        }

        requestAnimationFrame(step);
    });
})();
</script>

@endsection
