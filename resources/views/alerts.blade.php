{{--if use ->with('status', ' my message')--}}
@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif


{{--if use ->withErrors()--}}
@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


{{--if use ->withSuccess()--}}
@if (\Session::has('success'))
    <br />
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>
            <i class="glyphicon glyphicon-ok-circle"></i> Готово.
        </strong>
        {{ \Session::get('success') }}
    </div>
@endif