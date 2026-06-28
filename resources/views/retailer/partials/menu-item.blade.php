@php

use Illuminate\Support\Facades\Route;

$hasChildren = $menu->children->count() > 0;

$isActive = false;

/*
|--------------------------------------------------------------------------
| ACTIVE MENU CHECK
|--------------------------------------------------------------------------
*/

if (!empty($menu->route_name)) {

    $isActive = request()->routeIs(
        $menu->route_name
    );

}

foreach ($menu->children as $child) {

    if (
        !empty($child->route_name)
        &&
        request()->routeIs($child->route_name)
    ) {

        $isActive = true;
        break;
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
    &&
    Route::has($menu->route_name)
) {

    try {

        $route = Route::getRoutes()
            ->getByName($menu->route_name);

        $parameters = [];

        if ($route) {

            foreach (
                $route->parameterNames()
                as $parameter
            ) {

                switch ($parameter) {

                    case 'service':

                        $parameters[$parameter] =
                            $menu->slug;

                    break;

                    case 'slug':

                        $parameters[$parameter] =
                            $menu->slug;

                    break;

                    case 'id':

                        $parameters[$parameter] =
                            $menu->id;

                    break;

                }

            }

        }

        $url = route(
            $menu->route_name,
            $parameters
        );

    } catch (\Throwable $e) {

        $url = '#';

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
