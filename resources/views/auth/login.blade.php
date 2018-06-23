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
        <h1>Арбітратор</h1>
    </div>
    <div class="login-box">


        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
            <div class="form-group">
                <input id="email" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" >

                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                @endif
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
            </div>
        </form>
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