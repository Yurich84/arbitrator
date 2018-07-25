<header class="app-header">
    <a class="app-header__logo" href="/">Arbitrator</a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <li><a class="app-nav__item" href="#">Test</a></li>
        <li><a class="app-nav__item" href="#">Test</a></li>
        <li><a class="app-nav__item" href="#">Test</a></li>
        <!-- User Menu-->
        @if( Auth::check() )
            <li><a class="app-nav__item" href="#"><i class="fa fa-user fa-lg"></i> hello {{ Auth::user()->name }}</a></li>
            <li><a class="app-nav__item" href="{{ route('logout') }}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
        @else
            <li><a class="app-nav__item" href="{{ route('login') }}">Sing In</a></li>
            <li><a class="app-nav__item" href="{{ url('register') }}">Sing Up</a></li>
        @endif
    </ul>
</header>