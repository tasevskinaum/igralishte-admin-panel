<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials/_head')
</head>

<body>
    <div class="app d-flex overflow-hidden">
        <aside id="sidebar">
            <div class="inner d-flex flex-column">
                <div class="d-flex align-items-center position-relative">
                    <span class="toggle-btn me-3">
                        <img src="{{ auth()->user()->profile_picture }}" alt="" />
                    </span>
                    <div class="user-info">
                        <div>{{ auth()->user()->name }}</div>
                        <div>{{ auth()->user()->email }}</div>
                    </div>
                    <span id="close-sidebar-btn">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="{{ route('products.index') }}" class="sidebar-link @if(Route::is('products.index')) active @endif">
                            <i class='bx bxs-cog'></i>
                            <span>Vintage облека</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('discounts.index') }}" class="sidebar-link @if(Route::is('discounts.index')) active @endif">
                            <i class='bx bxs-offer'></i>
                            <span>Попусти / промо</span>
                        </a>

                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('brands.index') }}" class="sidebar-link @if(Route::is('brands.index')) active @endif">
                            <i class='bx bx-polygon'></i>
                            <span>Брендови</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('profile.edit') }}" class="sidebar-link @if(Route::is('profile.edit')) active @endif">
                            <i class='bx bxs-user'></i>
                            <span>Профил</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit">
                            <a class="sidebar-link">
                                <i class="fa-solid fa-right-from-bracket logout"></i>
                                <span><strong>Одјави се</strong></span>
                            </a>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

    @include('partials/_script')
    @yield('script')
</body>

</html>