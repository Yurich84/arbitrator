<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Арбитратор</title>

    <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    @yield('styles')

</head>
<body class="app sidebar-mini rtl pace-done">

<div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
        <div class="pace-progress-inner"></div>
    </div>
    <div class="pace-activity"></div>
</div>

    @include('admin.header')
    @include('admin.aside')

    <!--Main layout-->
    <main class="app-content">

        <div class="app-title">
            <div>
                <h1>@yield('name')</h1>
                <p>@yield('desc')</p>
            </div>
            @include('breadcrumbs')
        </div>

        <div class="container-fluid">

            @admin
            @if(config('bot.inter.go'))
                <div class="alert alert-success text-center">
                    Бот запущен
                </div>
            @else
                <div class="alert alert-danger text-center">
                    Бот отключен
                </div>
            @endif
            @endadmin

            @include('alerts')

            @yield('content')
        </div>
    </main>
    <!--/Main layout-->

    <!-- SCRIPTS -->
    <script src="{{ asset("js/vali/jquery-3.2.1.min.js") }}"></script>
    <script src="{{ asset("js/vali/popper.min.js") }}"></script>
    <script src="{{ asset("js/vali/bootstrap.min.js") }}"></script>
    <script src="{{ asset("js/vali/main.js") }}"></script>
    <!-- The javascript plugin to display page loading on top-->
    <script src="{{ asset("js/vali/plugins/pace.min.js") }}"></script>


    @yield('scripts')
    @stack('script')
</body>
</html>
