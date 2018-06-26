<aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/48.jpg" alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ \Auth::user()->name }}</p>
            <p class="app-sidebar__user-designation"></p>
        </div>
    </div>

    <ul class="app-menu">
        <li><a class="app-menu__item @if( Route::currentRouteName() == 'admin.dashboard' ) active @endif" href="{{ route('admin.dashboard') }}">
                <i class="app-menu__icon fa fa-dashboard"></i>
                <span class="app-menu__label">Панель</span></a>
        </li>

        @admin
            <li><a class="app-menu__item @if( Route::currentRouteName() == 'admin.config' ) active @endif" href="{{ route('admin.config') }}">
                    <i class="app-menu__icon fa fa-cogs"></i>
                    <span class="app-menu__label">Конфигурация</span></a>
            </li>
            <li><a class="app-menu__item @if( Route::currentRouteName() == 'admin.stock.index' ) active @endif" href="{{ route('admin.stock.index') }}">
                    <i class="app-menu__icon fa fa-exchange"></i>
                    <span class="app-menu__label">Биржи</span></a>
            </li>
        @endadmin

        <li class="treeview @if( in_array(Route::currentRouteName(), ['admin.triangle.current', 'admin.triangle.logs'] )) is-expanded @endif ">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class="app-menu__icon fa fa-th-list"></i>
                <span class="app-menu__label">Внутрибиржевий</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li><a class="treeview-item @if( Route::currentRouteName() == 'admin.triangle.logs' ) active @endif" href="{{ route('admin.triangle.logs') }}">
                        <i class="app-menu__icon fa fa-file-text"></i>
                        <span class="app-menu__label">Логи</span></a>
                </li>
                <li><a class="treeview-item @if( Route::currentRouteName() == 'admin.triangle.current' ) active @endif" href="{{ route('admin.triangle.current') }}">
                        <i class="app-menu__icon fa fa-file-text"></i>
                        <span class="app-menu__label">Текушие</span></a>
                </li>
            </ul>
        </li>
    </ul>
</aside>