@extends('admin.main')

@section('name', 'Создать')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    {!! Form::open(['route' => 'admin.key.store' ])!!}
                    @include('admin.key._form')

                    <div class="form-group">
                        {!! Form::submit('Создать', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection