@extends('front.layouts.1_column')

@section('content')
<br/>
<div class="jumbotron center">
    <h2>404. Запрашиваемая страница не найдена.</h2>

    @if(isset($message))
        <p>{!! $message !!}</p>
    @endif
</div>
@endsection