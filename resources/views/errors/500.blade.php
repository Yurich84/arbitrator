@extends('layout')

@section('content')
<br/>
<div class="jumbotron center">
    <h2>500.  Ошибка сервера.</h2>

    @if(isset($message))
        <p>{!! $message !!}</p>
    @endif
</div>
@endsection