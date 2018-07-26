<header class="app-header">
    <a class="app-header__logo" href="/">Arbitrator</a>

    <!-- Navbar Right Menu-->
    <ul class="app-nav">
        <li><a class="app-nav__item" href="{{ route('instructions') }}">Стать арбитром</a></li>
        <li><a class="app-nav__item" href="{{ route('inter.current') }}">Новости</a></li>
        <li><a class="app-nav__item" href="{{ route('contact') }}">Контакты</a></li>

        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
            <ul class="dropdown-menu settings-menu dropdown-menu-right">
                {{--<li><a class="dropdown-item" href="{{ route('admin.key.index') }}"><i class="fa fa-cog fa-lg"></i> Мои биржи</a></li>--}}
                <li><a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                <li><a class="dropdown-item" href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            </ul>
        </li>
    </ul>
</header>