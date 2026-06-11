@extends('layout.retailer')

@section('content')

@php

    $heroButtons = collect();
    $trendingServicesCount = 0;

    foreach ($retailerMenus ?? [] as $menu) {

        foreach ($menu->children as $child) {

            if (
                !empty($child->route_name) &&
                Route::has($child->route_name)
            ) {

                $heroButtons->push($child);
                $trendingServicesCount++;
            }
        }
    }

    $heroButtons = $heroButtons->take(2);

@endphp

<div class="container-fluid rtd-custom-dashboard">

    {{-- =====================================================
       STATS
    ====================================================== --}}
    <div class="rtd-stats-grid">

        @if($hasPanModule)
            <div class="rtd-stat-card">
                <div class="rtd-stat-icon">🪪</div>
                <div>
                    <div class="rtd-stat-value">
                        {{ $panServices ?? 0 }}
                    </div>
                    <div class="rtd-stat-text">
                        PAN Applications
                    </div>
                </div>
            </div>
        @endif

        @if($hasAadhaarModule)
            <div class="rtd-stat-card">
                <div class="rtd-stat-icon">🔐</div>
                <div>
                    <div class="rtd-stat-value">
                        {{ $aadhaarServices ?? 0 }}
                    </div>
                    <div class="rtd-stat-text">
                        Aadhaar Services
                    </div>
                </div>
            </div>
        @endif

        @if($hasPanModule)
            <div class="rtd-stat-card">
                <div class="rtd-stat-icon">🪪</div>
                <div>
                    <div class="rtd-stat-value">
                        {{ $panCorrectioServices ?? 0 }}
                    </div>
                    <div class="rtd-stat-text">
                        PAN Correction Applications
                    </div>
                </div>
            </div>
        @endif

        @if($hasVerificationModule)
            <div class="rtd-stat-card">
                <div class="rtd-stat-icon">🔍</div>
                <div>
                    <div class="rtd-stat-value">
                        {{ $totalVerifications ?? 0 }}
                    </div>
                    <div class="rtd-stat-text">
                        Verification Services
                    </div>
                </div>
            </div>
        @endif

        @if($hasUtilityModule)
            <div class="rtd-stat-card">
                <div class="rtd-stat-icon">⚙️</div>
                <div>
                    <div class="rtd-stat-value">
                        {{ $utilityServices ?? 0 }}
                    </div>
                    <div class="rtd-stat-text">
                        Utility Services
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- =====================================================
       TRENDING SERVICES
    ====================================================== --}}
    @if(($retailerMenus ?? collect())->count())

        <div class="rtd-section">

            <div class="rtd-section-head">

                <h4 class="rtd-section-title">

                    <i class="fas fa-fire text-warning"></i>

                    Trending Services

                </h4>

                <span class="rtd-section-count">

                    {{ $trendingServicesCount }}

                    Services

                </span>

            </div>

            @foreach($retailerMenus as $parent)

                @php

                    $services = $parent->children->filter(function ($child) {

                        return !empty($child->route_name)
                            && Route::has($child->route_name);

                    });

                @endphp

                @if($services->count())

                    <div class="rtd-category-block">

                        <div class="rtd-category-head">

                            <div class="rtd-category-title">

                                @if(!empty($parent->icon))
                                    <i class="{{ $parent->icon }}"></i>
                                @endif

                                {{ $parent->name }}

                            </div>

                            <span class="rtd-category-badge">

                                {{ $services->count() }}

                            </span>

                        </div>

                        <div class="rtd-services">

                            @foreach($services as $child)

                                @php

                                    $serviceUrl = '#';

                                    if (
                                        $child->route_name ===
                                        'retailer.aadhaar.service'
                                    ) {

                                        $serviceUrl = route(
                                            'retailer.aadhaar.service',
                                            [
                                                'service' => $child->slug
                                            ]
                                        );

                                    } else {

                                        $serviceUrl = route(
                                            $child->route_name
                                        );
                                    }

                                @endphp

                                <a
                                    href="{{ $serviceUrl }}"
                                    class="rtd-service-card"
                                >

                                    <div class="rtd-service-icon">

                                        <i class="{{ $child->icon ?: 'fa fa-circle' }}"></i>

                                    </div>

                                    <div class="rtd-service-title">

                                        {{ $child->name }}

                                    </div>

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

@endsection