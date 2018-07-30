<nav class="navbar navbar-expand-md navbar-dark fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img width="209" height="43" src="/imgs/templ/logo.png">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName() == 'instructions') active @endif"
                       href="{{ route('instructions') }}">Стать арбитром</a></li>

                <li class="nav-item">
                    <a class="nav-link @if(Route::currentRouteName() == 'contact') active @endif"
                       href="{{ route('contact') }}">Контакты</a></li>

                <!-- User Menu-->
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
                    <ul class="dropdown-menu settings-menu dropdown-menu-right">
                        @if(Auth::check())
                        <li><a class="dropdown-item" href="{{ route('admin.key.index') }}"><i class="fa fa-cog fa-lg"></i> Мои биржи</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa fa-user fa-lg"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ url('/logout') }}"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fa fa-user fa-lg"></i> Login</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>