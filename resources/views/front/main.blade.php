<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Арбитратор</title>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    {{--<link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @yield('styles')
    @stack('style')

</head>
<body class="app">

    @include('front.header')

    <!--Main layout-->
    <main style="margin-top: 70px;">

        <div class="container">

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
