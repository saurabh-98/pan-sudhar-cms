@php

    $hasChildren = $menu->children->count() > 0;

    $isActive = false;

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CHECK
    |--------------------------------------------------------------------------
    */

    if ($menu->route_name) {

        if (
            $menu->route_name === 'retailer.aadhaar.service'
        ) {

            $isActive =
                request()->routeIs(
                    'retailer.aadhaar.service'
                );

        } else {

            $isActive =
                request()->routeIs(
                    $menu->route_name
                );
        }
    }

    foreach ($menu->children as $child) {

        if ($child->route_name) {

            if (
                $child->route_name === 'retailer.aadhaar.service'
            ) {

                if (
                    request()->routeIs(
                        'retailer.aadhaar.service'
                    )
                ) {
                    $isActive = true;
                    break;
                }

            } else {

                if (
                    request()->routeIs(
                        $child->route_name
                    )
                ) {
                    $isActive = true;
                    break;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | URL GENERATION
    |--------------------------------------------------------------------------
    */

    $url = '#';

    if (
        !$hasChildren
        &&
        !empty($menu->route_name)
    ) {

        if (
            $menu->route_name === 'retailer.aadhaar.service'
        ) {

            $url = route(

                'retailer.aadhaar.service',

                [
                    'service' => $menu->slug
                ]

            );

        } else {

            $url = route(
                $menu->route_name
            );
        }
    }

@endphp

<li class="{{ $hasChildren ? 'stf-dropdown' : '' }}">

    <a
        href="{{ $url }}"
        class="stf-link {{ $isActive ? 'stf-active' : '' }}"
    >

        <div class="stf-link-left">

            <i class="{{ $menu->icon ?: 'fa fa-circle' }}"></i>

            <span>

                {{ $menu->name }}

            </span>

        </div>

        @if($hasChildren)

            <i class="fa fa-chevron-down stf-arrow"></i>

        @endif

    </a>

    @if($hasChildren)

        <ul class="stf-submenu">

            @foreach($menu->children as $child)

                @include(
                    'retailer.partials.menu-item',
                    [
                        'menu' => $child
                    ]
                )

            @endforeach

        </ul>

    @endif

</li>