<nav class="navbar navbar-expand-lg bg-white border-bottom position-sticky top-0">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#aboutUs">About Us</a>
                    </li>
                    <li class="nav-item login">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Login
                        </a>
                    </li>
                @endguest

                @auth

                    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('admin.orders.index') }}">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.create') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('admin.products.index') }}">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.index') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('admin.users.index') }}">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.histories.index') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('admin.histories.index') }}">History</a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'user')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.orders.index') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('user.orders.index') }}">My order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('user.products.index') ? 'border-top text-black' : '' }}"
                                aria-current="page" href="{{ route('user.products.index') }}">Products</a>
                        </li>
                    @endif


                </ul>
                <form action="{{ route('logout') }}" method="post" class="text-center ms-5 logout">
                    @csrf

                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span> Logout </span>
                    </button>
                </form>

            @endauth
        </div>
    </div>
</nav>
