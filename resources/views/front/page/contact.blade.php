@extends('front.main')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <h2>Написать нам</h2>
            <form action="/contact" method="post">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <div class="control-group">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>
                </div>
                <div class="control-group">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ old('email') }}">
                    </div>
                </div>
                <div class="control-group">
                    <div class="form-group controls">
                        <label for="message">Сообщение</label>
                    <textarea rows="5" class="form-control" id="message"
                    name="message">{{ old('message') }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class=" btn btn-green">Отправить</button>
                </div>
            </form>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

            <h2>Наши реквизиты</h2>

            <br/>
            <p>Email: {{ config('app.email') }}</p>
            <p>BTC: </p>
            <p>ETH: </p>
        </div>
    </div>

@endsection