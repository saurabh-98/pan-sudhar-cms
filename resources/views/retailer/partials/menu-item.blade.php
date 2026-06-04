@php
    $hasChildren = $menu->children->count() > 0;

    $isActive =
        ($menu->route_name &&
        request()->routeIs($menu->route_name));

    foreach ($menu->children as $child) {
        if (
            $child->route_name &&
            request()->routeIs($child->route_name)
        ) {
            $isActive = true;
            break;
        }
    }
@endphp

<li class="{{ $hasChildren ? 'stf-dropdown' : '' }}">

    <a
        href="{{
            !$hasChildren && $menu->route_name
                ? route($menu->route_name)
                : '#'
        }}"
        class="stf-link {{ $isActive ? 'stf-active' : '' }}"
    >

        <div class="stf-link-left">

            <i class="{{ $menu->icon ?: 'fa fa-circle' }}"></i>

            <span>{{ $menu->name }}</span>

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
                    ['menu' => $child]
                )

            @endforeach

        </ul>

    @endif

</li>