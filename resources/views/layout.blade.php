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
<body>

<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <h1>Arbitrator</h1>
    </div>
    <div class="login-box">

        @yield('content')

    </div>
</section>

<!-- SCRIPTS -->
<script src="{{ asset("js/vali/jquery-3.2.1.min.js") }}"></script>
<script src="{{ asset("js/vali/popper.min.js") }}"></script>
<script src="{{ asset("js/vali/bootstrap.min.js") }}"></script>
<script src="{{ asset("js/vali/main.js") }}"></script>
<!-- The javascript plugin to display page loading on top-->
<script src="{{ asset("js/vali/plugins/pace.min.js") }}"></script>


</body>
</html>