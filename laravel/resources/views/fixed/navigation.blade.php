<header class="header_section">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="{{ route('index') }}">
                <span>Hostit</span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav  ml-auto">
                    @foreach ($navLinks as $link)
                        @unless ($link->is_footer)
                            <li class="nav-item {{ Request::routeIs($link['route']) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route($link['route']) }}">
                                    {{ $link['name'] }}
                                    @if (Request::routeIs($link['route']))
                                        <span class="sr-only">(current)</span>
                                    @endif
                                </a>
                            </li>
                        @endunless
                    @endforeach
                </ul>
                <div class="quote_btn-container">
                    @if (Auth::user() && Auth::user()->hasVerifiedEmail())
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    My profile
                                </a>
                                <a class="dropdown-item" href="{{ route('rent-server') }}">
                                    Rent a server
                                </a>
                                @if (Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="/admin">
                                        Admin Panel
                                    </a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @else
                        <a href="{{ route('showLogin') }}"><span>Login</span></a>
                        <a href="{{ route('showRegister') }}"><span>Register</span></a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</header>
