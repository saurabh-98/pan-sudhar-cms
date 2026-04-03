<header class="main-header">
    <div class="container-custom header-inner">

        <!-- LOGO -->
        <div class="logo">
            <a href="{{ url('/') }}">

                @if(($settings['logo_type'] ?? 'image') === 'image' && !empty($settings['logo']))
                    <img src="{{ asset($settings['logo']) }}" alt="Logo" height="40">

                @elseif(($settings['logo_type'] ?? '') === 'text')
                    <span class="text-logo"
                        style="
                            color: {{ $settings['color'] ?? '#ff5722' }};
                            font-size: {{ $settings['font_size'] ?? 26 }}px;
                            font-weight: bold;
                        ">
                        {{ $settings['logo_text'] ?? 'Foodies' }}
                    </span>

                @else
                    <span class="text-logo-default">
                        🍴 {{ $settings['site_name'] ?? 'Foodies' }}
                    </span>
                @endif

            </a>
        </div>

        <!-- MOBILE TOGGLE -->
        <div class="menu-toggle">☰</div>

        <!-- NAV -->
        <nav class="nav-links">

            @foreach($navMenus as $menu)
                <a href="{{ url($menu->url) }}"
                   class="{{ request()->is(ltrim($menu->url,'/').'*') ? 'active' : '' }}">
                    {{ $menu->name }}
                </a>
            @endforeach

            <!-- CART -->
            <a href="javascript:void(0)" onclick="toggleCart()" class="cart-link">
                🛒 Cart <span id="cartCount">{{ session('cart_count', 0) }}</span>
            </a>

            
                @auth
                <div class="user-dropdown">

                    <!-- TRIGGER -->
                    <div class="user-trigger">
                        👋 {{ auth()->user()->name }}
                        <i class="fa fa-angle-down"></i>
                    </div>

                    <!-- DROPDOWN -->
                    <div class="dropdown-menu-user">

                        @if(auth()->user()->role === 'customer')

                            <a href="{{ route('customer.dashboard') }}">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </a>

                            <a href="{{ route('customer.profile') }}">
                                <i class="fa fa-user"></i> Profile
                            </a>

                        @endif

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="fa fa-sign-out-alt"></i> Logout
                            </button>
                        </form>

                    </div>

                </div>
                @endauth
            @guest
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            @endguest

        </nav>

    </div>
</header>